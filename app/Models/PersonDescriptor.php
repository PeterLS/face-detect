<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonDescriptor extends Model {
    protected $table = 'person_descriptors';

    protected $fillable = [
        'person_id',
        'descriptor',
        'url',
    ];

    public function person(): BelongsTo {
        return $this->belongsTo(Person::class);
    }
}
