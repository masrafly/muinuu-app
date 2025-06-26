<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'key', 'receipt', 'type', 'value',
        'transaction_date', 'description', 'user_id'
    ];

    public function key()
    {
        return $this->belongsTo(Account::class, 'key', 'key');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
