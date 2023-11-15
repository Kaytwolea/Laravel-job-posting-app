<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class JobExperience extends Model
{
    use HasFactory;
    use SoftDeletes;


    public $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string)Uuid::uuid4()->getHex();
        });
    }

    public function user(): BelongsTo
    {
        $this->belongsTo(User::class);
    }
}
