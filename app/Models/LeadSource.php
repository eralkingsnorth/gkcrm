<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadSource extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the clients that use this lead source.
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'lead_source', 'name');
    }

    /**
     * Scope a query to only include active lead sources.
     */
    public function scopeActive($query)
    {
        return $query->where('deleted_at', null);
    }

    /**
     * Scope a query to only include inactive lead sources.
     */
    public function scopeInactive($query)
    {
        return $query->whereNotNull('deleted_at');
    }
} 