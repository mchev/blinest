import { unref, withCtx, createVNode, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { T as Tip } from "./Tip-9a139e5c.mjs";
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
  __name: "Banned",
  __ssrInlineRender: true,
  props: {
    ban: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<title${_scopeId}>Vous avez été banni !</title>`);
          } else {
            return [
              createVNode("title", null, "Vous avez été banni !")
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
                  _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h1 class="text-xl my-4 text-red-500"${_scopeId2}>Vous avez été banni de blinest.com !</h1></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex w-full items-center justify-between" }, [
                      createVNode("h1", { class: "text-xl my-4 text-red-500" }, "Vous avez été banni de blinest.com !")
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="prose prose-invert"${_scopeId2}><p${_scopeId2}><b${_scopeId2}>Raison du ban :</b><br${_scopeId2}>${ssrInterpolate(__props.ban.comment)}</p><p${_scopeId2}><b${_scopeId2}>L&#39;expulsion du site se termine :</b> <br${_scopeId2}>${ssrInterpolate(__props.ban.expired_at)}</p>`);
                  _push3(ssrRenderComponent(Tip, { class: "text-orange-500" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Dans certains cas, un signalement pourra être effectué auprès des autorités compétentes.`);
                      } else {
                        return [
                          createTextVNode("Dans certains cas, un signalement pourra être effectué auprès des autorités compétentes.")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(Tip, { class: "text-orange-500" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Il est toujours possible d&#39;accèder à la gestion de votre compte afin de respecter les règles sur la protection des données mais en cas de signalement nous nous reservons le droit de conserver les éléments de preuve comme le prévoit la loi.`);
                      } else {
                        return [
                          createTextVNode("Il est toujours possible d'accèder à la gestion de votre compte afin de respecter les règles sur la protection des données mais en cas de signalement nous nous reservons le droit de conserver les éléments de preuve comme le prévoit la loi.")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</div>`);
                } else {
                  return [
                    createVNode("div", { class: "prose prose-invert" }, [
                      createVNode("p", null, [
                        createVNode("b", null, "Raison du ban :"),
                        createVNode("br"),
                        createTextVNode(toDisplayString(__props.ban.comment), 1)
                      ]),
                      createVNode("p", null, [
                        createVNode("b", null, "L'expulsion du site se termine :"),
                        createTextVNode(),
                        createVNode("br"),
                        createTextVNode(toDisplayString(__props.ban.expired_at), 1)
                      ]),
                      createVNode(Tip, { class: "text-orange-500" }, {
                        default: withCtx(() => [
                          createTextVNode("Dans certains cas, un signalement pourra être effectué auprès des autorités compétentes.")
                        ]),
                        _: 1
                      }),
                      createVNode(Tip, { class: "text-orange-500" }, {
                        default: withCtx(() => [
                          createTextVNode("Il est toujours possible d'accèder à la gestion de votre compte afin de respecter les règles sur la protection des données mais en cas de signalement nous nous reservons le droit de conserver les éléments de preuve comme le prévoit la loi.")
                        ]),
                        _: 1
                      })
                    ])
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
                    createVNode("h1", { class: "text-xl my-4 text-red-500" }, "Vous avez été banni de blinest.com !")
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("div", { class: "prose prose-invert" }, [
                    createVNode("p", null, [
                      createVNode("b", null, "Raison du ban :"),
                      createVNode("br"),
                      createTextVNode(toDisplayString(__props.ban.comment), 1)
                    ]),
                    createVNode("p", null, [
                      createVNode("b", null, "L'expulsion du site se termine :"),
                      createTextVNode(),
                      createVNode("br"),
                      createTextVNode(toDisplayString(__props.ban.expired_at), 1)
                    ]),
                    createVNode(Tip, { class: "text-orange-500" }, {
                      default: withCtx(() => [
                        createTextVNode("Dans certains cas, un signalement pourra être effectué auprès des autorités compétentes.")
                      ]),
                      _: 1
                    }),
                    createVNode(Tip, { class: "text-orange-500" }, {
                      default: withCtx(() => [
                        createTextVNode("Il est toujours possible d'accèder à la gestion de votre compte afin de respecter les règles sur la protection des données mais en cas de signalement nous nous reservons le droit de conserver les éléments de preuve comme le prévoit la loi.")
                      ]),
                      _: 1
                    })
                  ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Pages/Banned.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
