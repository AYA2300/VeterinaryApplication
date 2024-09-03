<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['conversation_id', 'type', 'message','sender_message_type'];



      //Relations
           public function conversation(){
            return $this->beLongsTo(Conversation::class);
           }

           public function messageable_sender()
           {
            return $this->morphTo();
           }
}
