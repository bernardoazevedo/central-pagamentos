<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transactions_id',
        'products_id',
        'quantity'
    ];
}
