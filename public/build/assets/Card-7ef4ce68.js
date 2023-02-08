import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.js";
import { o as openBlock, c as createElementBlock, r as renderSlot, g as createCommentVNode, b as createBaseVNode } from "./app-910e457d.js";
const _sfc_main = {};
const _hoisted_1 = { class: "flex flex-col rounded-md bg-neutral-800 shadow" };
const _hoisted_2 = {
  key: 0,
  class: "min-h-16 mb-2 flex min-h-fit flex-wrap items-center border-b-2 border-neutral-700 p-2 text-neutral-300"
};
const _hoisted_3 = { class: "flex-1 p-2" };
const _hoisted_4 = {
  key: 1,
  class: "flex flex-wrap items-center rounded-b-md border-t border-neutral-700 px-2 py-4"
};
function _sfc_render(_ctx, _cache) {
  return openBlock(), createElementBlock("section", _hoisted_1, [
    _ctx.$slots.header ? (openBlock(), createElementBlock("header", _hoisted_2, [
      renderSlot(_ctx.$slots, "header")
    ])) : createCommentVNode("", true),
    createBaseVNode("main", _hoisted_3, [
      renderSlot(_ctx.$slots, "default")
    ]),
    _ctx.$slots.footer ? (openBlock(), createElementBlock("footer", _hoisted_4, [
      renderSlot(_ctx.$slots, "footer")
    ])) : createCommentVNode("", true)
  ]);
}
const Card = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export {
  Card as C
};
