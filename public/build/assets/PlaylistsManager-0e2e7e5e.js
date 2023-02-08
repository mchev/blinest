import { k as ref, P, l as watch, o as openBlock, h as createBlock, w as withCtx, b as createBaseVNode, t as toDisplayString, a as createVNode, c as createElementBlock, F as Fragment, v as renderList, g as createCommentVNode, d as createTextVNode, u as unref, n as ne } from "./app-910e457d.js";
import { _ as _sfc_main$3 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$2 } from "./Dropdown-f0e2d937.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { d as debounce_1 } from "./debounce-89ee665e.js";
import { T as Tip } from "./Tip-da87eaf5.js";
const _hoisted_1$1 = { class: "text-xl font-bold" };
const _hoisted_2$1 = {
  key: 0,
  class: "max-w-50 max-h-80 overflow-y-auto"
};
const _hoisted_3$1 = ["src"];
const _hoisted_4$1 = { class: "mr-4" };
const _hoisted_5$1 = ["title", "onClick"];
const _hoisted_6$1 = { key: 0 };
const _hoisted_7$1 = ["src"];
const _hoisted_8$1 = ["title", "onClick"];
const _hoisted_9$1 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "ml-2 h-6 w-6",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
  })
], -1);
const _hoisted_10$1 = {
  key: 1,
  class: "my-2 text-sm text-neutral-400"
};
const _sfc_main$1 = {
  __name: "ModeratorsManager",
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const search = ref("");
    const searching = ref(false);
    const users = ref(null);
    const form = P({
      user_id: null
    });
    watch(
      search,
      debounce_1(() => {
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        header: withCtx(() => [
          createBaseVNode("h3", _hoisted_1$1, toDisplayString(_ctx.__("Moderators")), 1)
        ]),
        default: withCtx(() => [
          createVNode(_sfc_main$2, {
            placement: "bottom-start",
            class: "mb-2 pb-2",
            onClosed: _cache[1] || (_cache[1] = ($event) => search.value = "")
          }, {
            default: withCtx(() => [
              createVNode(_sfc_main$3, {
                modelValue: search.value,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => search.value = $event),
                "prepend-icon": "search",
                "append-icon": "cheveron-down",
                loading: searching.value,
                placeholder: _ctx.__("Add a moderator")
              }, null, 8, ["modelValue", "loading", "placeholder"])
            ]),
            dropdown: withCtx(() => [
              users.value && users.value.data.length ? (openBlock(), createElementBlock("ul", _hoisted_2$1, [
                (openBlock(true), createElementBlock(Fragment, null, renderList(users.value.data, (user) => {
                  return openBlock(), createElementBlock("li", {
                    key: user.id,
                    class: "flex items-center p-2"
                  }, [
                    user.photo ? (openBlock(), createElementBlock("img", {
                      key: 0,
                      class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                      src: user.photo
                    }, null, 8, _hoisted_3$1)) : createCommentVNode("", true),
                    createBaseVNode("span", _hoisted_4$1, toDisplayString(user.name), 1),
                    createBaseVNode("button", {
                      class: "ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                      title: _ctx.__("Add"),
                      onClick: ($event) => attach(user)
                    }, toDisplayString(_ctx.__("Add")), 9, _hoisted_5$1)
                  ]);
                }), 128))
              ])) : createCommentVNode("", true)
            ]),
            _: 1
          }),
          __props.room.moderators && __props.room.moderators.length ? (openBlock(), createElementBlock("ul", _hoisted_6$1, [
            (openBlock(true), createElementBlock(Fragment, null, renderList(__props.room.moderators, (moderator) => {
              return openBlock(), createElementBlock("li", {
                key: moderator.id,
                class: "flex items-center rounded p-3"
              }, [
                moderator.photo ? (openBlock(), createElementBlock("img", {
                  key: 0,
                  class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                  src: moderator.photo
                }, null, 8, _hoisted_7$1)) : createCommentVNode("", true),
                createTextVNode(" " + toDisplayString(moderator.name) + " ", 1),
                createBaseVNode("button", {
                  class: "ml-auto flex text-red-500",
                  title: _ctx.__("Remove"),
                  onClick: ($event) => detach(moderator)
                }, [
                  createTextVNode(toDisplayString(_ctx.__("Remove")) + " ", 1),
                  _hoisted_9$1
                ], 8, _hoisted_8$1)
              ]);
            }), 128))
          ])) : (openBlock(), createElementBlock("p", _hoisted_10$1, toDisplayString(_ctx.__("No moderator")), 1))
        ]),
        _: 1
      });
    };
  }
};
const _hoisted_1 = { class: "flex w-full items-center justify-between" };
const _hoisted_2 = { class: "text-xl font-bold" };
const _hoisted_3 = { class: "flex items-center" };
const _hoisted_4 = {
  type: "button",
  class: "btn-secondary"
};
const _hoisted_5 = {
  key: 0,
  class: "max-w-50 max-h-80 overflow-y-auto"
};
const _hoisted_6 = { class: "mr-4" };
const _hoisted_7 = ["title", "onClick"];
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "mr-1 h-6 w-6",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
  })
], -1);
const _hoisted_9 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_10 = { key: 1 };
const _hoisted_11 = ["title", "onClick"];
const _hoisted_12 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "mr-1 h-6 w-6",
  viewBox: "0 0 24 24"
}, [
  /* @__PURE__ */ createBaseVNode("g", null, [
    /* @__PURE__ */ createBaseVNode("path", {
      fill: "currentColor",
      d: "M17.657 14.828l-1.414-1.414L17.657 12A4 4 0 1 0 12 6.343l-1.414 1.414-1.414-1.414 1.414-1.414a6 6 0 0 1 8.485 8.485l-1.414 1.414zm-2.829 2.829l-1.414 1.414a6 6 0 1 1-8.485-8.485l1.414-1.414 1.414 1.414L6.343 12A4 4 0 1 0 12 17.657l1.414-1.414 1.414 1.414zm0-9.9l1.415 1.415-7.071 7.07-1.415-1.414 7.071-7.07zM5.775 2.293l1.932-.518L8.742 5.64l-1.931.518-1.036-3.864zm9.483 16.068l1.931-.518 1.036 3.864-1.932.518-1.035-3.864zM2.293 5.775l3.864 1.036-.518 1.931-3.864-1.035.518-1.932zm16.068 9.483l3.864 1.035-.518 1.932-3.864-1.036.518-1.931z"
    })
  ])
], -1);
const _hoisted_13 = {
  key: 2,
  class: "my-2 text-sm text-neutral-400"
};
const _sfc_main = {
  __name: "PlaylistsManager",
  props: {
    room: Object,
    playlists: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        header: withCtx(() => [
          createBaseVNode("div", _hoisted_1, [
            createBaseVNode("h3", _hoisted_2, toDisplayString(_ctx.__("Playlists")), 1),
            createBaseVNode("div", _hoisted_3, [
              createVNode(_sfc_main$2, {
                placement: "bottom-start",
                class: "mr-2",
                onClosed: _cache[0] || (_cache[0] = ($event) => _ctx.search = "")
              }, {
                default: withCtx(() => [
                  createBaseVNode("button", _hoisted_4, toDisplayString(_ctx.__("Attach a playlist")), 1)
                ]),
                dropdown: withCtx(() => [
                  __props.playlists && __props.playlists.length ? (openBlock(), createElementBlock("ul", _hoisted_5, [
                    (openBlock(true), createElementBlock(Fragment, null, renderList(__props.playlists, (playlist) => {
                      return openBlock(), createElementBlock("li", {
                        key: playlist.id,
                        class: "flex items-center p-2"
                      }, [
                        createBaseVNode("span", _hoisted_6, toDisplayString(playlist.name), 1),
                        createBaseVNode("button", {
                          class: "ml-auto flex items-center rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                          title: _ctx.__("Add"),
                          onClick: ($event) => attach(playlist)
                        }, [
                          _hoisted_8,
                          createTextVNode(" " + toDisplayString(_ctx.__("Attach")), 1)
                        ], 8, _hoisted_7)
                      ]);
                    }), 128))
                  ])) : createCommentVNode("", true)
                ]),
                _: 1
              }),
              createVNode(unref(ne), {
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
        ]),
        default: withCtx(() => [
          !__props.room.playlists.length ? (openBlock(), createBlock(Tip, { key: 0 }, {
            default: withCtx(() => [
              createTextVNode(toDisplayString(_ctx.__("The room must be associated with one or more playlists to work.")), 1),
              _hoisted_9,
              createTextVNode(" " + toDisplayString(_ctx.__("It is possible to use the official Blinest playlists or to create your own playlists.")), 1)
            ]),
            _: 1
          })) : createCommentVNode("", true),
          __props.room.playlists && __props.room.playlists.length ? (openBlock(), createElementBlock("ul", _hoisted_10, [
            (openBlock(true), createElementBlock(Fragment, null, renderList(__props.room.playlists, (playlist) => {
              return openBlock(), createElementBlock("li", {
                key: playlist.id,
                class: "flex items-center rounded p-3"
              }, [
                createTextVNode(toDisplayString(playlist.name) + " ", 1),
                createBaseVNode("button", {
                  class: "ml-auto flex items-center text-red-500",
                  title: _ctx.__("Detach"),
                  onClick: ($event) => detach(playlist)
                }, [
                  _hoisted_12,
                  createTextVNode(" " + toDisplayString(_ctx.__("Detach")), 1)
                ], 8, _hoisted_11)
              ]);
            }), 128))
          ])) : (openBlock(), createElementBlock("p", _hoisted_13, toDisplayString(_ctx.__("No playlist")), 1))
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main$1 as _,
  _sfc_main as a
};
