<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilesAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'miles_account_number',
        'miles_accounts_balance',
        'miles_accounts_limit',
        'miles_supplier_id',
        'company_id',
        'active'
    ];

    public function milesSupplier()
    {
        return $this->belongsTo(MilesSupplier::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function milesOperations()
    {
        return $this->hasMany(MilesOperation::class);
    }
}
