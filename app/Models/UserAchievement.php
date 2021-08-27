<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use HasFactory;

    /**
     * Base table for the model
     *
     * @var string
     */
    protected $table = 'users_achievements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'achievement_id'
    ];

    /**
     * Get the achievement where the record belongs.
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
