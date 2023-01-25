import { ssrRenderAttrs, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import "@inertiajs/vue3";
import { useSSRContext } from "vue";
const _sfc_main = {
  __name: "Pagination",
  __ssrInlineRender: true,
  props: {
    links: Array
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.links.length > 3) {
        _push(`<div${ssrRenderAttrs(_attrs)}><div class="flex flex-wrap justify-end m-6"><!--[-->`);
        ssrRenderList(__props.links, (link, key) => {
          _push(`<!--[-->`);
          if (link.url === null) {
            _push(`<div class="mb-1 mr-1 bg-neutral-700 rounded px-4 py-3 text-sm leading-4 text-neutral-300">${link.label}</div>`);
          } else {
            _push(`<button class="${ssrRenderClass([link.active ? "bg-neutral-500" : "bg-neutral-700", "mb-1 mr-1 rounded px-4 py-3 text-sm leading-4 hover:bg-neutral-500"])}">${link.label}</button>`);
          }
          _push(`<!--]-->`);
        });
        _push(`<!--]--></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Pagination.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
