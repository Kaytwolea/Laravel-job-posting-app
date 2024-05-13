<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ApplicationResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'isAdmin' => $this->isAdmin,
            'image' => $this->image,
            'email_verified_at' => $this->email_verified_at,
            'kyc' => $this->kyc,
            'cv' => $this->cv,
            'skills' => $this->skills,
            'about' => $this->about,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'pivot' => [
                'listings_id' => $this->pivot->listings_id,
                'user_id' => $this->pivot->user_id,
                'status' => $this->pivot->status,
                'cover_letter' => $this->pivot->cover_letter,
            ],
        ];
    }
}
