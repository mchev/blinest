import { watch, unref, withCtx, createVNode, withDirectives, vModelSelect, openBlock, createBlock, createCommentVNode, createTextVNode, toDisplayString, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
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
    rooms: Object
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
        router.get("/admin/rooms", pickBy(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Rooms" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>Rooms (${ssrInterpolate(__props.rooms.total)})</h1><div class="mb-6 flex items-center justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).search,
              "onUpdate:modelValue": ($event) => unref(form).search = $event,
              class: "mr-4 w-full max-w-md",
              onReset: reset
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<label class="mt-4 block text-gray-700"${_scopeId2}>Trashed:</label><select class="form-select mt-1 w-full"${_scopeId2}><option${ssrRenderAttr("value", null)}${_scopeId2}></option><option value="with"${_scopeId2}>With Trashed</option><option value="only"${_scopeId2}>Only Trashed</option></select>`);
                } else {
                  return [
                    createVNode("label", { class: "mt-4 block text-gray-700" }, "Trashed:"),
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
              href: _ctx.route("admin.rooms.create")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span${_scopeId2}>Create</span><span class="hidden md:inline"${_scopeId2}> Room</span>`);
                } else {
                  return [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Room")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(Card, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="overflow-x-auto"${_scopeId2}><table class="w-full whitespace-nowrap"${_scopeId2}><tr class="text-left font-bold"${_scopeId2}><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Name"))}</th><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Owner"))}</th><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Moderators"))}</th><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Playlists"))}</th><th class="px-2 pb-4 pt-6" colspan="2"${_scopeId2}>${ssrInterpolate(_ctx.__("Public"))}</th></tr><!--[-->`);
                  ssrRenderList(__props.rooms.data, (room) => {
                    _push3(`<tr class="hover:bg-neutral-200"${_scopeId2}><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-2 py-4",
                      href: _ctx.route("admin.rooms.edit", room.id)
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          if (room.photo) {
                            _push4(`<img class="-my-2 mr-2 block h-10 w-10 rounded-full"${ssrRenderAttr("src", room.photo)}${_scopeId3}>`);
                          } else {
                            _push4(`<!---->`);
                          }
                          _push4(`<div class="flex flex-col"${_scopeId3}>${ssrInterpolate(room.name)} <small class="max-w-[18rem] truncate text-gray-500"${_scopeId3}>${ssrInterpolate(room.description)}</small></div>`);
                          if (room.deleted_at) {
                            _push4(ssrRenderComponent(_sfc_main$3, {
                              name: "trash",
                              class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                            }, null, _parent4, _scopeId3));
                          } else {
                            _push4(`<!---->`);
                          }
                        } else {
                          return [
                            room.photo ? (openBlock(), createBlock("img", {
                              key: 0,
                              class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                              src: room.photo
                            }, null, 8, ["src"])) : createCommentVNode("", true),
                            createVNode("div", { class: "flex flex-col" }, [
                              createTextVNode(toDisplayString(room.name) + " ", 1),
                              createVNode("small", { class: "max-w-[18rem] truncate text-gray-500" }, toDisplayString(room.description), 1)
                            ]),
                            room.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                              key: 1,
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
                      class: "flex items-center px-2 py-4",
                      href: _ctx.route("admin.rooms.edit", room.id),
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          if (room.owner.photo) {
                            _push4(`<img class="-my-2 mr-2 block h-10 w-10 rounded-full"${ssrRenderAttr("src", room.owner.photo)}${_scopeId3}>`);
                          } else {
                            _push4(`<!---->`);
                          }
                          _push4(` ${ssrInterpolate(room.owner.name)}`);
                        } else {
                          return [
                            room.owner.photo ? (openBlock(), createBlock("img", {
                              key: 0,
                              class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                              src: room.owner.photo
                            }, null, 8, ["src"])) : createCommentVNode("", true),
                            createTextVNode(" " + toDisplayString(room.owner.name), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t"${_scopeId2}><ul class="flex items-center px-2 py-4 text-sm"${_scopeId2}><!--[-->`);
                    ssrRenderList(room.moderators, (moderator) => {
                      _push3(`<li${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                        href: _ctx.route("admin.users.edit", moderator.id),
                        tabindex: "-1"
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`${ssrInterpolate(moderator.name)}`);
                          } else {
                            return [
                              createTextVNode(toDisplayString(moderator.name), 1)
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</li>`);
                    });
                    _push3(`<!--]--></ul></td><td class="border-t"${_scopeId2}><ul class="flex items-center px-2 py-4 text-sm"${_scopeId2}><!--[-->`);
                    ssrRenderList(room.playlists, (playlist) => {
                      _push3(`<li${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                        href: _ctx.route("admin.playlists.edit", playlist.id),
                        tabindex: "-1"
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`${ssrInterpolate(playlist.name)}`);
                          } else {
                            return [
                              createTextVNode(toDisplayString(playlist.name), 1)
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</li>`);
                    });
                    _push3(`<!--]--></ul></td><td class="border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-2 py-4",
                      href: _ctx.route("admin.rooms.edit", room.id),
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`<span class="${ssrRenderClass([room.is_public ? "bg-teal-600  text-neutral-100" : "bg-neutral-300", "m-1 rounded px-2 py-1"])}"${_scopeId3}>${ssrInterpolate(room.is_public ? _ctx.__("Yes") : _ctx.__("No"))}</span>`);
                        } else {
                          return [
                            createVNode("span", {
                              class: ["m-1 rounded px-2 py-1", room.is_public ? "bg-teal-600  text-neutral-100" : "bg-neutral-300"]
                            }, toDisplayString(room.is_public ? _ctx.__("Yes") : _ctx.__("No")), 3)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="w-px border-t"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-4",
                      href: _ctx.route("admin.rooms.edit", room.id),
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(ssrRenderComponent(_sfc_main$3, {
                            name: "cheveron-right",
                            class: "block h-6 w-6 fill-gray-400"
                          }, null, _parent4, _scopeId3));
                        } else {
                          return [
                            createVNode(_sfc_main$3, {
                              name: "cheveron-right",
                              class: "block h-6 w-6 fill-gray-400"
                            })
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td></tr>`);
                  });
                  _push3(`<!--]-->`);
                  if (__props.rooms.length === 0) {
                    _push3(`<tr${_scopeId2}><td class="border-t px-2 py-4" colspan="6"${_scopeId2}>${ssrInterpolate(_ctx.__("No rooms found."))}</td></tr>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`</table></div>`);
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    links: __props.rooms.links
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("div", { class: "overflow-x-auto" }, [
                      createVNode("table", { class: "w-full whitespace-nowrap" }, [
                        createVNode("tr", { class: "text-left font-bold" }, [
                          createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Name")), 1),
                          createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Owner")), 1),
                          createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Moderators")), 1),
                          createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Playlists")), 1),
                          createVNode("th", {
                            class: "px-2 pb-4 pt-6",
                            colspan: "2"
                          }, toDisplayString(_ctx.__("Public")), 1)
                        ]),
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.rooms.data, (room) => {
                          return openBlock(), createBlock("tr", {
                            key: room.id,
                            class: "hover:bg-neutral-200"
                          }, [
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-2 py-4",
                                href: _ctx.route("admin.rooms.edit", room.id)
                              }, {
                                default: withCtx(() => [
                                  room.photo ? (openBlock(), createBlock("img", {
                                    key: 0,
                                    class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                                    src: room.photo
                                  }, null, 8, ["src"])) : createCommentVNode("", true),
                                  createVNode("div", { class: "flex flex-col" }, [
                                    createTextVNode(toDisplayString(room.name) + " ", 1),
                                    createVNode("small", { class: "max-w-[18rem] truncate text-gray-500" }, toDisplayString(room.description), 1)
                                  ]),
                                  room.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                                    key: 1,
                                    name: "trash",
                                    class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                                  })) : createCommentVNode("", true)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-2 py-4",
                                href: _ctx.route("admin.rooms.edit", room.id),
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  room.owner.photo ? (openBlock(), createBlock("img", {
                                    key: 0,
                                    class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                                    src: room.owner.photo
                                  }, null, 8, ["src"])) : createCommentVNode("", true),
                                  createTextVNode(" " + toDisplayString(room.owner.name), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode("ul", { class: "flex items-center px-2 py-4 text-sm" }, [
                                (openBlock(true), createBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                                  return openBlock(), createBlock("li", {
                                    key: moderator.id
                                  }, [
                                    createVNode(unref(Link), {
                                      class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                                      href: _ctx.route("admin.users.edit", moderator.id),
                                      tabindex: "-1"
                                    }, {
                                      default: withCtx(() => [
                                        createTextVNode(toDisplayString(moderator.name), 1)
                                      ]),
                                      _: 2
                                    }, 1032, ["href"])
                                  ]);
                                }), 128))
                              ])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode("ul", { class: "flex items-center px-2 py-4 text-sm" }, [
                                (openBlock(true), createBlock(Fragment, null, renderList(room.playlists, (playlist) => {
                                  return openBlock(), createBlock("li", {
                                    key: playlist.id
                                  }, [
                                    createVNode(unref(Link), {
                                      class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                                      href: _ctx.route("admin.playlists.edit", playlist.id),
                                      tabindex: "-1"
                                    }, {
                                      default: withCtx(() => [
                                        createTextVNode(toDisplayString(playlist.name), 1)
                                      ]),
                                      _: 2
                                    }, 1032, ["href"])
                                  ]);
                                }), 128))
                              ])
                            ]),
                            createVNode("td", { class: "border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-2 py-4",
                                href: _ctx.route("admin.rooms.edit", room.id),
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  createVNode("span", {
                                    class: ["m-1 rounded px-2 py-1", room.is_public ? "bg-teal-600  text-neutral-100" : "bg-neutral-300"]
                                  }, toDisplayString(room.is_public ? _ctx.__("Yes") : _ctx.__("No")), 3)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("td", { class: "w-px border-t" }, [
                              createVNode(unref(Link), {
                                class: "flex items-center px-4",
                                href: _ctx.route("admin.rooms.edit", room.id),
                                tabindex: "-1"
                              }, {
                                default: withCtx(() => [
                                  createVNode(_sfc_main$3, {
                                    name: "cheveron-right",
                                    class: "block h-6 w-6 fill-gray-400"
                                  })
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ])
                          ]);
                        }), 128)),
                        __props.rooms.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                          createVNode("td", {
                            class: "border-t px-2 py-4",
                            colspan: "6"
                          }, toDisplayString(_ctx.__("No rooms found.")), 1)
                        ])) : createCommentVNode("", true)
                      ])
                    ]),
                    createVNode(_sfc_main$4, {
                      links: __props.rooms.links
                    }, null, 8, ["links"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode("h1", { class: "mb-8 text-3xl font-bold" }, "Rooms (" + toDisplayString(__props.rooms.total) + ")", 1),
              createVNode("div", { class: "mb-6 flex items-center justify-between" }, [
                createVNode(_sfc_main$2, {
                  modelValue: unref(form).search,
                  "onUpdate:modelValue": ($event) => unref(form).search = $event,
                  class: "mr-4 w-full max-w-md",
                  onReset: reset
                }, {
                  default: withCtx(() => [
                    createVNode("label", { class: "mt-4 block text-gray-700" }, "Trashed:"),
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
                  href: _ctx.route("admin.rooms.create")
                }, {
                  default: withCtx(() => [
                    createVNode("span", null, "Create"),
                    createVNode("span", { class: "hidden md:inline" }, " Room")
                  ]),
                  _: 1
                }, 8, ["href"])
              ]),
              createVNode(Card, null, {
                default: withCtx(() => [
                  createVNode("div", { class: "overflow-x-auto" }, [
                    createVNode("table", { class: "w-full whitespace-nowrap" }, [
                      createVNode("tr", { class: "text-left font-bold" }, [
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Name")), 1),
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Owner")), 1),
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Moderators")), 1),
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Playlists")), 1),
                        createVNode("th", {
                          class: "px-2 pb-4 pt-6",
                          colspan: "2"
                        }, toDisplayString(_ctx.__("Public")), 1)
                      ]),
                      (openBlock(true), createBlock(Fragment, null, renderList(__props.rooms.data, (room) => {
                        return openBlock(), createBlock("tr", {
                          key: room.id,
                          class: "hover:bg-neutral-200"
                        }, [
                          createVNode("td", { class: "border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex items-center px-2 py-4",
                              href: _ctx.route("admin.rooms.edit", room.id)
                            }, {
                              default: withCtx(() => [
                                room.photo ? (openBlock(), createBlock("img", {
                                  key: 0,
                                  class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                                  src: room.photo
                                }, null, 8, ["src"])) : createCommentVNode("", true),
                                createVNode("div", { class: "flex flex-col" }, [
                                  createTextVNode(toDisplayString(room.name) + " ", 1),
                                  createVNode("small", { class: "max-w-[18rem] truncate text-gray-500" }, toDisplayString(room.description), 1)
                                ]),
                                room.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                                  key: 1,
                                  name: "trash",
                                  class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                                })) : createCommentVNode("", true)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex items-center px-2 py-4",
                              href: _ctx.route("admin.rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                room.owner.photo ? (openBlock(), createBlock("img", {
                                  key: 0,
                                  class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                                  src: room.owner.photo
                                }, null, 8, ["src"])) : createCommentVNode("", true),
                                createTextVNode(" " + toDisplayString(room.owner.name), 1)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            createVNode("ul", { class: "flex items-center px-2 py-4 text-sm" }, [
                              (openBlock(true), createBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                                return openBlock(), createBlock("li", {
                                  key: moderator.id
                                }, [
                                  createVNode(unref(Link), {
                                    class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                                    href: _ctx.route("admin.users.edit", moderator.id),
                                    tabindex: "-1"
                                  }, {
                                    default: withCtx(() => [
                                      createTextVNode(toDisplayString(moderator.name), 1)
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ]);
                              }), 128))
                            ])
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            createVNode("ul", { class: "flex items-center px-2 py-4 text-sm" }, [
                              (openBlock(true), createBlock(Fragment, null, renderList(room.playlists, (playlist) => {
                                return openBlock(), createBlock("li", {
                                  key: playlist.id
                                }, [
                                  createVNode(unref(Link), {
                                    class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                                    href: _ctx.route("admin.playlists.edit", playlist.id),
                                    tabindex: "-1"
                                  }, {
                                    default: withCtx(() => [
                                      createTextVNode(toDisplayString(playlist.name), 1)
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ]);
                              }), 128))
                            ])
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex items-center px-2 py-4",
                              href: _ctx.route("admin.rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createVNode("span", {
                                  class: ["m-1 rounded px-2 py-1", room.is_public ? "bg-teal-600  text-neutral-100" : "bg-neutral-300"]
                                }, toDisplayString(room.is_public ? _ctx.__("Yes") : _ctx.__("No")), 3)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("td", { class: "w-px border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex items-center px-4",
                              href: _ctx.route("admin.rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createVNode(_sfc_main$3, {
                                  name: "cheveron-right",
                                  class: "block h-6 w-6 fill-gray-400"
                                })
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ])
                        ]);
                      }), 128)),
                      __props.rooms.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                        createVNode("td", {
                          class: "border-t px-2 py-4",
                          colspan: "6"
                        }, toDisplayString(_ctx.__("No rooms found.")), 1)
                      ])) : createCommentVNode("", true)
                    ])
                  ]),
                  createVNode(_sfc_main$4, {
                    links: __props.rooms.links
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Rooms/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
