<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;
    protected $fillable = [
        'account_name',
        'short_name',
        'business_address',
        'facility_address',
        'customer_category',
        'cooperation_period_start_date',
        'cooperation_period_end_date',
        'contract_price',
        'contracted_demand',
        'other_information',
        'contact_name',
        'designation',
        'customer_id',
        'email',
        'mobile_number',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
