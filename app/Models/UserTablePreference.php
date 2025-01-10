<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTablePreference extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'table_name', 'visible_columns'];

    protected $casts = [
        'visible_columns' => 'array', // Convierte automáticamente JSON a array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
