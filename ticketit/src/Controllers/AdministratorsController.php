<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Setting;
use Illuminate\Support\Facades\Auth; 
use App\User;
use DB;
use Carbon\Carbon;
use Toast;
class AdministratorsController extends Controller
{
    public function index()
    {
        if (Auth::user()->ticketit_admin == 0 && Auth::user()->ticketit_agent == 0){

            return view('unauth');
        }
        else if(Auth::user()->ticketit_agent == 1 && Auth::user()->ticketit_admin == 0){
            $complete = 0;
            Toast::warning('Unauthorized', 'Anda tidak dapat melihat page ini | ');
            return view('ticketit::admin.index404');

        }
        else{
            $administrators = Agent::admins();

            return view('ticketit::admin.administrator.index', compact('administrators'));
        }

    }
    public function indexDesute()
    {
        if (Auth::user()->ticketit_admin == 0 && Auth::user()->ticketit_agent == 0){

            return view('unauth');
        }
        else if(Auth::user()->ticketit_agent == 1 && Auth::user()->ticketit_admin == 0){
            $complete = 0;
            Toast::warning('Unauthorized', 'Anda tidak dapat melihat page ini | ');
            return view('ticketit::admin.index404');

        }
        else{
            $administrators = User::all();

            return view('ticketit::admin.administrator.index', compact('administrators'));
        }

    }
    public function create()
    {
        $users = Agent::paginate(Setting::grab('paginate_items'));

        return view('ticketit::admin.administrator.create', compact('users'));
    }
    public function changepass()
    {
        return view('ticketit::misc.chgpw');
    }
    public function store(Request $request)
    {
        $administrators_list = $this->addAdministrators($request->input('administrators'));
        $administrators_names = implode(',', $administrators_list);

        Session::flash('status', trans('ticketit::lang.administrators-are-added-to-administrators', ['names' => $administrators_names]));

        return redirect()->action('\Kordy\Ticketit\Controllers\AdministratorsController@index');
    }

    public function update($id, Request $request)
    {
        $this->syncAdministratorCategories($id, $request);

        Session::flash('status', trans('ticketit::lang.administrators-joined-categories-ok'));

        return redirect()->action('\Kordy\Ticketit\Controllers\AdministratorsController@index');
    }

    public function destroy($id)
    {
        $administrator = $this->removeAdministrator($id);

        Session::flash('status', trans('ticketit::lang.administrators-is-removed-from-team', ['name' => $administrator->name]));

        return redirect()->action('\Kordy\Ticketit\Controllers\AdministratorsController@index');
    }

    /**
     * Assign users as administrators.
     *
     * @param $user_ids
     *
     * @return array
     */
    public function addAdministrators($user_ids)
    {
        $users = Agent::find($user_ids);
        foreach ($users as $user) {
            $user->ticketit_admin = true;
            $user->save();
            $users_list[] = $user->name;
            
        }

        return $users_list;
    }

    /**
     * Remove user from the administrators.
     *
     * @param $id
     *
     * @return mixed
     */
    public function removeAdministrator($id)
    {
        $administrator = Agent::find($id);
        $administrator->ticketit_admin = false;
        $administrator->save();

        // Remove him from tickets categories as well
        if (version_compare(app()->version(), '5.2.0', '>=')) {
            $administrator_cats = $administrator->categories->pluck('id')->toArray();
        } else { // if Laravel 5.1
            $administrator_cats = $administrator->categories->lists('id')->toArray();
        }

        $administrator->categories()->detach($administrator_cats);

        return $administrator;
    }

    /**
     * Sync Administrator categories with the selected categories got from update form.
     *
     * @param $id
     * @param Request $request
     */
    public function syncAdministratorCategories($id, Request $request)
    {
        $form_cats = ($request->input('administrator_cats') == null) ? [] : $request->input('administrator_cats');
        $administrator = Agent::find($id);
        $administrator->categories()->sync($form_cats);
    }
}
