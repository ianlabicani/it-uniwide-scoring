<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Competition extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'date',
        'location',
        'description',
    ];

    protected $dates = ['date']; // This will cast your date as a Carbon instance

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('F d, Y');
    }

    /**
     * The judges that belong to the competition.
     */
    public function judges()
    {
        return $this->belongsToMany(User::class, 'competition_judge')
            ->withTimestamps();
    }

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function contestants()
    {
        return $this->belongsToMany(User::class, 'competition_contestant')
            ->withTimestamps();
    }


}
