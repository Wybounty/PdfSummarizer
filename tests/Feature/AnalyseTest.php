<?php

use App\Models\Analyse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

beforeEach(function () {
    config(['services.n8n.webhook_url' => 'https://n8n.test/webhook']);
});

test('home page can be rendered', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Home')
            ->has('analyses', 0));
});

test('home page lists analyses from newest to oldest', function () {
    $older = Analyse::factory()->create(['created_at' => now()->subDay()]);
    $newer = Analyse::factory()->create(['created_at' => now()]);

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Home')
            ->has('analyses', 2)
            ->where('analyses.0.id', $newer->id)
            ->where('analyses.1.id', $older->id));
});

test('file analysis requires a pdf file', function () {
    $response = $this->post(route('file.analysis'));

    $response
        ->assertRedirect(route('home'))
        ->assertSessionHasErrors('file');

    $this->assertDatabaseCount('analyses', 0);
});

test('file analysis rejects non pdf files', function () {
    $file = UploadedFile::fake()->create('notes.txt', 100, 'text/plain');

    $response = $this->post(route('file.analysis'), ['file' => $file]);

    $response
        ->assertRedirect(route('home'))
        ->assertSessionHasErrors('file');

    $this->assertDatabaseCount('analyses', 0);
});

test('file analysis stores result when n8n responds successfully', function () {
    Storage::fake('public');

    $payload = sampleAnalysisPayload();

    Http::fake([
        'https://n8n.test/webhook' => Http::response($payload, 200),
    ]);

    $file = UploadedFile::fake()->createWithContent('cours.pdf', '%PDF-1.4 contenu test', 'application/pdf');

    $response = $this->post(route('file.analysis'), ['file' => $file]);

    $response
        ->assertRedirect(route('home'))
        ->assertSessionHas('success', 'Analyse terminée');

    $this->assertDatabaseHas('analyses', [
        'title' => 'Introduction à Laravel',
        'original_filename' => 'cours.pdf',
        'status' => 'completed',
    ]);

    $analyse = Analyse::first();

    expect($analyse)->not->toBeNull()
        ->and($analyse->analysis_json)->toBe($payload)
        ->and(Storage::disk('public')->exists($analyse->file_path))->toBeTrue();

    Http::assertSent(fn ($request) => $request->url() === 'https://n8n.test/webhook'
        && $request->method() === 'POST');
});

test('file analysis redirects with error when n8n fails', function () {
    Http::fake([
        'https://n8n.test/webhook' => Http::response(['error' => 'failure'], 500),
    ]);

    $file = UploadedFile::fake()->createWithContent('cours.pdf', '%PDF-1.4 contenu test', 'application/pdf');

    $response = $this->post(route('file.analysis'), ['file' => $file]);

    $response
        ->assertRedirect(route('home'))
        ->assertSessionHas('error', 'Erreur API analyse');

    $this->assertDatabaseCount('analyses', 0);
});

test('summary page can be rendered', function () {
    $analyse = Analyse::factory()->create();

    $response = $this->get(route('summary.show', $analyse));

    $response
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Summary')
            ->where('analyse.id', $analyse->id)
            ->where('analyse.title', $analyse->title));
});

test('summary page renders even when analysis json is missing', function () {
    $analyse = Analyse::factory()->withoutAnalysis()->create();

    $response = $this->get(route('summary.show', $analyse));

    $response
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Summary')
            ->where('analyse.id', $analyse->id)
            ->where('analyse.analysis_json', null));
});

test('summary pdf can be downloaded', function () {
    Pdf::fake();

    $analyse = Analyse::factory()->create([
        'title' => 'Mon cours Laravel',
    ]);

    $response = $this->get(route('summary.pdf', $analyse));

    $response->assertOk();

    Pdf::assertRespondedWithPdf(function ($pdf) use ($analyse) {
        return $pdf->viewName === 'pdf.analyse'
            && ($pdf->viewData['analyse']->id ?? null) === $analyse->id;
    });
});
