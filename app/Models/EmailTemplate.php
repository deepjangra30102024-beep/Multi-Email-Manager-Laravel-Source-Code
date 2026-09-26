<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\EmailTemplateFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'subject',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
