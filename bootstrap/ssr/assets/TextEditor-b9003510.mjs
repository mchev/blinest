import { ref, watch, onMounted, onBeforeUnmount, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrInterpolate, ssrIncludeBooleanAttr, ssrRenderClass, ssrRenderComponent } from "vue/server-renderer";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
const _sfc_main = {
  __name: "TextEditor",
  __ssrInlineRender: true,
  props: {
    label: String,
    error: String,
    modelValue: {
      type: String,
      default: ""
    }
  },
  emits: ["update:modelValue"],
  setup(__props, { emit }) {
    const props = __props;
    const editor = ref(null);
    watch(
      () => props.modelValue,
      (value) => {
        let isSame = editor.value.getHTML() === value;
        if (isSame) {
          return;
        }
        editor.value.commands.setContent(value, false);
      }
    );
    onMounted(() => {
      editor.value = new Editor({
        extensions: [StarterKit],
        content: props.modelValue,
        onUpdate: () => {
          emit("update:modelValue", editor.value.getHTML());
        }
      });
    });
    onBeforeUnmount(() => {
      editor.value.destroy();
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "$attrs.class" }, _attrs))}>`);
      if (__props.label) {
        _push(`<label class="form-label"${ssrRenderAttr("for", _ctx.id)}>${ssrInterpolate(__props.label)}:</label>`);
      } else {
        _push(`<!---->`);
      }
      if (editor.value) {
        _push(`<div class="mb-2 text-sm"><button type="button"${ssrIncludeBooleanAttr(!editor.value.can().chain().focus().toggleBold().run()) ? " disabled" : ""} class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("bold") }, "mr-1 rounded border p-1"])}">bold</button><button type="button"${ssrIncludeBooleanAttr(!editor.value.can().chain().focus().toggleItalic().run()) ? " disabled" : ""} class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("italic") }, "mr-1 rounded border p-1"])}">italic</button><button type="button"${ssrIncludeBooleanAttr(!editor.value.can().chain().focus().toggleStrike().run()) ? " disabled" : ""} class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("strike") }, "mr-1 rounded border p-1"])}">strike</button><button type="button"${ssrIncludeBooleanAttr(!editor.value.can().chain().focus().toggleCode().run()) ? " disabled" : ""} class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("code") }, "mr-1 rounded border p-1"])}">code</button><button class="mr-1 rounded border p-1" type="button">clear marks</button><button class="mr-1 rounded border p-1" type="button">clear nodes</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("paragraph") }, "mr-1 rounded border p-1"])}">paragraph</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("heading", { level: 1 }) }, "mr-1 rounded border p-1"])}">h1</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("heading", { level: 2 }) }, "mr-1 rounded border p-1"])}">h2</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("heading", { level: 3 }) }, "mr-1 rounded border p-1"])}">h3</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("heading", { level: 4 }) }, "mr-1 rounded border p-1"])}">h4</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("heading", { level: 5 }) }, "mr-1 rounded border p-1"])}">h5</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("heading", { level: 6 }) }, "mr-1 rounded border p-1"])}">h6</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("bulletList") }, "mr-1 rounded border p-1"])}">bullet list</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("orderedList") }, "mr-1 rounded border p-1"])}">ordered list</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("codeBlock") }, "mr-1 rounded border p-1"])}">code block</button><button type="button" class="${ssrRenderClass([{ "bg-neutral-600": editor.value.isActive("blockquote") }, "mr-1 rounded border p-1"])}">blockquote</button><button class="mr-1 rounded border p-1" type="button">horizontal rule</button><button class="mr-1 rounded border p-1" type="button">hard break</button><button class="mr-1 rounded border p-1" type="button"${ssrIncludeBooleanAttr(!editor.value.can().chain().focus().undo().run()) ? " disabled" : ""}>undo</button><button class="mr-1 rounded border p-1" type="button"${ssrIncludeBooleanAttr(!editor.value.can().chain().focus().redo().run()) ? " disabled" : ""}>redo</button></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(unref(EditorContent), {
        editor: editor.value,
        onUpdate: ($event) => _ctx.$emit("update:modelValue", $event.target.value),
        class: ["prose prose-invert w-full rounded border border-neutral-500 p-1 text-inherit", { error: __props.error }]
      }, null, _parent));
      if (__props.error) {
        _push(`<div class="form-error">${ssrInterpolate(__props.error)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/TextEditor.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
