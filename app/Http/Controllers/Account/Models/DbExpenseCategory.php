<?php

namespace App\Http\Controllers\Account\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbExpenseCategory extends Model
{
    use HasFactory;
    protected $guarded = []; 

    protected $table = "db_expense_categories";

    public function user() {
        return $this->belongsTo(User::class, 'creator');
    }

}
