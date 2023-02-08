import { P, l as watch, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, h as createBlock, v as renderList, W as TransitionGroup, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$3 } from "./Pagination-cf5f2783.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import _sfc_main$4 from "./FAQ-e3768652.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./SocialIcon-5ed77127.js";
import "./v4-e7604ebc.js";
import "./Card-7ef4ce68.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
const _hoisted_1 = { class: "w-full flex flex-col items-center justify-center" };
const _hoisted_2 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_3 = {
  key: 1,
  class: "max-w-6xl mx-auto text-center my-8"
};
const _hoisted_4 = { class: "max-w-6xl mx-auto text-center my-4" };
const _sfc_main = {
  __name: "Index",
  props: {
    filters: Object,
    faqs: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
      search: props.filters.search
    });
    watch(
      form,
      throttle(() => {
        Je.get("/faq", pickBy_1(form), {
          remember: "forget",
          preserveState: true
        });
      }, 150),
      { deep: true }
    );
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "FAQ" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              createBaseVNode("h1", _hoisted_2, toDisplayString(_ctx.__("FAQ")), 1),
              createVNode(_sfc_main$2, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).search = $event),
                class: "mr-4 w-full max-w-md",
                placeholder: "Une question ?"
              }, null, 8, ["modelValue"]),
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
                (openBlock(true), createElementBlock(Fragment, null, renderList(__props.faqs.data, (faq) => {
                  return openBlock(), createBlock(_sfc_main$4, {
                    faq,
                    key: faq.id
                  }, null, 8, ["faq"]);
                }), 128))
              ]),
              _: 1
            })) : (openBlock(), createElementBlock("div", _hoisted_3, " Aucun resultat ")),
            createBaseVNode("div", _hoisted_4, [
              createVNode(_sfc_main$3, {
                links: __props.faqs.links,
                class: "my-0 mx-0 justify-center"
              }, null, 8, ["links"])
            ])
          ]),
          _: 1
        })
      ], 64);
    };
  }
};
export {
  _sfc_main as default
};
