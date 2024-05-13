<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'tags' => ($this->tags),
            'company_name' => $this->company_name,
            'skills' => ($this->skills),
            'job_type' => $this->job_type,
            'open' => $this->open,
            'location' => $this->location,
            'email' => $this->email,
            'website' => $this->website,
            'description' => $this->description,
            'views' => $this->views,
            'accepted' => $this->accepted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
