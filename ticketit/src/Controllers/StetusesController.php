<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kordy\Ticketit\Models\Status;
use Kordy\Ticketit\Models\Ticket;
use Kordy\Ticketit\Models\Setting;
use Illuminate\Support\Facades\Hash;
use App\User;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Toast;
class StetusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // seconds expected for L5.8<=, minutes before that
        $time = LaravelVersion::min('5.8') ? 60*60 : 60;
        $statuses = \Cache::remember('ticketit::statuses', $time, function () {
            return Status::all();
        });

        return view('ticketit::admin.status.index', compact('statuses'));
    }
    public function userList()
    {
        // seconds expected for L5.8<=, minutes before that
        $time = LaravelVersion::min('5.8') ? 60*60 : 60;
        $statuses = User::where('ticketit_admin', 1)->where('ticketit_agent', 1)->paginate(10);

        return view('ticketit::admin.user.index', compact('statuses'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $new = true;
        return view('ticketit::admin.user.create', compact('new'));
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
            'name'      => 'required',
            'email'     => 'required',
            'identitas' => 'required',
            'noidentitas' => 'required',
            'password'  => 'required'
        ]);

        $data = new User();
        $data->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'identitas' => $request['identitas'],
            'noidentitas' => $request['noidentitas'],
            'password' => Hash::make($request['password']),
        ]);

        Session::flash('status', 'user has been Created');

        \Cache::forget('ticketit::users');

        return redirect()->action('\Kordy\Ticketit\Controllers\StatusesController@userList');
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
        return trans('ticketit::lang.status-all-tickets-here');
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
        $new = false;
        $status = User::findOrFail($id);
        if($status->ticketit_admin == 1){
            Toast::warning('Anda Tidak Bisa Mengubah data Manager');
            $statuses = User::paginate(10);
            return view('ticketit::admin.user.index', compact('statuses'));
        }
        return view('ticketit::admin.user.edit', compact('status','new'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email' => 'required',
            'noidentitas'     => 'required',
        ]);

        $status = User::findOrFail($id);
        $status->update(['name' => $request->name, 'email' => $request->email ,'noidentitas' => $request->noidentitas]);

        Session::flash('status', 'User Detail telah diupdate ');

        \Cache::forget('ticketit::users');

        return redirect()->action('\Kordy\Ticketit\Controllers\StatusesController@userList');
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
        $status = User::findOrFail($id);
        $gotticket = Ticket::where('user_id', $id)->first();
        if($status->ticketit_admin == 1){
            Toast::warning('Anda Tidak Bisa Menghapus Manager');
            $statuses = User::paginate(10);
            return view('ticketit::admin.user.index', compact('statuses'));
        }
        elseif($status->ticketit_agent == 1){
            Toast::warning('Anda Tidak Bisa Menghapus Surveyor');
            $statuses = User::paginate(10);
            return view('ticketit::admin.user.index', compact('statuses'));
        }
        elseif($gotticket !== null){
            Toast::warning('Akun ini Sedang mempunyai Keluhan');
            $statuses = User::paginate(10);
            return view('ticketit::admin.user.index', compact('statuses')); 
        }
        else{
        $name = $status->name;
        $status->delete();

        Session::flash('status', 'User Telah dihapus');

        \Cache::forget('ticketit::statuses');

        return redirect()->action('\Kordy\Ticketit\Controllers\StatusesController@userList');
        }
    }
}
