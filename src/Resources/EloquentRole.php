<?php

namespace AwemaPL\Permission\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentRole extends JsonResource
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
            'name' => $this->name,
            'permissions' =>$this->permissions,
        ];
    }
}