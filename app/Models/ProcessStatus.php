<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProcessStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'nomination_starts_at',
        'nomination_ends_at',
        'voting_starts_at',
        'voting_ends_at',
    ];

    protected $casts = [
        'nomination_starts_at' => 'datetime',
        'nomination_ends_at' => 'datetime',
        'voting_starts_at' => 'datetime',
        'voting_ends_at' => 'datetime',
    ];

    /**
     * Get the current status of the process based on dates.
     *
     * @return string
     */
    public function getStatusAttribute($value)
    {
        $now = Carbon::now();
        if ($this->nomination_starts_at && $this->nomination_ends_at && $now->between($this->nomination_starts_at, $this->nomination_ends_at)) {
            return 'nominating';
        }
        if ($this->voting_starts_at && $this->voting_ends_at && $now->between($this->voting_starts_at, $this->voting_ends_at)) {
            return 'voting';
        }

        // If voting has ended, the process is completed.
        if ($this->voting_ends_at && $now->greaterThan($this->voting_ends_at)) {
            return 'completed';
        }

        // Fallback to the stored value (e.g., 'inactive', 'completed') if not in an active date-based phase.
        return $value ?? 'inactive';
    }
}