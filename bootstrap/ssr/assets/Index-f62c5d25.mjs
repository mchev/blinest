import { watch, unref, withCtx, createVNode, createTextVNode, toDisplayString, openBlock, createBlock, Fragment, renderList, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate } from "vue/server-renderer";
import { useForm, router, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-c9eae547.mjs";
import { _ as _sfc_main$3 } from "./Icon-4a47e6e0.mjs";
import pickBy from "lodash/pickBy.js";
import throttle from "lodash/throttle.js";
import { _ as _sfc_main$4 } from "./Pagination-6aa0e1ca.mjs";
import { _ as _sfc_main$2 } from "./SearchFilter-c44fa86b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
const _sfc_main = {
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    filters: Object,
    answer_types: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      search: props.filters.search
    });
    watch(
      form,
      throttle(() => {
        router.get("/admin/answer_types", pickBy(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Answer Types" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>Answer Types</h1><div class="mb-6 flex items-center justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).search,
              "onUpdate:modelValue": ($event) => unref(form).search = $event,
              class: "mr-4 w-full max-w-md",
              onReset: reset
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              class: "btn-primary",
              href: "/admin/answer_types/create"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span${_scopeId2}>Create</span><span class="hidden md:inline"${_scopeId2}> Answer Type</span>`);
                } else {
                  return [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Answer Type")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(Card, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="overflow-x-auto"${_scopeId2}><table class="w-full whitespace-nowrap"${_scopeId2}><thead${_scopeId2}><tr class="text-left font-bold"${_scopeId2}><th class="px-6 pb-4 pt-6"${_scopeId2}>Name</th><th class="px-6 pb-4 pt-6"${_scopeId2}>Pronoun</th><th class="px-6 pb-4 pt-6" colspan="2"${_scopeId2}>Icon</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                  ssrRenderList(__props.answer_types.data, (answer_type) => {
                    _push3(`<tr class="focus-within:bg-gray-100 hover:bg-gray-100"${_scopeId2}><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4 focus:text-indigo-500",
                      href: `/admin/answer_types/${answer_type.id}/edit`
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(answer_type.name)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(answer_type.name), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: `/admin/answer_types/${answer_type.id}/edit`,
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(answer_type.pronoun)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(answer_type.pronoun), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: `/admin/answer_types/${answer_type.id}/edit`,
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`<span${_scopeId3}>${answer_type.svg_icon}</span>`);
                        } else {
                          return [
                            createVNode("span", {
                              innerHTML: answer_type.svg_icon
                            }, null, 8, ["innerHTML"])
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="w-px border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-4",
                      href: `/admin/answer_types/${answer_type.id}/edit`,
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(ssrRenderComponent(_sfc_main$3, {
                            name: "cheveron-right",
                            class: "block h-6 w-6"
                          }, null, _parent4, _scopeId3));
                        } else {
                          return [
                            createVNode(_sfc_main$3, {
                              name: "cheveron-right",
                              class: "block h-6 w-6"
                            })
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td></tr>`);
                  });
                  _push3(`<!--]-->`);
                  if (__props.answer_types.data.length === 0) {
                    _push3(`<tr${_scopeId2}><td class="border-t px-6 py-4" colspan="4"${_scopeId2}>No answer_types found.</td></tr>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`</tbody></table></div>`);
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    links: __props.answer_types.links
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("div", { class: "overflow-x-auto" }, [
                      createVNode("table", { class: "w-full whitespace-nowrap" }, [
                        createVNode("thead", null, [
                          createVNode("tr", { class: "text-left font-bold" }, [
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, "Name"),
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, "Pronoun"),
                            createVNode("th", {
                              class: "px-6 pb-4 pt-6",
                              colspan: "2"
                            }, "Icon")
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.answer_types.data, (answer_type) => {
                            return openBlock(), createBlock("tr", {
                              key: answer_type.id,
                              class: "focus-within:bg-gray-100 hover:bg-gray-100"
                            }, [
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4 focus:text-indigo-500",
                                  href: `/admin/answer_types/${answer_type.id}/edit`
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(answer_type.name), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: `/admin/answer_types/${answer_type.id}/edit`,
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(answer_type.pronoun), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: `/admin/answer_types/${answer_type.id}/edit`,
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createVNode("span", {
                                      innerHTML: answer_type.svg_icon
                                    }, null, 8, ["innerHTML"])
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "w-px border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-4",
                                  href: `/admin/answer_types/${answer_type.id}/edit`,
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
                          __props.answer_types.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                            createVNode("td", {
                              class: "border-t px-6 py-4",
                              colspan: "4"
                            }, "No answer_types found.")
                          ])) : createCommentVNode("", true)
                        ])
                      ])
                    ]),
                    createVNode(_sfc_main$4, {
                      links: __props.answer_types.links
                    }, null, 8, ["links"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode("h1", { class: "mb-8 text-3xl font-bold" }, "Answer Types"),
              createVNode("div", { class: "mb-6 flex items-center justify-between" }, [
                createVNode(_sfc_main$2, {
                  modelValue: unref(form).search,
                  "onUpdate:modelValue": ($event) => unref(form).search = $event,
                  class: "mr-4 w-full max-w-md",
                  onReset: reset
                }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode(unref(Link), {
                  class: "btn-primary",
                  href: "/admin/answer_types/create"
                }, {
                  default: withCtx(() => [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Answer Type")
                  ]),
                  _: 1
                })
              ]),
              createVNode(Card, null, {
                default: withCtx(() => [
                  createVNode("div", { class: "overflow-x-auto" }, [
                    createVNode("table", { class: "w-full whitespace-nowrap" }, [
                      createVNode("thead", null, [
                        createVNode("tr", { class: "text-left font-bold" }, [
                          createVNode("th", { class: "px-6 pb-4 pt-6" }, "Name"),
                          createVNode("th", { class: "px-6 pb-4 pt-6" }, "Pronoun"),
                          createVNode("th", {
                            class: "px-6 pb-4 pt-6",
                            colspan: "2"
                          }, "Icon")
                        ])
                      ]),
                      createVNode("tbody", null, [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.answer_types.data, (answer_type) => {
                          return openBlock(), createBlock("tr", {
                            key: answer_type.id,
                            class: "focus-within:bg-gray-100 hover:bg-gray-100"
                          }, [
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-6 py-4 focus:text-indigo-500",
                                href: `/admin/answer_types/${answer_type.id}/edit`
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(answer_type.name), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-6 py-4",
                                href: `/admin/answer_types/${answer_type.id}/edit`,
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(answer_type.pronoun), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-6 py-4",
                                href: `/admin/answer_types/${answer_type.id}/edit`,
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  createVNode("span", {
                                    innerHTML: answer_type.svg_icon
                                  }, null, 8, ["innerHTML"])
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "w-px border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-4",
                                href: `/admin/answer_types/${answer_type.id}/edit`,
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
                        __props.answer_types.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                          createVNode("td", {
                            class: "border-t px-6 py-4",
                            colspan: "4"
                          }, "No answer_types found.")
                        ])) : createCommentVNode("", true)
                      ])
                    ])
                  ]),
                  createVNode(_sfc_main$4, {
                    links: __props.answer_types.links
                  }, null, 8, ["links"])
                ]),
                _: 1
              })
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/AnswerTypes/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
