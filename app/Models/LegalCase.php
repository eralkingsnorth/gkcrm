<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalCase extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_number',
        'case_title',
        'case_type',
        'priority',
        'description',
        'client_id',
        'status_id',

        'case_reference',
        'creditor_name',
        'account_number',
        'amount',
        'start_date',
        'other_data',
        'documents_needed',
        'email_status',
        'sort_code',
        'account_reference',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'amount' => 'decimal:2',
        'documents_needed' => 'boolean',
    ];

    /**
     * Get the client that owns the case.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the status of the case.
     */
    public function status()
    {
        return $this->belongsTo(CaseStatus::class, 'status_id');
    }


}