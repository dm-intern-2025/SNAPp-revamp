<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        // 'user_id',
    ];
}
