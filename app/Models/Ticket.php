<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['title', 'description', 'status_id', 'priority_id', 'assigned_user_id'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
