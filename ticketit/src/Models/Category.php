<?php

namespace Kordy\Ticketit\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'ticketit_categories';

    protected $fillable = ['name', 'alternate' , 'color'];

    /**
     * Indicates that this model should not be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get related tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {

        return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'category_id')->select('*', \DB::raw('UNIX_TIMESTAMP(created_at) AS createdunix, UNIX_TIMESTAMP(updated_at) AS updatedunix, UNIX_TIMESTAMP(completed_at) AS completedunix'))->orderBy('created_at', 'DESC');
    }
    /**
     * Get related agents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents()
    {
        return $this->belongsToMany('\Kordy\Ticketit\Models\Agent', 'ticketit_categories_users', 'category_id', 'user_id');
    }
}
