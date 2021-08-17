<?php

namespace App\Models\Components;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    public function datas()
    {
        return $this->hasMany(Data::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function algorithms()
    {
        return $this->belongsToMany(Algorithm::class);
    }
}

