<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Account extends Model
{

    Use HasFactory;

    protected $primarykey = 'id';

    protected $fillable = [
        'key','key_parent','name', 'element_level', 'acc_type', 'created_by', 'user_id'
    ];

    

    public function key()
    {
        return $this->hasMany(Account::class, 'key');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    // public function parent()
    // {
    //     return $this->belongsTo(Account::class, 'parent_id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(Account::class, 'parent_id');
    // }

    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }

    
}
