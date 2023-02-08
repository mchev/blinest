import { o as openBlock, c as createElementBlock, b as createBaseVNode, d as createTextVNode, a as createVNode, w as withCtx, u as unref, n as ne, t as toDisplayString, k as ref, l as watch, X, r as renderSlot, g as createCommentVNode, R as Transition, F as Fragment } from "./app-910e457d.js";
import { F as FlashMessages } from "./LanguageSwitcher-da420d03.js";
import { _ as _sfc_main$2 } from "./Navbar-cadf2428.js";
const _hoisted_1$1 = { class: "mt-8 border-t border-neutral-500 text-sm text-neutral-500 md:flex md:flex-shrink-0" };
const _hoisted_2$1 = { class: "flex w-full items-center justify-between py-2 md:flex-shrink-0" };
const _hoisted_3$1 = { class: "flex flex-col gap-4 md:flex-row" };
const _hoisted_4$1 = {
  target: "_blank",
  href: "https://github.com/mchev/blinest/issues/new"
};
const _hoisted_5$1 = /* @__PURE__ */ createBaseVNode("li", null, [
  /* @__PURE__ */ createBaseVNode("a", {
    target: "_blank",
    href: "https://donate.stripe.com/00g2bvf8i08X8De6oo"
  }, "Faire un don")
], -1);
const _sfc_main$1 = {
  __name: "Footer",
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1$1, [
        createBaseVNode("div", _hoisted_2$1, [
          createTextVNode(" Blinest "),
          createBaseVNode("ul", _hoisted_3$1, [
            createBaseVNode("li", null, [
              createVNode(unref(ne), { href: "/contact" }, {
                default: withCtx(() => [
                  createTextVNode("Contact")
                ]),
                _: 1
              })
            ]),
            createBaseVNode("li", null, [
              createBaseVNode("a", _hoisted_4$1, toDisplayString(_ctx.__("Report a bug")), 1)
            ]),
            createBaseVNode("li", null, [
              createVNode(unref(ne), {
                href: _ctx.route("pages.show", "mentions-legales")
              }, {
                default: withCtx(() => [
                  createTextVNode("Mentions légales")
                ]),
                _: 1
              }, 8, ["href"])
            ]),
            createBaseVNode("li", null, [
              createVNode(unref(ne), {
                href: _ctx.route("pages.show", "politique-de-confidentialite")
              }, {
                default: withCtx(() => [
                  createTextVNode("Politique de confidentialité")
                ]),
                _: 1
              }, 8, ["href"])
            ]),
            _hoisted_5$1
          ])
        ])
      ]);
    };
  }
};
const _hoisted_1 = /* @__PURE__ */ createBaseVNode("meta", {
  itemprop: "url",
  content: "https://blinest.com"
}, null, -1);
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "og:url",
  content: "https://blinest.com"
}, null, -1);
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "og:type",
  content: "website"
}, null, -1);
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "og:title",
  content: "Blinest - Quiz musicaux gratuits et multijoueurs"
}, null, -1);
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "og:description",
  content: "Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."
}, null, -1);
const _hoisted_6 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "og:image",
  content: "https://blinest.com/images/statics/screenshot.png"
}, null, -1);
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("meta", {
  name: "twitter:card",
  content: "summary_large_image"
}, null, -1);
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "twitter:domain",
  content: "blinest.com"
}, null, -1);
const _hoisted_9 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "twitter:url",
  content: "https://blinest.com"
}, null, -1);
const _hoisted_10 = /* @__PURE__ */ createBaseVNode("meta", {
  name: "twitter:title",
  content: "Blinest - Quiz musicaux gratuits et multijoueurs"
}, null, -1);
const _hoisted_11 = /* @__PURE__ */ createBaseVNode("meta", {
  name: "twitter:description",
  content: "Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."
}, null, -1);
const _hoisted_12 = /* @__PURE__ */ createBaseVNode("meta", {
  name: "twitter:image",
  content: "https://blinest.com/images/statics/screenshot.png"
}, null, -1);
const _hoisted_13 = { class: "text-neutral-200" };
const _hoisted_14 = /* @__PURE__ */ createBaseVNode("div", { id: "dropdown" }, null, -1);
const _hoisted_15 = { class: "md:flex md:flex-col" };
const _hoisted_16 = { class: "md:flex md:h-screen md:flex-col" };
const _hoisted_17 = { class: "md:flex md:flex-grow md:overflow-hidden" };
const _hoisted_18 = {
  key: 0,
  class: "flex flex-col justify-between px-4 py-4 md:flex-1 md:overflow-y-auto md:px-12 md:py-4",
  "scroll-region": ""
};
const _sfc_main = {
  __name: "AppLayout",
  props: {
    session: Object
  },
  setup(__props) {
    const props = __props;
    const authModalIsOpen = ref(false);
    watch(
      () => props.session,
      (session) => {
        authModalIsOpen.value = !!(sessions == null ? void 0 : sessions.requireAuth);
      }
    );
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), null, {
          default: withCtx(() => [
            _hoisted_1,
            _hoisted_2,
            _hoisted_3,
            _hoisted_4,
            _hoisted_5,
            _hoisted_6,
            _hoisted_7,
            _hoisted_8,
            _hoisted_9,
            _hoisted_10,
            _hoisted_11,
            _hoisted_12
          ]),
          _: 1
        }),
        createBaseVNode("div", _hoisted_13, [
          _hoisted_14,
          createBaseVNode("div", _hoisted_15, [
            createBaseVNode("div", _hoisted_16, [
              createVNode(_sfc_main$2),
              createBaseVNode("div", _hoisted_17, [
                createVNode(Transition, {
                  name: "slide-right",
                  appear: ""
                }, {
                  default: withCtx(() => [
                    _ctx.$slots.default ? (openBlock(), createElementBlock("div", _hoisted_18, [
                      createVNode(FlashMessages),
                      renderSlot(_ctx.$slots, "default"),
                      createVNode(_sfc_main$1)
                    ])) : createCommentVNode("", true)
                  ]),
                  _: 3
                })
              ])
            ])
          ])
        ])
      ], 64);
    };
  }
};
export {
  _sfc_main as _
};
