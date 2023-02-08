import { k as ref, l as watch, o as openBlock, c as createElementBlock, t as toDisplayString, g as createCommentVNode, z as withDirectives, A as vModelSelect, b as createBaseVNode, r as renderSlot, m as mergeProps, f as normalizeClass } from "./app-910e457d.js";
import { v as v4 } from "./v4-e7604ebc.js";
const _hoisted_1 = ["for"];
const _hoisted_2 = ["id"];
const _hoisted_3 = {
  key: 1,
  class: "form-error"
};
const __default__ = {
  inheritAttrs: false
};
const _sfc_main = /* @__PURE__ */ Object.assign(__default__, {
  __name: "SelectInput",
  props: {
    id: {
      type: String,
      default() {
        return `select-input-${v4()}`;
      }
    },
    error: String,
    label: String,
    modelValue: [String, Number, Boolean]
  },
  emits: ["update:modelValue"],
  setup(__props, { emit }) {
    const props = __props;
    const selected = ref(props.modelValue);
    const input = ref(null);
    watch(selected, (selected2) => {
      emit("update:modelValue", selected2);
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        class: normalizeClass(_ctx.$attrs.class)
      }, [
        __props.label ? (openBlock(), createElementBlock("label", {
          key: 0,
          class: "form-label",
          for: __props.id
        }, toDisplayString(__props.label) + ":", 9, _hoisted_1)) : createCommentVNode("", true),
        withDirectives(createBaseVNode("select", mergeProps({
          id: __props.id,
          ref_key: "input",
          ref: input,
          "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => selected.value = $event)
        }, { ..._ctx.$attrs, class: null }, {
          class: ["form-select", { error: __props.error }]
        }), [
          renderSlot(_ctx.$slots, "default")
        ], 16, _hoisted_2), [
          [vModelSelect, selected.value]
        ]),
        __props.error ? (openBlock(), createElementBlock("div", _hoisted_3, toDisplayString(__props.error), 1)) : createCommentVNode("", true)
      ], 2);
    };
  }
});
export {
  _sfc_main as _
};
