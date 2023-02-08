import { C as Card } from "./Card-7ef4ce68.js";
import { c as createElementBlock, a as createVNode, w as withCtx, o as openBlock, d as createTextVNode, t as toDisplayString, b as createBaseVNode } from "./app-910e457d.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "mb-4 flex w-full flex-wrap justify-between gap-4 text-center text-xl" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("h3", { class: "mx-auto text-lg uppercase" }, "Utilisateurs", -1);
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("h3", { class: "mx-auto text-lg uppercase" }, "Playlists publiques", -1);
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("h3", { class: "mx-auto text-lg uppercase" }, "Rooms publiques", -1);
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("h3", { class: "mx-auto text-lg uppercase" }, "Utilisateurs bannis", -1);
const _sfc_main = {
  __name: "Stats",
  props: {
    stats: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        createVNode(Card, { class: "flex-grow" }, {
          header: withCtx(() => [
            _hoisted_2
          ]),
          default: withCtx(() => [
            createTextVNode(" " + toDisplayString(__props.stats.users_count), 1)
          ]),
          _: 1
        }),
        createVNode(Card, { class: "flex-grow" }, {
          header: withCtx(() => [
            _hoisted_3
          ]),
          default: withCtx(() => [
            createTextVNode(" " + toDisplayString(__props.stats.public_playlists_count) + " / " + toDisplayString(__props.stats.playlists_count), 1)
          ]),
          _: 1
        }),
        createVNode(Card, { class: "flex-grow" }, {
          header: withCtx(() => [
            _hoisted_4
          ]),
          default: withCtx(() => [
            createTextVNode(" " + toDisplayString(__props.stats.public_rooms_count) + " / " + toDisplayString(__props.stats.rooms_count), 1)
          ]),
          _: 1
        }),
        createVNode(Card, { class: "flex-grow" }, {
          header: withCtx(() => [
            _hoisted_5
          ]),
          default: withCtx(() => [
            createTextVNode(" " + toDisplayString(__props.stats.banned_users_count), 1)
          ]),
          _: 1
        })
      ]);
    };
  }
};
export {
  _sfc_main as default
};
