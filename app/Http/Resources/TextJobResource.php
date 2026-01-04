<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TextJobResource extends JsonResource
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
            'input_text' => $this->input_text,
            'output_text' => $this->output_text,
            'action_type' => $this->action_type,
            'created_at' => $this->created_at,
            'created_formatted' => $this->created_at->format('M d, Y h:i A'),
        ];
    }
}
