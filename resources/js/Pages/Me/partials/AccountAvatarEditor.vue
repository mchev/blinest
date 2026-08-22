<script setup>
import { computed, onBeforeUnmount, ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import UserAvatar from '@/Components/UserAvatar.vue'
import LucideIcon from '@/Components/Icons/LucideIcon.vue'
import { useTranslate } from '@/composables/useTranslate'

const props = defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const translate = useTranslate()
const fileInput = ref(null)
const isDragging = ref(false)
const previewUrl = ref(null)

const form = useForm({
  photo: null,
})

const displayUser = computed(() => ({
  ...props.account,
  photo: previewUrl.value || props.account.photo,
}))

const uploadFile = (file) => {
  if (!file || !file.type.startsWith('image/')) {
    return
  }

  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
  }

  previewUrl.value = URL.createObjectURL(file)
  form.photo = file

  form.post(route('users.photo.update', props.account.id), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      form.reset()
      if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
        previewUrl.value = null
      }
    },
    onError: () => {
      if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
        previewUrl.value = null
      }
    },
  })
}

const openPicker = () => {
  if (!form.processing) {
    fileInput.value?.click()
  }
}

const onInputChange = (event) => {
  const file = event.target.files?.[0]

  if (file) {
    uploadFile(file)
  }

  event.target.value = ''
}

const onDrop = (event) => {
  isDragging.value = false
  uploadFile(event.dataTransfer?.files?.[0])
}

const removePhoto = () => {
  if (!props.account.has_custom_photo || form.processing) {
    return
  }

  if (!confirm(translate('Remove avatar confirm'))) {
    return
  }

  router.delete(route('users.photo.destroy', props.account.id), {
    preserveScroll: true,
  })
}

onBeforeUnmount(() => {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
  }
})
</script>

<template>
  <div class="flex flex-col items-center gap-4 sm:items-start">
    <div class="group relative" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="onDrop">
      <button type="button" class="relative rounded-full outline-none ring-brand-primary/40 transition focus-visible:ring-2" :class="isDragging ? 'ring-2 ring-brand-secondary' : ''" :disabled="form.processing" @click="openPicker">
        <UserAvatar :user="displayUser" img-class="h-28 w-28 rounded-full border-2 border-white/15 object-cover shadow-lg sm:h-32 sm:w-32" crown-size="lg" />

        <span class="absolute inset-0 flex flex-col items-center justify-center gap-1 rounded-full bg-brand-midnight/70 opacity-0 transition group-focus-within:opacity-100 group-hover:opacity-100" :class="form.processing || isDragging ? '!opacity-100' : ''">
          <LucideIcon v-if="!form.processing" name="camera" icon-class="h-6 w-6 text-white" />
          <svg v-else class="h-6 w-6 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
          </svg>
          <span class="text-[10px] font-semibold uppercase tracking-wide text-white/90">
            {{ isDragging ? __('Drop image here') : __('Change photo') }}
          </span>
        </span>
      </button>

      <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onInputChange" />
    </div>

    <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
      <button type="button" class="rounded-lg border border-white/15 px-3 py-1.5 text-xs font-semibold text-white/80 transition hover:border-brand-primary/40 hover:text-white" :disabled="form.processing" @click="openPicker">
        {{ __('Change photo') }}
      </button>
      <button v-if="account.has_custom_photo" type="button" class="rounded-lg border border-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-300/90 transition hover:border-red-400/40 hover:text-red-200" :disabled="form.processing" @click="removePhoto">
        {{ __('Remove photo') }}
      </button>
    </div>

    <p v-if="form.errors.photo" class="text-xs text-red-400">{{ form.errors.photo }}</p>
    <p class="max-w-xs text-center text-xs text-white/40 sm:text-left">{{ __('Avatar upload help') }}</p>
  </div>
</template>
