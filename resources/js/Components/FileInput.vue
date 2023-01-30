<script setup>
import { watch, ref } from "vue";

const props = defineProps({
  modelValue: File,
  label: String,
  accept: String,
  error: String,
})

const emit = defineEmits(['update:modelValue'])
const file = ref(null)

watch(props.modelValue, (value) => {
  if (!value) {
    file.value = ''
  }
})

const filesize = (size) => {
  var i = Math.floor(Math.log(size) / Math.log(1024))
  return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
}

const browse = () => {
  file.value.click()
}

const change = (e) => {
  emit('update:modelValue', e.target.files[0])
}

const remove = () => {
  emit('update:modelValue', null)
}

</script>
<template>
  <div>
    <label v-if="label" class="form-label">{{ label }}:</label>
    <div class="form-input p-0" :class="{ error: error }">
      <input ref="file" type="file" :accept="accept" class="hidden" @change="change" />
      <div v-if="!modelValue" class="px-2">
        <button type="button" class="rounded-sm bg-neutral-500 px-4 py-1 text-xs font-medium text-white hover:bg-neutral-700" @click="browse">{{ __('Browse') }}</button>
      </div>
      <div v-else class="flex items-center justify-between p-2">
        <div class="flex-1 pr-1">
          {{ modelValue.name }} <span class="text-xs text-neutral-500">({{ filesize(modelValue.size) }})</span>
        </div>
        <button type="button" class="rounded-sm bg-neutral-500 px-4 py-1 text-xs font-medium text-white hover:bg-neutral-700" @click="remove">{{ __('Remove') }}</button>
      </div>
    </div>
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>