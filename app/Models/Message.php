<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}
