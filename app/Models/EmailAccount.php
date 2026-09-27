<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAccount extends Model
{
    /** @use HasFactory<\Database\Factories\EmailAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_address',
        'access_token',
        'refresh_token',
        'expires_in',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
