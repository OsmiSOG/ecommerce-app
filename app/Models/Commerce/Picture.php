<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picture extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'path'
    ];

    protected $appends = [
        'url'
    ];

    public function url(): Attribute
    {
        return new Attribute(
            get: fn () => asset('storage/'.$this->path),
        );
    }

    /**
     * Get the parent picturable model (product or service).
     */
    public function picturable(): MorphTo
    {
        return $this->morphTo();
    }
}
