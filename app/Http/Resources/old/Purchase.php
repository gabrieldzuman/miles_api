<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Purchase extends JsonResource
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
            'purchases_total_amount' => $this->purchases_total_amount,
            'purchases_cash' => $this->purchases_cash,
            'purchases_miles' => $this->purchases_miles,
            'client_id' => $this->client_id,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'deleted_at' => $this->deleted_at->format('d/m/Y'),
        ];
    }
}