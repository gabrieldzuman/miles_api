<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_cpf',
        'client_name',
        'miles_supplier_id'
    ];

    public function milesSupplier()
    {
        return $this->belongsTo(MilesSupplier::class);
    }
    
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
