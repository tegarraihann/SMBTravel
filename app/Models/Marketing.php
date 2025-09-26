<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'status',
        'notes',
        'created_by'
    ];

    public function agents()
    {
        return $this->hasMany(MarketingAgent::class);
    }

    // Helper: format code otomatis (opsional, bisa dipakai di Service/Observer)
    public static function nextCode(): string
    {
        $lastId = (int) static::withTrashed()->max('id');
        return 'MK-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }
}
