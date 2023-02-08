import { c as createElementBlock, a as createVNode, w as withCtx, u as unref, F as Fragment, o as openBlock, b as createBaseVNode, t as toDisplayString, X } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { C as Card } from "./Card-7ef4ce68.js";
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
const _hoisted_1 = /* @__PURE__ */ createBaseVNode("meta", {
  name: "description",
  content: "Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."
}, null, -1);
const _hoisted_2 = { class: "flex w-full items-center justify-between" };
const _hoisted_3 = { class: "my-4 text-xl" };
const _hoisted_4 = { class: "text-xs text-neutral-500" };
const _hoisted_5 = ["innerHTML"];
const _sfc_main = {
  __name: "Show",
  props: {
    page: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), null, {
          default: withCtx(() => [
            createBaseVNode("title", null, toDisplayString(__props.page.title), 1),
            _hoisted_1
          ]),
          _: 1
        }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createVNode(Card, { class: "mx-auto" }, {
              header: withCtx(() => [
                createBaseVNode("div", _hoisted_2, [
                  createBaseVNode("h1", _hoisted_3, toDisplayString(__props.page.title), 1),
                  createBaseVNode("small", _hoisted_4, toDisplayString(_ctx.__("Last revision")) + " : " + toDisplayString(__props.page.date), 1)
                ])
              ]),
              default: withCtx(() => [
                createBaseVNode("section", {
                  class: "prose prose-invert",
                  innerHTML: __props.page.content
                }, null, 8, _hoisted_5)
              ]),
              _: 1
            })
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
