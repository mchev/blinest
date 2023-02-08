import { P, l as watch, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, t as toDisplayString, v as renderList, d as createTextVNode, g as createCommentVNode, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$3 } from "./Icon-86c99edc.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import { _ as _sfc_main$4 } from "./Pagination-cf5f2783.js";
import { _ as _sfc_main$2 } from "./SearchFilter-74587dd6.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
const _hoisted_1 = /* @__PURE__ */ createBaseVNode("h1", { class: "mb-8 text-3xl font-bold" }, "FAQ", -1);
const _hoisted_2 = { class: "mb-6 flex items-center justify-between" };
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("span", null, "Create", -1);
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("span", { class: "hidden md:inline" }, " FAQ", -1);
const _hoisted_5 = { class: "overflow-x-auto" };
const _hoisted_6 = { class: "w-full whitespace-nowrap" };
const _hoisted_7 = { class: "text-left font-bold" };
const _hoisted_8 = { class: "px-6 pb-4 pt-6" };
const _hoisted_9 = {
  class: "px-6 pb-4 pt-6",
  colspan: "2"
};
const _hoisted_10 = { class: "border-t" };
const _hoisted_11 = { class: "border-t" };
const _hoisted_12 = { class: "w-px border-t" };
const _hoisted_13 = { key: 0 };
const _hoisted_14 = /* @__PURE__ */ createBaseVNode("td", {
  class: "border-t px-6 py-4",
  colspan: "4"
}, "No faqs found.", -1);
const _hoisted_15 = [
  _hoisted_14
];
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
        Je.get("/admin/faqs", pickBy_1(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "FAQ" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            _hoisted_1,
            createBaseVNode("div", _hoisted_2, [
              createVNode(_sfc_main$2, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).search = $event),
                class: "mr-4 w-full max-w-md",
                onReset: reset
              }, null, 8, ["modelValue"]),
              createVNode(unref(ne), {
                class: "btn-primary",
                href: "/admin/faqs/create"
              }, {
                default: withCtx(() => [
                  _hoisted_3,
                  _hoisted_4
                ]),
                _: 1
              })
            ]),
            createVNode(Card, null, {
              default: withCtx(() => [
                createBaseVNode("div", _hoisted_5, [
                  createBaseVNode("table", _hoisted_6, [
                    createBaseVNode("thead", null, [
                      createBaseVNode("tr", _hoisted_7, [
                        createBaseVNode("th", _hoisted_8, toDisplayString(_ctx.__("Question")), 1),
                        createBaseVNode("th", _hoisted_9, toDisplayString(_ctx.__("Updated at")), 1)
                      ])
                    ]),
                    createBaseVNode("tbody", null, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(__props.faqs.data, (faq) => {
                        return openBlock(), createElementBlock("tr", {
                          key: faq.id
                        }, [
                          createBaseVNode("td", _hoisted_10, [
                            createVNode(unref(ne), {
                              class: "flex items-center px-6 py-4",
                              href: `/admin/faqs/${faq.id}/edit`
                            }, {
                              default: withCtx(() => [
                                createTextVNode(toDisplayString(faq.question), 1)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createBaseVNode("td", _hoisted_11, [
                            createVNode(unref(ne), {
                              class: "flex items-center px-6 py-4",
                              href: `/admin/faqs/${faq.id}/edit`,
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createTextVNode(toDisplayString(faq.updated_at), 1)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createBaseVNode("td", _hoisted_12, [
                            createVNode(unref(ne), {
                              class: "flex items-center px-4",
                              href: `/admin/faqs/${faq.id}/edit`,
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createVNode(_sfc_main$3, {
                                  name: "cheveron-right",
                                  class: "block h-6 w-6"
                                })
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ])
                        ]);
                      }), 128)),
                      __props.faqs.data.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_13, _hoisted_15)) : createCommentVNode("", true)
                    ])
                  ])
                ]),
                createVNode(_sfc_main$4, {
                  links: __props.faqs.links
                }, null, 8, ["links"])
              ]),
              _: 1
            })
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
