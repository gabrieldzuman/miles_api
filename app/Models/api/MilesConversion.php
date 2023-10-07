<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilesConversion extends Model
{
    use HasFactory;
    protected $fillable = [
        'miles_conversion_currency',
        'miles_operation_type',
        'miles_conversion_amount',
        'miles_provider',
        'active'
    ]; 
}
