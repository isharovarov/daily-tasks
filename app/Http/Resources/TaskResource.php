<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'category'   => $this->category,
            'title'      => $this->title,
            'body'       => $this->body,
            'solved'     => $this->solved,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
