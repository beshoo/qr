<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Qr extends Model
{
    use PowerJoins;

    protected $guarded = [];

    protected $hidden = ['user_id', 'id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
