import { mergeProps, withCtx, createVNode, toDisplayString, createTextVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
import { C as Card } from "./Card-ee13c838.mjs";
import { S as SocialIcon } from "./SocialIcon-bb2fc3a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "Socialite",
  __ssrInlineRender: true,
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, mergeProps({ class: "w-full px-4 lg:w-auto" }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex h-full flex-col justify-center"${_scopeId}><small class="mb-2 text-center"${_scopeId}>${ssrInterpolate(_ctx.__("Login with"))}</small><a${ssrRenderAttr("href", _ctx.route("auth.redirect", "google"))} class="btn-secondary my-2"${_scopeId}>`);
            _push2(ssrRenderComponent(SocialIcon, {
              name: "google",
              class: "mr-2 h-6 w-6"
            }, null, _parent2, _scopeId));
            _push2(` Google </a><a${ssrRenderAttr("href", _ctx.route("auth.redirect", "discord"))} class="btn-secondary my-2"${_scopeId}>`);
            _push2(ssrRenderComponent(SocialIcon, {
              name: "discord",
              class: "mr-2 h-6 w-6"
            }, null, _parent2, _scopeId));
            _push2(`<span class="inline"${_scopeId}>Discord</span></a><a${ssrRenderAttr("href", _ctx.route("auth.redirect", "deezer"))} class="btn-secondary my-2"${_scopeId}>`);
            _push2(ssrRenderComponent(SocialIcon, {
              name: "deezer",
              class: "mr-2 h-6 w-6"
            }, null, _parent2, _scopeId));
            _push2(` Deezer </a><a${ssrRenderAttr("href", _ctx.route("auth.redirect", "spotify"))} class="btn-secondary my-2"${_scopeId}>`);
            _push2(ssrRenderComponent(SocialIcon, {
              name: "spotify",
              class: "mr-2 h-6 w-6"
            }, null, _parent2, _scopeId));
            _push2(` Spotify </a><a${ssrRenderAttr("href", _ctx.route("auth.redirect", "facebook"))} class="btn-secondary my-2"${_scopeId}>`);
            _push2(ssrRenderComponent(SocialIcon, {
              name: "facebook",
              class: "mr-2 h-6 w-6"
            }, null, _parent2, _scopeId));
            _push2(` Facebook </a></div>`);
          } else {
            return [
              createVNode("div", { class: "flex h-full flex-col justify-center" }, [
                createVNode("small", { class: "mb-2 text-center" }, toDisplayString(_ctx.__("Login with")), 1),
                createVNode("a", {
                  href: _ctx.route("auth.redirect", "google"),
                  class: "btn-secondary my-2"
                }, [
                  createVNode(SocialIcon, {
                    name: "google",
                    class: "mr-2 h-6 w-6"
                  }),
                  createTextVNode(" Google ")
                ], 8, ["href"]),
                createVNode("a", {
                  href: _ctx.route("auth.redirect", "discord"),
                  class: "btn-secondary my-2"
                }, [
                  createVNode(SocialIcon, {
                    name: "discord",
                    class: "mr-2 h-6 w-6"
                  }),
                  createVNode("span", { class: "inline" }, "Discord")
                ], 8, ["href"]),
                createVNode("a", {
                  href: _ctx.route("auth.redirect", "deezer"),
                  class: "btn-secondary my-2"
                }, [
                  createVNode(SocialIcon, {
                    name: "deezer",
                    class: "mr-2 h-6 w-6"
                  }),
                  createTextVNode(" Deezer ")
                ], 8, ["href"]),
                createVNode("a", {
                  href: _ctx.route("auth.redirect", "spotify"),
                  class: "btn-secondary my-2"
                }, [
                  createVNode(SocialIcon, {
                    name: "spotify",
                    class: "mr-2 h-6 w-6"
                  }),
                  createTextVNode(" Spotify ")
                ], 8, ["href"]),
                createVNode("a", {
                  href: _ctx.route("auth.redirect", "facebook"),
                  class: "btn-secondary my-2"
                }, [
                  createVNode(SocialIcon, {
                    name: "facebook",
                    class: "mr-2 h-6 w-6"
                  }),
                  createTextVNode(" Facebook ")
                ], 8, ["href"])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/Socialite.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
