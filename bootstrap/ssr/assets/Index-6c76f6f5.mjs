import { watch, unref, withCtx, createVNode, createTextVNode, toDisplayString, openBlock, createBlock, createCommentVNode, Fragment, renderList, useSSRContext } from "vue";
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
    categories: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      search: props.filters.search
    });
    watch(
      form,
      throttle(() => {
        router.get("/admin/categories", pickBy(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Categories" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>Categories</h1><div class="mb-6 flex items-center justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).search,
              "onUpdate:modelValue": ($event) => unref(form).search = $event,
              class: "mr-4 w-full max-w-md",
              onReset: reset
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              class: "btn-primary",
              href: "/admin/categories/create"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span${_scopeId2}>Create</span><span class="hidden md:inline"${_scopeId2}> Category</span>`);
                } else {
                  return [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Category")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(Card, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="overflow-x-auto"${_scopeId2}><table class="w-full whitespace-nowrap"${_scopeId2}><thead${_scopeId2}><tr class="text-left font-bold"${_scopeId2}><th class="px-6 pb-4 pt-6"${_scopeId2}>Name</th><th class="px-6 pb-4 pt-6"${_scopeId2}>Public Rooms</th><th class="px-6 pb-4 pt-6" colspan="2"${_scopeId2}>Private Rooms</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                  ssrRenderList(__props.categories.data, (category) => {
                    _push3(`<tr class="hover:bg-gray-100"${_scopeId2}><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: `/admin/categories/${category.id}/edit`
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(category.name)} `);
                          if (category.deleted_at) {
                            _push4(ssrRenderComponent(_sfc_main$3, {
                              name: "trash",
                              class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                            }, null, _parent4, _scopeId3));
                          } else {
                            _push4(`<!---->`);
                          }
                        } else {
                          return [
                            createTextVNode(toDisplayString(category.name) + " ", 1),
                            category.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                              key: 0,
                              name: "trash",
                              class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                            })) : createCommentVNode("", true)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: `/admin/categories/${category.id}/edit`,
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(category.public_rooms_count)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(category.public_rooms_count), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: `/admin/categories/${category.id}/edit`,
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(category.private_rooms_count)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(category.private_rooms_count), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="w-px border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-4",
                      href: `/admin/categories/${category.id}/edit`,
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
                  if (__props.categories.data.length === 0) {
                    _push3(`<tr${_scopeId2}><td class="border-t px-6 py-4" colspan="4"${_scopeId2}>No categories found.</td></tr>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`</tbody></table></div>`);
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    links: __props.categories.links
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("div", { class: "overflow-x-auto" }, [
                      createVNode("table", { class: "w-full whitespace-nowrap" }, [
                        createVNode("thead", null, [
                          createVNode("tr", { class: "text-left font-bold" }, [
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, "Name"),
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, "Public Rooms"),
                            createVNode("th", {
                              class: "px-6 pb-4 pt-6",
                              colspan: "2"
                            }, "Private Rooms")
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.categories.data, (category) => {
                            return openBlock(), createBlock("tr", {
                              key: category.id,
                              class: "hover:bg-gray-100"
                            }, [
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: `/admin/categories/${category.id}/edit`
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(category.name) + " ", 1),
                                    category.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                                      key: 0,
                                      name: "trash",
                                      class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                                    })) : createCommentVNode("", true)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: `/admin/categories/${category.id}/edit`,
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(category.public_rooms_count), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: `/admin/categories/${category.id}/edit`,
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(category.private_rooms_count), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "w-px border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-4",
                                  href: `/admin/categories/${category.id}/edit`,
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
                          __props.categories.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                            createVNode("td", {
                              class: "border-t px-6 py-4",
                              colspan: "4"
                            }, "No categories found.")
                          ])) : createCommentVNode("", true)
                        ])
                      ])
                    ]),
                    createVNode(_sfc_main$4, {
                      links: __props.categories.links
                    }, null, 8, ["links"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode("h1", { class: "mb-8 text-3xl font-bold" }, "Categories"),
              createVNode("div", { class: "mb-6 flex items-center justify-between" }, [
                createVNode(_sfc_main$2, {
                  modelValue: unref(form).search,
                  "onUpdate:modelValue": ($event) => unref(form).search = $event,
                  class: "mr-4 w-full max-w-md",
                  onReset: reset
                }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode(unref(Link), {
                  class: "btn-primary",
                  href: "/admin/categories/create"
                }, {
                  default: withCtx(() => [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Category")
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
                          createVNode("th", { class: "px-6 pb-4 pt-6" }, "Public Rooms"),
                          createVNode("th", {
                            class: "px-6 pb-4 pt-6",
                            colspan: "2"
                          }, "Private Rooms")
                        ])
                      ]),
                      createVNode("tbody", null, [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.categories.data, (category) => {
                          return openBlock(), createBlock("tr", {
                            key: category.id,
                            class: "hover:bg-gray-100"
                          }, [
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-6 py-4",
                                href: `/admin/categories/${category.id}/edit`
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(category.name) + " ", 1),
                                  category.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                                    key: 0,
                                    name: "trash",
                                    class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                                  })) : createCommentVNode("", true)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-6 py-4",
                                href: `/admin/categories/${category.id}/edit`,
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(category.public_rooms_count), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-6 py-4",
                                href: `/admin/categories/${category.id}/edit`,
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(category.private_rooms_count), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "w-px border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-4",
                                href: `/admin/categories/${category.id}/edit`,
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
                        __props.categories.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                          createVNode("td", {
                            class: "border-t px-6 py-4",
                            colspan: "4"
                          }, "No categories found.")
                        ])) : createCommentVNode("", true)
                      ])
                    ])
                  ]),
                  createVNode(_sfc_main$4, {
                    links: __props.categories.links
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Categories/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
