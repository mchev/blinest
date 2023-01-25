import { useSSRContext, mergeProps, withCtx, createVNode, toDisplayString, openBlock, createBlock, renderSlot } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderStyle, ssrRenderSlot, ssrRenderClass, ssrRenderAttr } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./Dropdown-6785e0d2.mjs";
const _sfc_main = {
  __name: "SearchFilter",
  __ssrInlineRender: true,
  props: {
    modelValue: String,
    maxWidth: {
      type: Number,
      default: 300
    }
  },
  emits: ["update:modelValue", "reset"],
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex items-center" }, _attrs))}><div class="flex w-full bg-neutral-700 rounded shadow">`);
      if (_ctx.$slots.default) {
        _push(ssrRenderComponent(_sfc_main$1, {
          "auto-close": false,
          class: "focus:z-10 px-4 hover:bg-neutral-500 border-r focus:border-white rounded-l focus:ring md:px-6",
          placement: "left"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="flex items-center"${_scopeId}><span class="hidden md:inline"${_scopeId}>${ssrInterpolate(_ctx.__("Filter"))}</span><svg class="w-2 h-2 md:ml-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 961.243 599.998"${_scopeId}><path d="M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z"${_scopeId}></path></svg></div>`);
            } else {
              return [
                createVNode("div", { class: "flex items-center" }, [
                  createVNode("span", { class: "hidden md:inline" }, toDisplayString(_ctx.__("Filter")), 1),
                  (openBlock(), createBlock("svg", {
                    class: "w-2 h-2 md:ml-2",
                    fill: "currentColor",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 961.243 599.998"
                  }, [
                    createVNode("path", { d: "M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z" })
                  ]))
                ])
              ];
            }
          }),
          dropdown: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="mt-2 px-4 py-6 w-screen bg-white rounded shadow-xl" style="${ssrRenderStyle({ maxWidth: `${__props.maxWidth}px` })}"${_scopeId}>`);
              ssrRenderSlot(_ctx.$slots, "default", {}, null, _push2, _parent2, _scopeId);
              _push2(`</div>`);
            } else {
              return [
                createVNode("div", {
                  class: "mt-2 px-4 py-6 w-screen bg-white rounded shadow-xl",
                  style: { maxWidth: `${__props.maxWidth}px` }
                }, [
                  renderSlot(_ctx.$slots, "default")
                ], 4)
              ];
            }
          }),
          _: 3
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<input class="${ssrRenderClass([{ "rounded-l": !_ctx.$slots.default }, "relative px-6 py-3 w-full rounded-r focus:shadow-outline text-neutral-600"])}" autocomplete="off" type="text" name="search"${ssrRenderAttr("placeholder", _ctx.__("Search…"))}${ssrRenderAttr("value", __props.modelValue)}></div><button class="ml-3 text-sm" type="button">${ssrInterpolate(_ctx.__("Reset"))}</button></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/SearchFilter.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
