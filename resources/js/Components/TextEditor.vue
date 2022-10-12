<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

const props = defineProps({
  label: String,
  error: String,
  modelValue: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:modelValue'])

const editor = ref(null)

// const editor = useEditor({
//   content: '<p>I’m running Tiptap with Vue.js. 🎉</p>',
//   extensions: [StarterKit],
//   onUpdate({ editor }) {
//     console.log(editor.value)
//     //emit('update:modelValue', editor)
//   },
// })

watch(
  () => props.modelValue,
  (value) => {
    let isSame = editor.value.getHTML() === value
    if (isSame) {
      return
    }
    editor.value.commands.setContent(value, false)
  },
)

onMounted(() => {
  editor.value = new Editor({
    extensions: [
      StarterKit,
    ],
    content: props.modelValue,
    onUpdate: () => {
      emit('update:modelValue', editor.value.getHTML())
    },
  })
})

onBeforeUnmount(() => {
  editor.value.destroy()
})
</script>
<template>
  <div class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <div v-if="editor" class="mb-2 text-sm">
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleBold().run()" :disabled="!editor.can().chain().focus().toggleBold().run()" :class="{ 'bg-neutral-600': editor.isActive('bold') }">bold</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleItalic().run()" :disabled="!editor.can().chain().focus().toggleItalic().run()" :class="{ 'bg-neutral-600': editor.isActive('italic') }">italic</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleStrike().run()" :disabled="!editor.can().chain().focus().toggleStrike().run()" :class="{ 'bg-neutral-600': editor.isActive('strike') }">strike</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleCode().run()" :disabled="!editor.can().chain().focus().toggleCode().run()" :class="{ 'bg-neutral-600': editor.isActive('code') }">code</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().unsetAllMarks().run()">clear marks</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().clearNodes().run()">clear nodes</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().setParagraph().run()" :class="{ 'bg-neutral-600': editor.isActive('paragraph') }">paragraph</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="{ 'bg-neutral-600': editor.isActive('heading', { level: 1 }) }">h1</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'bg-neutral-600': editor.isActive('heading', { level: 2 }) }">h2</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'bg-neutral-600': editor.isActive('heading', { level: 3 }) }">h3</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" :class="{ 'bg-neutral-600': editor.isActive('heading', { level: 4 }) }">h4</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleHeading({ level: 5 }).run()" :class="{ 'bg-neutral-600': editor.isActive('heading', { level: 5 }) }">h5</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleHeading({ level: 6 }).run()" :class="{ 'bg-neutral-600': editor.isActive('heading', { level: 6 }) }">h6</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'bg-neutral-600': editor.isActive('bulletList') }">bullet list</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'bg-neutral-600': editor.isActive('orderedList') }">ordered list</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleCodeBlock().run()" :class="{ 'bg-neutral-600': editor.isActive('codeBlock') }">code block</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'bg-neutral-600': editor.isActive('blockquote') }">blockquote</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().setHorizontalRule().run()">horizontal rule</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().setHardBreak().run()">hard break</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().undo().run()" :disabled="!editor.can().chain().focus().undo().run()">undo</button>
      <button class="mr-1 rounded border p-1" type="button" @click="editor.chain().focus().redo().run()" :disabled="!editor.can().chain().focus().redo().run()">redo</button>
    </div>
    <editor-content :editor="editor" @update="$emit('update:modelValue', $event.target.value)" class="prose border p-1 rounded w-full border-neutral-500 text-inherit" :class="{ error: error }" />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
