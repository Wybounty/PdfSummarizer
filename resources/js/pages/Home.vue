<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { analysis } from '@/routes/file'

interface Analysis {
    id: number
    title: string
    original_filename: string
    file_path: string
    analysis_json: any
    status: string
    error_message: string
}

defineProps<{
    analyses: Analysis[]
}>()

const fileInput = ref<HTMLInputElement | null>(null)
const isGenerating = ref(false)

const truncate = (text: string, limit = 250) => {
    if (!text) {
        return ''
    }

    return text.length > limit
        ? text.substring(0, limit) + '...'
        : text
}

const submitAnalysis = () => {
    const input = fileInput.value
    const file = input?.files?.[0]

    if (!file) {
        return
    }

    isGenerating.value = true

    router.post(
        analysis.url(),
        {
            file,
        },
        {
            forceFormData: true,
            preserveScroll: true,
            onFinish: () => {
                isGenerating.value = false
            },
        },
    )
}
</script>

<template>
    <Head title="PDF Summary Generator">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link
            href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap"
            rel="stylesheet"
        >
    </Head>

    <!-- Popup chargement -->
    <Teleport to="body">
        <div
            v-if="isGenerating"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/55 p-6 backdrop-blur-[2px]"
            role="dialog"
            aria-modal="true"
            aria-labelledby="generating-title"
            aria-busy="true"
        >
            <div
                class="w-full max-w-md rounded-2xl bg-white p-10 text-center shadow-2xl ring-1 ring-slate-200"
            >
                <h2
                    id="generating-title"
                    class="text-xl font-bold text-slate-800"
                    style="font-family: 'Archivo Black', sans-serif;"
                >
                    Génération en cours
                </h2>
                <p class="mt-4 text-base leading-relaxed text-slate-600">
                    Votre document est en cours de génération.
                    Merci de patienter, cette étape peut prendre un peu de temps.
                </p>
            </div>
        </div>
    </Teleport>

    <section
        class="min-h-screen bg-[#f4f7fb] py-20 px-6 flex flex-col items-center gap-20"
    >
        <!-- HERO -->
        <div
            class="w-full max-w-5xl bg-gradient-to-br from-blue-500 via-indigo-500 to-indigo-700 rounded-[2.5rem] p-14 text-white shadow-2xl"
        >
            <div class="flex flex-col gap-8">
                <div class="flex flex-col gap-5">
                    <h1 class="text-6xl leading-tight">
                        PDF Summary Generator
                    </h1>

                    <p class="text-xl text-white/90 leading-9 max-w-3xl">
                        Générez automatiquement des fiches de révision intelligentes
                        à partir de vos cours PDF grâce à l’IA.
                    </p>
                </div>

                <form
                    class="flex flex-col gap-6"
                    @submit.prevent="submitAnalysis"
                >
                    <input
                        ref="fileInput"
                        type="file"
                        name="file"
                        accept="application/pdf"
                        class="bg-white text-black rounded-2xl p-5 border-2 border-white/20"
                        required
                    >

                    <button
                        type="submit"
                        class="bg-white text-blue-600 font-bold py-4 rounded-2xl hover:scale-[1.02] transition-all duration-300 disabled:opacity-60"
                        :disabled="isGenerating"
                    >
                        Générer le résumé
                    </button>
                </form>
            </div>
        </div>

        <!-- LISTE ANALYSES -->
        <div class="w-full max-w-5xl flex flex-col gap-10">
            <div class="flex items-center justify-between">
                <h2 class="section-title">
                    Vos résumés
                </h2>

                <span
                    class="bg-blue-100 text-blue-600 px-5 py-2 rounded-full text-sm font-semibold"
                >
                    {{ analyses.length }} analyses
                </span>
            </div>

            <!-- EMPTY -->
            <div
                v-if="analyses.length === 0"
                class="bg-white rounded-3xl shadow-lg p-16 text-center"
            >
                <p class="text-2xl font-bold text-blue-500">
                    Aucun résumé généré
                </p>

                <p class="text-gray-500 mt-4">
                    Importez un PDF pour commencer.
                </p>
            </div>

            <!-- ANALYSES -->
            <div
                v-else
                class="flex flex-col gap-8"
            >
                <div
                    v-for="analysisItem in analyses"
                    :key="analysisItem.id"
                    class="bg-white rounded-[2rem] shadow-lg p-10 hover:shadow-2xl transition-all duration-300"
                >
                    <div class="flex flex-col gap-6">
                        <div class="flex items-start justify-between gap-6 flex-wrap">
                            <div class="flex flex-col gap-3">
                                <h3 class="text-3xl font-bold text-blue-500">
                                    {{ analysisItem.title }}
                                </h3>

                                <div class="flex items-center gap-3 flex-wrap">
                                    <span
                                        v-if="analysisItem.analysis_json?.presentation?.niveau"
                                        class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-semibold"
                                    >
                                        {{
                                            analysisItem.analysis_json.presentation
                                                .niveau
                                        }}
                                    </span>

                                    <span
                                        v-if="analysisItem.analysis_json?.presentation?.temps_revision_estime"
                                        class="bg-indigo-100 text-indigo-600 px-4 py-2 rounded-full text-sm font-semibold"
                                    >
                                        ⏱
                                        {{
                                            analysisItem.analysis_json.presentation
                                                .temps_revision_estime
                                        }}
                                    </span>
                                </div>
                            </div>

                            <a
                                :href="`/summary/${analysisItem.id}`"
                                class="bg-blue-500 text-white px-6 py-3 rounded-2xl font-semibold hover:bg-blue-600 transition-all"
                            >
                                Voir le résumé
                            </a>
                        </div>

                        <p class="text-gray-700 leading-9 text-lg">
                            {{
                                truncate(
                                    analysisItem.analysis_json?.presentation_generale,
                                )
                            }}
                        </p>

                        <div
                            v-if="analysisItem.analysis_json?.mots_cles?.length"
                            class="flex flex-wrap gap-3"
                        >
                            <span
                                v-for="mot in analysisItem.analysis_json.mots_cles"
                                :key="mot"
                                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm"
                            >
                                # {{ mot }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-sm text-gray-400">
                                {{ analysisItem.original_filename }}
                            </span>

                            <span
                                class="bg-green-100 text-green-600 px-4 py-2 rounded-full text-sm font-semibold"
                            >
                                {{ analysisItem.status }}
                            </span>
                        </div>
                    </div>
                </div>
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
    font-size: 2.5rem;
    color: #2563eb;
}
</style>
