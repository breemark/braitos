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
            'project_id' => $this->project_id,
            'users_id' => DB::table('task_user')->where('task_id', $this->id)->pluck('user_id'),
        ];
    }
}
