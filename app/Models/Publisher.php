<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function addImprint(Publisher|array $imprint): Publisher
    {
        if (is_array($imprint)) {
            $imprint = Publisher::create($imprint);
        }

        if ($imprint->id === $this->id) {
            throw new \InvalidArgumentException("A publisher cannot be its own imprint");
        }

        $imprint->parent()->associate($this);
        $imprint->save();

        return $imprint;
    }

    public function imprints()
    {
        return $this->hasMany(Publisher::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Publisher::class, 'parent_id');
    }

    public function series()
    {
        return $this->hasMany(Series::class);
    }
}
