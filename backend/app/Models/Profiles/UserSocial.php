<?php

namespace App\Models\Profiles;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    use HasFactory, Uuids;

    protected $table = 'com_user_socials';
    protected $guarded = [];
}
