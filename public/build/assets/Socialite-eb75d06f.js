import { C as Card } from "./Card-7ef4ce68.js";
import { S as SocialIcon } from "./SocialIcon-5ed77127.js";
import { h as createBlock, w as withCtx, o as openBlock, b as createBaseVNode, t as toDisplayString, a as createVNode, d as createTextVNode } from "./app-910e457d.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "flex h-full flex-col justify-center" };
const _hoisted_2 = { class: "mb-2 text-center" };
const _hoisted_3 = ["href"];
const _hoisted_4 = ["href"];
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("span", { class: "inline" }, "Discord", -1);
const _hoisted_6 = ["href"];
const _hoisted_7 = ["href"];
const _hoisted_8 = ["href"];
const _sfc_main = {
  __name: "Socialite",
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, { class: "w-full px-4 lg:w-auto" }, {
        default: withCtx(() => [
          createBaseVNode("div", _hoisted_1, [
            createBaseVNode("small", _hoisted_2, toDisplayString(_ctx.__("Login with")), 1),
            createBaseVNode("a", {
              href: _ctx.route("auth.redirect", "google"),
              class: "btn-secondary my-2"
            }, [
              createVNode(SocialIcon, {
                name: "google",
                class: "mr-2 h-6 w-6"
              }),
              createTextVNode(" Google ")
            ], 8, _hoisted_3),
            createBaseVNode("a", {
              href: _ctx.route("auth.redirect", "discord"),
              class: "btn-secondary my-2"
            }, [
              createVNode(SocialIcon, {
                name: "discord",
                class: "mr-2 h-6 w-6"
              }),
              _hoisted_5
            ], 8, _hoisted_4),
            createBaseVNode("a", {
              href: _ctx.route("auth.redirect", "deezer"),
              class: "btn-secondary my-2"
            }, [
              createVNode(SocialIcon, {
                name: "deezer",
                class: "mr-2 h-6 w-6"
              }),
              createTextVNode(" Deezer ")
            ], 8, _hoisted_6),
            createBaseVNode("a", {
              href: _ctx.route("auth.redirect", "spotify"),
              class: "btn-secondary my-2"
            }, [
              createVNode(SocialIcon, {
                name: "spotify",
                class: "mr-2 h-6 w-6"
              }),
              createTextVNode(" Spotify ")
            ], 8, _hoisted_7),
            createBaseVNode("a", {
              href: _ctx.route("auth.redirect", "facebook"),
              class: "btn-secondary my-2"
            }, [
              createVNode(SocialIcon, {
                name: "facebook",
                class: "mr-2 h-6 w-6"
              }),
              createTextVNode(" Facebook ")
            ], 8, _hoisted_8)
          ])
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
