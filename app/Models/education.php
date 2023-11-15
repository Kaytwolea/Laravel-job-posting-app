<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class education extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = ['id'];

//    protected $casts = [
//        'start_date' => 'integer',
//        'end_date' => 'integer',
//    ];

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
