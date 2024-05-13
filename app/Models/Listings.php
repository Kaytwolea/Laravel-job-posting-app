<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class listings extends Model
{
    use HasFactory;
    use SoftDeletes;


    public $incrementing = false;
    protected $guarded = ['id'];
    protected $casts = [
        'tags' => 'array',
        'skills' => 'array'
    ];
//
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::created(function ($job) {
//            $job->applicants()->create([
//                'listing_id' => $job->id
//            ]);
//        });
//    }

    public function applicants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'applications')->withPivot('status', 'cover_letter');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
