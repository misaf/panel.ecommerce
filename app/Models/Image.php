<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Image extends Model
{
    use SoftDeletes;

    protected $casts = [
        'id'    => 'integer',
        'image' => 'string',
    ];

    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'image',
    ];

    protected $table = 'model_has_images';

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
