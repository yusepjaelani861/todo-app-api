<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'completed',
        'completed_at',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}