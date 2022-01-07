<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matchs extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'home_team',
        'guest_team',
        'date_match',
        'time_match',
        'total_skor',
    ];

    public function hometeam()
    {
        return $this->belongsTo(Team::class, 'home_team', 'id');
    }

    public function guestteam()
    {
        return $this->belongsTo(Team::class, 'guest_team', 'id');
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'match_id', 'id');
    }
}
