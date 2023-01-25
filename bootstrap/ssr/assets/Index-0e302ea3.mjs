import { unref, withCtx, createVNode, openBlock, createBlock, Fragment, renderList, createCommentVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import _sfc_main$2 from "./Room-848f4a78.mjs";
import _sfc_main$3 from "./Rooms-7e5a0517.mjs";
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
import "./Top-32c0f170.mjs";
import "swiper";
import "swiper/vue";
const _sfc_main = {
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    filters: Object,
    categories: Object,
    private_rooms: Object,
    user_rooms: Object,
    top_rooms: Array,
    search_result: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<title${_scopeId}>Quiz musicaux gratuits et multijoueurs</title><meta name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc."${_scopeId}>`);
          } else {
            return [
              createVNode("title", null, "Quiz musicaux gratuits et multijoueurs"),
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
            if (__props.search_result) {
              _push2(`<section${_scopeId}><div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4"${_scopeId}><!--[-->`);
              ssrRenderList(__props.search_result, (room) => {
                _push2(ssrRenderComponent(_sfc_main$2, {
                  room,
                  key: room.id
                }, null, _parent2, _scopeId));
              });
              _push2(`<!--]--></div></section>`);
            } else {
              _push2(`<div${_scopeId}>`);
              if (!__props.filters.search) {
                _push2(`<section${_scopeId}><div class="flex flex-wrap items-center"${_scopeId}>`);
                if (__props.top_rooms) {
                  _push2(`<div class="relative mb-4 flex-grow"${_scopeId}><h2 class="text-xl text-neutral-400 lg:text-2xl mb-1"${_scopeId}>TOP 5</h2>`);
                  _push2(ssrRenderComponent(_sfc_main$3, {
                    rooms: __props.top_rooms,
                    is_top_5: true
                  }, null, _parent2, _scopeId));
                  _push2(`</div>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(`</div></section>`);
              } else {
                _push2(`<!---->`);
              }
              if (__props.categories.length) {
                _push2(`<!--[-->`);
                ssrRenderList(__props.categories, (category) => {
                  _push2(`<section${_scopeId}>`);
                  if (category.rooms.length) {
                    _push2(`<div class="relative mb-4"${_scopeId}><h2 class="text-xl text-neutral-400 lg:text-2xl mb-1"${_scopeId}>${ssrInterpolate(category.name)}</h2>`);
                    _push2(ssrRenderComponent(_sfc_main$3, {
                      rooms: category.rooms,
                      id: category.id
                    }, null, _parent2, _scopeId));
                    _push2(`</div>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`</section>`);
                });
                _push2(`<!--]-->`);
              } else {
                _push2(`<!---->`);
              }
              if (__props.private_rooms && __props.private_rooms.length) {
                _push2(`<section${_scopeId}><div class="relative"${_scopeId}><h2 class="text-xl text-neutral-400 lg:text-2xl mb-1"${_scopeId}>${ssrInterpolate(_ctx.__("Private rooms"))}</h2>`);
                _push2(ssrRenderComponent(_sfc_main$3, {
                  rooms: __props.private_rooms,
                  id: "private"
                }, null, _parent2, _scopeId));
                _push2(`</div></section>`);
              } else {
                _push2(`<!---->`);
              }
              if (__props.user_rooms && __props.user_rooms.length) {
                _push2(`<section${_scopeId}><div class="relative"${_scopeId}><h2 class="text-xl text-neutral-400 lg:text-2xl mb-1"${_scopeId}>${ssrInterpolate(_ctx.__("My rooms"))}</h2>`);
                _push2(ssrRenderComponent(_sfc_main$3, {
                  rooms: __props.user_rooms,
                  id: "private"
                }, null, _parent2, _scopeId));
                _push2(`</div></section>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div>`);
            }
          } else {
            return [
              __props.search_result ? (openBlock(), createBlock("section", { key: 0 }, [
                createVNode("div", { class: "grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4" }, [
                  (openBlock(true), createBlock(Fragment, null, renderList(__props.search_result, (room) => {
                    return openBlock(), createBlock(_sfc_main$2, {
                      room,
                      key: room.id
                    }, null, 8, ["room"]);
                  }), 128))
                ])
              ])) : (openBlock(), createBlock("div", { key: 1 }, [
                !__props.filters.search ? (openBlock(), createBlock("section", { key: 0 }, [
                  createVNode("div", { class: "flex flex-wrap items-center" }, [
                    __props.top_rooms ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "relative mb-4 flex-grow"
                    }, [
                      createVNode("h2", { class: "text-xl text-neutral-400 lg:text-2xl mb-1" }, "TOP 5"),
                      createVNode(_sfc_main$3, {
                        rooms: __props.top_rooms,
                        is_top_5: true
                      }, null, 8, ["rooms"])
                    ])) : createCommentVNode("", true)
                  ])
                ])) : createCommentVNode("", true),
                __props.categories.length ? (openBlock(true), createBlock(Fragment, { key: 1 }, renderList(__props.categories, (category) => {
                  return openBlock(), createBlock("section", {
                    key: category.id
                  }, [
                    category.rooms.length ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "relative mb-4"
                    }, [
                      createVNode("h2", { class: "text-xl text-neutral-400 lg:text-2xl mb-1" }, toDisplayString(category.name), 1),
                      createVNode(_sfc_main$3, {
                        rooms: category.rooms,
                        id: category.id
                      }, null, 8, ["rooms", "id"])
                    ])) : createCommentVNode("", true)
                  ]);
                }), 128)) : createCommentVNode("", true),
                __props.private_rooms && __props.private_rooms.length ? (openBlock(), createBlock("section", { key: 2 }, [
                  createVNode("div", { class: "relative" }, [
                    createVNode("h2", { class: "text-xl text-neutral-400 lg:text-2xl mb-1" }, toDisplayString(_ctx.__("Private rooms")), 1),
                    createVNode(_sfc_main$3, {
                      rooms: __props.private_rooms,
                      id: "private"
                    }, null, 8, ["rooms"])
                  ])
                ])) : createCommentVNode("", true),
                __props.user_rooms && __props.user_rooms.length ? (openBlock(), createBlock("section", { key: 3 }, [
                  createVNode("div", { class: "relative" }, [
                    createVNode("h2", { class: "text-xl text-neutral-400 lg:text-2xl mb-1" }, toDisplayString(_ctx.__("My rooms")), 1),
                    createVNode(_sfc_main$3, {
                      rooms: __props.user_rooms,
                      id: "private"
                    }, null, 8, ["rooms"])
                  ])
                ])) : createCommentVNode("", true)
              ]))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Home/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
