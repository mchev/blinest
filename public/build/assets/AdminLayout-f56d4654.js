import { o as openBlock, c as createElementBlock, b as createBaseVNode, a as createVNode, w as withCtx, f as normalizeClass, u as unref, n as ne, J, d as createTextVNode, t as toDisplayString, r as renderSlot } from "./app-910e457d.js";
import { _ as _sfc_main$2, a as _sfc_main$4, L as LanguageSwitcher, F as FlashMessages } from "./LanguageSwitcher-da420d03.js";
import { _ as _sfc_main$3 } from "./Dropdown-f0e2d937.js";
const _hoisted_1$1 = { class: "text-neutral-100" };
const _hoisted_2$1 = { class: "mb-6" };
const _hoisted_3$1 = { class: "mb-6" };
const _hoisted_4$1 = { class: "mb-6" };
const _hoisted_5$1 = { class: "mb-6" };
const _hoisted_6$1 = { class: "mb-6" };
const _hoisted_7$1 = { class: "mb-6" };
const _hoisted_8$1 = { class: "mb-6" };
const _hoisted_9$1 = { class: "mb-6" };
const _hoisted_10$1 = { class: "mb-6" };
const _sfc_main$1 = {
  __name: "AdminMenu",
  setup(__props) {
    const isUrl = (...urls) => {
      let currentUrl = J().url.substr(1);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      return urls.filter((url) => currentUrl.startsWith("admin/" + url)).length;
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1$1, [
        createBaseVNode("div", _hoisted_2$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("dashboard") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.dashboard")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("dashboard") ? "font-bold" : "font-normal")
              }, "Dashboard", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_3$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("pages") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.pages.index")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("pages") ? "font-bold" : "font-normal")
              }, "Pages", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_4$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("faqs") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.faqs.index")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("faqs") ? "font-bold" : "font-normal")
              }, "FAQ", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_5$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("users") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.users")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("users") ? "font-bold" : "font-normal")
              }, "Users", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_6$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("teams") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.teams")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("teams") ? "font-bold" : "font-normal")
              }, "Teams", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_7$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("playlists") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.playlists")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("playlists") ? "font-bold" : "font-normal")
              }, "Playlists", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_8$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("categories") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.categories.index")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("categories") ? "font-bold" : "font-normal")
              }, "Categories", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_9$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("rooms") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.rooms")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("rooms") ? "font-bold" : "font-normal")
              }, "Rooms", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ]),
        createBaseVNode("div", _hoisted_10$1, [
          createVNode(unref(ne), {
            class: normalizeClass(["group", isUrl("answer_types") ? "btn-primary" : "btn-secondary"]),
            href: _ctx.route("admin.answer_types.index")
          }, {
            default: withCtx(() => [
              createBaseVNode("div", {
                class: normalizeClass(isUrl("answer_types") ? "font-bold" : "font-normal")
              }, "Answer Types", 2)
            ]),
            _: 1
          }, 8, ["class", "href"])
        ])
      ]);
    };
  }
};
const _hoisted_1 = { class: "text-neutral-700" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { id: "dropdown" }, null, -1);
const _hoisted_3 = { class: "md:flex md:flex-col" };
const _hoisted_4 = { class: "md:flex md:h-screen md:flex-col" };
const _hoisted_5 = { class: "text-white md:flex md:flex-shrink-0" };
const _hoisted_6 = { class: "flex items-center justify-between px-6 py-4 md:w-56 md:flex-shrink-0 md:justify-center" };
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("svg", {
  class: "h-6 w-6 fill-white",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 20 20"
}, [
  /* @__PURE__ */ createBaseVNode("path", { d: "M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" })
], -1);
const _hoisted_8 = { class: "mt-2 rounded px-4 py-4 shadow-lg" };
const _hoisted_9 = { class: "md:text-md items center grid w-full grid-cols-2 p-4 text-sm md:px-12 md:py-0" };
const _hoisted_10 = { class: "text-xl flex items-center justify-end uppercase text-yellow-500 gap-2" };
const _hoisted_11 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "w-6 h-6"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
  })
], -1);
const _hoisted_12 = { class: "flex items-center justify-end" };
const _hoisted_13 = { class: "md:flex md:flex-grow md:overflow-hidden" };
const _hoisted_14 = {
  class: "px-4 py-8 text-neutral-100 md:flex-1 md:overflow-y-auto md:p-12",
  "scroll-region": ""
};
const _sfc_main = {
  __name: "AdminLayout",
  props: {
    auth: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        _hoisted_2,
        createBaseVNode("div", _hoisted_3, [
          createBaseVNode("div", _hoisted_4, [
            createBaseVNode("div", _hoisted_5, [
              createBaseVNode("div", _hoisted_6, [
                createVNode(unref(ne), {
                  class: "mt-1",
                  href: "/"
                }, {
                  default: withCtx(() => [
                    createVNode(_sfc_main$2, {
                      class: "fill-white",
                      width: "120",
                      height: "28"
                    })
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$3, {
                  class: "md:hidden",
                  placement: "bottom-end"
                }, {
                  default: withCtx(() => [
                    _hoisted_7
                  ]),
                  dropdown: withCtx(() => [
                    createBaseVNode("div", _hoisted_8, [
                      createVNode(_sfc_main$1)
                    ])
                  ]),
                  _: 1
                })
              ]),
              createBaseVNode("div", _hoisted_9, [
                createBaseVNode("div", _hoisted_10, [
                  _hoisted_11,
                  createTextVNode(" " + toDisplayString(_ctx.__("Administration")), 1)
                ]),
                createBaseVNode("div", _hoisted_12, [
                  createVNode(_sfc_main$4, { class: "mr-4" }),
                  createVNode(LanguageSwitcher)
                ])
              ])
            ]),
            createBaseVNode("div", _hoisted_13, [
              createVNode(_sfc_main$1, { class: "hidden w-56 flex-shrink-0 overflow-y-auto py-12 px-4 md:block" }),
              createBaseVNode("div", _hoisted_14, [
                createVNode(FlashMessages),
                renderSlot(_ctx.$slots, "default")
              ])
            ])
          ])
        ])
      ]);
    };
  }
};
export {
  _sfc_main as _
};
