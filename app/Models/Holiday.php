<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'total_days',
        'user_id',
        'name',
        'status',
        'rejection_reason'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
