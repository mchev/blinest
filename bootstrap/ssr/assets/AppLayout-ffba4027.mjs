import { mergeProps, unref, withCtx, createTextVNode, useSSRContext, ref, watch, createVNode } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderSlot } from "vue/server-renderer";
import { Link, Head } from "@inertiajs/vue3";
import { F as FlashMessages } from "./LanguageSwitcher-18bdae21.mjs";
import { _ as _sfc_main$2 } from "./Navbar-d136d2f8.mjs";
const _sfc_main$1 = {
  __name: "Footer",
  __ssrInlineRender: true,
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "md:flex md:flex-shrink-0 mt-8 border-t border-neutral-500 text-neutral-500 text-sm" }, _attrs))}><div class="flex w-full items-center justify-between py-2 md:flex-shrink-0"> Blinest <ul class="flex gap-4 flex-col md:flex-row"><li>`);
      _push(ssrRenderComponent(unref(Link), { href: "/contact" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`Contact`);
          } else {
            return [
              createTextVNode("Contact")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</li><li><a target="_blank" href="https://github.com/mchev/blinest/issues/new">${ssrInterpolate(_ctx.__("Report a bug"))}</a></li><li>`);
      _push(ssrRenderComponent(unref(Link), {
        href: _ctx.route("pages.show", "mentions-legales")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`Mentions légales`);
          } else {
            return [
              createTextVNode("Mentions légales")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</li><li>`);
      _push(ssrRenderComponent(unref(Link), {
        href: _ctx.route("pages.show", "politique-de-confidentialite")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`Politique de confidentialité`);
          } else {
            return [
              createTextVNode("Politique de confidentialité")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</li><li><a target="_blank" href="https://donate.stripe.com/00g2bvf8i08X8De6oo">Faire un don</a></li></ul></div></div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Footer.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "AppLayout",
  __ssrInlineRender: true,
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<meta itemprop="url" content="https://blinest.com"${_scopeId}><meta property="og:url" content="https://blinest.com"${_scopeId}><meta property="og:type" content="website"${_scopeId}><meta property="og:title" content="Blinest - Quiz musicaux gratuits et multijoueurs"${_scopeId}><meta property="og:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."${_scopeId}><meta property="og:image" content="https://blinest.com/images/statics/screenshot.png"${_scopeId}><meta name="twitter:card" content="summary_large_image"${_scopeId}><meta property="twitter:domain" content="blinest.com"${_scopeId}><meta property="twitter:url" content="https://blinest.com"${_scopeId}><meta name="twitter:title" content="Blinest - Quiz musicaux gratuits et multijoueurs"${_scopeId}><meta name="twitter:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."${_scopeId}><meta name="twitter:image" content="https://blinest.com/images/statics/screenshot.png"${_scopeId}>`);
          } else {
            return [
              createVNode("meta", {
                itemprop: "url",
                content: "https://blinest.com"
              }),
              createVNode("meta", {
                property: "og:url",
                content: "https://blinest.com"
              }),
              createVNode("meta", {
                property: "og:type",
                content: "website"
              }),
              createVNode("meta", {
                property: "og:title",
                content: "Blinest - Quiz musicaux gratuits et multijoueurs"
              }),
              createVNode("meta", {
                property: "og:description",
                content: "Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."
              }),
              createVNode("meta", {
                property: "og:image",
                content: "https://blinest.com/images/statics/screenshot.png"
              }),
              createVNode("meta", {
                name: "twitter:card",
                content: "summary_large_image"
              }),
              createVNode("meta", {
                property: "twitter:domain",
                content: "blinest.com"
              }),
              createVNode("meta", {
                property: "twitter:url",
                content: "https://blinest.com"
              }),
              createVNode("meta", {
                name: "twitter:title",
                content: "Blinest - Quiz musicaux gratuits et multijoueurs"
              }),
              createVNode("meta", {
                name: "twitter:description",
                content: "Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."
              }),
              createVNode("meta", {
                name: "twitter:image",
                content: "https://blinest.com/images/statics/screenshot.png"
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<div class="text-neutral-200" itemscope itemtype="https://schema.org/WebSite"><div id="dropdown"></div><div class="md:flex md:flex-col"><div class="md:flex md:h-screen md:flex-col">`);
      _push(ssrRenderComponent(_sfc_main$2, null, null, _parent));
      _push(`<div class="md:flex md:flex-grow md:overflow-hidden">`);
      if (_ctx.$slots.default) {
        _push(`<div class="flex flex-col justify-between px-4 py-4 md:flex-1 md:overflow-y-auto md:px-12 md:py-4" scroll-region>`);
        _push(ssrRenderComponent(FlashMessages, null, null, _parent));
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
        _push(ssrRenderComponent(_sfc_main$1, null, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></div></div><!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppLayout.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
