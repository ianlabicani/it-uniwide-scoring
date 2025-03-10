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

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function calculateEasyScore($userId)
    {
        return $this->calculateScore($userId, [1, 2], 20);
    }

    public function calculateModerateScore($userId)
    {
        return $this->calculateScore($userId, [3, 4], 30);
    }

    public function calculateHardScore($userId)
    {
        return $this->calculateScore($userId, [5], 50);
    }

    public function calculateOverallScore($userId)
    {
        $easy = $this->calculateEasyScore($userId);
        $moderate = $this->calculateModerateScore($userId);
        $hard = $this->calculateHardScore($userId);

        // Overall formula: (easy + moderate + hard) / 3 * 100
        $overall = (($easy + $moderate + $hard) / 3) * 100;

        return number_format($overall, 2);
    }

    private function calculateScore($userId, $problemIds, $weight)
    {
        $competitions = [
            1 => 'Gen Prog - Problem 1',
            2 => 'Gen Prog - Problem 2',
            3 => 'Gen Prog - Problem 3',
            4 => 'Gen Prog - Problem 4',
            5 => 'Gen Prog - Problem 5',
        ];

        $totalScore = 0;
        $totalWeight = 0;

        foreach ($problemIds as $id) {
            $competition = $this->where('name', $competitions[$id])->first();
            $score = $competition->scores()
                ->where('user_id', $userId)
                ->get();

            $scoreTotal = 0;
            $percentageTotal = 0;

            foreach ($score as $s) {
                $criteria = $s->criteria;

                foreach ($criteria as $criterion) {
                    $scoreTotal += ($s->score * ($criterion->percentage / 100));
                    $percentageTotal += $criterion->percentage;
                }
            }

            // Calculate weighted score
            $finalScore = $percentageTotal > 0 ? ($scoreTotal / $percentageTotal) * $weight : 0;
            $totalScore += $finalScore;
            $totalWeight += $weight;
        }

        // Return the final weighted score for this set
        return $totalWeight > 0 ? ($totalScore / $totalWeight) : 0;
    }
}
