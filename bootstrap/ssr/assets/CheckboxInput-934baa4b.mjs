import { v4 } from "uuid";
import { mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.mjs";
const CheckboxInput_vue_vue_type_style_index_0_scoped_599c107c_lang = "";
const _sfc_main = {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `checkbox-input-${v4()}`;
      }
    },
    error: String,
    label: String,
    modelValue: [Boolean, Number, String]
  },
  emits: ["update:modelValue"],
  methods: {
    focus() {
      this.$refs.input.focus();
    },
    select() {
      this.$refs.input.select();
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end);
    }
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<div${ssrRenderAttrs(mergeProps({
    class: _ctx.$attrs.class
  }, _attrs))} data-v-599c107c>`);
  if ($props.label) {
    _push(`<label class="flex cursor-pointer items-center"${ssrRenderAttr("for", $props.id)} data-v-599c107c><div class="relative" data-v-599c107c><input${ssrRenderAttrs(mergeProps({ id: $props.id }, { ..._ctx.$attrs, class: null }, {
      value: $props.modelValue,
      checked: $props.modelValue,
      type: "checkbox",
      class: "sr-only"
    }))} data-v-599c107c><div class="h-4 w-10 rounded-full bg-neutral-500 shadow-inner" data-v-599c107c></div><div class="dot absolute -left-1 -top-1 h-6 w-6 rounded-full bg-neutral-200 shadow transition" data-v-599c107c></div></div>`);
    if ($props.label) {
      _push(`<div class="ml-3 font-medium" data-v-599c107c>${ssrInterpolate($props.label)}</div>`);
    } else {
      _push(`<!---->`);
    }
    _push(`</label>`);
  } else {
    _push(`<!---->`);
  }
  if ($props.error) {
    _push(`<div class="form-error" data-v-599c107c>${ssrInterpolate($props.error)}</div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div>`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/CheckboxInput.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const CheckboxInput = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender], ["__scopeId", "data-v-599c107c"]]);
export {
  CheckboxInput as C
};
