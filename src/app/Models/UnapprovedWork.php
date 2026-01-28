<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnapprovedWork extends Model
{
    use HasFactory;

    protected $fillable = ['work_id', 'user_id', 'date', 'attendance', 'leaving', 'remarks'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
