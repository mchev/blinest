import { unref, withCtx, createVNode, toDisplayString, createTextVNode, openBlock, createBlock, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
import { Head, Link } from "@inertiajs/vue3";
import "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import "uuid";
import "./Icon-4a47e6e0.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-d136d2f8.mjs";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
const _sfc_main = {
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    bestUsers: Object,
    bestTeams: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: _ctx.__("Rankings") + " - Top 50"
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<section${_scopeId}><div class="mx-auto py-8 px-4 text-center"${_scopeId}><div class="mx-auto mb-8 lg:mb-16"${_scopeId}><h2 class="mb-4 text-4xl font-extrabold"${_scopeId}>Top 50</h2><p${_scopeId}>public rooms</p></div><div${_scopeId}><section class="mb-8"${_scopeId}><ul class="flex flex-wrap items-center justify-center"${_scopeId}><!--[-->`);
            ssrRenderList(__props.bestUsers, (score, index) => {
              _push2(`<li class="flex flex-col items-center m-4"${_scopeId}><div class="relative"${_scopeId}><span class="p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2"${_scopeId}>${ssrInterpolate(index + 1)}</span><img${ssrRenderAttr("src", score.user.photo)} class="mb-2 h-20 w-20 rounded-full"${_scopeId}></div><span class="mb-1 font-bold"${_scopeId}>${ssrInterpolate(score.user.name)}</span><span${_scopeId}>${ssrInterpolate(score.total_score)}<sup${_scopeId}>PTS</sup></span></li>`);
            });
            _push2(`<!--]--></ul></section><section class="mb-4"${_scopeId}><h3 class="mb-4 text-3xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Teams"))}</h3><ul class="flex flex-wrap items-center justify-center"${_scopeId}><!--[-->`);
            ssrRenderList(__props.bestTeams, (score, index) => {
              _push2(`<li${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("teams.show", score.team.id),
                class: "flex flex-col items-center m-4"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="relative"${_scopeId2}><span class="p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2"${_scopeId2}>${ssrInterpolate(index + 1)}</span><img${ssrRenderAttr("src", score.team.photo)} class="mb-2 h-20 w-20 rounded-full"${_scopeId2}></div><span class="mb-1 font-bold"${_scopeId2}>${ssrInterpolate(score.team.name)}</span><span${_scopeId2}>${ssrInterpolate(score.total_score)}<sup${_scopeId2}>PTS</sup></span>`);
                  } else {
                    return [
                      createVNode("div", { class: "relative" }, [
                        createVNode("span", { class: "p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2" }, toDisplayString(index + 1), 1),
                        createVNode("img", {
                          src: score.team.photo,
                          class: "mb-2 h-20 w-20 rounded-full"
                        }, null, 8, ["src"])
                      ]),
                      createVNode("span", { class: "mb-1 font-bold" }, toDisplayString(score.team.name), 1),
                      createVNode("span", null, [
                        createTextVNode(toDisplayString(score.total_score), 1),
                        createVNode("sup", null, "PTS")
                      ])
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
              _push2(`</li>`);
            });
            _push2(`<!--]--></ul></section></div></div></section>`);
          } else {
            return [
              createVNode("section", null, [
                createVNode("div", { class: "mx-auto py-8 px-4 text-center" }, [
                  createVNode("div", { class: "mx-auto mb-8 lg:mb-16" }, [
                    createVNode("h2", { class: "mb-4 text-4xl font-extrabold" }, "Top 50"),
                    createVNode("p", null, "public rooms")
                  ]),
                  createVNode("div", null, [
                    createVNode("section", { class: "mb-8" }, [
                      createVNode("ul", { class: "flex flex-wrap items-center justify-center" }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.bestUsers, (score, index) => {
                          return openBlock(), createBlock("li", {
                            key: score.user.id,
                            class: "flex flex-col items-center m-4"
                          }, [
                            createVNode("div", { class: "relative" }, [
                              createVNode("span", { class: "p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2" }, toDisplayString(index + 1), 1),
                              createVNode("img", {
                                src: score.user.photo,
                                class: "mb-2 h-20 w-20 rounded-full"
                              }, null, 8, ["src"])
                            ]),
                            createVNode("span", { class: "mb-1 font-bold" }, toDisplayString(score.user.name), 1),
                            createVNode("span", null, [
                              createTextVNode(toDisplayString(score.total_score), 1),
                              createVNode("sup", null, "PTS")
                            ])
                          ]);
                        }), 128))
                      ])
                    ]),
                    createVNode("section", { class: "mb-4" }, [
                      createVNode("h3", { class: "mb-4 text-3xl font-bold" }, toDisplayString(_ctx.__("Teams")), 1),
                      createVNode("ul", { class: "flex flex-wrap items-center justify-center" }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.bestTeams, (score, index) => {
                          return openBlock(), createBlock("li", {
                            key: score.team.id
                          }, [
                            createVNode(unref(Link), {
                              href: _ctx.route("teams.show", score.team.id),
                              class: "flex flex-col items-center m-4"
                            }, {
                              default: withCtx(() => [
                                createVNode("div", { class: "relative" }, [
                                  createVNode("span", { class: "p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2" }, toDisplayString(index + 1), 1),
                                  createVNode("img", {
                                    src: score.team.photo,
                                    class: "mb-2 h-20 w-20 rounded-full"
                                  }, null, 8, ["src"])
                                ]),
                                createVNode("span", { class: "mb-1 font-bold" }, toDisplayString(score.team.name), 1),
                                createVNode("span", null, [
                                  createTextVNode(toDisplayString(score.total_score), 1),
                                  createVNode("sup", null, "PTS")
                                ])
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]);
                        }), 128))
                      ])
                    ])
                  ])
                ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rankings/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
