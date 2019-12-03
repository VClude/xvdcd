<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kordy\Ticketit\Models;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Ticket;
use Kordy\Ticketit\Models\Comment;
use Kordy\Ticketit\Models\Image;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use FCM;
class CommentsController extends Controller
{
    protected $comment;
    public function __construct(Comment $comment)
    {
        $this->middleware('Kordy\Ticketit\Middleware\IsAdminMiddleware', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('Kordy\Ticketit\Middleware\ResAccessMiddleware', ['only' => 'store']);
        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ticket_id'   => 'required|exists:ticketit,id',
            'content'     => 'required|min:1',
        ]);

        $comment = new Models\Comment();
        // $catname = Category::select('name')->where('id', $ticket->category_id)->first();
        $userid = Ticket::select('user_id')->where('id', $request->get('ticket_id'))->first();
        $fbtoke = User::select('firebasetoken')->where('id', $userid->user_id)->first();
        $fbtoken = $fbtoke->firebasetoken;
        $comment->setPurifiedContent($request->get('content'));

        $comment->ticket_id = $request->get('ticket_id');
        $comment->user_id = \Auth::user()->id;
        $comment->save();

        $ticket = Models\Ticket::find($comment->ticket_id);
        $ticket->updated_at = $comment->created_at;
        $ticket->save();
        if(!$fbtoken == ""){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda Dikomentari');
        $notificationBuilder->setBody(Auth::user()->name . ' : ' . $request->get('content'))
                            ->setClickAction("NOTIFICATION_TARGET")
                            ->setSound('default');
        

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['notification_title' => 'Keluhan Anda Dikomentari',
                                'notification_body' => Auth::user()->name . ' : ' . $request->get('content'), 'ID' => $comment->ticket_id]); 

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
        }

        return back()->with('status', trans('ticketit::lang.comment-has-been-added-ok'));
    }


    public function jsonstore(Request $request)
    {
        $asd = Validator::make($request->all(), [ 
            'ticket_id'   => 'required|exists:ticketit,id',
            'content'     => 'required|min:6'
        ]);
        if ($asd->fails()) { 
            return response()->json(['error'=>$asd->errors()], 401);            
        }
        $comment = new Models\Comment();
        // $catname = Category::select('name')->where('id', $ticket->category_id)->first();
        $userid = Ticket::select('user_id')->where('id', $request->get('ticket_id'))->first();
        $fbtoke = User::select('firebasetoken')->where('id', $userid->user_id)->first();
        $fbtoken = $fbtoke->firebasetoken;
        $comment->setPurifiedContent($request->get('content'));

        $comment->ticket_id = $request->get('ticket_id');
        $comment->user_id = \Auth::user()->id;
        $comment->save();

        $ticket = Models\Ticket::find($comment->ticket_id);
        $ticket->updated_at = $comment->created_at;
        $ticket->save();
        if(!$fbtoken == ""){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder('Keluhan anda Dikomentari');
        $notificationBuilder->setBody(Auth::user()->name . ' : ' . $request->get('content'))
                            ->setClickAction("NOTIFICATION_TARGET")
                            ->setSound('default');
        

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['notification_title' => 'Keluhan Anda Dikomentari',
                                'notification_body' => Auth::user()->name . ' : ' . $request->get('content'), 'ID' => $comment->ticket_id]); 

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
        }
        return response()->json(['success'=>'OK', 'comment'=>$comment], 200); 

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
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
        //
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

        $ticket = $this->comment->findOrFail($id);
        if(Auth::user()->ticketit_admin == 1 || Auth::user()->id == $ticket->user_id ){
        $ticket->delete();
        session()->flash('status', 'Komentar terhapus', 'sukses');

        return back();
        }
        else{
            session()->flash('warning', 'No Permission to remove Comments', 'Failed');

            return back();
        }
        //
    }
}
