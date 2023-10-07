<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchases_total_amount',
        'purchases_cash',
        'purchases_miles',
        'currency_abreviation',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function milesOperation()
    {
        return $this->hasOne(MilesOperation::class);
    }
}
