<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'holiday_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
