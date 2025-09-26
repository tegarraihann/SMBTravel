<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingAgent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'marketing_id',
        'name',
        'phone',
        'email',
        'commission_rate',
        'status',
        'notes'
    ];

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
