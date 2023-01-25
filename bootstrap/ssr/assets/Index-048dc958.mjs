import { watch, unref, withCtx, withDirectives, createVNode, vModelSelect, createTextVNode, toDisplayString, openBlock, createBlock, createCommentVNode, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderList, ssrInterpolate } from "vue/server-renderer";
import { useForm, router, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-a34dfeea.mjs";
import { _ as _sfc_main$3 } from "./Icon-4a47e6e0.mjs";
import pickBy from "lodash/pickBy.js";
import throttle from "lodash/throttle.js";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { _ as _sfc_main$4 } from "./Pagination-d517bd16.mjs";
import { _ as _sfc_main$2 } from "./SearchFilter-26a0a59f.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
const _sfc_main = {
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    filters: Object,
    teams: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      search: props.filters.search,
      trashed: props.filters.trashed
    });
    watch(
      form,
      throttle(() => {
        router.get("/admin/teams", pickBy(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Teams" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>Teams</h1><div class="mb-6 flex items-center justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).search,
              "onUpdate:modelValue": ($event) => unref(form).search = $event,
              class: "mr-4 w-full max-w-md",
              onReset: reset
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<select class="form-select mt-1 w-full"${_scopeId2}><option${ssrRenderAttr("value", null)}${_scopeId2}></option><option value="with"${_scopeId2}>With Trashed</option><option value="only"${_scopeId2}>Only Trashed</option></select>`);
                } else {
                  return [
                    withDirectives(createVNode("select", {
                      "onUpdate:modelValue": ($event) => unref(form).trashed = $event,
                      class: "form-select mt-1 w-full"
                    }, [
                      createVNode("option", { value: null }),
                      createVNode("option", { value: "with" }, "With Trashed"),
                      createVNode("option", { value: "only" }, "Only Trashed")
                    ], 8, ["onUpdate:modelValue"]), [
                      [vModelSelect, unref(form).trashed]
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              class: "btn-primary",
              href: "/admin/teams/create"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span${_scopeId2}>Create</span><span class="hidden md:inline"${_scopeId2}> Team</span>`);
                } else {
                  return [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Team")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(Card, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="overflow-x-auto"${_scopeId2}><table class="w-full whitespace-nowrap"${_scopeId2}><thead${_scopeId2}><tr class="text-left font-bold"${_scopeId2}><th class="px-6 pb-4 pt-6"${_scopeId2}>Name</th><th class="px-6 pb-4 pt-6" colspan="2"${_scopeId2}>Members</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                  ssrRenderList(__props.teams.data, (team) => {
                    _push3(`<tr class="hover:bg-neutral-200"${_scopeId2}><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4 focus:text-indigo-500",
                      href: _ctx.route("admin.teams.edit", team)
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(team.name)} `);
                          if (team.deleted_at) {
                            _push4(ssrRenderComponent(_sfc_main$3, {
                              name: "trash",
                              class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                            }, null, _parent4, _scopeId3));
                          } else {
                            _push4(`<!---->`);
                          }
                        } else {
                          return [
                            createTextVNode(toDisplayString(team.name) + " ", 1),
                            team.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
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
                      href: _ctx.route("admin.teams.edit", team),
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(team.members_count)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(team.members_count), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="w-px border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-4",
                      href: _ctx.route("admin.teams.edit", team),
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
                  if (__props.teams.data.length === 0) {
                    _push3(`<tr${_scopeId2}><td class="border-t px-6 py-4" colspan="4"${_scopeId2}>No teams found.</td></tr>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`</tbody></table></div>`);
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    links: __props.teams.links
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("div", { class: "overflow-x-auto" }, [
                      createVNode("table", { class: "w-full whitespace-nowrap" }, [
                        createVNode("thead", null, [
                          createVNode("tr", { class: "text-left font-bold" }, [
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, "Name"),
                            createVNode("th", {
                              class: "px-6 pb-4 pt-6",
                              colspan: "2"
                            }, "Members")
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.teams.data, (team) => {
                            return openBlock(), createBlock("tr", {
                              key: team.id,
                              class: "hover:bg-neutral-200"
                            }, [
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4 focus:text-indigo-500",
                                  href: _ctx.route("admin.teams.edit", team)
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(team.name) + " ", 1),
                                    team.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
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
                                  href: _ctx.route("admin.teams.edit", team),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(team.members_count), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "w-px border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-4",
                                  href: _ctx.route("admin.teams.edit", team),
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
                          __props.teams.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                            createVNode("td", {
                              class: "border-t px-6 py-4",
                              colspan: "4"
                            }, "No teams found.")
                          ])) : createCommentVNode("", true)
                        ])
                      ])
                    ]),
                    createVNode(_sfc_main$4, {
                      links: __props.teams.links
                    }, null, 8, ["links"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode("h1", { class: "mb-8 text-3xl font-bold" }, "Teams"),
              createVNode("div", { class: "mb-6 flex items-center justify-between" }, [
                createVNode(_sfc_main$2, {
                  modelValue: unref(form).search,
                  "onUpdate:modelValue": ($event) => unref(form).search = $event,
                  class: "mr-4 w-full max-w-md",
                  onReset: reset
                }, {
                  default: withCtx(() => [
                    withDirectives(createVNode("select", {
                      "onUpdate:modelValue": ($event) => unref(form).trashed = $event,
                      class: "form-select mt-1 w-full"
                    }, [
                      createVNode("option", { value: null }),
                      createVNode("option", { value: "with" }, "With Trashed"),
                      createVNode("option", { value: "only" }, "Only Trashed")
                    ], 8, ["onUpdate:modelValue"]), [
                      [vModelSelect, unref(form).trashed]
                    ])
                  ]),
                  _: 1
                }, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode(unref(Link), {
                  class: "btn-primary",
                  href: "/admin/teams/create"
                }, {
                  default: withCtx(() => [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Team")
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
                          createVNode("th", {
                            class: "px-6 pb-4 pt-6",
                            colspan: "2"
                          }, "Members")
                        ])
                      ]),
                      createVNode("tbody", null, [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.teams.data, (team) => {
                          return openBlock(), createBlock("tr", {
                            key: team.id,
                            class: "hover:bg-neutral-200"
                          }, [
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-6 py-4 focus:text-indigo-500",
                                href: _ctx.route("admin.teams.edit", team)
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(team.name) + " ", 1),
                                  team.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
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
                                href: _ctx.route("admin.teams.edit", team),
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(team.members_count), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "w-px border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-4",
                                href: _ctx.route("admin.teams.edit", team),
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
                        __props.teams.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                          createVNode("td", {
                            class: "border-t px-6 py-4",
                            colspan: "4"
                          }, "No teams found.")
                        ])) : createCommentVNode("", true)
                      ])
                    ])
                  ]),
                  createVNode(_sfc_main$4, {
                    links: __props.teams.links
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Teams/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
