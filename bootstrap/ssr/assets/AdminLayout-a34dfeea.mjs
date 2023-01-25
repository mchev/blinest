import { mergeProps, unref, withCtx, createVNode, useSSRContext, openBlock, createBlock } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderClass, ssrInterpolate, ssrRenderSlot } from "vue/server-renderer";
import { Link, usePage } from "@inertiajs/vue3";
import { _ as _sfc_main$2, a as _sfc_main$4, L as LanguageSwitcher, F as FlashMessages } from "./LanguageSwitcher-18bdae21.mjs";
import { _ as _sfc_main$3 } from "./Dropdown-6785e0d2.mjs";
import "./Icon-4a47e6e0.mjs";
const _sfc_main$1 = {
  __name: "AdminMenu",
  __ssrInlineRender: true,
  setup(__props) {
    const isUrl = (...urls) => {
      let currentUrl = usePage().url.substr(1);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      return urls.filter((url) => currentUrl.startsWith("admin/" + url)).length;
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "text-neutral-100" }, _attrs))}><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.dashboard")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("dashboard") ? "font-bold" : "font-normal")}"${_scopeId}>Dashboard</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("dashboard") ? "font-bold" : "font-normal"
              }, "Dashboard", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.pages.index")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("pages") ? "font-bold" : "font-normal")}"${_scopeId}>Pages</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("pages") ? "font-bold" : "font-normal"
              }, "Pages", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.users")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("users") ? "font-bold" : "font-normal")}"${_scopeId}>Users</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("users") ? "font-bold" : "font-normal"
              }, "Users", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.teams")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("teams") ? "font-bold" : "font-normal")}"${_scopeId}>Teams</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("teams") ? "font-bold" : "font-normal"
              }, "Teams", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.playlists")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("playlists") ? "font-bold" : "font-normal")}"${_scopeId}>Playlists</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("playlists") ? "font-bold" : "font-normal"
              }, "Playlists", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.categories.index")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("categories") ? "font-bold" : "font-normal")}"${_scopeId}>Categories</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("categories") ? "font-bold" : "font-normal"
              }, "Categories", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.rooms")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("rooms") ? "font-bold" : "font-normal")}"${_scopeId}>Rooms</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("rooms") ? "font-bold" : "font-normal"
              }, "Rooms", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="mb-6">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "group flex items-center",
        href: _ctx.route("admin.answer_types.index")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass(isUrl("answer_types") ? "font-bold" : "font-normal")}"${_scopeId}>Answer Types</div>`);
          } else {
            return [
              createVNode("div", {
                class: isUrl("answer_types") ? "font-bold" : "font-normal"
              }, "Answer Types", 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/AdminMenu.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "AdminLayout",
  __ssrInlineRender: true,
  props: {
    auth: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "text-neutral-700" }, _attrs))}><div id="dropdown"></div><div class="md:flex md:flex-col"><div class="md:flex md:h-screen md:flex-col"><div class="text-white md:flex md:flex-shrink-0"><div class="flex items-center justify-between px-6 py-4 md:w-56 md:flex-shrink-0 md:justify-center">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "mt-1",
        href: "/"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, {
              class: "fill-white",
              width: "120",
              height: "28"
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, {
                class: "fill-white",
                width: "120",
                height: "28"
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$3, {
        class: "md:hidden",
        placement: "bottom-end"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<svg class="h-6 w-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"${_scopeId}><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"${_scopeId}></path></svg>`);
          } else {
            return [
              (openBlock(), createBlock("svg", {
                class: "h-6 w-6 fill-white",
                xmlns: "http://www.w3.org/2000/svg",
                viewBox: "0 0 20 20"
              }, [
                createVNode("path", { d: "M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" })
              ]))
            ];
          }
        }),
        dropdown: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="mt-2 rounded px-8 py-4 shadow-lg"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$1, null, null, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "mt-2 rounded px-8 py-4 shadow-lg" }, [
                createVNode(_sfc_main$1)
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="md:text-md items center grid w-full grid-cols-2 p-4 text-sm md:px-12 md:py-0"><div class="text-md flex items-center justify-end uppercase text-yellow-500">${ssrInterpolate(_ctx.__("Administration"))}</div><div class="flex items-center justify-end">`);
      _push(ssrRenderComponent(_sfc_main$4, { class: "mr-4" }, null, _parent));
      _push(ssrRenderComponent(LanguageSwitcher, null, null, _parent));
      _push(`</div></div></div><div class="md:flex md:flex-grow md:overflow-hidden">`);
      _push(ssrRenderComponent(_sfc_main$1, { class: "hidden w-56 flex-shrink-0 overflow-y-auto p-12 md:block" }, null, _parent));
      _push(`<div class="px-4 py-8 md:flex-1 md:overflow-y-auto md:p-12 text-neutral-100" scroll-region>`);
      _push(ssrRenderComponent(FlashMessages, null, null, _parent));
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</div></div></div></div></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AdminLayout.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
