import { unref, withCtx, createVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "./TextInput-cddc091b.mjs";
import "uuid";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
const _sfc_main = {
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    page: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<title${_scopeId}>${ssrInterpolate(__props.page.title)}</title><meta name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."${_scopeId}>`);
          } else {
            return [
              createVNode("title", null, toDisplayString(__props.page.title), 1),
              createVNode("meta", {
                name: "description",
                content: "Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, { class: "mx-auto" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h1 class="my-4 text-xl"${_scopeId2}>${ssrInterpolate(__props.page.title)}</h1><small class="text-xs text-neutral-500"${_scopeId2}>${ssrInterpolate(_ctx.__("Last revision"))} : ${ssrInterpolate(__props.page.date)}</small></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex w-full items-center justify-between" }, [
                      createVNode("h1", { class: "my-4 text-xl" }, toDisplayString(__props.page.title), 1),
                      createVNode("small", { class: "text-xs text-neutral-500" }, toDisplayString(_ctx.__("Last revision")) + " : " + toDisplayString(__props.page.date), 1)
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<section class="prose prose-invert"${_scopeId2}>${__props.page.content}</section>`);
                } else {
                  return [
                    createVNode("section", {
                      class: "prose prose-invert",
                      innerHTML: __props.page.content
                    }, null, 8, ["innerHTML"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, { class: "mx-auto" }, {
                header: withCtx(() => [
                  createVNode("div", { class: "flex w-full items-center justify-between" }, [
                    createVNode("h1", { class: "my-4 text-xl" }, toDisplayString(__props.page.title), 1),
                    createVNode("small", { class: "text-xs text-neutral-500" }, toDisplayString(_ctx.__("Last revision")) + " : " + toDisplayString(__props.page.date), 1)
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("section", {
                    class: "prose prose-invert",
                    innerHTML: __props.page.content
                  }, null, 8, ["innerHTML"])
                ]),
                _: 1
              })
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Pages/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
