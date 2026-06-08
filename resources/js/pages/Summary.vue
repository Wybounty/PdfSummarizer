<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import type { PropType } from 'vue'
import { home } from '@/routes'

interface Analyse {
    id: number
    title: string
    original_filename: string
    file_path: string
    analysis_json: any
    status: string
    error_message: string
}

const props = defineProps({
    analyse: Object as PropType<Analyse>,
})

const getImportanceColor = (importance: string) => {
    switch (importance) {
        case 'élevée':
            return 'bg-red-100 text-red-700'
        case 'moyenne':
            return 'bg-orange-100 text-orange-700'
        case 'faible':
            return 'bg-emerald-100 text-emerald-700'
        default:
            return 'bg-gray-100 text-gray-700'
    }
}

const data = props.analyse?.analysis_json || {}

const hasExempleConcret = (ex: unknown): ex is Record<string, string> => {
    if (!ex || typeof ex !== 'object') {
        return false
    }

    const o = ex as Record<string, string>

    return Boolean(
        o.situation ||
            o.probleme ||
            o.solution ||
            o.pourquoi_cette_solution ||
            o.resultat,
    )
}
</script>

<template>
    <Head title="Analyse PDF" />

    <section
        v-if="!analyse?.analysis_json"
        class="min-h-screen flex flex-col bg-[#f4f7fb] px-4 py-8 sm:px-6"
    >
        <div class="w-full max-w-4xl mx-auto mb-8">
            <Link
                :href="home()"
                class="inline-flex items-center gap-2 text-blue-600 font-semibold text-sm hover:text-blue-700"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="m15 18-6-6 6-6" />
                </svg>
                Accueil
            </Link>
        </div>
        <div class="flex flex-1 items-center justify-center px-2">
            <div
                class="bg-white rounded-2xl shadow-lg border border-gray-100 p-10 max-w-xl text-center w-full"
            >
                <h1
                    class="text-2xl text-red-600 mb-4"
                    style="font-family: 'Archivo Black', sans-serif;"
                >
                    Analyse indisponible
                </h1>
                <p class="text-gray-600 leading-relaxed">
                    Cette analyse n'a pas encore été générée ou une erreur est
                    survenue pendant le traitement.
                </p>
            </div>
        </div>
    </section>

    <section
        v-else
        class="min-h-screen bg-[#f4f7fb] py-12 px-4 sm:px-6 flex justify-center"
    >
        <div class="w-full max-w-4xl flex flex-col gap-8">
            <div>
                <Link
                    :href="home()"
                    class="inline-flex items-center gap-2 text-blue-600 font-semibold text-sm hover:text-blue-700"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Accueil
                </Link>
            </div>

            <!-- HERO -->
            <div
                class="bg-gradient-to-br from-blue-500 via-indigo-500 to-indigo-700 rounded-2xl p-8 sm:p-10 text-white shadow-xl"
            >
                <div class="flex flex-col gap-5">
                    <div
                        v-if="data.presentation?.sujet"
                        class="inline-flex w-fit"
                    >
                        <span
                            class="bg-white/20 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide uppercase text-white/95"
                        >
                            {{ data.presentation.sujet }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl leading-tight font-normal">
                        {{ analyse?.title }}
                    </h1>

                    <p
                        v-if="data.presentation?.objectif"
                        class="text-base sm:text-lg text-white/90 leading-relaxed max-w-3xl"
                    >
                        {{ data.presentation.objectif }}
                    </p>

                    <div
                        v-if="(data.presentation?.themes || []).length"
                        class="flex flex-wrap gap-2 pt-1"
                    >
                        <span
                            v-for="theme in data.presentation.themes"
                            :key="theme"
                            class="bg-white text-blue-600 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold shadow-sm"
                        >
                            {{ theme }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2 pt-2">

                        <a
                            :href="`/summary/${analyse?.id}/pdf`"
                            class="inline-flex items-center gap-2 bg-white text-blue-600 px-5 py-2.5 rounded-full text-sm font-semibold shadow-md hover:bg-blue-50 transition-colors"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 shrink-0"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"
                                />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Télécharger la fiche PDF
                        </a>
                        <a
                            v-if="analyse?.file_path"
                            :href="`/storage/${analyse.file_path}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 bg-white/15 border border-white/30 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-white/25 transition-colors"
                        >
                            PDF original
                        </a>
                    </div>
                </div>
            </div>

            <!-- PRÉSENTATION GÉNÉRALE -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
                <h2 class="section-title text-xl sm:text-2xl mb-4">
                    Présentation générale
                </h2>
                <p
                    class="text-gray-700 leading-8 text-[1.05rem] whitespace-pre-line"
                >
                    {{ data.presentation_generale }}
                </p>
            </div>

            <!-- POINTS CLÉS -->
            <div class="flex flex-col gap-4">
                <h2 class="section-title text-xl sm:text-2xl px-1">
                    Points clés
                </h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 list-none">
                    <li
                        v-for="(point, i) in data.points_cles || []"
                        :key="i"
                        class="bg-white rounded-xl p-5 shadow-sm border border-blue-50/80"
                    >
                        <p class="text-gray-800 leading-relaxed text-sm sm:text-base">
                            {{ point }}
                        </p>
                    </li>
                </ul>
            </div>

            <!-- NOTIONS IMPORTANTES -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
                <h2 class="section-title text-xl sm:text-2xl mb-6">
                    Notions importantes
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div
                        v-for="notion in data.notions_importantes || []"
                        :key="notion.titre"
                        class="rounded-xl border border-gray-100 p-5 flex flex-col gap-3 bg-gray-50/50"
                    >
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <h3
                                class="text-lg font-bold text-blue-600 leading-snug pr-2"
                            >
                                {{ notion.titre }}
                            </h3>
                            <span
                                v-if="notion.importance"
                                :class="getImportanceColor(notion.importance)"
                                class="shrink-0 px-3 py-1 rounded-full text-xs font-bold capitalize"
                            >
                                {{ notion.importance }}
                            </span>
                        </div>
                        <p class="text-gray-700 leading-relaxed text-sm sm:text-[0.95rem]">
                            {{ notion.explication }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- EXEMPLE CONCRET -->
            <div
                v-if="hasExempleConcret(data.exemple_concret)"
                class="bg-white rounded-2xl shadow-md border border-indigo-100 p-8"
            >
                <h2 class="section-title text-xl sm:text-2xl mb-5">
                    Exemple concret
                </h2>
                <div class="space-y-4 text-sm sm:text-base">
                    <div
                        v-if="data.exemple_concret.situation"
                        class="rounded-lg bg-indigo-50/80 border border-indigo-100 p-4"
                    >
                        <p class="text-xs font-bold uppercase tracking-wide text-indigo-700 mb-2">
                            Situation
                        </p>
                        <p class="text-gray-800 leading-relaxed">
                            {{ data.exemple_concret.situation }}
                        </p>
                    </div>
                    <div
                        v-if="data.exemple_concret.probleme"
                        class="rounded-lg border border-gray-100 p-4"
                    >
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-600 mb-2">
                            Problème
                        </p>
                        <p class="text-gray-800 leading-relaxed">
                            {{ data.exemple_concret.probleme }}
                        </p>
                    </div>
                    <div
                        v-if="data.exemple_concret.solution"
                        class="rounded-lg border border-gray-100 p-4"
                    >
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-600 mb-2">
                            Solution
                        </p>
                        <p class="text-gray-800 leading-relaxed">
                            {{ data.exemple_concret.solution }}
                        </p>
                    </div>
                    <div
                        v-if="data.exemple_concret.pourquoi_cette_solution"
                        class="rounded-lg border border-gray-100 p-4"
                    >
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-600 mb-2">
                            Pourquoi cette solution
                        </p>
                        <p class="text-gray-800 leading-relaxed">
                            {{ data.exemple_concret.pourquoi_cette_solution }}
                        </p>
                    </div>
                    <div
                        v-if="data.exemple_concret.resultat"
                        class="rounded-lg bg-emerald-50/80 border border-emerald-100 p-4"
                    >
                        <p class="text-xs font-bold uppercase tracking-wide text-emerald-800 mb-2">
                            Résultat
                        </p>
                        <p class="text-gray-800 leading-relaxed">
                            {{ data.exemple_concret.resultat }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- MOTS CLÉS -->
            <div
                v-if="(data.mots_cles || []).length"
                class="bg-white rounded-2xl shadow-md border border-gray-100 p-8"
            >
                <h2 class="section-title text-xl sm:text-2xl mb-4">
                    Mots clés
                </h2>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="mot in data.mots_cles"
                        :key="mot"
                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 text-slate-800 text-xs sm:text-sm font-medium border border-slate-200/80"
                    >
                        {{ mot }}
                    </span>
                </div>
            </div>

            <!-- QUIZ -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
                <h2 class="section-title text-xl sm:text-2xl mb-6">
                    Quiz de révision
                </h2>
                <div class="flex flex-col gap-6">
                    <div
                        v-for="(q, index) in data.quiz || []"
                        :key="index"
                        class="rounded-xl border border-blue-100 p-6 bg-white"
                    >
                        <h3 class="text-blue-600 font-bold text-base sm:text-lg mb-3">
                            Question {{ index + 1 }}
                        </h3>
                        <p class="text-gray-900 leading-relaxed mb-4 font-medium">
                            {{ q.question }}
                        </p>
                        <div class="rounded-lg bg-blue-50/90 border border-blue-100/80 p-4">
                            <p class="text-xs font-semibold text-blue-800 uppercase tracking-wide mb-2">
                                Réponse
                            </p>
                            <p class="text-gray-800 leading-relaxed text-sm sm:text-base">
                                {{ q.reponse }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONCLUSION -->
            <div
                class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl p-8 shadow-lg"
            >
                <h2 class="text-2xl mb-4" style="font-family: 'Archivo Black', sans-serif;">
                    Conclusion
                </h2>
                <p class="leading-relaxed text-white/95 whitespace-pre-line text-sm sm:text-base">
                    {{ data.conclusion || 'Aucune conclusion générée.' }}
                </p>
            </div>
        </div>
    </section>
</template>

<style>
h1,
.section-title {
    font-family: 'Archivo Black', sans-serif;
}

.section-title {
    color: #2563eb;
}
</style>
