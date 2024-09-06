<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_Message extends Model
{
    use HasFactory;
    protected $fillable = ['message', 'community_id', 'breeder_id','type'];

    public function breeder()
    {
        return $this->belongsTo(Breeder::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
