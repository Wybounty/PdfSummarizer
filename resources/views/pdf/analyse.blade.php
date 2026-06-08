<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $analyse->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page { size: A4; margin: 0; }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f4f7fb;
            color: #374151;
            font-size: 13px;
            line-height: 1.65;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            width: 210mm;
            padding: 14mm 16mm;
            background: #f4f7fb;
        }

        .hero {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 55%, #4338ca 100%);
            border-radius: 20px;
            padding: 32px 36px;
            color: white;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .hero-sujet {
            display: inline-block;
            background: rgba(255,255,255,0.22);
            padding: 5px 14px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.98);
            margin-bottom: 12px;
        }

        .hero h1 {
            font-family: 'Archivo Black', 'Arial Black', Arial, sans-serif;
            font-size: 32px;
            line-height: 1.2;
            margin-bottom: 12px;
            color: white;
        }

        .hero-objectif {
            font-size: 14px;
            color: rgba(255,255,255,0.92);
            line-height: 1.75;
            margin-bottom: 14px;
        }

        .hero-themes { display: flex; flex-wrap: wrap; gap: 6px; }

        .hero-theme {
            background: white;
            color: #2563eb;
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
        }

        .section {
            background: white;
            border-radius: 18px;
            padding: 26px 28px;
            margin-bottom: 14px;
            border: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }

        .section-loose { page-break-inside: auto; }

        .section-title {
            font-family: 'Archivo Black', 'Arial Black', Arial, sans-serif;
            font-size: 20px;
            color: #2563eb;
            margin-bottom: 14px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .point-card {
            background: #fafbff;
            border-radius: 12px;
            padding: 14px 16px;
            border: 1px solid #e0e7ff;
            page-break-inside: avoid;
        }

        .notion-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 16px;
            background: #fafafa;
            page-break-inside: avoid;
        }

        .notion-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
        }

        .notion-title {
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
            line-height: 1.35;
            flex: 1;
        }

        .badge {
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .ex-block {
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 10px;
            page-break-inside: avoid;
            border: 1px solid #e5e7eb;
        }

        .ex-block--situation {
            background: #eef2ff;
            border-color: #c7d2fe;
        }

        .ex-block--resultat {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .ex-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .ex-block--situation .ex-label { color: #4338ca; }
        .ex-block--resultat .ex-label { color: #047857; }

        .keyword {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            font-size: 11px;
            font-weight: 600;
            color: #334155;
            margin: 3px 6px 3px 0;
        }

        .quiz-card {
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .quiz-q-title {
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .quiz-question {
            font-size: 13px;
            color: #111827;
            font-weight: 600;
            line-height: 1.55;
            margin-bottom: 10px;
        }

        .quiz-answer {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .quiz-answer-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #1d4ed8;
            margin-bottom: 6px;
        }

        .conclusion {
            background: linear-gradient(to right, #4f46e5, #2563eb);
            color: white;
            border-radius: 18px;
            padding: 28px 32px;
            page-break-inside: avoid;
        }

        .conclusion h2 {
            font-family: 'Archivo Black', 'Arial Black', Arial, sans-serif;
            font-size: 22px;
            margin-bottom: 12px;
            color: white;
        }

        .conclusion p {
            line-height: 1.8;
            font-size: 13px;
            color: rgba(255,255,255,0.95);
        }

        p.body { font-size: 13px; color: #374151; line-height: 1.8; }
    </style>
</head>
<body>
@php
    $data = is_array($analyse->analysis_json) ? $analyse->analysis_json : [];
    $presentation = $data['presentation'] ?? [];
    $themes = $presentation['themes'] ?? [];
    $points_cles = $data['points_cles'] ?? [];
    $notions = $data['notions_importantes'] ?? [];
    $exemple = $data['exemple_concret'] ?? [];
    $exemple = is_array($exemple) ? $exemple : [];
    $mots_cles = $data['mots_cles'] ?? [];
    $quiz = $data['quiz'] ?? [];
    $conclusion = $data['conclusion'] ?? 'Aucune conclusion générée.';

    $hasExemple = !empty(array_filter([
        $exemple['situation'] ?? '',
        $exemple['probleme'] ?? '',
        $exemple['solution'] ?? '',
        $exemple['pourquoi_cette_solution'] ?? '',
        $exemple['resultat'] ?? '',
    ]));
@endphp

<div class="page">

    <div class="hero">
        @if(!empty($presentation['sujet']))
            <div class="hero-sujet">{{ $presentation['sujet'] }}</div>
        @endif
        <h1>{{ $analyse->title }}</h1>
        @if(!empty($presentation['objectif']))
            <p class="hero-objectif">{{ $presentation['objectif'] }}</p>
        @endif
        @if(!empty($themes))
            <div class="hero-themes">
                @foreach($themes as $theme)
                    <span class="hero-theme">{{ $theme }}</span>
                @endforeach
            </div>
        @endif
    </div>

    @if(!empty($data['presentation_generale']))
        <div class="section section-loose">
            <h2 class="section-title">Présentation générale</h2>
            <p class="body">{{ $data['presentation_generale'] }}</p>
        </div>
    @endif

    @if(!empty($points_cles))
        <div class="section section-loose">
            <h2 class="section-title">Points clés</h2>
            <div class="grid-2">
                @foreach($points_cles as $point)
                    <div class="point-card"><p class="body">{{ $point }}</p></div>
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($notions))
        <div class="section section-loose">
            <h2 class="section-title">Notions importantes</h2>
            <div class="grid-2">
                @foreach($notions as $notion)
                    @php
                        $imp = $notion['importance'] ?? '';
                        $badgeStyle = match($imp) {
                            'élevée' => 'background:#fee2e2;color:#b91c1c',
                            'moyenne' => 'background:#ffedd5;color:#c2410c',
                            'faible' => 'background:#d1fae5;color:#047857',
                            default => 'background:#f3f4f6;color:#4b5563',
                        };
                    @endphp
                    <div class="notion-card">
                        <div class="notion-header">
                            <span class="notion-title">{{ $notion['titre'] ?? '' }}</span>
                            @if($imp !== '')
                                <span class="badge" style="{{ $badgeStyle }}">{{ $imp }}</span>
                            @endif
                        </div>
                        <p class="body">{{ $notion['explication'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($hasExemple)
        <div class="section section-loose">
            <h2 class="section-title">Exemple concret</h2>
            @if(!empty($exemple['situation']))
                <div class="ex-block ex-block--situation">
                    <div class="ex-label">Situation</div>
                    <p class="body">{{ $exemple['situation'] }}</p>
                </div>
            @endif
            @if(!empty($exemple['probleme']))
                <div class="ex-block">
                    <div class="ex-label">Problème</div>
                    <p class="body">{{ $exemple['probleme'] }}</p>
                </div>
            @endif
            @if(!empty($exemple['solution']))
                <div class="ex-block">
                    <div class="ex-label">Solution</div>
                    <p class="body">{{ $exemple['solution'] }}</p>
                </div>
            @endif
            @if(!empty($exemple['pourquoi_cette_solution']))
                <div class="ex-block">
                    <div class="ex-label">Pourquoi cette solution</div>
                    <p class="body">{{ $exemple['pourquoi_cette_solution'] }}</p>
                </div>
            @endif
            @if(!empty($exemple['resultat']))
                <div class="ex-block ex-block--resultat">
                    <div class="ex-label">Résultat</div>
                    <p class="body">{{ $exemple['resultat'] }}</p>
                </div>
            @endif
        </div>
    @endif

    @if(!empty($mots_cles))
        <div class="section">
            <h2 class="section-title">Mots clés</h2>
            <div>
                @foreach($mots_cles as $mot)
                    <span class="keyword">{{ $mot }}</span>
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($quiz))
        <div class="section section-loose">
            <h2 class="section-title">Quiz de révision</h2>
            @foreach($quiz as $index => $q)
                <div class="quiz-card">
                    <div class="quiz-q-title">Question {{ $index + 1 }}</div>
                    <p class="quiz-question">{{ $q['question'] ?? '' }}</p>
                    <div class="quiz-answer">
                        <div class="quiz-answer-label">Réponse</div>
                        <p class="body" style="margin:0;">{{ $q['reponse'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="conclusion">
        <h2>Conclusion</h2>
        <p>{{ $conclusion }}</p>
    </div>

</div>
</body>
</html>
