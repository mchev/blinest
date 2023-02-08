import { watch, unref, withCtx, createVNode, toDisplayString, openBlock, createBlock, TransitionGroup, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttrs, ssrRenderList } from "vue/server-renderer";
import { useForm, router, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import { _ as _sfc_main$3 } from "./Pagination-6aa0e1ca.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import _sfc_main$4 from "./FAQ-08cfec8a.mjs";
import pickBy from "lodash/pickBy.js";
import throttle from "lodash/throttle.js";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
import "./Card-ee13c838.mjs";
const _sfc_main = {
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    filters: Object,
    faqs: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      search: props.filters.search
    });
    watch(
      form,
      throttle(() => {
        router.get("/faq", pickBy(form), {
          remember: "forget",
          preserveState: true
        });
      }, 150),
      { deep: true }
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "FAQ" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="w-full flex flex-col items-center justify-center"${_scopeId}><h1 class="mb-8 text-3xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("FAQ"))}</h1>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).search,
              "onUpdate:modelValue": ($event) => unref(form).search = $event,
              class: "mr-4 w-full max-w-md",
              placeholder: "Une question ?"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$3, {
              links: __props.faqs.links,
              class: "mt-4 mb-0 mx-0 justify-center"
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
            if (__props.faqs.data.length) {
              _push2(`<ul${ssrRenderAttrs({
                name: "faq",
                class: "flex flex-col gap-4 max-w-6xl mx-auto my-8"
              })}>`);
              ssrRenderList(__props.faqs.data, (faq) => {
                _push2(ssrRenderComponent(_sfc_main$4, {
                  faq,
                  key: faq.id
                }, null, _parent2, _scopeId));
              });
              _push2(`</ul>`);
            } else {
              _push2(`<div class="max-w-6xl mx-auto text-center my-8"${_scopeId}> Aucun resultat </div>`);
            }
            _push2(`<div class="max-w-6xl mx-auto text-center my-4"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$3, {
              links: __props.faqs.links,
              class: "my-0 mx-0 justify-center"
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "w-full flex flex-col items-center justify-center" }, [
                createVNode("h1", { class: "mb-8 text-3xl font-bold" }, toDisplayString(_ctx.__("FAQ")), 1),
                createVNode(_sfc_main$2, {
                  modelValue: unref(form).search,
                  "onUpdate:modelValue": ($event) => unref(form).search = $event,
                  class: "mr-4 w-full max-w-md",
                  placeholder: "Une question ?"
                }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode(_sfc_main$3, {
                  links: __props.faqs.links,
                  class: "mt-4 mb-0 mx-0 justify-center"
                }, null, 8, ["links"])
              ]),
              __props.faqs.data.length ? (openBlock(), createBlock(TransitionGroup, {
                key: 0,
                name: "faq",
                tag: "ul",
                class: "flex flex-col gap-4 max-w-6xl mx-auto my-8"
              }, {
                default: withCtx(() => [
                  (openBlock(true), createBlock(Fragment, null, renderList(__props.faqs.data, (faq) => {
                    return openBlock(), createBlock(_sfc_main$4, {
                      faq,
                      key: faq.id
                    }, null, 8, ["faq"]);
                  }), 128))
                ]),
                _: 1
              })) : (openBlock(), createBlock("div", {
                key: 1,
                class: "max-w-6xl mx-auto text-center my-8"
              }, " Aucun resultat ")),
              createVNode("div", { class: "max-w-6xl mx-auto text-center my-4" }, [
                createVNode(_sfc_main$3, {
                  links: __props.faqs.links,
                  class: "my-0 mx-0 justify-center"
                }, null, 8, ["links"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/FAQ/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
