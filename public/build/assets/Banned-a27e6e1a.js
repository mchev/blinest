import { c as createElementBlock, a as createVNode, w as withCtx, u as unref, F as Fragment, o as openBlock, X, b as createBaseVNode, d as createTextVNode, t as toDisplayString } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { T as Tip } from "./Tip-da87eaf5.js";
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
const _hoisted_1 = /* @__PURE__ */ createBaseVNode("title", null, "Vous avez été banni !", -1);
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { class: "flex w-full items-center justify-between" }, [
  /* @__PURE__ */ createBaseVNode("h1", { class: "my-4 text-xl text-red-500" }, "Vous avez été banni de blinest.com !")
], -1);
const _hoisted_3 = { class: "prose prose-invert" };
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("b", null, "Raison du ban :", -1);
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_6 = /* @__PURE__ */ createBaseVNode("b", null, "L'expulsion du site se termine :", -1);
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _sfc_main = {
  __name: "Banned",
  props: {
    ban: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), null, {
          default: withCtx(() => [
            _hoisted_1
          ]),
          _: 1
        }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createVNode(Card, { class: "mx-auto" }, {
              header: withCtx(() => [
                _hoisted_2
              ]),
              default: withCtx(() => [
                createBaseVNode("div", _hoisted_3, [
                  createBaseVNode("p", null, [
                    _hoisted_4,
                    _hoisted_5,
                    createTextVNode(toDisplayString(__props.ban.comment), 1)
                  ]),
                  createBaseVNode("p", null, [
                    _hoisted_6,
                    createTextVNode(),
                    _hoisted_7,
                    createTextVNode(toDisplayString(__props.ban.expired_at), 1)
                  ]),
                  createVNode(Tip, { class: "text-orange-500" }, {
                    default: withCtx(() => [
                      createTextVNode("Dans certains cas, un signalement pourra être effectué auprès des autorités compétentes.")
                    ]),
                    _: 1
                  }),
                  createVNode(Tip, { class: "text-orange-500" }, {
                    default: withCtx(() => [
                      createTextVNode("Il est toujours possible d'accèder à la gestion de votre compte afin de respecter les règles sur la protection des données mais en cas de signalement nous nous reservons le droit de conserver les éléments de preuve comme le prévoit la loi.")
                    ]),
                    _: 1
                  })
                ])
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
