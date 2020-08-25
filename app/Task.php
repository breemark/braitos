<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'finished',
        'deadline',
        'project_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'finished' => 'boolean',
        'project_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deadline',
    ];


    public function users()
    {
        return $this->belongsToMany(\App\User::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Project::class);
    }
}
