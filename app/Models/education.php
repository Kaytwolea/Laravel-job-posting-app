<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class education extends Model
{
    use HasFactory;
    use SoftDeletes;


    public $guarded = ['id'];

    public $incrementing = false;


//    protected $casts = [
//        'start_date' => 'integer',
//        'end_date' => 'integer',
//    ];


    public function user(): BelongsTo
    {
        $this->belongsTo(User::class);
    }
}
