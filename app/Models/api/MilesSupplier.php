<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilesSupplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'miles_suppliers_cnpj',
        'miles_suppliers_name',
        'active'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    
    public function milesAccounts()
    {
        return $this->hasMany(MilesAccount::class);
    }
}
