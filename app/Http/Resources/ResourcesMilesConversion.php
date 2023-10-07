<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilesConversion extends JsonResource
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
            'miles_conversion_currency' => $this->miles_conversion_currency,
            'miles_operation_type' => $this->miles_operation_type,
            'miles_conversion_amount' => $this->miles_conversion_amount,
            'miles_provider' => $this->miles_provider,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
