import { unref, withCtx, createVNode, toDisplayString, createTextVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import "./TextareaInput-989d56d6.mjs";
import "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-d136d2f8.mjs";
import "./TextInput-cddc091b.mjs";
import "uuid";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
const _sfc_main = {
  __name: "Sent",
  __ssrInlineRender: true,
  setup(__props) {
    useForm({
      message: ""
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: _ctx.__("Contact")
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="mx-auto flex w-full max-w-2xl flex-col"${_scopeId}>`);
            _push2(ssrRenderComponent(Card, { class: "mb-4" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h3 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Message sent"))}!</h3>`);
                } else {
                  return [
                    createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Message sent")) + "!", 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(unref(Link), {
                    class: "btn-primary",
                    href: _ctx.route("home")
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Go back to home"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Go back to home")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(unref(Link), {
                      class: "btn-primary",
                      href: _ctx.route("home")
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Go back to home")), 1)
                      ]),
                      _: 1
                    }, 8, ["href"])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<p class="mb-2"${_scopeId2}>Merci, le message a bien été envoyé.</p>`);
                } else {
                  return [
                    createVNode("p", { class: "mb-2" }, "Merci, le message a bien été envoyé.")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "mx-auto flex w-full max-w-2xl flex-col" }, [
                createVNode(Card, { class: "mb-4" }, {
                  header: withCtx(() => [
                    createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Message sent")) + "!", 1)
                  ]),
                  footer: withCtx(() => [
                    createVNode(unref(Link), {
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
                    createVNode("p", { class: "mb-2" }, "Merci, le message a bien été envoyé.")
                  ]),
                  _: 1
                })
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Contact/Sent.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
