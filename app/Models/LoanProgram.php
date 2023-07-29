<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProgram extends Model
{
    protected $fillable = [
        'program_name',
        'target_min_credit_score',
        'target_min_income',
        'interest_rate',
        'min_loan_amount',
        'max_loan_amount',
        'max_loan_term',
    ];

    // Add any additional relationships or methods if needed
}
