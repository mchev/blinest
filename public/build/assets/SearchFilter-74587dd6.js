import { _ as _sfc_main$1 } from "./Dropdown-f0e2d937.js";
import { o as openBlock, c as createElementBlock, b as createBaseVNode, h as createBlock, w as withCtx, t as toDisplayString, x as normalizeStyle, r as renderSlot, g as createCommentVNode, f as normalizeClass } from "./app-910e457d.js";
const _hoisted_1 = { class: "flex items-center" };
const _hoisted_2 = { class: "flex w-full rounded bg-neutral-700 shadow" };
const _hoisted_3 = { class: "flex items-center" };
const _hoisted_4 = { class: "hidden md:inline" };
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("svg", {
  class: "h-2 w-2 md:ml-2",
  fill: "currentColor",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 961.243 599.998"
}, [
  /* @__PURE__ */ createBaseVNode("path", { d: "M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z" })
], -1);
const _hoisted_6 = ["placeholder", "value"];
const _sfc_main = {
  __name: "SearchFilter",
  props: {
    modelValue: String,
    maxWidth: {
      type: Number,
      default: 300
    }
  },
  emits: ["update:modelValue", "reset"],
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        createBaseVNode("div", _hoisted_2, [
          _ctx.$slots.default ? (openBlock(), createBlock(_sfc_main$1, {
            key: 0,
            "auto-close": false,
            class: "rounded-l border-r px-4 hover:bg-neutral-500 focus:z-10 focus:border-white focus:ring md:px-6",
            placement: "left"
          }, {
            default: withCtx(() => [
              createBaseVNode("div", _hoisted_3, [
                createBaseVNode("span", _hoisted_4, toDisplayString(_ctx.__("Filter")), 1),
                _hoisted_5
              ])
            ]),
            dropdown: withCtx(() => [
              createBaseVNode("div", {
                class: "mt-2 w-screen rounded bg-white px-4 py-6 shadow-xl",
                style: normalizeStyle({ maxWidth: `${__props.maxWidth}px` })
              }, [
                renderSlot(_ctx.$slots, "default")
              ], 4)
            ]),
            _: 3
          })) : createCommentVNode("", true),
          createBaseVNode("input", {
            class: normalizeClass(["focus:shadow-outline relative w-full rounded-r px-6 py-3 text-neutral-600", { "rounded-l": !_ctx.$slots.default }]),
            autocomplete: "off",
            type: "text",
            name: "search",
            placeholder: _ctx.__("Search…"),
            value: __props.modelValue,
            onInput: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("update:modelValue", $event.target.value))
          }, null, 42, _hoisted_6)
        ]),
        createBaseVNode("button", {
          class: "ml-3 text-sm",
          type: "button",
          onClick: _cache[1] || (_cache[1] = ($event) => _ctx.$emit("reset"))
        }, toDisplayString(_ctx.__("Reset")), 1)
      ]);
    };
  }
};
export {
  _sfc_main as _
};
