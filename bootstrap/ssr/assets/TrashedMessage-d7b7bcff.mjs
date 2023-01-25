import { useSSRContext, mergeProps } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderSlot } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./Icon-4a47e6e0.mjs";
const _sfc_main = {
  __name: "TrashedMessage",
  __ssrInlineRender: true,
  emits: ["restore"],
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex items-center justify-between p-4 max-w-3xl bg-yellow-400 rounded" }, _attrs))}><div class="flex items-center">`);
      _push(ssrRenderComponent(_sfc_main$1, {
        name: "trash",
        class: "flex-shrink-0 mr-2 w-4 h-4 fill-yellow-800"
      }, null, _parent));
      _push(`<div class="text-yellow-800 text-sm font-medium">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</div></div><button class="text-yellow-800 hover:underline text-sm" tabindex="-1" type="button">Restore</button></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/TrashedMessage.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
