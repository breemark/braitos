<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;


class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'finished' => $this->finished,
            'deadline' => $this->deadline,

            'project' => DB::table('projects')
                ->select('id', 'title')
                ->where('id', $this->project_id)
                ->get(),

            'users_assigned' => DB::table('users')
                ->join('task_user', 'users.id', '=', 'task_user.user_id')
                ->select('task_user.user_id as id', 'users.name')
                ->where('task_user.task_id', $this->id)
                ->get()
        ];
    }
}
