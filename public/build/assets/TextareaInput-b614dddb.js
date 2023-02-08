import { v as v4 } from "./v4-e7604ebc.js";
import { o as openBlock, c as createElementBlock, t as toDisplayString, g as createCommentVNode, b as createBaseVNode, m as mergeProps, f as normalizeClass } from "./app-910e457d.js";
const _hoisted_1 = ["for"];
const _hoisted_2 = ["id", "value"];
const _hoisted_3 = {
  key: 1,
  class: "form-error"
};
const __default__ = {
  inheritAttrs: false
};
const _sfc_main = /* @__PURE__ */ Object.assign(__default__, {
  __name: "TextareaInput",
  props: {
    id: {
      type: String,
      default() {
        return `textarea-input-${v4()}`;
      }
    },
    error: String,
    label: String,
    modelValue: String
  },
  emits: ["update:modelValue"],
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        class: normalizeClass(_ctx.$attrs.class)
      }, [
        __props.label ? (openBlock(), createElementBlock("label", {
          key: 0,
          class: "form-label",
          for: __props.id
        }, toDisplayString(__props.label) + ":", 9, _hoisted_1)) : createCommentVNode("", true),
        createBaseVNode("textarea", mergeProps({
          id: __props.id,
          ref: "input"
        }, { ..._ctx.$attrs, class: null }, {
          class: ["form-textarea", { error: __props.error }],
          value: __props.modelValue,
          onInput: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("update:modelValue", $event.target.value))
        }), null, 16, _hoisted_2),
        __props.error ? (openBlock(), createElementBlock("div", _hoisted_3, toDisplayString(__props.error), 1)) : createCommentVNode("", true)
      ], 2);
    };
  }
});
export {
  _sfc_main as _
};
