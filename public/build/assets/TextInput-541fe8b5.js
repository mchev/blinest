import { _ as _sfc_main$1 } from "./Icon-86c99edc.js";
import { v as v4 } from "./v4-e7604ebc.js";
import { o as openBlock, c as createElementBlock, t as toDisplayString, g as createCommentVNode, b as createBaseVNode, h as createBlock, m as mergeProps, f as normalizeClass } from "./app-910e457d.js";
const _hoisted_1 = ["for"];
const _hoisted_2 = { class: "relative" };
const _hoisted_3 = ["id", "type", "value"];
const _hoisted_4 = {
  key: 2,
  class: "pointer-events-none absolute top-1/2 right-3 z-10 -mt-2 ml-2 h-5 w-5 flex-shrink-0 animate-spin text-gray-400",
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24"
};
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("circle", {
  class: "opacity-25",
  cx: "12",
  cy: "12",
  r: "10",
  stroke: "currentColor",
  "stroke-width": "4"
}, null, -1);
const _hoisted_6 = /* @__PURE__ */ createBaseVNode("path", {
  class: "opacity-75",
  fill: "currentColor",
  d: "M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
}, null, -1);
const _hoisted_7 = [
  _hoisted_5,
  _hoisted_6
];
const _hoisted_8 = {
  key: 1,
  class: "form-error"
};
const _sfc_main = {
  __name: "TextInput",
  props: {
    id: {
      type: String,
      default() {
        return `text-input-${v4()}`;
      }
    },
    type: {
      type: String,
      default: "text"
    },
    loading: Boolean,
    error: String,
    label: String,
    modelValue: String | Number,
    appendIcon: String,
    prependIcon: String
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
        createBaseVNode("div", _hoisted_2, [
          __props.prependIcon ? (openBlock(), createBlock(_sfc_main$1, {
            key: 0,
            name: __props.prependIcon,
            class: "pointer-events-none absolute top-1/2 left-3 z-10 mr-2 h-5 w-5 flex-shrink-0 -translate-y-1/2 transform fill-gray-500"
          }, null, 8, ["name"])) : createCommentVNode("", true),
          createBaseVNode("input", mergeProps({
            id: __props.id,
            ref: "input"
          }, { ..._ctx.$attrs, class: _ctx.$attrs.inputClass }, {
            class: ["form-input px-2", { error: __props.error, "pl-10": __props.prependIcon, "pr-10": __props.appendIcon }],
            type: __props.type,
            value: __props.modelValue,
            onInput: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("update:modelValue", $event.target.value))
          }), null, 16, _hoisted_3),
          __props.appendIcon ? (openBlock(), createBlock(_sfc_main$1, {
            key: 1,
            name: __props.appendIcon,
            class: "pointer-events-none absolute top-1/2 right-3 z-10 ml-2 h-5 w-5 flex-shrink-0 -translate-y-1/2 transform fill-gray-500"
          }, null, 8, ["name"])) : createCommentVNode("", true),
          __props.loading ? (openBlock(), createElementBlock("svg", _hoisted_4, _hoisted_7)) : createCommentVNode("", true)
        ]),
        __props.error ? (openBlock(), createElementBlock("div", _hoisted_8, toDisplayString(__props.error), 1)) : createCommentVNode("", true)
      ], 2);
    };
  }
};
export {
  _sfc_main as _
};
