<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Analyse;
use Illuminate\Support\Str;
use Spatie\LaravelPdf\Facades\Pdf;

class SummaryController extends Controller
{
    // Afficher la fiche de révision
    public function show(Analyse $analyse)
    {
        //dd($analyse);
        return Inertia::render('Summary', [
            'analyse' => $analyse,
        ]);
    }

    // Convertion & téléchargement de la fiche de révision en PDF
    public function download(Analyse $analyse)
    {
        // Générer le nom du fichier PDF
        $filename = 'fiche-' . Str::slug($analyse->title ?? 'analyse-pdf') . '.pdf';
    
        // Générer le PDF
        return Pdf::view('pdf.analyse', [
                'analyse' => $analyse
            ])
            ->format('a4')
            ->download($filename);
    }

}
