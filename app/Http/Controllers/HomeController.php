<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use App\Models\Analyse;

class HomeController extends Controller
{
    // Home page
    public function index()
    {

        // Afficher les analyses des plus récentes aux plus anciennes
        $analyses = Analyse::orderBy('created_at', 'desc')->get();

        return Inertia::render('Home', [
            'analyses' => $analyses,
        ]);
    }

    public function file_analysis(Request $request)
    {

        // File validation
        $request->validate([
            'file' => 'required|mimes:pdf|max:10240'
        ]);


        // Essayer d'analyser le fichier
        try {

            // Récupérer le fichier
            $file = $request->file('file');

            // Envoyer le fichier à l'API
            $response = Http::timeout(120)
                ->attach(
                    'data',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )
                    ->post(config('services.n8n.webhook_url'));

            // Vérifier si la réponse de l'API est réussie
            if (!$response->successful()) {
                return redirect()->route('home')
                    ->with('error', 'Erreur API analyse');
            }

            // Récupérer les données de la réponse de l'API
            $data = $response->json();

            // Stocker le fichier
            $path = $file->store('analyses', 'public');

            // Insérer l'analyse dans la base de données
            Analyse::create([
                'title' => $data['presentation']['sujet'] ?? 'Analyse PDF',
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'analysis_json' => $data,
                'status' => 'completed',
            ]);

            // Rediriger vers la page d'accueil avec un message de succès
            return redirect()->route('home')
                ->with('success', 'Analyse terminée');

        } catch (\Exception $e) {

            // Rediriger vers la page d'accueil avec un message d'erreur si l'analyse échoue
            return redirect()->route('home')
                ->with('error', $e->getMessage());
        }
    }
}
