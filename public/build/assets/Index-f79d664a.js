import { c as createElementBlock, a as createVNode, w as withCtx, u as unref, F as Fragment, o as openBlock, b as createBaseVNode, t as toDisplayString, X, v as renderList, h as createBlock, g as createCommentVNode } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import _sfc_main$2 from "./Room-507fdf3a.js";
import _sfc_main$3 from "./Rooms-be646e91.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./TextInput-541fe8b5.js";
import "./v4-e7604ebc.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
import "./Top-c52c3cf6.js";
const _hoisted_1 = /* @__PURE__ */ createBaseVNode("meta", {
  name: "description",
  content: "Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."
}, null, -1);
const _hoisted_2 = { key: 0 };
const _hoisted_3 = { class: "grid grid-cols-1 gap-4 md:grid-cols-4 lg:grid-cols-6" };
const _hoisted_4 = { key: 1 };
const _hoisted_5 = { key: 0 };
const _hoisted_6 = { class: "flex flex-wrap items-center" };
const _hoisted_7 = {
  key: 0,
  class: "relative mb-4 flex-grow"
};
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("h2", { class: "mb-1 text-xl text-neutral-400 lg:text-2xl" }, "TOP 5", -1);
const _hoisted_9 = {
  key: 0,
  class: "relative mb-4"
};
const _hoisted_10 = { class: "mb-1 text-xl text-neutral-400 lg:text-2xl" };
const _hoisted_11 = { key: 2 };
const _hoisted_12 = { class: "relative" };
const _hoisted_13 = { class: "mb-1 text-xl text-neutral-400 lg:text-2xl" };
const _hoisted_14 = { key: 3 };
const _hoisted_15 = { class: "relative" };
const _hoisted_16 = { class: "mb-1 text-xl text-neutral-400 lg:text-2xl" };
const _sfc_main = {
  __name: "Index",
  props: {
    filters: Object,
    categories: Object,
    private_rooms: Object,
    user_rooms: Object,
    top_rooms: Array,
    search_result: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), null, {
          default: withCtx(() => [
            createBaseVNode("title", null, toDisplayString(_ctx.__("Free multiplayer music quizzes")), 1),
            _hoisted_1
          ]),
          _: 1
        }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            __props.search_result ? (openBlock(), createElementBlock("section", _hoisted_2, [
              createBaseVNode("div", _hoisted_3, [
                (openBlock(true), createElementBlock(Fragment, null, renderList(__props.search_result, (room) => {
                  return openBlock(), createBlock(_sfc_main$2, {
                    room,
                    key: room.id
                  }, null, 8, ["room"]);
                }), 128))
              ])
            ])) : (openBlock(), createElementBlock("div", _hoisted_4, [
              !__props.filters.search ? (openBlock(), createElementBlock("section", _hoisted_5, [
                createBaseVNode("div", _hoisted_6, [
                  __props.top_rooms ? (openBlock(), createElementBlock("div", _hoisted_7, [
                    _hoisted_8,
                    createVNode(_sfc_main$3, {
                      rooms: __props.top_rooms,
                      is_top_5: true
                    }, null, 8, ["rooms"])
                  ])) : createCommentVNode("", true)
                ])
              ])) : createCommentVNode("", true),
              __props.categories.length ? (openBlock(true), createElementBlock(Fragment, { key: 1 }, renderList(__props.categories, (category) => {
                return openBlock(), createElementBlock("section", {
                  key: category.id
                }, [
                  category.rooms.length ? (openBlock(), createElementBlock("div", _hoisted_9, [
                    createBaseVNode("h2", _hoisted_10, toDisplayString(category.name), 1),
                    createVNode(_sfc_main$3, {
                      rooms: category.rooms,
                      id: category.id
                    }, null, 8, ["rooms", "id"])
                  ])) : createCommentVNode("", true)
                ]);
              }), 128)) : createCommentVNode("", true),
              __props.private_rooms && __props.private_rooms.length ? (openBlock(), createElementBlock("section", _hoisted_11, [
                createBaseVNode("div", _hoisted_12, [
                  createBaseVNode("h2", _hoisted_13, toDisplayString(_ctx.__("Private rooms")), 1),
                  createVNode(_sfc_main$3, {
                    rooms: __props.private_rooms,
                    id: "private"
                  }, null, 8, ["rooms"])
                ])
              ])) : createCommentVNode("", true),
              __props.user_rooms && __props.user_rooms.length ? (openBlock(), createElementBlock("section", _hoisted_14, [
                createBaseVNode("div", _hoisted_15, [
                  createBaseVNode("h2", _hoisted_16, toDisplayString(_ctx.__("My rooms")), 1),
                  createVNode(_sfc_main$3, {
                    rooms: __props.user_rooms,
                    id: "private"
                  }, null, 8, ["rooms"])
                ])
              ])) : createCommentVNode("", true)
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
