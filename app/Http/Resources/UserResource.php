<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_city' => $this->id_city,
            'employee_id' => $this->employee_id,
            'email' => $this->email,
            'password' => $this->password,
            'fullname' => $this->fullname,
            'nickname' => $this->nickname,
            'phone' => $this->phone,
            'address' => $this->address,
            'profile_pict' => $this->profile_pict,
            'link' => $this->link,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'city' => new CityResource($this->city),
        ];
    }
}
