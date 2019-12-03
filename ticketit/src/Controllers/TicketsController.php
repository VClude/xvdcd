<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Kordy\Ticketit\Models;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Ticket;
use Kordy\Ticketit\Models\Image;
use Kordy\Ticketit\Models\Comment;
use App\Notif; 
use App\User; 
use App\Categoryusers; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use FCM;
use Benwilkins\FCM\FcmMessage;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
class TicketsController extends Controller
{
    public $successStatus = 200;

    protected $tickets;
    protected $agent;

    public function __construct(Ticket $tickets, Agent $agent)
    {
        $this->middleware('Kordy\Ticketit\Middleware\ResAccessMiddleware', ['only' => ['show']]);
        $this->middleware('Kordy\Ticketit\Middleware\IsAgentMiddleware', ['only' => ['edit', 'update']]);
        $this->middleware('Kordy\Ticketit\Middleware\IsAdminMiddleware', ['only' => ['destroy']]);

        $this->tickets = $tickets;
        $this->agent = $agent;
    }


    public function agentData(Request $request){
        $reqID = $request->catID;
        $tickets_count = Ticket::where('category_id', $reqID)->count();
        $open_tickets_count = Ticket::where('category_id', $reqID)->whereNull('completed_at')->count();
        $closed_tickets_count = Ticket::where('category_id', $reqID)->whereNotNull('completed_at')->count();
        return response()->json(['request_id'=> $reqID ,'tc'=>$tickets_count , 'otc'=>$open_tickets_count, 'ctc'=>$closed_tickets_count  ], $this-> successStatus); 
    }


    public function data($complete)
    {
        if (LaravelVersion::min('5.4')) {
            $datatables = app(\Yajra\DataTables\DataTables::class);
        } else {
            $datatables = app(\Yajra\Datatables\Datatables::class);
        }

        $user = $this->agent->find(auth()->user()->id);

        if ($user->isAdmin() || $user->isAgent()) {
            if ($complete == 0) {
                $collection = Ticket::alliweekly();
            } 
            else if($complete == 1) {
                $collection = Ticket::complete();
            }
            else if($complete == 2) {
                $collection = Ticket::completeweekly();
            }
            else if($complete == 3) {
                $collection = Ticket::activeweekly();
            }
            else if($complete == 4) {
                $collection = Ticket::alliakbar();
            }
            else if($complete == 5) {
                $collection = Ticket::active();
            }
            else{
                $collection = Ticket::alliakbar();
            }
        }
        
        else {
            if ($complete) {
                $collection = Ticket::userTickets($user->id)->complete();
            } else {
                $collection = Ticket::userTickets($user->id)->active();
            }
        }

        $collection
            ->join('users', 'users.id', '=', 'ticketit.user_id')
            // ->join('users', 'users.id', '=', 'ticketit_categories_users.user_id')
            ->join('ticketit_statuses', 'ticketit_statuses.id', '=', 'ticketit.status_id')
            ->join('ticketit_priorities', 'ticketit_priorities.id', '=', 'ticketit.priority_id')
            ->join('ticketit_categories', 'ticketit_categories.id', '=', 'ticketit.category_id')
            ->select([
                'ticketit.id',
                'ticketit.subject AS subject',
                'ticketit_statuses.alternate AS status',
                'ticketit_statuses.color AS color_status',
                'ticketit_priorities.color AS color_priority',
                'ticketit_categories.color AS color_category',
                'ticketit.id AS agent',
                'ticketit.updated_at AS updated_at',
                'ticketit_priorities.name AS priority',
                'users.name AS owner',
                'ticketit.agent_id',
                'ticketit_categories.alternate AS category',
            ]);

        $collection = $datatables->of($collection);

        $this->renderTicketTable($collection);

        $collection->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->diffForHumans() !!}');

        // method rawColumns was introduced in laravel-datatables 7, which is only compatible with >L5.4
        // in previous laravel-datatables versions escaping columns wasn't defaut
        if (LaravelVersion::min('5.4')) {
            $collection->rawColumns(['subject', 'status', 'priority', 'category', 'agent']);
        }

        return $collection->make(true);
    }

    public function renderTicketTable($collection)
    {
        $collection->editColumn('subject', function ($ticket) {
            return (string) link_to_route(
                Setting::grab('main_route').'.show',
                $ticket->subject,
                $ticket->id
            );
        });

        $collection->editColumn('status', function ($ticket) {
            $color = $ticket->color_status;
            $status = e($ticket->status);

            return "<div style='color: $color'>$status</div>";
        });

        $collection->editColumn('priority', function ($ticket) {
            $color = $ticket->color_priority;
            $priority = e($ticket->priority);

            return "<div style='color: $color'>$priority</div>";
        });

        $collection->editColumn('category', function ($ticket) {
            $color = $ticket->color_category;
            $category = e($ticket->category);

            return "<div style='color: $color'>$category</div>";
        });

        $collection->editColumn('agent', function ($ticket) {
            $ticket = $this->tickets->find($ticket->id);

            return e($ticket->agent->name);
        });

        return $collection;
    }

    /**
     * Display a listing of active tickets related to user.
     *
     * @return Response
     */
    public function index()
    {
        $complete = 0;
        if (Auth::user()->ticketit_admin == 0 && Auth::user()->ticketit_agent == 0){
            return view('unauth');
        }
        else if(Auth::user()->ticketit_agent == 1 && Auth::user()->ticketit_admin == 0){
            $complete = 0;
            return view('ticketit::index', compact('complete'));
        }
        else{
            return view('ticketit::index', compact('complete'));
        }
        
    }

    /**
     * Display a listing of completed tickets related to user.
     *
     * @return Response
     */
    public function indexComplete()
    {
        $complete = 1;

        return view('ticketit::index', compact('complete'));
    }
    public function completeWeekly()
    {
        $complete = 2;
        return view('ticketit::index', compact('complete'));
    }
    public function indexWeekly()
    {
        $complete = 3;
        return view('ticketit::index', compact('complete'));
    }
    public function indexAll()
    {
        $complete = 4;
        return view('ticketit::index', compact('complete'));
    }
    public function indexOpen()
    {
        $complete = 5;
        return view('ticketit::index', compact('complete'));
    }
    
    public function allWeekly()
    {
        $complete = 0;
        return view('ticketit::index', compact('complete'));
    }
    /**
     * Returns priorities, categories and statuses lists in this order
     * Decouple it with list().
     *
     * @return array
     */
    protected function PCS()
    {
        // seconds expected for L5.8<=, minutes before that
        $time = LaravelVersion::min('5.8') ? 60*60 : 60;

        // $priorities = Cache::remember('ticketit::priorities', $time, function () {
        //     return Models\Priority::all();
        // });

        $categories = Cache::remember('ticketit::categories', $time, function () {
            return Models\Category::all();
        });

        $statuses = Cache::remember('ticketit::statuses', $time, function () {
            return Models\Status::all();
        });

        if (LaravelVersion::min('5.3.0')) {
            return [$categories->pluck('name', 'id'), $statuses->pluck('name', 'id')];
        } else {
            return [$categories->lists('name', 'id'), $statuses->lists('name', 'id')];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        list($categories) = $this->PCS();

        return view('ticketit::tickets.create', compact('categories'));
    }

    /**
     * Store a newly created ticket and auto assign an agent for it.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject'     => 'required|min:3',
            'content'     => 'required|min:6',
            // 'priority_id' => 'required|exists:ticketit_priorities,id',
            'category_id' => 'required|exists:ticketit_categories,id',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);
       $ayey = Ticket::orderBy('id','DESC')->first();
       $idnow = $ayey->id + 1;
        $ticket = new Ticket();

        $ticket->subject = $request->subject;

        $ticket->setPurifiedContent($request->get('content'));

        $ticket->priority_id = $request->priority_id;
        $ticket->id = $idnow;
        $ticket->category_id = $request->category_id;
        $ticket->status_id = Setting::grab('default_status_id');
        $ticket->user_id = auth()->user()->id;
        $ticket->autoSelectAgent();

        $ticket->save();
        if ($image = $request->file('image')) {
            foreach ($image as $files) {
            $imeg = new Image();
            $destinationPath = public_path().'/images/';
            $profileImage = date('YmdHis') . "-" . Str::random(16) . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $imeg->ticket_id = "$idnow";
            $imeg->image = "$profileImage";
            $imeg->save();
        }
        }

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-created'));

        return redirect()->action('\Kordy\Ticketit\Controllers\TicketsController@index');
    }


    public function jsonstore(Request $request)
    {
        $asd = Validator::make($request->all(), [ 
            'subject'     => 'required|min:3',
            'content'     => 'required|min:6',
            'location'    => 'required',
            'category_id' => 'required|exists:ticketit_categories,id',
            'image'       => 'required',
            'image.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);
        // $asd = $this->validate($request, [
        //     'subject'     => 'required|min:3',
        //     'content'     => 'required|min:6',
        //     'priority_id' => 'required|exists:ticketit_priorities,id',
        //     'category_id' => 'required|exists:ticketit_categories,id',
        //     'image' => 'required',
        //     'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        // ]);
        if ($asd->fails()) { 
            return response()->json(['error'=>$asd->errors()], 401);            
        }
        $ayey = Ticket::orderBy('id','DESC')->first();
        if($ayey == null){
            $idnow = 1;
        }
        else{
            $idnow = $ayey->id + 1;
        }
        $ticket = new Ticket();

        $ticket->subject = $request->subject;

        $ticket->setPurifiedContent($request->get('content'));
        $ticket->location = $request->location;
        // $ticket->priority_id = $request->priority_id;
        $ticket->id = $idnow;
        $ticket->category_id = $request->category_id;
        $ticket->status_id = Setting::grab('default_status_id');
        $ticket->user_id = Auth::user()->id;
        $ticket->autoSelectAgent();
        $surveyord = Categoryusers::where('category_id', $ticket->category_id)->with('useres')->get();
        $catname = Category::select('name')->where('id', $ticket->category_id)->first();
        switch ($catname->name){
            case "FACILITIES_AND_INFRASTRUCTURE" :
                $catname = "Sarana dan Prasarana";
                break;
            case "BUILDINGS" :
                $catname = "Gedung";
                break;
            case "HUMAN_RESOURCE" :
                $catname = "Tenaga Kependidikan";
                break;
            case "CLEANING_AND_GARDENING" :
                $catname = "Kebersihan";
                break;
            case "INCIDENT_AND_RULE_VIOLATION" :
                $catname = "Insiden";                    
                break;
            case "OTHERS" :
                $catname = "Lain-Lain";                    
                break;
            default:
                $catname = "Tidak Diketahui";
        }
        foreach($surveyord as $svyr){
            $notif = new Notif();
            $notif->user_id = $svyr->user_id;
            $notif->ticket_id = $idnow;
            $notif->content = 'Keluhan Baru dari ' . Auth::user()->name . ', Kategori ' . $catname;
            $notif->readed = 0;
            $notif->save();
        }
        $surveyor = Categoryusers::where('category_id', $ticket->category_id)->with('useres')->get()->pluck('useres.firebasetoken')->toArray();

        $ticket->save();
        $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);

            $notificationBuilder = new PayloadNotificationBuilder('Mahasiswa Telah Membuat Komplain');
            $notificationBuilder->setBody('Oleh : ' . Auth::user()->name . ' | Kategori : ' . $catname)
                                ->setClickAction("NOTIFICATION_TARGET")
                                ->setSound('default');
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['notification_title' => 'Mahasiswa Telah Membuat Komplain',
                                   'notification_body' => 'Oleh : ' . Auth::user()->name . ' | Kategori : ' . $catname, 
                                   'ID' => $idnow]); 
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            $downstreamResponse = FCM::sendTo($surveyor, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            $downstreamResponse->tokensToDelete();
            $downstreamResponse->tokensToModify();
            $downstreamResponse->tokensToRetry();
            $downstreamResponse->tokensWithError();
        if ($image = $request->file('image')) {
            foreach ($image as $files) {
            $imeg = new Image();
            $destinationPath = public_path().'/images/';
            $profileImage = date('YmdHis') . "-" . Str::random(16) . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $imeg->ticket_id = "$idnow";
            $imeg->image = "$profileImage";
            $imeg->save();
        }
        }
        ActivityLogger::activity("Complain was created");
        return response()->json(['success'=>'OK', 'ticket'=>$ticket], $this-> successStatus); 

    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ticket = $this->tickets->findOrFail($id);

        list($category_lists, $status_lists) = $this->PCS();

        $close_perm = $this->permToClose($id);
        $reopen_perm = $this->permToReopen($id);
        $proses_perm = $this->permToProses($id);
        $cat_agents = Models\Category::find($ticket->category_id)->agents()->agentsLists();
        if (is_array($cat_agents)) {
            $agent_lists = ['auto' => 'Auto Select'] + $cat_agents;
        } else {
            $agent_lists = ['auto' => 'Auto Select'];
        }
        $images = $ticket->gambar()->paginate(Setting::grab('paginate_items'));
        $comments = $ticket->comments()->paginate(Setting::grab('paginate_items'));
        $commentscount = $ticket->comments()->count();
                     
        return view('ticketit::tickets.show',
            compact('ticket', 'status_lists', 'category_lists', 'comments', 'images',
                'close_perm', 'reopen_perm', 'proses_perm', 'commentscount'));
    }
    public function jsonshow($id)
    {
        
        // $ticket = $this->tickets->findOrFail($id);
        // $ticket = $this->tickets->selectRaw('*,ticketit.id as t_id')->where('id',$id)->with('status')->first();
         $close_perm = $this->permToClose($id);
         $reopen_perm = $this->permToReopen($id);
         $proses_perm = $this->permToProses($id);
        $ticket = Ticket::select(DB::raw('ticketit.id,ticketit.subject,ticketit.content as keluhan,ticketit.html as keluhan_html_format, ticketit_statuses.name as status, users.name as pelapor, ticketit_categories.name as kategori, ticketit.created_at, ticketit.updated_at'))
                    ->leftJoin('ticketit_statuses', 'ticketit.status_id', '=', 'ticketit_statuses.id')
                    ->leftJoin('users', 'ticketit.user_id', '=', 'users.id')
                    ->leftJoin('ticketit_categories', 'ticketit.category_id', '=', 'ticketit_categories.id')
                    ->where('ticketit.id', $id)
                    ->get();
                    $urlgambar = url('/') . '/images/';
                    $bsd = array('urlgambar'=>$urlgambar);

                    foreach ($ticket as $p) {

                        $l = Image::select('image')
                                  ->where('ticket_id', $p->id)
                                  ->get();
                  $perm['close_permission'] =$close_perm;
                  $perm['reopen_permission'] =$reopen_perm;
                  $perm['proses_permission'] =$proses_perm;
                  $asd[] = array('keluhan'=>$p,'permission'=>$perm, 'lampiran'=>$l);

                  }
                  $surveyor = Categoryusers::where('category_id', $ticket->category_id);
                  return response()->json([
                    'urlimg' => $urlgambar,
                    'result' => $asd,
                    'penanggung_jawab' => $surveyor
                  ], $this-> successStatus);
        // list($category_lists, $status_lists) = $this->PCS();

        // $cat_agents = Models\Category::find($ticket->category_id)->agents()->agentsLists();
        // if (is_array($cat_agents)) {
        //     $agent_lists = ['auto' => 'Auto Select'] + $cat_agents;
        // } else {
        //     $agent_lists = ['auto' => 'Auto Select'];
        // }
        // $images = $ticket->gambar()->paginate(Setting::grab('paginate_items'));
        // $comments = $ticket->comments()->paginate(Setting::grab('paginate_items'));

        return response()->json(['success' => $ticket], $this-> successStatus); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject'     => 'required|min:3',
            'content'     => 'required|min:6',
            // 'priority_id' => 'required|exists:ticketit_priorities,id',
            'category_id' => 'required|exists:ticketit_categories,id',
            'status_id'   => 'required|exists:ticketit_statuses,id',
            // 'agent_id'    => 'required',
        ]);

        $ticket = $this->tickets->findOrFail($id);

        $ticket->subject = $request->subject;

        $ticket->setPurifiedContent($request->get('content'));

        $ticket->status_id = $request->status_id;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = 1;
        $ticket->agent_id = 1;
        // if ($request->input('agent_id') == 'auto') {
        //     $ticket->autoSelectAgent();
        // } else {
        //     $ticket->agent_id = $request->input('agent_id');
        // }

        $ticket->save();

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-modified'));

        return redirect()->route(Setting::grab('main_route').'.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ticket = $this->tickets->findOrFail($id);
        $subject = $ticket->subject;
        $gambar = Image::Where('ticket_id', $id)->delete();
        $komena = Comment::where('ticket_id',$id)->delete();
        $ticket->delete();
        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-deleted', ['name' => $subject]));

        return redirect()->route(Setting::grab('main_route').'.index');
    }

    public function jsondelete($id)
    {
        $ticket = $this->tickets->findOrFail($id);
        $close_perm = $this->permToClose($id);
        if($close_perm == "no"){
        return response()->json(['status'=>'Unauthorized'], 401); 
        }
        $gambar = Image::Where('ticket_id', $id)->delete();
        $subject = $ticket->subject;
        $komena = Comment::where('ticket_id',$id)->delete();

        $ticket->delete();

        return response()->json(['status'=>'OK'], $this-> successStatus); 
    }
    /**
     * Mark ticket as complete.
     *
     * @param int $id
     *
     * @return Response
     */
    public function complete($id)
    {
        if ($this->permToClose($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->completed_at = Carbon::now();

            if (Setting::grab('default_close_status_id')) {
                $ticket->status_id = Setting::grab('default_close_status_id');
            }
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);
            $user = Auth::user(); 
            $fbtoke = User::select('firebasetoken')->where('id', $ticket->user_id)->first();
            $fbtoken = $fbtoke->firebasetoken;
            $subject = $ticket->subject;
            $ticket->save();
            $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda : ' . $ticket->subject);
            $notificationBuilder->setBody('Telah Diselesaikan oleh : ' . $user->name)
            ->setClickAction("NOTIFICATION_TARGET")
            ->setSound('default');
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['notification_title' => 'Keluhan anda : ' . $ticket->subject,
            'notification_body' => 'Telah Diselesaikan oleh : ' . $user->name , 'ID' => $id]);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();


            $downstreamResponse = FCM::sendTo($fbtoken, $option, $notification, $data);

            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            $downstreamResponse->tokensToDelete();
            $downstreamResponse->tokensToModify();
            $downstreamResponse->tokensToRetry();
            $downstreamResponse->tokensWithError();
            session()->flash('status', trans('ticketit::lang.the-ticket-has-been-completed', ['name' => $subject]));

            return redirect()->route(Setting::grab('main_route').'.index');
        }

        return redirect()->route(Setting::grab('main_route').'.index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-do-this'));
    }

    /**
     * Reopen ticket from complete status.
     *
     * @param int $id
     *
     * @return Response
     */
    public function reopen($id)
    {
        if ($this->permToReopen($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->completed_at = null;

            if (Setting::grab('default_reopen_status_id')) {
                $ticket->status_id = Setting::grab('default_reopen_status_id');
            }
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);
            $user = Auth::user(); 
            $fbtoke = User::select('firebasetoken')->where('id', $ticket->user_id)->first();
            $fbtoken = $fbtoke->firebasetoken;
            $subject = $ticket->subject;
            $ticket->save();
            $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda : ' . $ticket->subject);
            $notificationBuilder->setBody('Telah Dibukakembali oleh : ' . $user->name)
            ->setClickAction("NOTIFICATION_TARGET")
            ->setSound('default');
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['notification_title' => 'Keluhan anda : ' . $ticket->subject,
            'notification_body' => 'Telah Dibukakembali oleh : ' . $user->name , 'ID' => $id]);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();


            $downstreamResponse = FCM::sendTo($fbtoken, $option, $notification, $data);

            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            $downstreamResponse->tokensToDelete();
            $downstreamResponse->tokensToModify();
            $downstreamResponse->tokensToRetry();
            $downstreamResponse->tokensWithError();
            session()->flash('status', trans('ticketit::lang.the-ticket-has-been-reopened', ['name' => $subject]));

            return redirect()->route(Setting::grab('main_route').'.index');
        }

        return redirect()->route(Setting::grab('main_route').'.index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-do-this'));
    }

    public function proses($id)
    {
        if ($this->permToProses($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->updated_at = Carbon::now();

            if (Setting::grab('default_proses_status_id')) {
                $ticket->status_id = Setting::grab('default_proses_status_id');
            }
            $user = Auth::user(); 
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);
            $fbtoke = User::select('firebasetoken')->where('id', $ticket->user_id)->first();
            $fbtoken = $fbtoke->firebasetoken;
            $subject = $ticket->subject;
            $ticket->save();
            $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda : ' . $ticket->subject);
            $notificationBuilder->setBody('Sedang Diproses oleh : ' . $user->name)
            ->setClickAction("NOTIFICATION_TARGET")
            ->setSound('default');
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['notification_title' => 'Keluhan anda : ' . $ticket->subject,
            'notification_body' => 'Sedang Diproses oleh : ' . $user->name, 'ID' => $id]);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();


            $downstreamResponse = FCM::sendTo($fbtoken, $option, $notification, $data);

            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            $downstreamResponse->tokensToDelete();
            $downstreamResponse->tokensToModify();
            $downstreamResponse->tokensToRetry();
            $downstreamResponse->tokensWithError();
            session()->flash('status', trans('ticketit::lang.the-ticket-has-been-processed', ['name' => $subject]));

            return redirect()->route(Setting::grab('main_route').'.index');
        }

        return redirect()->route(Setting::grab('main_route').'.index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-do-this'));
    }

    public function jsonReopen($id)
    {
        if ($this->permToReopen($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->updated_at = Carbon::now();
            $ticket->completed_at = null;

            if (Setting::grab('default_reopen_status_id')) {
                $ticket->status_id = Setting::grab('default_reopen_status_id');
            }

            $subject = $ticket->subject;
            $catname = Category::select('name')->where('id', $ticket->category_id)->first();
            $fbtoke = User::select('firebasetoken')->where('id', $ticket->user_id)->first();
            $fbtoken = $fbtoke->firebasetoken;
            $ticket->save();
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60*20);
                    $user = Auth::user(); 
                    $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda : ' . $ticket->subject);
                    $notificationBuilder->setBody('Telah Dibukakembali oleh : ' . $user->name)
                    ->setClickAction("NOTIFICATION_TARGET")
                    ->setSound('default');
                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['notification_title' => 'Keluhan anda : ' . $ticket->subject,
                    'notification_body' => 'Telah Dibukakembali oleh : ' . $user->name, 'ID' => $id]);

                    $option = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data = $dataBuilder->build();


                    $downstreamResponse = FCM::sendTo($fbtoken, $option, $notification, $data);

                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                    $downstreamResponse->tokensToDelete();
                    $downstreamResponse->tokensToModify();
                    $downstreamResponse->tokensToRetry();
                    $downstreamResponse->tokensWithError();


            return response()->json(['message' => 'Complain Reopened', 'ticket' => $subject], 200); 
        }

            return response()->json(['message' => 'You are not Allowed to Reopen this Complain'], 401); 
    }

    public function jsonProses($id)
    {

        if ($this->permToProses($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->updated_at = Carbon::now();

            if (Setting::grab('default_proses_status_id')) {
                $ticket->status_id = Setting::grab('default_proses_status_id');
            }

            $subject = $ticket->subject;
            $catname = Category::select('name')->where('id', $ticket->category_id)->first();
            $fbtoke = User::select('firebasetoken')->where('id', $ticket->user_id)->first();
            $fbtoken = $fbtoke->firebasetoken;
            $ticket->save();
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60*20);
                    $user = Auth::user(); 
                    $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda : ' . $ticket->subject);
                    $notificationBuilder->setBody('Telah Diproses oleh : ' . $user->name)
                                        ->setSound('default');
                    

                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['notification_title' => 'Keluhan anda : ' . $ticket->subject,
                    'notification_body' => 'Sedang Diproses oleh : ' . $user->name . $catname, 'ID' => $id]); 

                    $option = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data = $dataBuilder->build();


                    $downstreamResponse = FCM::sendTo($fbtoken, $option, $notification, $data);

                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                    $downstreamResponse->tokensToDelete();
                    $downstreamResponse->tokensToModify();
                    $downstreamResponse->tokensToRetry();
                    $downstreamResponse->tokensWithError();

            //eof notification manager


            return response()->json(['message' => 'Complain Processed', 'ticket' =>$downstreamResponse->numberSuccess()], 200); 
        }

            return response()->json(['message' => 'You are not Allowed to Process this Complain'], 401); 
    }
    public function jsonComplete($id)
    {
        if ($this->permToClose($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->completed_at = Carbon::now();

            if (Setting::grab('default_close_status_id')) {
                $ticket->status_id = Setting::grab('default_close_status_id');
            }

            $subject = $ticket->subject;
            $catname = Category::select('name')->where('id', $ticket->category_id)->first();
            $fbtoke = User::select('firebasetoken')->where('id', $ticket->user_id)->first();
            $fbtoken = $fbtoke->firebasetoken;
            $ticket->save();
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60*20);
                    $user = Auth::user(); 
                    $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda : ' . $ticket->subject);
                    $notificationBuilder->setBody('Telah Diselesaikan oleh : ' . $user->name)
                    ->setClickAction("NOTIFICATION_TARGET")
                    ->setSound('default');
                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['notification_title' => 'Keluhan anda : ' . $ticket->subject,
                    'notification_body' => 'Telah Diselesaikan oleh : ' . $user->name . $catname, 'ID' => $id]); 
                    $option = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data = $dataBuilder->build();


                    $downstreamResponse = FCM::sendTo($fbtoken, $option, $notification, $data);

                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                    $downstreamResponse->tokensToDelete();
                    $downstreamResponse->tokensToModify();
                    $downstreamResponse->tokensToRetry();
                    $downstreamResponse->tokensWithError();


            return response()->json(['message' => 'Complain Completed', 'ticket' => $subject], 200); 
        }

            return response()->json(['message' => 'You are not Allowed to Complete this Complain'], 401); 
    }
    public function agentSelectList($category_id, $ticket_id)
    {
        $cat_agents = Models\Category::find($category_id)->agents()->agentsLists();
        if (is_array($cat_agents)) {
            $agents = ['auto' => 'Auto Select'] + $cat_agents;
        } else {
            $agents = ['auto' => 'Auto Select'];
        }

        $selected_Agent = $this->tickets->find($ticket_id)->agent->id;
        $select = '<select class="form-control" id="agent_id" name="agent_id">';
        foreach ($agents as $id => $name) {
            $selected = ($id == $selected_Agent) ? 'selected' : '';
            $select .= '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
        }
        $select .= '</select>';

        return $select;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function permToClose($id)
    {
        $close_ticket_perm = Setting::grab('close_ticket_perm');

        if ($this->agent->isAdmin() && $close_ticket_perm['admin'] == 'yes') {
            return 'yes';
        }
        if ($this->agent->isAgent() && $close_ticket_perm['agent'] == 'yes') {
            return 'yes';
        }
        if ($this->agent->isTicketOwner($id) && $close_ticket_perm['owner'] == 'yes') {
            return 'yes';
        }

        return 'no';
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function permToReopen($id)
    {
        $reopen_ticket_perm = Setting::grab('reopen_ticket_perm');
        if ($this->agent->isAdmin() && $reopen_ticket_perm['admin'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isAgent() && $reopen_ticket_perm['agent'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isTicketOwner($id) && $reopen_ticket_perm['owner'] == 'yes') {
            return 'yes';
        }

        return 'no';
    }
    public function permToProses($id)
    {
        $proses_ticket_perm = Setting::grab('proses_ticket_perm');
        if ($this->agent->isAdmin() && $proses_ticket_perm['admin'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isAgent() && $proses_ticket_perm['agent'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isTicketOwner($id) && $proses_ticket_perm['owner'] == 'yes') {
            return 'yes';
        }

        return 'no';
    }
    /**
     * Calculate average closing period of days per category for number of months.
     *
     * @param int $period
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function monthlyPerfomance($period = 2)
    {
        $categories = Category::all();
        foreach ($categories as $cat) {
            $records['categories'][] = $cat->name;
        }

        for ($m = $period; $m >= 0; $m--) {
            $from = Carbon::now();
            $from->day = 1;
            $from->subMonth($m);
            $to = Carbon::now();
            $to->day = 1;
            $to->subMonth($m);
            $to->endOfMonth();
            $records['interval'][$from->format('F Y')] = [];
            foreach ($categories as $cat) {
                $records['interval'][$from->format('F Y')][] = round($this->intervalPerformance($from, $to, $cat->id), 1);
            }
        }

        return $records;
    }

    /**
     * Calculate the date length it took to solve a ticket.
     *
     * @param Ticket $ticket
     *
     * @return int|false
     */
    public function ticketPerformance($ticket)
    {
        if ($ticket->completed_at == null) {
            return false;
        }

        $created = new Carbon($ticket->created_at);
        $completed = new Carbon($ticket->completed_at);
        $length = $created->diff($completed)->days;

        return $length;
    }

    /**
     * Calculate the average date length it took to solve tickets within date period.
     *
     * @param $from
     * @param $to
     *
     * @return int
     */
    public function intervalPerformance($from, $to, $cat_id = false)
    {
        if ($cat_id) {
            $tickets = Ticket::where('category_id', $cat_id)->whereBetween('completed_at', [$from, $to])->get();
        } else {
            $tickets = Ticket::whereBetween('completed_at', [$from, $to])->get();
        }

        if (empty($tickets->first())) {
            return false;
        }

        $performance_count = 0;
        $counter = 0;
        foreach ($tickets as $ticket) {
            $performance_count += $this->ticketPerformance($ticket);
            $counter++;
        }
        $performance_average = $performance_count / $counter;

        return $performance_average;
    }
}
