<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ClientNote;
use App\Models\ClientDocument;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lead_source',
        'title',
        'forename',
        'surname',
        'date_of_birth',
        'country_of_birth',
        'marital_status',
        'email_address',
        'mobile_number',
        'home_phone',
        'postcode',
        'house_number',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'town_city',
        'county',
        'country',
        'other',
        'notes',
        'client_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the client's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim($this->forename . ' ' . $this->surname);
    }

    /**
     * Get the client's full address.
     *
     * @return string
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
        if ($this->address_line_3) {
            $address[] = $this->address_line_3;
        }
        if ($this->town_city) {
            $address[] = $this->town_city;
        }
        if ($this->county) {
            $address[] = $this->county;
        }

        if ($this->country) {
            $address[] = $this->country;
        }
        
        return implode(', ', $address);
    }

    /**
     * Scope a query to only include active clients.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to only include deleted clients.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeleted($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Get the documents for the client.
     */
    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }

    /**
     * Get documents by type.
     */
    public function getDocumentsByType($type)
    {
        return $this->documents()->where('document_type', $type)->get();
    }

    /**
     * Get the notes for the client.
     */
    public function clientNotes()
    {
        return $this->hasMany(ClientNote::class)->orderBy('created_at', 'desc');
    }


}
