<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['file_path', 'ticket_id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
