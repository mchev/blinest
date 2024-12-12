<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  diskSpace: {
    type: String,
    required: true
  },
  cacheDriver: {
    type: String,
    required: true
  }
});

// Add loading state refs
const isLoadingClearRounds = ref(false)
const isLoadingCache = ref(false)
const isLoadingTop10 = ref(false)

// Add new ref for command outputs
const clearRoundsOutput = ref('');
const cacheOutput = ref('');
const top10Output = ref('');

const resetOutputs = () => {
  clearRoundsOutput.value = ''
  cacheOutput.value = ''
  top10Output.value = ''
}

const forceClearRounds = () => {
  isLoadingClearRounds.value = true
  resetOutputs()
  router.post(route('admin.force-clear-rounds'), {}, {
    only: ['flash'],
    onSuccess: () => {
      clearRoundsOutput.value = computed(() => usePage().props.flash.message);
    },
    onFinish: () => isLoadingClearRounds.value = false,
    preserveScroll: true
  });
};

const clearCache = () => {
  isLoadingCache.value = true
  resetOutputs()
  router.post(route('admin.clear-cache'), {}, {
    only: ['flash'],
    onSuccess: () => {
      cacheOutput.value = computed(() => usePage().props.flash.message);
    },
    onFinish: () => isLoadingCache.value = false,
    preserveScroll: true
  });
};

const regenerateTop10 = () => {
  isLoadingTop10.value = true
  resetOutputs()
  router.post(route('admin.regenerate-top-10'), {}, {
    only: ['flash'],
    onSuccess: () => {
      top10Output.value = computed(() => usePage().props.flash.message);
    },
    onFinish: () => isLoadingTop10.value = false,
    preserveScroll: true
  });
};

</script>
<template>
  <Head title="Dashboard" />
  <AdminLayout>
   
    <section class="space-y-8">
      <h2 class="text-2xl font-bold">Maintenance</h2>

      <div class="grid grid-cols-3 gap-8 border-b border-gray-200 pb-8">
        <div class="col-span-1 prose dark:prose-invert">
          <h3>Espace restant sur le disque</h3>
          <p>Affiche l'espace disque restant sur le serveur. Un espace disque insuffisant peut causer des problèmes de performance et de stabilité.</p>
        </div>
        <div class="col-span-2">
          <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded space-y-4">
            <code class="whitespace-pre-wrap">{{ diskSpace }}</code>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-8 border-b border-gray-200 pb-8">
        <div class="col-span-1 prose dark:prose-invert">
          <h3>Forcer la mise à zéro de toutes les rooms</h3>
          <p>La mise à zéro des rooms et rounds mets le site en maintenance puis nettoie les files d'attente et termine les rounds en cours. Un fois terminé, le site sort de la maintenance.</p>
        </div>
        <div class="col-span-2">
          <button class="btn btn-primary" 
            @click="forceClearRounds" 
            :disabled="isLoadingClearRounds">
            {{ isLoadingClearRounds ? 'Chargement...' : 'Forcer la mise à zéro de toutes les rooms' }}
          </button>
          <div v-if="clearRoundsOutput" class="mt-4 p-4 bg-gray-100 dark:bg-gray-800 rounded">
            <code class="whitespace-pre-wrap">{{ clearRoundsOutput }}</code>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-8 border-b border-gray-200 pb-8">
        <div class="col-span-1 prose dark:prose-invert">
          <h3>Vider le cache</h3>
          <p>Cette action lance <code>php artisan optimize:clear</code> puis <code>php artisan optimize</code>.</p>
        </div>
        <div class="col-span-2">
          <button class="btn btn-primary" 
            @click="clearCache" 
            :disabled="isLoadingCache">
            {{ isLoadingCache ? 'Chargement...' : 'Vider le cache' }}
          </button>
          <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-800 rounded">
            <p>Driver: {{ cacheDriver }}</p>
          </div>
          <div v-if="cacheOutput" class="mt-4 p-4 bg-gray-100 dark:bg-gray-800 rounded">
            <code class="whitespace-pre-wrap">{{ cacheOutput }}</code>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-8 border-b border-gray-200 pb-8">
        <div class="col-span-1 prose dark:prose-invert">
          <h3>Regénérer le top 10 de la semaine</h3>
          <p>Regénérer le top 10 de la semaine. Cette action peut être utilisée pour réinitialiser le classement des joueurs.</p>
        </div>
        <div class="col-span-2">
          <button class="btn btn-primary" 
            @click="regenerateTop10" 
            :disabled="isLoadingTop10">
            {{ isLoadingTop10 ? 'Chargement...' : 'Regénérer le top 10 de la semaine' }}
          </button>
          <div v-if="top10Output" class="mt-4 p-4 bg-gray-100 dark:bg-gray-800 rounded">
            <code class="whitespace-pre-wrap">{{ top10Output }}</code>
          </div>
        </div>
      </div>

    </section>

  </AdminLayout>
</template>

