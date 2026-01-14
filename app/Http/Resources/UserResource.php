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
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'is_verified'      => $this->hasVerifiedEmail(),
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'joined_at' => $this->created_at?->toISOString(),
            'joined_at_label' => $this->created_at
                ? strtoupper($this->created_at->format('M Y'))
                : null,
        ];
    }
}
