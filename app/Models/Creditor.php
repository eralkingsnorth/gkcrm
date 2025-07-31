<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Creditor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'creditor_type',
        'address_line_1',
        'address_line_2',
        'town_city',
        'county',
        'country',
        'postcode',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];



    /**
     * Get the cases that use this creditor.
     */
    public function cases()
    {
        return $this->hasMany(\App\Models\LegalCase::class, 'creditor_name', 'name');
    }

    /**
     * Scope a query to only include active creditors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive creditors.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get the creditor's full address.
     */
    public function getFullAddressAttribute()
    {
        $address = [];
        
        if ($this->address_line_1) {
            $address[] = $this->address_line_1;
        }
        if ($this->address_line_2) {
            $address[] = $this->address_line_2;
        }
        if ($this->town_city) {
            $address[] = $this->town_city;
        }
        if ($this->county) {
            $address[] = $this->county;
        }
        if ($this->postcode) {
            $address[] = $this->postcode;
        }
        if ($this->country) {
            $address[] = $this->country;
        }
        
        return implode(', ', $address);
    }
}
