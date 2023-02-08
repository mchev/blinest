import { P, l as watch, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, t as toDisplayString, g as createCommentVNode, n as ne, d as createTextVNode, h as createBlock, b as createBaseVNode, v as renderList, f as normalizeClass, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$3 } from "./Icon-86c99edc.js";
import { _ as _sfc_main$4 } from "./Pagination-cf5f2783.js";
import { _ as _sfc_main$2 } from "./SearchFilter-74587dd6.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Navbar-cadf2428.js";
import "./TextInput-541fe8b5.js";
import "./v4-e7604ebc.js";
import "./SocialIcon-5ed77127.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
const _hoisted_1 = {
  key: 0,
  class: "mb-8 text-3xl font-bold"
};
const _hoisted_2 = {
  key: 1,
  class: "mb-6 flex items-center justify-between"
};
const _hoisted_3 = { class: "overflow-x-auto" };
const _hoisted_4 = { class: "w-full whitespace-nowrap" };
const _hoisted_5 = { class: "text-left font-bold" };
const _hoisted_6 = { class: "px-2 pb-4 pt-6" };
const _hoisted_7 = { class: "px-2 pb-4 pt-6" };
const _hoisted_8 = { class: "px-2 pb-4 pt-6" };
const _hoisted_9 = { class: "px-2 pb-4 pt-6" };
const _hoisted_10 = { class: "px-2 pb-4 pt-6" };
const _hoisted_11 = {
  class: "px-2 pb-4 pt-6",
  colspan: "2"
};
const _hoisted_12 = { class: "border-t" };
const _hoisted_13 = ["src"];
const _hoisted_14 = { class: "flex flex-col" };
const _hoisted_15 = { class: "max-w-[18rem] truncate text-gray-500" };
const _hoisted_16 = { class: "border-t" };
const _hoisted_17 = { class: "flex items-center px-2 py-4 text-sm" };
const _hoisted_18 = { class: "border-t" };
const _hoisted_19 = { class: "border-t" };
const _hoisted_20 = {
  key: 0,
  class: "flex items-center px-2 py-4 text-sm"
};
const _hoisted_21 = {
  key: 1,
  class: "text-xs text-neutral-500"
};
const _hoisted_22 = { class: "border-t" };
const _hoisted_23 = { class: "border-t" };
const _hoisted_24 = {
  key: 0,
  class: "text-xs text-neutral-500"
};
const _hoisted_25 = { class: "w-px border-t" };
const _hoisted_26 = { key: 0 };
const _hoisted_27 = {
  class: "border-t px-2 py-4",
  colspan: "6"
};
const _hoisted_28 = {
  key: 3,
  class: "mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6"
};
const _hoisted_29 = { class: "mx-auto mb-8 max-w-screen-sm lg:mb-16" };
const _hoisted_30 = { class: "mb-4 text-4xl font-extrabold tracking-tight" };
const _hoisted_31 = /* @__PURE__ */ createBaseVNode("p", { class: "text-lg" }, [
  /* @__PURE__ */ createTextVNode(`"Si à 50 ans on n'a pas de Room sur Blinest, on a raté sa vie." `),
  /* @__PURE__ */ createBaseVNode("br"),
  /* @__PURE__ */ createBaseVNode("small", { class: "text-xs italic" }, "Anonyme.")
], -1);
const _hoisted_32 = { class: "my-8 flex justify-center" };
const _sfc_main = {
  __name: "Index",
  props: {
    filters: Object,
    rooms: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
      search: props.filters.search,
      trashed: props.filters.trashed
    });
    watch(
      form,
      throttle(() => {
        Je.get("/rooms", pickBy_1(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Rooms" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            __props.rooms && __props.rooms.data.length ? (openBlock(), createElementBlock("h1", _hoisted_1, toDisplayString(_ctx.__("Rooms")), 1)) : createCommentVNode("", true),
            __props.rooms && __props.rooms.data.length ? (openBlock(), createElementBlock("div", _hoisted_2, [
              createVNode(_sfc_main$2, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).search = $event),
                class: "mr-4 w-full max-w-md",
                onReset: reset
              }, null, 8, ["modelValue"]),
              createVNode(unref(ne), {
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
                createBaseVNode("div", _hoisted_3, [
                  createBaseVNode("table", _hoisted_4, [
                    createBaseVNode("tr", _hoisted_5, [
                      createBaseVNode("th", _hoisted_6, toDisplayString(_ctx.__("Name")), 1),
                      createBaseVNode("th", _hoisted_7, toDisplayString(_ctx.__("Moderators")), 1),
                      createBaseVNode("th", _hoisted_8, toDisplayString(_ctx.__("Category")), 1),
                      createBaseVNode("th", _hoisted_9, toDisplayString(_ctx.__("Playlists")), 1),
                      createBaseVNode("th", _hoisted_10, toDisplayString(_ctx.__("Rounds played")), 1),
                      createBaseVNode("th", _hoisted_11, toDisplayString(_ctx.__("Visibility")), 1)
                    ]),
                    (openBlock(true), createElementBlock(Fragment, null, renderList(__props.rooms.data, (room) => {
                      return openBlock(), createElementBlock("tr", {
                        key: room.id
                      }, [
                        createBaseVNode("td", _hoisted_12, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-2 py-4",
                            href: _ctx.route("rooms.edit", room.id)
                          }, {
                            default: withCtx(() => [
                              room.photo ? (openBlock(), createElementBlock("img", {
                                key: 0,
                                class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                                src: room.photo
                              }, null, 8, _hoisted_13)) : createCommentVNode("", true),
                              createBaseVNode("div", _hoisted_14, [
                                createTextVNode(toDisplayString(room.name) + " ", 1),
                                createBaseVNode("small", _hoisted_15, toDisplayString(room.description), 1)
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
                        createBaseVNode("td", _hoisted_16, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-2 py-4",
                            href: _ctx.route("rooms.edit", room.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              createBaseVNode("ul", _hoisted_17, [
                                (openBlock(true), createElementBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                                  return openBlock(), createElementBlock("li", {
                                    key: moderator.id,
                                    class: "badge"
                                  }, toDisplayString(moderator.name), 1);
                                }), 128))
                              ])
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_18, [
                          room.category ? (openBlock(), createBlock(unref(ne), {
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
                        createBaseVNode("td", _hoisted_19, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-2 py-4",
                            href: _ctx.route("rooms.edit", room.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              room.playlists.length ? (openBlock(), createElementBlock("ul", _hoisted_20, [
                                (openBlock(true), createElementBlock(Fragment, null, renderList(room.playlists, (playlist) => {
                                  return openBlock(), createElementBlock("li", {
                                    key: playlist.id,
                                    class: "badge"
                                  }, toDisplayString(playlist.name), 1);
                                }), 128))
                              ])) : (openBlock(), createElementBlock("span", _hoisted_21, toDisplayString(_ctx.__("No playlist")), 1))
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_22, [
                          createVNode(unref(ne), {
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
                        createBaseVNode("td", _hoisted_23, [
                          createVNode(unref(ne), {
                            class: "flex flex-col items-start px-2 py-4",
                            href: _ctx.route("rooms.edit", room.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              createBaseVNode("span", {
                                class: normalizeClass(["badge", !room.password ? "bg-teal-600  text-neutral-100" : "bg-neutral-600"])
                              }, toDisplayString(room.password ? _ctx.__("No") : _ctx.__("Yes")), 3),
                              room.password ? (openBlock(), createElementBlock("small", _hoisted_24, toDisplayString(_ctx.__("Password protected")), 1)) : createCommentVNode("", true)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_25, [
                          createVNode(unref(ne), {
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
                    __props.rooms && __props.rooms.data.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_26, [
                      createBaseVNode("td", _hoisted_27, toDisplayString(_ctx.__("No rooms found.")), 1)
                    ])) : createCommentVNode("", true)
                  ])
                ]),
                createVNode(_sfc_main$4, {
                  links: __props.rooms.links
                }, null, 8, ["links"])
              ]),
              _: 1
            })) : (openBlock(), createElementBlock("div", _hoisted_28, [
              createBaseVNode("div", _hoisted_29, [
                createBaseVNode("h2", _hoisted_30, toDisplayString(_ctx.__("Rooms")), 1),
                _hoisted_31,
                createBaseVNode("div", _hoisted_32, [
                  createVNode(unref(ne), {
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
