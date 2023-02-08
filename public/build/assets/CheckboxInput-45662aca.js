import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.js";
import { v as v4 } from "./v4-e7604ebc.js";
import { o as openBlock, c as createElementBlock, b as createBaseVNode, m as mergeProps, t as toDisplayString, g as createCommentVNode, f as normalizeClass, S as pushScopeId, U as popScopeId } from "./app-910e457d.js";
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
const _withScopeId = (n) => (pushScopeId("data-v-599c107c"), n = n(), popScopeId(), n);
const _hoisted_1 = ["for"];
const _hoisted_2 = { class: "relative" };
const _hoisted_3 = ["id", "value", "checked"];
const _hoisted_4 = /* @__PURE__ */ _withScopeId(() => /* @__PURE__ */ createBaseVNode("div", { class: "h-4 w-10 rounded-full bg-neutral-500 shadow-inner" }, null, -1));
const _hoisted_5 = /* @__PURE__ */ _withScopeId(() => /* @__PURE__ */ createBaseVNode("div", { class: "dot absolute -left-1 -top-1 h-6 w-6 rounded-full bg-neutral-200 shadow transition" }, null, -1));
const _hoisted_6 = {
  key: 0,
  class: "ml-3 font-medium"
};
const _hoisted_7 = {
  key: 1,
  class: "form-error"
};
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(_ctx.$attrs.class)
  }, [
    $props.label ? (openBlock(), createElementBlock("label", {
      key: 0,
      class: "flex cursor-pointer items-center",
      for: $props.id
    }, [
      createBaseVNode("div", _hoisted_2, [
        createBaseVNode("input", mergeProps({ id: $props.id }, { ..._ctx.$attrs, class: null }, {
          value: $props.modelValue,
          checked: $props.modelValue,
          type: "checkbox",
          class: "sr-only",
          onInput: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("update:modelValue", $event.target.checked))
        }), null, 16, _hoisted_3),
        _hoisted_4,
        _hoisted_5
      ]),
      $props.label ? (openBlock(), createElementBlock("div", _hoisted_6, toDisplayString($props.label), 1)) : createCommentVNode("", true)
    ], 8, _hoisted_1)) : createCommentVNode("", true),
    $props.error ? (openBlock(), createElementBlock("div", _hoisted_7, toDisplayString($props.error), 1)) : createCommentVNode("", true)
  ], 2);
}
const CheckboxInput = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render], ["__scopeId", "data-v-599c107c"]]);
export {
  CheckboxInput as C
};
