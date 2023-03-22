<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class listings extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'tags',
        'company',
        'location',
        'email',
        'website',
        'description',
        'user_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}