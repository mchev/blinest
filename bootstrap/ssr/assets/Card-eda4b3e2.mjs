import { mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderSlot } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs) {
  _push(`<section${ssrRenderAttrs(mergeProps({ class: "flex flex-col rounded-md bg-neutral-800 shadow" }, _attrs))}>`);
  if (_ctx.$slots.header) {
    _push(`<header class="mb-2 flex min-h-16 min-h-fit flex-wrap items-center border-b-2 border-neutral-700 p-2 text-neutral-300">`);
    ssrRenderSlot(_ctx.$slots, "header", {}, null, _push, _parent);
    _push(`</header>`);
  } else {
    _push(`<!---->`);
  }
  _push(`<main class="flex-1 p-2">`);
  ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
  _push(`</main>`);
  if (_ctx.$slots.footer) {
    _push(`<footer class="flex flex-wrap items-center rounded-b-md border-t border-neutral-700 px-2 py-4">`);
    ssrRenderSlot(_ctx.$slots, "footer", {}, null, _push, _parent);
    _push(`</footer>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</section>`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Card.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const Card = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
export {
  Card as C
};
