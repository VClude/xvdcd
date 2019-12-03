<?php

namespace Kordy\Ticketit\Models;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;
use Kordy\Ticketit\Traits\ContentEllipse;
use Kordy\Ticketit\Traits\Purifiable;
use App\User; 
use App\Categoryusers;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth; 
class Ticket extends Model
{
    use ContentEllipse;
    use Purifiable;
    protected $table = 'ticketit';
    protected $dates = ['completed_at'];

    /**
     * List of completed tickets.
     *
     * @return bool
     */
    public function hasComments()
    {
        return (bool) count($this->comments);
    }

    public function isComplete()
    {
        return (bool) $this->completed_at;
    }
    public function getStatus()
    {
        return (int) $this->status_id;
    }
    /**
     * List of completed tickets.
     *
     * @return Collection
     */


    public function scopeAlliakbar($query)
    {
        $user = Auth::user();
        if(Auth::user()->ticketit_admin == 1){
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query;
        }
        
        else{
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query->whereIn('category_id',$ticketa);
        }
    }
    public function scopeAlliweekly($query)
    {
        $user = Auth::user();
        $ena = CarbonImmutable::now()->locale('en_US');
        if(Auth::user()->ticketit_admin == 1){
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query->whereBetween('ticketit.created_at', [$ena->startOfWeek()->format('Y-m-d H:i'), $ena->endOfWeek()->format('Y-m-d H:i')]);
        }
        else{
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query->whereIn('category_id',$ticketa)->whereBetween('ticketit.created_at', [$ena->startOfWeek()->format('Y-m-d H:i'), $ena->endOfWeek()->format('Y-m-d H:i')]);
        }

        
    }
    public function scopeComplete($query)
    {
        $user = Auth::user();
        if(Auth::user()->ticketit_admin == 1){
            return $query->whereNotNull('completed_at');
        }
        else{
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query->whereIn('category_id',$ticketa)->whereNotNull('completed_at');

        }

    }
    public function scopeCompleteweekly($query)
    {
        $user = Auth::user();
        $ena = CarbonImmutable::now()->locale('en_US');
        if(Auth::user()->ticketit_admin == 1){
            return $query->whereNotNull('completed_at')->whereBetween('ticketit.updated_at', [$ena->startOfWeek()->format('Y-m-d H:i'), $ena->endOfWeek()->format('Y-m-d H:i')]);
        }
        else{
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query->whereNotNull('completed_at')->whereIn('category_id',$ticketa)->whereBetween('ticketit.updated_at', [$ena->startOfWeek()->format('Y-m-d H:i'), $ena->endOfWeek()->format('Y-m-d H:i')]);

        }
    }
    public function scopeActive($query)
    {
        $user = Auth::user();
        if(Auth::user()->ticketit_admin == 1){
            return $query->whereNull('completed_at');
        }
        else{
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query->whereIn('category_id',$ticketa)->whereNull('completed_at');

        }
    }
    public function scopeActiveweekly($query)
    {
        $user = Auth::user();
        $ena = CarbonImmutable::now()->locale('en_US');
        if(Auth::user()->ticketit_admin == 1){
            return $query->whereNull('completed_at')->whereBetween('ticketit.updated_at', [$ena->startOfWeek()->format('Y-m-d H:i'), $ena->endOfWeek()->format('Y-m-d H:i')]);
        }
        else{
            $ticketa = Categoryusers::where('user_id',$user->id)->pluck('category_id');
            return $query->whereIn('category_id',$ticketa)->whereNull('completed_at')->whereBetween('ticketit.updated_at', [$ena->startOfWeek()->format('Y-m-d H:i'), $ena->endOfWeek()->format('Y-m-d H:i')]);

        }


    }
    /**
     * Get Ticket status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Status', 'status_id');
    }

    /**
     * Get Ticket priority.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Priority', 'priority_id');
    }
    public function getLatestId()
    {

    }
    /**
     * Get Ticket category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Category', 'category_id');
    }

    /**
     * Get Ticket owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get Ticket agent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Agent', 'agent_id');
    }

    /**
     * Get Ticket comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Comment', 'ticket_id')->with('user');
    }

    public function commentswithuser()
    {
        
    }

    public function gambar()
    {

        return $this->hasMany('\Kordy\Ticketit\Models\Image', 'ticket_id');
    }
//    /**
    //     * Get Ticket audits
    //     *
    //     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //     */
    //    public function audits()
    //    {
    //        return $this->hasMany('Kordy\Ticketit\Models\Audit', 'ticket_id');
    //    }
    //

    /**
     * @see Illuminate/Database/Eloquent/Model::asDateTime
     */
    public function freshTimestamp()
    {
        return new Date();
    }

    
    
    /**
     * @see Illuminate/Database/Eloquent/Model::asDateTime
     */
    protected function asDateTime($value)
    {
        if (is_numeric($value)) {
            return Date::createFromTimestamp($value);
        } elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
            return Date::createFromFormat('Y-m-d', $value)->startOfDay();
        } elseif (!$value instanceof \DateTimeInterface) {
            $format = $this->getDateFormat();

            return Date::createFromFormat($format, $value);
        }

        return Date::instance($value);
    }

    /**
     * Get all user tickets.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeUserTickets($query, $id)
    {
        return $query->where('user_id', $id);
    }
    public function scopeMyKeluhan($query, $id)
    {

        return $query->where(function ($subquery) use ($id) {
            $ticketa = Categoryusers::where('user_id',$id)->pluck('category_id');
            $subquery->whereIn('category_id', $ticketa);
        });

        $tticket = Ticket::whereIn('category_id',$ticketa);
    }
    /**
     * Get all agent tickets.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeAgentTickets($query, $id)
    {
        return $query->where('agent_id', $id);
    }

    /**
     * Get all agent tickets.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeAgentUserTickets($query, $id)
    {
        return $query->where(function ($subquery) use ($id) {
            $subquery->where('agent_id', $id)->orWhere('user_id', $id);
        });
    }
    public function scopeAgentCategory($query, $id)
    {
        return $query->where(function ($subquery) use ($id) {
            $subquery->join('ticketit_categories_users', 'ticketit_categories_users.user_id', '=', 'ticketit.user_id')
                     ->where('ticketit_categories_users.user_id', $id);
        });
    }
    /**
     * Sets the agent with the lowest tickets assigned in specific category.
     *
     * @return Ticket
     */
    public function autoSelectAgent()
    {
        $cat_id = $this->category_id;
        $agents = Category::find($cat_id)->agents()->with(['agentOpenTickets' => function ($query) {
            $query->addSelect(['id', 'agent_id']);
        }])->get();
        $count = 0;
        $lowest_tickets = 1000000;
        // If no agent selected, select the admin
        $first_admin = Agent::admins()->first();
        $selected_agent_id = $first_admin->id;
        foreach ($agents as $agent) {
            if ($count == 0) {
                $lowest_tickets = $agent->agentOpenTickets->count();
                $selected_agent_id = $agent->id;
            } else {
                $tickets_count = $agent->agentOpenTickets->count();
                if ($tickets_count < $lowest_tickets) {
                    $lowest_tickets = $tickets_count;
                    $selected_agent_id = $agent->id;
                }
            }
            $count++;
        }
        $this->agent_id = $selected_agent_id;

        return $this;
    }
}
