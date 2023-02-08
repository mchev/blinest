import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, n as ne, d as createTextVNode } from "./app-910e457d.js";
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
const _hoisted_1 = { class: "mx-auto flex w-full max-w-2xl flex-col" };
const _hoisted_2 = { class: "text-xl font-bold" };
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("p", { class: "mb-2" }, "Merci, le message a bien été envoyé.", -1);
const _sfc_main = {
  __name: "Sent",
  setup(__props) {
    P({
      message: ""
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Contact")
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              createVNode(Card, { class: "mb-4" }, {
                header: withCtx(() => [
                  createBaseVNode("h3", _hoisted_2, toDisplayString(_ctx.__("Message sent")) + "!", 1)
                ]),
                footer: withCtx(() => [
                  createVNode(unref(ne), {
                    class: "btn-primary",
                    href: _ctx.route("home")
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Go back to home")), 1)
                    ]),
                    _: 1
                  }, 8, ["href"])
                ]),
                default: withCtx(() => [
                  _hoisted_3
                ]),
                _: 1
              })
            ])
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
