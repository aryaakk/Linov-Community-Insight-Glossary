<?php

namespace App\Models\Utility;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory, Uuids;

    protected $table = 'utl_bank_accounts';
    protected $guarded = [];
}
