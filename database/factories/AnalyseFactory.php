<?php

namespace Database\Factories;

use App\Models\Analyse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Analyse>
 */
class AnalyseFactory extends Factory
{
    protected $model = Analyse::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payload = [
            'presentation' => [
                'sujet' => 'Introduction à Laravel',
                'objectif' => 'Comprendre les bases du framework',
                'themes' => ['MVC', 'Routing'],
            ],
            'presentation_generale' => 'Ce cours présente les fondamentaux de Laravel.',
            'notions_importantes' => [
                [
                    'titre' => 'Route',
                    'explication' => 'Une route associe une URL à une action.',
                    'importance' => 'élevée',
                ],
            ],
            'points_cles' => ['Laravel est un framework PHP'],
            'exemple_concret' => [
                'situation' => 'Création d\'une application web',
                'probleme' => 'Structurer le code',
                'solution' => 'Adopter le pattern MVC',
                'pourquoi_cette_solution' => 'Séparer les responsabilités',
                'resultat' => 'Application plus maintenable',
            ],
            'quiz' => [
                ['question' => 'Qu\'est-ce qu\'une route ?', 'reponse' => 'Un mapping URL vers une action.'],
                ['question' => 'Quel langage utilise Laravel ?', 'reponse' => 'PHP.'],
                ['question' => 'Qu\'est-ce que MVC ?', 'reponse' => 'Model-View-Controller.'],
                ['question' => 'À quoi sert Eloquent ?', 'reponse' => 'À interagir avec la base de données.'],
                ['question' => 'Qu\'est-ce qu\'Artisan ?', 'reponse' => 'La CLI de Laravel.'],
            ],
            'mots_cles' => ['Laravel', 'PHP'],
            'conclusion' => 'Laravel accélère le développement d\'applications web.',
        ];

        return [
            'title' => $payload['presentation']['sujet'],
            'original_filename' => fake()->word().'.pdf',
            'file_path' => 'analyses/'.fake()->uuid().'.pdf',
            'analysis_json' => $payload,
            'status' => 'completed',
            'error_message' => null,
        ];
    }

    public function withoutAnalysis(): static
    {
        return $this->state(fn (array $attributes) => [
            'analysis_json' => null,
            'status' => 'processing',
        ]);
    }
}
