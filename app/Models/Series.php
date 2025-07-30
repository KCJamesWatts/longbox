<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Series extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function setPublisherAttribute(Publisher $publisher): void
    {
        $this->publisher()->associate($publisher);
    }
}
