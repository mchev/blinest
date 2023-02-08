import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.js";
import { o as openBlock, c as createElementBlock, b as createBaseVNode, r as renderSlot } from "./app-910e457d.js";
const _sfc_main = {};
const _hoisted_1 = { class: "mb-4 flex items-center rounded bg-neutral-700 p-2 text-sm text-neutral-400" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { class: "mr-2 w-10" }, [
  /* @__PURE__ */ createBaseVNode("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    class: "mr-2 h-10 w-10",
    fill: "none",
    viewBox: "0 0 24 24",
    stroke: "currentColor",
    "stroke-width": "2"
  }, [
    /* @__PURE__ */ createBaseVNode("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      d: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
    })
  ])
], -1);
const _hoisted_3 = { class: "flex-grow" };
function _sfc_render(_ctx, _cache) {
  return openBlock(), createElementBlock("div", _hoisted_1, [
    _hoisted_2,
    createBaseVNode("p", _hoisted_3, [
      renderSlot(_ctx.$slots, "default")
    ])
  ]);
}
const Tip = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export {
  Tip as T
};
