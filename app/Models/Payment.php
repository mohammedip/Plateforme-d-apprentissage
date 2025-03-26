<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $table = 'payments';

    protected $fillable = [
        'enrollement_id',
        'payment_type',
        'status',
        'amount',
        'transaction_id',
    ];


    public function enrollement()
    {
        return $this->belongsTo(Enrollement::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
