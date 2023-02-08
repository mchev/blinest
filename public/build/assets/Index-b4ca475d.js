import { c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, v as renderList, t as toDisplayString, d as createTextVNode, n as ne } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./TextInput-541fe8b5.js";
import "./v4-e7604ebc.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
const _hoisted_1 = { class: "mx-auto py-8 px-4 text-center" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { class: "mx-auto mb-8 lg:mb-16" }, [
  /* @__PURE__ */ createBaseVNode("h2", { class: "mb-4 text-4xl font-extrabold" }, "Top 50"),
  /* @__PURE__ */ createBaseVNode("p", null, "public rooms")
], -1);
const _hoisted_3 = { class: "mb-8" };
const _hoisted_4 = { class: "flex flex-wrap items-center justify-center" };
const _hoisted_5 = { class: "relative" };
const _hoisted_6 = { class: "absolute -left-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 p-1 text-neutral-700" };
const _hoisted_7 = ["src"];
const _hoisted_8 = { class: "mb-1 font-bold" };
const _hoisted_9 = /* @__PURE__ */ createBaseVNode("sup", null, "PTS", -1);
const _hoisted_10 = { class: "mb-4" };
const _hoisted_11 = { class: "mb-4 text-3xl font-bold" };
const _hoisted_12 = { class: "flex flex-wrap items-center justify-center" };
const _hoisted_13 = { class: "relative" };
const _hoisted_14 = { class: "absolute -left-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 p-1 text-neutral-700" };
const _hoisted_15 = ["src"];
const _hoisted_16 = { class: "mb-1 font-bold" };
const _hoisted_17 = /* @__PURE__ */ createBaseVNode("sup", null, "PTS", -1);
const _sfc_main = {
  __name: "Index",
  props: {
    bestUsers: Object,
    bestTeams: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Rankings") + " - Top 50"
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("section", null, [
              createBaseVNode("div", _hoisted_1, [
                _hoisted_2,
                createBaseVNode("div", null, [
                  createBaseVNode("section", _hoisted_3, [
                    createBaseVNode("ul", _hoisted_4, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(__props.bestUsers, (score, index) => {
                        return openBlock(), createElementBlock("li", {
                          key: score.user.id,
                          class: "m-4 flex flex-col items-center"
                        }, [
                          createBaseVNode("div", _hoisted_5, [
                            createBaseVNode("span", _hoisted_6, toDisplayString(index + 1), 1),
                            createBaseVNode("img", {
                              src: score.user.photo,
                              class: "mb-2 h-20 w-20 rounded-full"
                            }, null, 8, _hoisted_7)
                          ]),
                          createBaseVNode("span", _hoisted_8, toDisplayString(score.user.name), 1),
                          createBaseVNode("span", null, [
                            createTextVNode(toDisplayString(score.total_score), 1),
                            _hoisted_9
                          ])
                        ]);
                      }), 128))
                    ])
                  ]),
                  createBaseVNode("section", _hoisted_10, [
                    createBaseVNode("h3", _hoisted_11, toDisplayString(_ctx.__("Teams")), 1),
                    createBaseVNode("ul", _hoisted_12, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(__props.bestTeams, (score, index) => {
                        return openBlock(), createElementBlock("li", {
                          key: score.team.id
                        }, [
                          createVNode(unref(ne), {
                            href: _ctx.route("teams.show", score.team.id),
                            class: "m-4 flex flex-col items-center"
                          }, {
                            default: withCtx(() => [
                              createBaseVNode("div", _hoisted_13, [
                                createBaseVNode("span", _hoisted_14, toDisplayString(index + 1), 1),
                                createBaseVNode("img", {
                                  src: score.team.photo,
                                  class: "mb-2 h-20 w-20 rounded-full"
                                }, null, 8, _hoisted_15)
                              ]),
                              createBaseVNode("span", _hoisted_16, toDisplayString(score.team.name), 1),
                              createBaseVNode("span", null, [
                                createTextVNode(toDisplayString(score.total_score), 1),
                                _hoisted_17
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
          ]),
          _: 1
        })
      ], 64);
    };
  }
};
export {
  _sfc_main as default
};
