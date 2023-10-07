<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilesOperation extends Model
{
    use HasFactory;
    protected $fillable = [
        'miles_operation_amount',
        'miles_operation_type',
        'miles_account_id',
        'active'
    ];

    public function milesAccount()
    {
        return $this->belongsTo(MilesAccount::class);
    }
    
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
