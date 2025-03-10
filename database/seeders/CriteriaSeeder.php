<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitions = [
            'Gen Prog - Problem 1' => [
                ['name' => 'Functionality', 'percentage' => 50],
                ['name' => 'Program Structure/Design', 'percentage' => 30],
                ['name' => 'Output', 'percentage' => 20]
            ],
            'Gen Prog - Problem 2' => [
                ['name' => 'Functionality', 'percentage' => 50],
                ['name' => 'Program Structure/Design', 'percentage' => 30],
                ['name' => 'Output', 'percentage' => 20]
            ],
            'Database Programming' => [
                ['name' => 'Logic', 'percentage' => 25],
                ['name' => 'Database Design', 'percentage' => 35],
                ['name' => 'Functionality', 'percentage' => 25],
                ['name' => 'Coding Style', 'percentage' => 15]
            ],
            'Dynamic Web Design' => [
                ['name' => 'Security', 'percentage' => 20],
                ['name' => 'Database Design', 'percentage' => 30],
                ['name' => 'User Interface', 'percentage' => 25],
                ['name' => 'Functionality', 'percentage' => 25]
            ],
            'Static Web Design' => [
                ['name' => 'Design', 'percentage' => 40],
                ['name' => 'Relevance', 'percentage' => 15],
                ['name' => 'Originality', 'percentage' => 25],
                ['name' => 'Responsive Design', 'percentage' => 20]
            ],
            'Digital Slogan' => [
                ['name' => 'Content', 'percentage' => 40],
                ['name' => 'Presentation', 'percentage' => 40],
                ['name' => 'Adherence', 'percentage' => 20]
            ],
            'ICT Quiz Bee' => [],
            'Digital Poster' => [
                ['name' => 'Design', 'percentage' => 25],
                ['name' => 'Relevance', 'percentage' => 25],
                ['name' => 'Originality', 'percentage' => 25],
                ['name' => 'Content', 'percentage' => 25]
            ],
            'Computer Networking' => [
                ['name' => 'Cable Termination', 'percentage' => 50],
                ['name' => 'Speed', 'percentage' => 30],
                ['name' => 'Output', 'percentage' => 20]
            ],
            'Photojournalism' => [
                ['name' => 'Content', 'percentage' => 60],
                ['name' => 'Organization', 'percentage' => 40]
            ],
            'Extemporaneous Speech' => [
                ['name' => 'Content and Substance', 'percentage' => 30],
                ['name' => 'Organization', 'percentage' => 20],
                ['name' => 'Language and Style', 'percentage' => 20],
                ['name' => 'Delivery', 'percentage' => 15],
                ['name' => 'Time Management', 'percentage' => 15]
            ],
            'Mobile Vlogging' => [
                ['name' => 'Content Relevance', 'percentage' => 30],
                ['name' => 'Creativity', 'percentage' => 30],
                ['name' => 'Visual and Audio Quality', 'percentage' => 20],
                ['name' => 'Overall Presentation', 'percentage' => 20]
            ],
            'IT Capstone Project and Research Presentation - Social' => [
                ['name' => 'Significance of Research', 'percentage' => 40],
                ['name' => 'Contribution to New Knowledge', 'percentage' => 10],
                ['name' => 'Relevance', 'percentage' => 10],
                ['name' => 'Applicability', 'percentage' => 20],
                ['name' => 'Manuscript Quality', 'percentage' => 30]
            ],
            'IT Capstone Project and Research Presentation - Developmental' => [
                ['name' => 'Significance of Research', 'percentage' => 40],
                ['name' => 'Contribution to New Knowledge', 'percentage' => 10],
                ['name' => 'Relevance', 'percentage' => 10],
                ['name' => 'Applicability', 'percentage' => 20],
                ['name' => 'Manuscript Quality', 'percentage' => 30]
            ]
        ];

        foreach ($competitions as $name => $criteria) {
            $competition = Competition::create([
                'name' => $name,
                'date' => '2025-03-12',
                'location' => 'Jay Kwa',
                'description' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Seed Criteria
            foreach ($criteria as $criterion) {
                $competition->criteria()->create([
                    'name' => $criterion['name'],
                    'percentage' => $criterion['percentage']
                ]);
            }
        }
    }
}
