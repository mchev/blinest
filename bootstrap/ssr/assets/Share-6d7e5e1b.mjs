import { ref, mergeProps, useSSRContext, withCtx, createVNode, toDisplayString } from "vue";
import { ssrRenderAttrs, ssrRenderClass, ssrInterpolate, ssrRenderAttr, ssrRenderComponent } from "vue/server-renderer";
import { _ as _sfc_main$2 } from "./Modal-61e7836d.mjs";
import "./Card-ee13c838.mjs";
const _sfc_main$1 = {
  __name: "Clip",
  __ssrInlineRender: true,
  props: {
    text: String
  },
  setup(__props) {
    const message = ref({});
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<span${ssrRenderAttrs(mergeProps({ class: "relative inline-block" }, _attrs))}>`);
      if (message.value.content) {
        _push(`<div class="${ssrRenderClass([message.value.status ? "bg-green-500" : "bg-red-500", "absolute right-0 bottom-full mb-1 rounded-lg p-1 text-xs text-white shadow"])}">${ssrInterpolate(message.value.content)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex"><div type="text" id="website-admin" class="min-w-0 rounded-none rounded-l-lg border border-gray-300 border-gray-300 bg-gray-50 px-1.5 py-1 font-mono text-xs text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">${ssrInterpolate(__props.text)}</div><span class="inline-flex cursor-pointer items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-200 px-2 text-sm text-gray-900 opacity-80 hover:opacity-100 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path></svg></span></div></span>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Clip.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Share",
  __ssrInlineRender: true,
  props: {
    url: String
  },
  setup(__props) {
    const show = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><div id="fb-root"></div><button class="${ssrRenderClass(_ctx.$attrs.class)}"${ssrRenderAttr("title", _ctx.__("Share"))}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"></path></svg></button>`);
      _push(ssrRenderComponent(_sfc_main$2, {
        show: show.value,
        onClose: ($event) => show.value = false
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="p-6"${_scopeId}><h2 class="my-4 text-xl"${_scopeId}>${ssrInterpolate(_ctx.__("Share this page"))}</h2><p class="mb-1 text-sm"${_scopeId}>Copier le lien :</p>`);
            _push2(ssrRenderComponent(_sfc_main$1, {
              class: "mb-4",
              text: __props.url
            }, null, _parent2, _scopeId));
            _push2(`<a class="btn-primary my-2" target="_blank"${ssrRenderAttr("href", `https://www.facebook.com/sharer/sharer.php?u=${__props.url}`)}${_scopeId}>${ssrInterpolate(_ctx.__("Share on Facebook"))}</a><a class="btn-primary my-2" target="_blank"${ssrRenderAttr("href", `https://twitter.com/intent/tweet?&url=${__props.url}`)}${_scopeId}>${ssrInterpolate(_ctx.__("Share on Twitter"))}</a><button class="btn-secondary mt-4 ml-auto"${_scopeId}>${ssrInterpolate(_ctx.__("Close"))}</button></div>`);
          } else {
            return [
              createVNode("div", { class: "p-6" }, [
                createVNode("h2", { class: "my-4 text-xl" }, toDisplayString(_ctx.__("Share this page")), 1),
                createVNode("p", { class: "mb-1 text-sm" }, "Copier le lien :"),
                createVNode(_sfc_main$1, {
                  class: "mb-4",
                  text: __props.url
                }, null, 8, ["text"]),
                createVNode("a", {
                  class: "btn-primary my-2",
                  target: "_blank",
                  href: `https://www.facebook.com/sharer/sharer.php?u=${__props.url}`
                }, toDisplayString(_ctx.__("Share on Facebook")), 9, ["href"]),
                createVNode("a", {
                  class: "btn-primary my-2",
                  target: "_blank",
                  href: `https://twitter.com/intent/tweet?&url=${__props.url}`
                }, toDisplayString(_ctx.__("Share on Twitter")), 9, ["href"]),
                createVNode("button", {
                  class: "btn-secondary mt-4 ml-auto",
                  onClick: ($event) => show.value = false
                }, toDisplayString(_ctx.__("Close")), 9, ["onClick"])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Share.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
