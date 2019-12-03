<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Status;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Illuminate\Support\Facades\Auth; 
use App\User;
use App\Categoryusers;
use DB;
use Carbon\Carbon;
use Toast;
use Kordy\Ticketit\Models\Ticket;
class RecapController extends Controller
{
    public function index()
    {
        $complete = false;
        $time = LaravelVersion::min('5.8') ? 60*60 : 60;
        $kat = DB::select("SELECT @id := id as 'id' ,alternate as 'nk', (SELECT COUNT(id) from  ticketit where category_id = @id) as 'jk',(SELECT COUNT(id) from  ticketit where category_id = @id AND ticketit.completed_at IS NOT NULL) as 'jkt', (SELECT COUNT(id) from  ticketit where category_id = @id AND ticketit.completed_at IS NOT NULL) as 'jkbt' FROM `ticketit_categories` WHERE 1;");
        $categories_all = Category::all();
        $katlabel = Category::all()->pluck('alternate');
        $month_count = [];
        $mk = array();
        $ck = DB::select("SELECT @id := id as 'id' ,alternate as 'nk', (SELECT COUNT(id) from  ticketit where status_id = @id) as 'jk' FROM `ticketit_statuses` WHERE 1");
        $ck2 = DB::select("SELECT @id := id as 'id' ,alternate as 'nk', (SELECT COUNT(id) from ticketit where category_id = @id) as 'jk' FROM `ticketit_categories` WHERE 1 ");
        $countkat = collect($ck)->pluck('jk');
        $countkat2 = collect($ck2)->pluck('jk');
        $category = Category::all();
        $labelkat = Status::all()->pluck('alternate');
        $colorkat = Status::all()->pluck('color');
        $colorkat2 = Category::all()->pluck('color');
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($months as $tkt) {
            $c = 0;
            $date = Carbon::createFromIsoFormat('M', $tkt, 'UTC');
            $month_count[$tkt]['timestamper'] = $date->isoFormat('YYYY-MM');
            $month_count[$tkt]['timestamp'] = $date->isoFormat('MMMM');
            foreach ($categories_all as $cate) {
                $rdc = Ticket::whereMonth('created_at',$tkt)->whereYear('created_at',Carbon::now()->format('Y'))->where('category_id',$cate->id)->count();

                $month_count[$tkt][$cate->alternate] = $rdc;            
                $c += $rdc;
            }
            $month_count[$tkt]['total'] = $c;
            }
            foreach ($month_count as $tkt) {
                $mk[]=array('timestamp'=> $tkt['timestamper'], 'a' => $tkt['Sarana dan Prasarana'], 'b' => $tkt['Bangunan'], 'c' => $tkt['Tenaga Kependidikan'], 'd' => $tkt['Kebersihan'], 'e' => $tkt['Insiden'], 'f' => $tkt['Dan Lain Lain']);
                }
            foreach ($category as $kate){
                
            }
            $complete = false;
            if (Auth::user()->ticketit_admin == 0 && Auth::user()->ticketit_agent == 0){

                return view('unauth');
            }
            else if(Auth::user()->ticketit_agent == 1 && Auth::user()->ticketit_admin == 0){
                $surveyor = Categoryusers::select('*')
                ->where('user_id', Auth::user()->id)
                ->with('details')
                ->get();
                // session()->flash('warning', 'You are Unauthorized');
                // session()->TOAST::warning('warning', 'You are Unauthorized');
                // Toast::clear();
                // Toast::warning('Unauthorized', 'Anda tidak dapat melihat page ini | ');
                return view('ticketit::admin.recap.agentindex',  compact('kat','month_count','surveyor' , 'labelkat', 'colorkat', 'colorkat2', 'countkat', 'countkat2', 'katlabel','mk'));

            }
            else{
                return view('ticketit::admin.recap.index', compact('kat','month_count', 'labelkat', 'colorkat', 'colorkat2', 'countkat', 'countkat2', 'katlabel','mk'));
            }
    }
    public function agentRedata(Request $request){
        $reqID = $request->catID;
        $complete = false;
        $time = LaravelVersion::min('5.8') ? 60*60 : 60;
        $categories_all = Category::where('id', $reqID);
        $katlabel = Category::where('id', $reqID)->pluck('alternate');
        $month_count = [];
        $mk = array();
        $ck2 = DB::select("SELECT @id := id as 'id' ,alternate as 'nk', (SELECT COUNT(id) from ticketit where status_id = @id AND category_id = $reqID) as 'jk' FROM `ticketit_statuses` WHERE 1 ");
        $countkat = collect($ck2)->pluck('jk');
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($months as $tkt) {
            $c = 0;
            $date = Carbon::createFromIsoFormat('M', $tkt, 'UTC');
            $month_count[$tkt]['timestamper'] = $date->isoFormat('YYYY-MM');
            $month_count[$tkt]['timestamp'] = $date->isoFormat('MMMM');
            $rdc = Ticket::whereMonth('created_at',$tkt)->whereYear('created_at',Carbon::now()->format('Y'))->where('category_id',$reqID)->count();
            $month_count[$tkt]['amount'] = $rdc;  
            }
            foreach ($month_count as $tkt) {
                $mk[]=array('timestamp'=> $tkt['timestamper'], 'amount' => $tkt['amount'], 'name' => $katlabel);
                }
        return response()->json([$mk, $countkat], 200); 
    }
}
