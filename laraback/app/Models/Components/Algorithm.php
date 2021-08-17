<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Algorithm extends Model
{
    use HasFactory;

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class);
    }
}
