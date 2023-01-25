import { watch, unref, withCtx, createTextVNode, toDisplayString, openBlock, createBlock, createCommentVNode, createVNode, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr, ssrRenderClass } from "vue/server-renderer";
import { useForm, router, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import { _ as _sfc_main$3 } from "./Icon-4a47e6e0.mjs";
import { _ as _sfc_main$4 } from "./Pagination-d517bd16.mjs";
import { _ as _sfc_main$2 } from "./SearchFilter-26a0a59f.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import pickBy from "lodash/pickBy.js";
import throttle from "lodash/throttle.js";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-d136d2f8.mjs";
import "./TextInput-cddc091b.mjs";
import "uuid";
import "./SocialIcon-bb2fc3a0.mjs";
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
        router.get("/rooms", pickBy(form), { remember: "forget", preserveState: true });
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
            if (__props.rooms && __props.rooms.data.length) {
              _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Rooms"))}</h1>`);
            } else {
              _push2(`<!---->`);
            }
            if (__props.rooms && __props.rooms.data.length) {
              _push2(`<div class="mb-6 flex items-center justify-between"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$2, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": ($event) => unref(form).search = $event,
                class: "mr-4 w-full max-w-md",
                onReset: reset
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(Link), {
                class: "btn-primary",
                href: _ctx.route("rooms.create")
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Create a room"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Create a room")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
            if (__props.rooms && __props.rooms.data.length) {
              _push2(ssrRenderComponent(Card, null, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="overflow-x-auto"${_scopeId2}><table class="w-full whitespace-nowrap"${_scopeId2}><tr class="text-left font-bold"${_scopeId2}><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Name"))}</th><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Moderators"))}</th><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Category"))}</th><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Playlists"))}</th><th class="px-2 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Rounds played"))}</th><th class="px-2 pb-4 pt-6" colspan="2"${_scopeId2}>${ssrInterpolate(_ctx.__("Visibility"))}</th></tr><!--[-->`);
                    ssrRenderList(__props.rooms.data, (room) => {
                      _push3(`<tr${_scopeId2}><td class="border-t"${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        class: "flex items-center px-2 py-4",
                        href: _ctx.route("rooms.edit", room.id)
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
                        href: _ctx.route("rooms.edit", room.id),
                        tabindex: "-1"
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`<ul class="flex items-center px-2 py-4 text-sm"${_scopeId3}><!--[-->`);
                            ssrRenderList(room.moderators, (moderator) => {
                              _push4(`<li class="badge"${_scopeId3}>${ssrInterpolate(moderator.name)}</li>`);
                            });
                            _push4(`<!--]--></ul>`);
                          } else {
                            return [
                              createVNode("ul", { class: "flex items-center px-2 py-4 text-sm" }, [
                                (openBlock(true), createBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                                  return openBlock(), createBlock("li", {
                                    key: moderator.id,
                                    class: "badge"
                                  }, toDisplayString(moderator.name), 1);
                                }), 128))
                              ])
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</td><td class="border-t"${_scopeId2}>`);
                      if (room.category) {
                        _push3(ssrRenderComponent(unref(Link), {
                          class: "flex items-center px-2 py-4",
                          href: _ctx.route("rooms.edit", room.id),
                          tabindex: "-1"
                        }, {
                          default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                            if (_push4) {
                              _push4(`${ssrInterpolate(room.category.name)}`);
                            } else {
                              return [
                                createTextVNode(toDisplayString(room.category.name), 1)
                              ];
                            }
                          }),
                          _: 2
                        }, _parent3, _scopeId2));
                      } else {
                        _push3(`<!---->`);
                      }
                      _push3(`</td><td class="border-t"${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        class: "flex items-center px-2 py-4",
                        href: _ctx.route("rooms.edit", room.id),
                        tabindex: "-1"
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            if (room.playlists.length) {
                              _push4(`<ul class="flex items-center px-2 py-4 text-sm"${_scopeId3}><!--[-->`);
                              ssrRenderList(room.playlists, (playlist) => {
                                _push4(`<li class="badge"${_scopeId3}>${ssrInterpolate(playlist.name)}</li>`);
                              });
                              _push4(`<!--]--></ul>`);
                            } else {
                              _push4(`<span class="text-xs text-neutral-500"${_scopeId3}>${ssrInterpolate(_ctx.__("No playlist"))}</span>`);
                            }
                          } else {
                            return [
                              room.playlists.length ? (openBlock(), createBlock("ul", {
                                key: 0,
                                class: "flex items-center px-2 py-4 text-sm"
                              }, [
                                (openBlock(true), createBlock(Fragment, null, renderList(room.playlists, (playlist) => {
                                  return openBlock(), createBlock("li", {
                                    key: playlist.id,
                                    class: "badge"
                                  }, toDisplayString(playlist.name), 1);
                                }), 128))
                              ])) : (openBlock(), createBlock("span", {
                                key: 1,
                                class: "text-xs text-neutral-500"
                              }, toDisplayString(_ctx.__("No playlist")), 1))
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</td><td class="border-t"${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        class: "flex flex-col items-start px-2 py-4",
                        href: _ctx.route("rooms.edit", room.id),
                        tabindex: "-1"
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`${ssrInterpolate(room.rounds_count)}`);
                          } else {
                            return [
                              createTextVNode(toDisplayString(room.rounds_count), 1)
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</td><td class="border-t"${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        class: "flex flex-col items-start px-2 py-4",
                        href: _ctx.route("rooms.edit", room.id),
                        tabindex: "-1"
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`<span class="${ssrRenderClass([!room.password ? "bg-teal-600  text-neutral-100" : "bg-neutral-600", "badge"])}"${_scopeId3}>${ssrInterpolate(room.password ? _ctx.__("No") : _ctx.__("Yes"))}</span>`);
                            if (room.password) {
                              _push4(`<small class="text-xs text-neutral-500"${_scopeId3}>${ssrInterpolate(_ctx.__("Password protected"))}</small>`);
                            } else {
                              _push4(`<!---->`);
                            }
                          } else {
                            return [
                              createVNode("span", {
                                class: ["badge", !room.password ? "bg-teal-600  text-neutral-100" : "bg-neutral-600"]
                              }, toDisplayString(room.password ? _ctx.__("No") : _ctx.__("Yes")), 3),
                              room.password ? (openBlock(), createBlock("small", {
                                key: 0,
                                class: "text-xs text-neutral-500"
                              }, toDisplayString(_ctx.__("Password protected")), 1)) : createCommentVNode("", true)
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</td><td class="w-px border-t"${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        class: "flex items-center px-4",
                        href: _ctx.route("rooms.edit", room.id),
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
                    if (__props.rooms && __props.rooms.data.length === 0) {
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
                            createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Moderators")), 1),
                            createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Category")), 1),
                            createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Playlists")), 1),
                            createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Rounds played")), 1),
                            createVNode("th", {
                              class: "px-2 pb-4 pt-6",
                              colspan: "2"
                            }, toDisplayString(_ctx.__("Visibility")), 1)
                          ]),
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.rooms.data, (room) => {
                            return openBlock(), createBlock("tr", {
                              key: room.id
                            }, [
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-2 py-4",
                                  href: _ctx.route("rooms.edit", room.id)
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
                                  href: _ctx.route("rooms.edit", room.id),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createVNode("ul", { class: "flex items-center px-2 py-4 text-sm" }, [
                                      (openBlock(true), createBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                                        return openBlock(), createBlock("li", {
                                          key: moderator.id,
                                          class: "badge"
                                        }, toDisplayString(moderator.name), 1);
                                      }), 128))
                                    ])
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                room.category ? (openBlock(), createBlock(unref(Link), {
                                  key: 0,
                                  class: "flex items-center px-2 py-4",
                                  href: _ctx.route("rooms.edit", room.id),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(room.category.name), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])) : createCommentVNode("", true)
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-2 py-4",
                                  href: _ctx.route("rooms.edit", room.id),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    room.playlists.length ? (openBlock(), createBlock("ul", {
                                      key: 0,
                                      class: "flex items-center px-2 py-4 text-sm"
                                    }, [
                                      (openBlock(true), createBlock(Fragment, null, renderList(room.playlists, (playlist) => {
                                        return openBlock(), createBlock("li", {
                                          key: playlist.id,
                                          class: "badge"
                                        }, toDisplayString(playlist.name), 1);
                                      }), 128))
                                    ])) : (openBlock(), createBlock("span", {
                                      key: 1,
                                      class: "text-xs text-neutral-500"
                                    }, toDisplayString(_ctx.__("No playlist")), 1))
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex flex-col items-start px-2 py-4",
                                  href: _ctx.route("rooms.edit", room.id),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(room.rounds_count), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex flex-col items-start px-2 py-4",
                                  href: _ctx.route("rooms.edit", room.id),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createVNode("span", {
                                      class: ["badge", !room.password ? "bg-teal-600  text-neutral-100" : "bg-neutral-600"]
                                    }, toDisplayString(room.password ? _ctx.__("No") : _ctx.__("Yes")), 3),
                                    room.password ? (openBlock(), createBlock("small", {
                                      key: 0,
                                      class: "text-xs text-neutral-500"
                                    }, toDisplayString(_ctx.__("Password protected")), 1)) : createCommentVNode("", true)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "w-px border-t" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-4",
                                  href: _ctx.route("rooms.edit", room.id),
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
                          __props.rooms && __props.rooms.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
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
              _push2(`<div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6"${_scopeId}><div class="mx-auto mb-8 max-w-screen-sm lg:mb-16"${_scopeId}><h2 class="mb-4 text-4xl font-extrabold tracking-tight"${_scopeId}>${ssrInterpolate(_ctx.__("Rooms"))}</h2><p class="text-lg"${_scopeId}>&quot;Si à 50 ans on n&#39;a pas de Room sur Blinest, on a raté sa vie.&quot; <br${_scopeId}><small class="text-xs italic"${_scopeId}>Anonyme.</small></p><div class="my-8 flex justify-center"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                class: "btn-primary btn-lg",
                href: _ctx.route("rooms.create")
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Create my first room"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Create my first room")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</div></div></div>`);
            }
          } else {
            return [
              __props.rooms && __props.rooms.data.length ? (openBlock(), createBlock("h1", {
                key: 0,
                class: "mb-8 text-3xl font-bold"
              }, toDisplayString(_ctx.__("Rooms")), 1)) : createCommentVNode("", true),
              __props.rooms && __props.rooms.data.length ? (openBlock(), createBlock("div", {
                key: 1,
                class: "mb-6 flex items-center justify-between"
              }, [
                createVNode(_sfc_main$2, {
                  modelValue: unref(form).search,
                  "onUpdate:modelValue": ($event) => unref(form).search = $event,
                  class: "mr-4 w-full max-w-md",
                  onReset: reset
                }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode(unref(Link), {
                  class: "btn-primary",
                  href: _ctx.route("rooms.create")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("Create a room")), 1)
                  ]),
                  _: 1
                }, 8, ["href"])
              ])) : createCommentVNode("", true),
              __props.rooms && __props.rooms.data.length ? (openBlock(), createBlock(Card, { key: 2 }, {
                default: withCtx(() => [
                  createVNode("div", { class: "overflow-x-auto" }, [
                    createVNode("table", { class: "w-full whitespace-nowrap" }, [
                      createVNode("tr", { class: "text-left font-bold" }, [
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Name")), 1),
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Moderators")), 1),
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Category")), 1),
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Playlists")), 1),
                        createVNode("th", { class: "px-2 pb-4 pt-6" }, toDisplayString(_ctx.__("Rounds played")), 1),
                        createVNode("th", {
                          class: "px-2 pb-4 pt-6",
                          colspan: "2"
                        }, toDisplayString(_ctx.__("Visibility")), 1)
                      ]),
                      (openBlock(true), createBlock(Fragment, null, renderList(__props.rooms.data, (room) => {
                        return openBlock(), createBlock("tr", {
                          key: room.id
                        }, [
                          createVNode("td", { class: "border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex items-center px-2 py-4",
                              href: _ctx.route("rooms.edit", room.id)
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
                              href: _ctx.route("rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createVNode("ul", { class: "flex items-center px-2 py-4 text-sm" }, [
                                  (openBlock(true), createBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                                    return openBlock(), createBlock("li", {
                                      key: moderator.id,
                                      class: "badge"
                                    }, toDisplayString(moderator.name), 1);
                                  }), 128))
                                ])
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            room.category ? (openBlock(), createBlock(unref(Link), {
                              key: 0,
                              class: "flex items-center px-2 py-4",
                              href: _ctx.route("rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createTextVNode(toDisplayString(room.category.name), 1)
                              ]),
                              _: 2
                            }, 1032, ["href"])) : createCommentVNode("", true)
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex items-center px-2 py-4",
                              href: _ctx.route("rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                room.playlists.length ? (openBlock(), createBlock("ul", {
                                  key: 0,
                                  class: "flex items-center px-2 py-4 text-sm"
                                }, [
                                  (openBlock(true), createBlock(Fragment, null, renderList(room.playlists, (playlist) => {
                                    return openBlock(), createBlock("li", {
                                      key: playlist.id,
                                      class: "badge"
                                    }, toDisplayString(playlist.name), 1);
                                  }), 128))
                                ])) : (openBlock(), createBlock("span", {
                                  key: 1,
                                  class: "text-xs text-neutral-500"
                                }, toDisplayString(_ctx.__("No playlist")), 1))
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex flex-col items-start px-2 py-4",
                              href: _ctx.route("rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createTextVNode(toDisplayString(room.rounds_count), 1)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("td", { class: "border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex flex-col items-start px-2 py-4",
                              href: _ctx.route("rooms.edit", room.id),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createVNode("span", {
                                  class: ["badge", !room.password ? "bg-teal-600  text-neutral-100" : "bg-neutral-600"]
                                }, toDisplayString(room.password ? _ctx.__("No") : _ctx.__("Yes")), 3),
                                room.password ? (openBlock(), createBlock("small", {
                                  key: 0,
                                  class: "text-xs text-neutral-500"
                                }, toDisplayString(_ctx.__("Password protected")), 1)) : createCommentVNode("", true)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("td", { class: "w-px border-t" }, [
                            createVNode(unref(Link), {
                              class: "flex items-center px-4",
                              href: _ctx.route("rooms.edit", room.id),
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
                      __props.rooms && __props.rooms.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
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
              })) : (openBlock(), createBlock("div", {
                key: 3,
                class: "mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6"
              }, [
                createVNode("div", { class: "mx-auto mb-8 max-w-screen-sm lg:mb-16" }, [
                  createVNode("h2", { class: "mb-4 text-4xl font-extrabold tracking-tight" }, toDisplayString(_ctx.__("Rooms")), 1),
                  createVNode("p", { class: "text-lg" }, [
                    createTextVNode(`"Si à 50 ans on n'a pas de Room sur Blinest, on a raté sa vie." `),
                    createVNode("br"),
                    createVNode("small", { class: "text-xs italic" }, "Anonyme.")
                  ]),
                  createVNode("div", { class: "my-8 flex justify-center" }, [
                    createVNode(unref(Link), {
                      class: "btn-primary btn-lg",
                      href: _ctx.route("rooms.create")
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Create my first room")), 1)
                      ]),
                      _: 1
                    }, 8, ["href"])
                  ])
                ])
              ]))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
