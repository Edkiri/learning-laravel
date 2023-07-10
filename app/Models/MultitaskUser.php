<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultitaskUser extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function multitask()
    {
        return $this->belongsTo(Multitask::class);
    }

    protected $table = 'multitasks_users';
}
