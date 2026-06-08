<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function sampleAnalysisPayload(array $overrides = []): array
{
    return array_merge([
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
    ], $overrides);
}
