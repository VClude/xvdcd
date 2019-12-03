<?php

namespace Kordy\Ticketit\Controllers;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Http\Controllers\Controller;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Ticket;
use App\Notif;
use App\User; 
use App\Categoryusers;
use Illuminate\Support\Facades\Auth; 
class DashboardController extends Controller
{
    public function index($indicator_period = 2)
    {
        $reqID = Auth::user()->id;
        $notif = Notif::where('user_id', $reqID)->get();
        if (Auth::user()->ticketit_admin == 0 && Auth::user()->ticketit_agent == 0){
            return view('unauth');
        }
        else if(Auth::user()->ticketit_agent == 1 && Auth::user()->ticketit_admin == 0){
            $surveyor = Categoryusers::select('*')
            ->where('user_id', Auth::user()->id)
            ->with('details')
            ->get();
            $surveyorg = Categoryusers::select('*')
            ->where('user_id', Auth::user()->id)
            ->with('details')
            ->first();
                    if($surveyorg == null){
                        return view('ticketit::admin.index404');
                    }
                    else{
                        $surveyorg = $surveyorg->pluck('category_id');
                        $reqID = $surveyorg;
                    }
            
            $tickets_count = Ticket::where('category_id', $reqID)->count();
            $open_tickets_count = Ticket::where('category_id', $reqID)->whereNull('completed_at')->count();
            $closed_tickets_count = Ticket::where('category_id', $reqID)->whereNotNull('completed_at')->count();
            $tkpr = Ticket::where('category_id', $reqID)->where('status_id', '2')->count();
            $tkro = Ticket::where('category_id', $reqID)->where('status_id', '4')->count();
            return view('ticketit::admin.agentindex', compact('surveyor','tickets_count','open_tickets_count','closed_tickets_count','tkpr','tkro', 'notif'));
        }
        
        else{

        $now = CarbonImmutable::now();
        $wst = $now->startOfWeek();
        $wed = $now->endOfWeek();
        $week_tickets_count = Ticket::alliweekly()
        ->count();
        $tickets_count = Ticket::count();
        $week_open_tickets_count = Ticket::activeweekly()->count();
        $open_tickets_count = Ticket::whereNull('completed_at')->count();
        $week_closed_tickets_count = Ticket::completeweekly()->count();
        $closed_tickets_count = $tickets_count - $open_tickets_count;
        if($tickets_count == 0){
        $percentage_completion = 'Belum ada Tiket';
        }
        else{
        $sd = ($tickets_count -  $open_tickets_count) / $tickets_count * 100;
        $percentage_completion = (int)$sd;
        }
        // Per Category pagination
        $categories = Category::paginate(10, ['*'], 'cat_page');
        // Total tickets counter per category for google pie chart
        $categories_all = Category::all();
        $categories_name = Category::all()->pluck('name');
        $categories_color = Category::all()->pluck('color');
        $categories_share = [];

        $dsa = 0;
        $dsaname = "";
        $categories_all = Category::all();
        $month_count = [];
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($months as $tkt) {
            $ads = Carbon::now()->format('Y') . '-' . $tkt;
            $month_count[$tkt]['timestamp'] = $ads;
            foreach ($categories_all as $cate) {
                $month_count[$tkt][$cate->name] = Ticket::whereMonth('created_at',$tkt)->whereYear('created_at',Carbon::now()->format('Y'))->where('category_id',$cate->id)->count();
            }
            
            }
            
            $month_json = json_encode([
                'monthly_report'    => $month_count
            ]);
        foreach ($categories_all as $cat) {
            $categories_share[$cat->name] = $cat->tickets()->count();
            if ($dsa < $cat->tickets()->count()){
                $dsa = $cat->tickets()->count();
                $dsaname = $cat->name;
            }
        }
        switch ($dsaname){
            case "FACILITIES_AND_INFRASTRUCTURE" :
                $dsaname = "Sarana dan Prasarana";
                break;
            case "BUILDINGS" :
                $dsaname = "Gedung";
                break;
            case "HUMAN_RESOURCE" :
                $dsaname = "Tenaga Kependidikan";
                break;
            case "CLEANING_AND_GARDENING" :
                $dsaname = "Kebersihan";
                break;
            case "INCIDENT_AND_RULE_VIOLATION" :
                $dsaname = "Insiden";                    
                break;
            case "OTHERS" :
                $dsaname = "Lain-Lain";                    
                break;
            default:
                $dsaname = "Tidak Diketahui";
        }
        // Total tickets counter per agent for google pie chart
        $agents_share_obj = Agent::agents()->with(['agentTotalTickets' => function ($query) {
            $query->addSelect(['id', 'agent_id']);
        }])->get();

        $agents_share = [];
        foreach ($agents_share_obj as $agent_share) {
            $agents_share[$agent_share->name] = $agent_share->agentTotalTickets->count();
        }

        // Per Agent
        $agents = Agent::agents(10);

        // Per User
        $users = Agent::users(10);

        // Per Category performance data
        $ticketController = new TicketsController(new Ticket(), new Agent());
        $monthly_performance = $ticketController->monthlyPerfomance($indicator_period);

        if (request()->has('cat_page')) {
            $active_tab = 'cat';
        } elseif (request()->has('agents_page')) {
            $active_tab = 'agents';
        } elseif (request()->has('users_page')) {
            $active_tab = 'users';
        } else {
            $active_tab = 'cat';
        }

        return view(
            'ticketit::admin.index',
            compact(
                'open_tickets_count',
                'closed_tickets_count',
                'tickets_count',
                'percentage_completion',
                'week_open_tickets_count',
                'week_closed_tickets_count',
                'week_tickets_count',
                'dsaname',
                'categories_name',
                'categories_color',
                'categories',
                'agents',
                'users',
                'categories_all',
                'monthly_performance',
                'month_json',
                'categories_share',
                'agents_share',
                'active_tab',
                'notif'
            ));
    }
}
}
