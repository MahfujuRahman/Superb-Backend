<?php

namespace App\Http\Controllers\Account\Models;

use App\Http\Controllers\Outlet\Models\CustomerSourceType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcAccount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['text'];

    protected $table = "ac_accounts";

    public function user()
    {
        return $this->belongsTo(User::class, 'creator');
    }

    public function debitTransactions()
    {
        return $this->hasMany(AcTransaction::class, 'debit_account_id');
    }

    public function creditTransactions()
    {
        return $this->hasMany(AcTransaction::class, 'credit_account_id');
    }


    // Relationship to fetch children accounts
    public function children()
    {
        return $this->hasMany(AcAccount::class, 'parent_id');
    }

    // Relationship to fetch parent account
    public function parent()
    {
        return $this->belongsTo(AcAccount::class, 'parent_id');
    }

    public function getTextAttribute()
    {
        return $this->account_name;
    }

    public function inc()
    {
        return $this->hasMany(AcAccount::class, 'parent_id')->with('inc');
    }


}
