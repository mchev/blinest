import { ref, watch, withCtx, createVNode, toDisplayString, openBlock, createBlock, Fragment, renderList, createCommentVNode, createTextVNode, useSSRContext, unref } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr } from "vue/server-renderer";
import { useForm, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$3 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$2 } from "./Dropdown-6785e0d2.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import debounce from "lodash/debounce.js";
import { T as Tip } from "./Tip-9a139e5c.mjs";
const _sfc_main$1 = {
  __name: "ModeratorsManager",
  __ssrInlineRender: true,
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const search = ref("");
    const searching = ref(false);
    const users = ref(null);
    const form = useForm({
      user_id: null
    });
    watch(
      search,
      debounce(() => {
        searching.value = true;
        axios.get("/api/users", { params: { search: search.value } }).then((response) => {
          users.value = response.data.users;
          searching.value = false;
        });
      }, 300)
    );
    const attach = (user) => {
      form.transform((data) => ({
        user_id: user.id
      })).post(`/rooms/${props.room.id}/moderators/attach`, {
        preserveScroll: true
      });
    };
    const detach = (user) => {
      form.transform((data) => ({
        user_id: user.id
      })).delete(`/rooms/${props.room.id}/moderators/detach`, {
        preserveScroll: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="text-xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Moderators"))}</h3>`);
          } else {
            return [
              createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Moderators")), 1)
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, {
              placement: "bottom-start",
              class: "mb-2 pb-2",
              onClosed: ($event) => search.value = ""
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: search.value,
                    "onUpdate:modelValue": ($event) => search.value = $event,
                    "prepend-icon": "search",
                    "append-icon": "cheveron-down",
                    loading: searching.value,
                    placeholder: _ctx.__("Add a moderator")
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$3, {
                      modelValue: search.value,
                      "onUpdate:modelValue": ($event) => search.value = $event,
                      "prepend-icon": "search",
                      "append-icon": "cheveron-down",
                      loading: searching.value,
                      placeholder: _ctx.__("Add a moderator")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "loading", "placeholder"])
                  ];
                }
              }),
              dropdown: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (users.value && users.value.data.length) {
                    _push3(`<ul class="max-w-50 max-h-80 overflow-y-auto"${_scopeId2}><!--[-->`);
                    ssrRenderList(users.value.data, (user) => {
                      _push3(`<li class="flex items-center p-2"${_scopeId2}>`);
                      if (user.photo) {
                        _push3(`<img class="-my-2 mr-2 block h-8 w-8 rounded-full"${ssrRenderAttr("src", user.photo)}${_scopeId2}>`);
                      } else {
                        _push3(`<!---->`);
                      }
                      _push3(`<span class="mr-4"${_scopeId2}>${ssrInterpolate(user.name)}</span><button class="ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase"${ssrRenderAttr("title", _ctx.__("Add"))}${_scopeId2}>${ssrInterpolate(_ctx.__("Add"))}</button></li>`);
                    });
                    _push3(`<!--]--></ul>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    users.value && users.value.data.length ? (openBlock(), createBlock("ul", {
                      key: 0,
                      class: "max-w-50 max-h-80 overflow-y-auto"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(users.value.data, (user) => {
                        return openBlock(), createBlock("li", {
                          key: user.id,
                          class: "flex items-center p-2"
                        }, [
                          user.photo ? (openBlock(), createBlock("img", {
                            key: 0,
                            class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                            src: user.photo
                          }, null, 8, ["src"])) : createCommentVNode("", true),
                          createVNode("span", { class: "mr-4" }, toDisplayString(user.name), 1),
                          createVNode("button", {
                            class: "ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                            title: _ctx.__("Add"),
                            onClick: ($event) => attach(user)
                          }, toDisplayString(_ctx.__("Add")), 9, ["title", "onClick"])
                        ]);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            if (__props.room.moderators && __props.room.moderators.length) {
              _push2(`<ul${_scopeId}><!--[-->`);
              ssrRenderList(__props.room.moderators, (moderator) => {
                _push2(`<li class="flex items-center rounded p-3"${_scopeId}>`);
                if (moderator.photo) {
                  _push2(`<img class="-my-2 mr-2 block h-8 w-8 rounded-full"${ssrRenderAttr("src", moderator.photo)}${_scopeId}>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(` ${ssrInterpolate(moderator.name)} <button class="ml-auto text-red-500 flex"${ssrRenderAttr("title", _ctx.__("Remove"))}${_scopeId}>${ssrInterpolate(_ctx.__("Remove"))} <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"${_scopeId}></path></svg></button></li>`);
              });
              _push2(`<!--]--></ul>`);
            } else {
              _push2(`<p class="my-2 text-sm text-neutral-400"${_scopeId}>${ssrInterpolate(_ctx.__("No moderator"))}</p>`);
            }
          } else {
            return [
              createVNode(_sfc_main$2, {
                placement: "bottom-start",
                class: "mb-2 pb-2",
                onClosed: ($event) => search.value = ""
              }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$3, {
                    modelValue: search.value,
                    "onUpdate:modelValue": ($event) => search.value = $event,
                    "prepend-icon": "search",
                    "append-icon": "cheveron-down",
                    loading: searching.value,
                    placeholder: _ctx.__("Add a moderator")
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "loading", "placeholder"])
                ]),
                dropdown: withCtx(() => [
                  users.value && users.value.data.length ? (openBlock(), createBlock("ul", {
                    key: 0,
                    class: "max-w-50 max-h-80 overflow-y-auto"
                  }, [
                    (openBlock(true), createBlock(Fragment, null, renderList(users.value.data, (user) => {
                      return openBlock(), createBlock("li", {
                        key: user.id,
                        class: "flex items-center p-2"
                      }, [
                        user.photo ? (openBlock(), createBlock("img", {
                          key: 0,
                          class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                          src: user.photo
                        }, null, 8, ["src"])) : createCommentVNode("", true),
                        createVNode("span", { class: "mr-4" }, toDisplayString(user.name), 1),
                        createVNode("button", {
                          class: "ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                          title: _ctx.__("Add"),
                          onClick: ($event) => attach(user)
                        }, toDisplayString(_ctx.__("Add")), 9, ["title", "onClick"])
                      ]);
                    }), 128))
                  ])) : createCommentVNode("", true)
                ]),
                _: 1
              }, 8, ["onClosed"]),
              __props.room.moderators && __props.room.moderators.length ? (openBlock(), createBlock("ul", { key: 0 }, [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.room.moderators, (moderator) => {
                  return openBlock(), createBlock("li", {
                    key: moderator.id,
                    class: "flex items-center rounded p-3"
                  }, [
                    moderator.photo ? (openBlock(), createBlock("img", {
                      key: 0,
                      class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                      src: moderator.photo
                    }, null, 8, ["src"])) : createCommentVNode("", true),
                    createTextVNode(" " + toDisplayString(moderator.name) + " ", 1),
                    createVNode("button", {
                      class: "ml-auto text-red-500 flex",
                      title: _ctx.__("Remove"),
                      onClick: ($event) => detach(moderator)
                    }, [
                      createTextVNode(toDisplayString(_ctx.__("Remove")) + " ", 1),
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "h-6 w-6 ml-2",
                        fill: "none",
                        viewBox: "0 0 24 24",
                        stroke: "currentColor",
                        "stroke-width": "2"
                      }, [
                        createVNode("path", {
                          "stroke-linecap": "round",
                          "stroke-linejoin": "round",
                          d: "M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        })
                      ]))
                    ], 8, ["title", "onClick"])
                  ]);
                }), 128))
              ])) : (openBlock(), createBlock("p", {
                key: 1,
                class: "my-2 text-sm text-neutral-400"
              }, toDisplayString(_ctx.__("No moderator")), 1))
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Rooms/ModeratorsManager.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "PlaylistsManager",
  __ssrInlineRender: true,
  props: {
    room: Object,
    playlists: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      playlist_id: null
    });
    const attach = (playlist) => {
      form.transform((data) => ({
        playlist_id: playlist.id
      })).post(`/rooms/${props.room.id}/playlists/attach`, {
        preserveScroll: true
      });
    };
    const detach = (playlist) => {
      form.transform((data) => ({
        playlist_id: playlist.id
      })).delete(`/rooms/${props.room.id}/playlists/detach`, {
        preserveScroll: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full items-center justify-between"${_scopeId}><h3 class="text-xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Playlists"))}</h3><div class="flex items-center"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              placement: "bottom-start",
              class: "mr-2",
              onClosed: ($event) => _ctx.search = ""
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<button type="button" class="btn-secondary"${_scopeId2}>${ssrInterpolate(_ctx.__("Attach a playlist"))}</button>`);
                } else {
                  return [
                    createVNode("button", {
                      type: "button",
                      class: "btn-secondary"
                    }, toDisplayString(_ctx.__("Attach a playlist")), 1)
                  ];
                }
              }),
              dropdown: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (__props.playlists && __props.playlists.length) {
                    _push3(`<ul class="max-w-50 max-h-80 overflow-y-auto"${_scopeId2}><!--[-->`);
                    ssrRenderList(__props.playlists, (playlist) => {
                      _push3(`<li class="flex items-center p-2"${_scopeId2}><span class="mr-4"${_scopeId2}>${ssrInterpolate(playlist.name)}</span><button class="ml-auto flex items-center rounded-full bg-blue-500 py-1 px-2 text-xs uppercase"${ssrRenderAttr("title", _ctx.__("Add"))}${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"${_scopeId2}></path></svg> ${ssrInterpolate(_ctx.__("Attach"))}</button></li>`);
                    });
                    _push3(`<!--]--></ul>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    __props.playlists && __props.playlists.length ? (openBlock(), createBlock("ul", {
                      key: 0,
                      class: "max-w-50 max-h-80 overflow-y-auto"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(__props.playlists, (playlist) => {
                        return openBlock(), createBlock("li", {
                          key: playlist.id,
                          class: "flex items-center p-2"
                        }, [
                          createVNode("span", { class: "mr-4" }, toDisplayString(playlist.name), 1),
                          createVNode("button", {
                            class: "ml-auto flex items-center rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                            title: _ctx.__("Add"),
                            onClick: ($event) => attach(playlist)
                          }, [
                            (openBlock(), createBlock("svg", {
                              xmlns: "http://www.w3.org/2000/svg",
                              class: "mr-1 h-6 w-6",
                              fill: "none",
                              viewBox: "0 0 24 24",
                              stroke: "currentColor",
                              "stroke-width": "2"
                            }, [
                              createVNode("path", {
                                "stroke-linecap": "round",
                                "stroke-linejoin": "round",
                                d: "M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                              })
                            ])),
                            createTextVNode(" " + toDisplayString(_ctx.__("Attach")), 1)
                          ], 8, ["title", "onClick"])
                        ]);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              href: "/playlists/create",
              class: "btn-secondary"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Create a playlist"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Create a playlist")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full items-center justify-between" }, [
                createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Playlists")), 1),
                createVNode("div", { class: "flex items-center" }, [
                  createVNode(_sfc_main$2, {
                    placement: "bottom-start",
                    class: "mr-2",
                    onClosed: ($event) => _ctx.search = ""
                  }, {
                    default: withCtx(() => [
                      createVNode("button", {
                        type: "button",
                        class: "btn-secondary"
                      }, toDisplayString(_ctx.__("Attach a playlist")), 1)
                    ]),
                    dropdown: withCtx(() => [
                      __props.playlists && __props.playlists.length ? (openBlock(), createBlock("ul", {
                        key: 0,
                        class: "max-w-50 max-h-80 overflow-y-auto"
                      }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.playlists, (playlist) => {
                          return openBlock(), createBlock("li", {
                            key: playlist.id,
                            class: "flex items-center p-2"
                          }, [
                            createVNode("span", { class: "mr-4" }, toDisplayString(playlist.name), 1),
                            createVNode("button", {
                              class: "ml-auto flex items-center rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                              title: _ctx.__("Add"),
                              onClick: ($event) => attach(playlist)
                            }, [
                              (openBlock(), createBlock("svg", {
                                xmlns: "http://www.w3.org/2000/svg",
                                class: "mr-1 h-6 w-6",
                                fill: "none",
                                viewBox: "0 0 24 24",
                                stroke: "currentColor",
                                "stroke-width": "2"
                              }, [
                                createVNode("path", {
                                  "stroke-linecap": "round",
                                  "stroke-linejoin": "round",
                                  d: "M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                })
                              ])),
                              createTextVNode(" " + toDisplayString(_ctx.__("Attach")), 1)
                            ], 8, ["title", "onClick"])
                          ]);
                        }), 128))
                      ])) : createCommentVNode("", true)
                    ]),
                    _: 1
                  }, 8, ["onClosed"]),
                  createVNode(unref(Link), {
                    href: "/playlists/create",
                    class: "btn-secondary"
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Create a playlist")), 1)
                    ]),
                    _: 1
                  })
                ])
              ])
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (!__props.room.playlists.length) {
              _push2(ssrRenderComponent(Tip, null, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("The room must be associated with one or more playlists to work."))}<br${_scopeId2}> ${ssrInterpolate(_ctx.__("It is possible to use the official Blinest playlists or to create your own playlists."))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("The room must be associated with one or more playlists to work.")), 1),
                      createVNode("br"),
                      createTextVNode(" " + toDisplayString(_ctx.__("It is possible to use the official Blinest playlists or to create your own playlists.")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            if (__props.room.playlists && __props.room.playlists.length) {
              _push2(`<ul${_scopeId}><!--[-->`);
              ssrRenderList(__props.room.playlists, (playlist) => {
                _push2(`<li class="flex items-center rounded p-3"${_scopeId}>${ssrInterpolate(playlist.name)} <button class="ml-auto flex items-center text-red-500"${ssrRenderAttr("title", _ctx.__("Detach"))}${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-6 w-6" viewBox="0 0 24 24"${_scopeId}><g${_scopeId}><path fill="currentColor" d="M17.657 14.828l-1.414-1.414L17.657 12A4 4 0 1 0 12 6.343l-1.414 1.414-1.414-1.414 1.414-1.414a6 6 0 0 1 8.485 8.485l-1.414 1.414zm-2.829 2.829l-1.414 1.414a6 6 0 1 1-8.485-8.485l1.414-1.414 1.414 1.414L6.343 12A4 4 0 1 0 12 17.657l1.414-1.414 1.414 1.414zm0-9.9l1.415 1.415-7.071 7.07-1.415-1.414 7.071-7.07zM5.775 2.293l1.932-.518L8.742 5.64l-1.931.518-1.036-3.864zm9.483 16.068l1.931-.518 1.036 3.864-1.932.518-1.035-3.864zM2.293 5.775l3.864 1.036-.518 1.931-3.864-1.035.518-1.932zm16.068 9.483l3.864 1.035-.518 1.932-3.864-1.036.518-1.931z"${_scopeId}></path></g></svg> ${ssrInterpolate(_ctx.__("Detach"))}</button></li>`);
              });
              _push2(`<!--]--></ul>`);
            } else {
              _push2(`<p class="my-2 text-sm text-neutral-400"${_scopeId}>${ssrInterpolate(_ctx.__("No playlist"))}</p>`);
            }
          } else {
            return [
              !__props.room.playlists.length ? (openBlock(), createBlock(Tip, { key: 0 }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("The room must be associated with one or more playlists to work.")), 1),
                  createVNode("br"),
                  createTextVNode(" " + toDisplayString(_ctx.__("It is possible to use the official Blinest playlists or to create your own playlists.")), 1)
                ]),
                _: 1
              })) : createCommentVNode("", true),
              __props.room.playlists && __props.room.playlists.length ? (openBlock(), createBlock("ul", { key: 1 }, [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.room.playlists, (playlist) => {
                  return openBlock(), createBlock("li", {
                    key: playlist.id,
                    class: "flex items-center rounded p-3"
                  }, [
                    createTextVNode(toDisplayString(playlist.name) + " ", 1),
                    createVNode("button", {
                      class: "ml-auto flex items-center text-red-500",
                      title: _ctx.__("Detach"),
                      onClick: ($event) => detach(playlist)
                    }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "mr-1 h-6 w-6",
                        viewBox: "0 0 24 24"
                      }, [
                        createVNode("g", null, [
                          createVNode("path", {
                            fill: "currentColor",
                            d: "M17.657 14.828l-1.414-1.414L17.657 12A4 4 0 1 0 12 6.343l-1.414 1.414-1.414-1.414 1.414-1.414a6 6 0 0 1 8.485 8.485l-1.414 1.414zm-2.829 2.829l-1.414 1.414a6 6 0 1 1-8.485-8.485l1.414-1.414 1.414 1.414L6.343 12A4 4 0 1 0 12 17.657l1.414-1.414 1.414 1.414zm0-9.9l1.415 1.415-7.071 7.07-1.415-1.414 7.071-7.07zM5.775 2.293l1.932-.518L8.742 5.64l-1.931.518-1.036-3.864zm9.483 16.068l1.931-.518 1.036 3.864-1.932.518-1.035-3.864zM2.293 5.775l3.864 1.036-.518 1.931-3.864-1.035.518-1.932zm16.068 9.483l3.864 1.035-.518 1.932-3.864-1.036.518-1.931z"
                          })
                        ])
                      ])),
                      createTextVNode(" " + toDisplayString(_ctx.__("Detach")), 1)
                    ], 8, ["title", "onClick"])
                  ]);
                }), 128))
              ])) : (openBlock(), createBlock("p", {
                key: 2,
                class: "my-2 text-sm text-neutral-400"
              }, toDisplayString(_ctx.__("No playlist")), 1))
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Rooms/PlaylistsManager.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main$1 as _,
  _sfc_main as a
};
