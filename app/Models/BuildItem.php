<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'build_id',
        'item_identifier',
        'amount',
        'submitted_amount',
    ];

    public function build()
    {
        return $this->belongsTo(Build::class);
    }
}
