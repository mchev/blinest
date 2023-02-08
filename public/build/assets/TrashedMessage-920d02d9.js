import { _ as _sfc_main$1 } from "./Icon-86c99edc.js";
import { o as openBlock, c as createElementBlock, b as createBaseVNode, a as createVNode, r as renderSlot } from "./app-910e457d.js";
const _hoisted_1 = { class: "flex max-w-3xl items-center justify-between rounded bg-yellow-400 p-4" };
const _hoisted_2 = { class: "flex items-center" };
const _hoisted_3 = { class: "text-sm font-medium text-yellow-800" };
const _sfc_main = {
  __name: "TrashedMessage",
  emits: ["restore"],
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        createBaseVNode("div", _hoisted_2, [
          createVNode(_sfc_main$1, {
            name: "trash",
            class: "mr-2 h-4 w-4 flex-shrink-0 fill-yellow-800"
          }),
          createBaseVNode("div", _hoisted_3, [
            renderSlot(_ctx.$slots, "default")
          ])
        ]),
        createBaseVNode("button", {
          class: "text-sm text-yellow-800 hover:underline",
          tabindex: "-1",
          type: "button",
          onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("restore"))
        }, "Restore")
      ]);
    };
  }
};
export {
  _sfc_main as _
};
