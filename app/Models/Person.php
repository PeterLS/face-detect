<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model {
    protected $table = 'persons';

    protected $fillable = [
        'first_name',
        'last_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function descriptors(): HasMany {
        return $this->hasMany(PersonDescriptor::class);
    }

    public function getFullNameAttribute(): string {
        return $this->last_name . ' ' . $this->first_name;
    }
}
