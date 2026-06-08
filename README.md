# PDF Summary Generator

Application web qui transforme des cours PDF en fiches de révision structurées grâce à l’IA. L’utilisateur dépose un document sur la page d’accueil ; le backend l’envoie à un workflow n8n qui extrait le texte, génère une analyse pédagogique via OpenAI, puis Laravel enregistre le résultat. La fiche peut ensuite être consultée en ligne ou exportée en PDF.

# Fonctionnalités

- Upload de fichiers PDF (validation MIME, taille max. 10 Mo)
- Analyse automatique via webhook n8n et modèle OpenAI (GPT-4o-mini)
- Génération d’une fiche de révision structurée : présentation, notions importantes, points clés, exemple concret, quiz, mots-clés et conclusion
- Historique des analyses sur la page d’accueil (triées du plus récent au plus ancien)
- Consultation détaillée d’une fiche (`/summary/{id}`)
- Export PDF de la fiche via Spatie Laravel PDF / Browsershot
- Accès au PDF original uploadé (stockage public)
- Authentification Laravel Fortify (inscription, connexion, réinitialisation de mot de passe, vérification e-mail, 2FA) — les routes `/login` et `/register` redirigent actuellement vers l’accueil
- Tableau de bord et paramètres utilisateur (profil, sécurité, apparence) pour les utilisateurs authentifiés
- Endpoint de santé Laravel (`/up`)

# Stack technique

| Catégorie | Technologies |
|-----------|--------------|
| **Backend** | PHP 8.3+, Laravel 13 |
| **Frontend** | Vue 3, TypeScript, Inertia.js 3, Tailwind CSS 4, Vite 8 |
| **Base de données** | SQLite par défaut (MySQL, MariaDB, PostgreSQL supportés via Laravel) |
| **Authentification** | Laravel Fortify |
| **Génération PDF** | Spatie Laravel PDF, Spatie Browsershot, Puppeteer |
| **Services externes** | n8n (webhook), OpenAI (via workflow n8n) |
| **HTTP client** | Guzzle (facade `Http` de Laravel) |
| **Outils de développement** | Pest, PHPUnit, Laravel Pint, ESLint, Prettier, vue-tsc, Laravel Pail, Laravel Wayfinder, GitHub Actions |

# Architecture

## Backend

- **Contrôleurs** : `HomeController` (accueil, upload et analyse), `SummaryController` (affichage et export PDF)
- **Modèle** : `Analyse` — stocke titre, nom de fichier, chemin, JSON d’analyse et statut
- **Configuration** : `config/services.php` expose l’URL du webhook n8n (`N8N_WEBHOOK_URL`)
- **Vues PDF** : template Blade `resources/views/pdf/analyse.blade.php`

## Frontend

- SPA Inertia avec pages Vue dans `resources/js/pages/`
- Pages principales : `Home.vue` (upload + liste), `Summary.vue` (fiche détaillée)
- Pages auth et settings héritées du starter kit Laravel + Vue

## Services

- Analyse PDF déléguée à n8n (timeout HTTP de 120 secondes côté Laravel)
- Fichiers uploadés stockés sur le disque `public` (`storage/app/public/analyses/`)
- File d’attente Laravel configurée sur `database` (écoute via `composer dev`)

## Automatisations

- Workflow n8n versionné dans `database/n8n/Workflow.json`
- CI GitHub Actions : tests (PHP 8.3–8.5) et lint (Pint, ESLint, Prettier)

## Intégrations externes

1. **n8n** — reçoit le PDF, extrait le texte, appelle OpenAI, renvoie du JSON
2. **OpenAI** — configuré dans n8n (credentials `openAiApi`), modèle `gpt-4o-mini`

# Installation

## Prérequis

- PHP ≥ 8.3 avec extensions Laravel habituelles
- [Composer](https://getcomposer.org/)
- Node.js ≥ 22 (version utilisée en CI)
- npm
- Chromium ou Google Chrome (requis pour la génération PDF via Browsershot)
- Instance n8n avec le workflow importé et une clé API OpenAI configurée

## Étapes

```bash
git clone <url-du-depot> PdfSummarizer
cd PdfSummarizer

composer install
cp .env.example .env
php artisan key:generate

# Base SQLite (défaut) — le fichier est créé automatiquement si absent
touch database/database.sqlite

php artisan migrate
php artisan storage:link

npm install
npm run build
```

### Installation automatisée

Le script Composer `setup` enchaîne plusieurs de ces étapes :

```bash
composer setup
php artisan storage:link
```

### Lancer en développement

```bash
composer dev
```

Cette commande démarre simultanément :

- `php artisan serve`
- `php artisan queue:listen --tries=1`
- `npm run dev`

L’application est alors accessible sur `http://localhost:8000` (port par défaut d’Artisan).

# Configuration

Variables d’environnement importantes identifiées dans le projet :

```env
APP_NAME=
APP_ENV=
APP_KEY=
APP_DEBUG=
APP_URL=

DB_CONNECTION=
DB_DATABASE=

SESSION_DRIVER=
QUEUE_CONNECTION=
CACHE_STORE=
FILESYSTEM_DISK=

N8N_WEBHOOK_URL=

VITE_APP_NAME=
```

| Variable | Rôle |
|----------|------|
| `APP_URL` | URL de base de l’application |
| `DB_*` | Connexion base de données (SQLite par défaut) |
| `N8N_WEBHOOK_URL` | URL du webhook n8n appelé lors de l’upload PDF |
| `QUEUE_CONNECTION` | File d’attente (`database` en dev via `composer dev`) |
| `FILESYSTEM_DISK` | Disque de stockage (`local` par défaut ; uploads sur disque `public`) |

> **OpenAI** n’est pas configuré dans Laravel : les credentials sont gérés directement dans n8n lors de l’import du workflow.

Optionnel (driver PDF Spatie, non présent dans `.env.example`, valeur par défaut `browsershot`) :

```env
LARAVEL_PDF_DRIVER=
```

# Utilisation

1. Configurer `N8N_WEBHOOK_URL` dans `.env` avec l’URL du webhook n8n actif.
2. Importer `database/n8n/Workflow.json` dans n8n et configurer les credentials OpenAI.
3. Démarrer l’application (`composer dev` ou `php artisan serve` + `npm run dev`).
4. Ouvrir la page d’accueil (`/`).
5. Sélectionner un fichier PDF et cliquer sur **Générer le résumé**.
6. Attendre la fin du traitement (popup « Génération en cours »).
7. Consulter la fiche depuis la liste **Vos résumés**, puis :
   - **Voir le résumé** — affichage web complet
   - **Télécharger la fiche PDF** — export PDF
   - **PDF original** — accès au fichier source

# API

L’application expose des routes web (pas d’API REST dédiée). Endpoints principaux :

| Méthode | Route | Nom | Description |
|---------|-------|-----|-------------|
| `GET` | `/` | `home` | Page d’accueil (upload + historique) |
| `POST` | `/file-analysis` | `file.analysis` | Upload et analyse d’un PDF |
| `GET` | `/summary/{analyse}` | `summary.show` | Affichage d’une fiche |
| `GET` | `/summary/{analyse}/pdf` | `summary.pdf` | Téléchargement PDF de la fiche |
| `GET` | `/up` | — | Health check Laravel |
| `GET` | `/dashboard` | `dashboard` | Tableau de bord (auth + verified) |
| `GET/PATCH` | `/settings/profile` | `profile.edit` / `profile.update` | Profil (auth) |
| `GET/PUT` | `/settings/security`, `/settings/password` | — | Sécurité et mot de passe (auth) |
| `GET` | `/settings/appearance` | `appearance.edit` | Apparence (auth + verified) |

Routes Fortify (login, register, reset password, 2FA, etc.) sont enregistrées automatiquement. `/login` et `/register` redirigent vers `/`.

**Corps de la requête d’analyse** : `multipart/form-data` avec le champ `file` (PDF, max. 10 240 Ko).

**Réponse attendue du webhook n8n** : JSON structuré contenant notamment `presentation`, `presentation_generale`, `notions_importantes`, `points_cles`, `exemple_concret`, `quiz`, `mots_cles`, `conclusion`.

# Workflows et automatisations

## Workflow n8n (`database/n8n/Workflow.json`)

Pipeline en 4 nœuds :

```text
Webhook (POST, binaire PDF)
    → Extract from File (extraction texte PDF)
    → Message a model (OpenAI GPT-4o-mini)
    → Respond to Webhook (JSON parsé)
```

- **Entrée** : PDF envoyé par Laravel en pièce jointe (`data`)
- **Traitement IA** : prompt pédagogique en français produisant une fiche de révision au format JSON strict
- **Sortie** : JSON consommé par `HomeController` et persisté dans `analyses.analysis_json`

Le workflow doit être **actif** dans n8n. L’URL du webhook doit correspondre à `N8N_WEBHOOK_URL`.

## CI GitHub Actions

- **tests.yml** — `composer install`, `npm run build`, exécution Pest (PHP 8.3, 8.4, 8.5)
- **lint.yml** — Laravel Pint, Prettier, ESLint

# Déploiement

Aucun fichier de déploiement spécifique (Docker, Forge, Vapor, etc.) n’a été identifié dans le dépôt. Les éléments disponibles suggèrent un déploiement Laravel classique :

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env   # puis configurer les variables de production
php artisan key:generate
php artisan migrate --force
php artisan storage:link
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Points à prévoir en production :

- Serveur web (Nginx/Apache) pointant vers `public/`
- Processus worker pour la file d’attente si `QUEUE_CONNECTION=database`
- Chromium disponible sur le serveur pour Browsershot
- Instance n8n accessible depuis le serveur Laravel
- CI GitHub Actions sur les branches `main`, `master`, `develop`, `workos`

# Structure du projet

```text
app/
├── Http/
│   ├── Controllers/       # HomeController, SummaryController, Settings
│   └── Middleware/
├── Models/                # Analyse, User
├── Actions/Fortify/       # Actions d'authentification
└── Providers/

config/                    # Configuration Laravel (services, fortify, etc.)

database/
├── migrations/            # users, analyses, cache, jobs
├── n8n/
│   └── Workflow.json      # Workflow d'analyse PDF
└── seeders/

resources/
├── js/
│   ├── pages/             # Home.vue, Summary.vue, auth, settings
│   └── app.ts
├── css/
└── views/
    └── pdf/               # Template Blade pour l'export PDF

routes/
├── web.php                # Routes principales
└── settings.php           # Routes paramètres utilisateur

tests/                     # Tests Pest (Feature, Unit)

public/                    # Point d'entrée web
storage/                   # Fichiers uploadés, logs, cache
```

# Auteur

**Wybounty**