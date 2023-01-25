import { withCtx, createVNode, toDisplayString, openBlock, createBlock, createTextVNode, unref, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { T as Tip } from "./Tip-9a139e5c.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-d136d2f8.mjs";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
const _sfc_main = {
  __name: "Password",
  __ssrInlineRender: true,
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      password: null
    });
    const submit = () => {
      if (form.password && form.password.length > 0)
        form.get(`/rooms/${props.room.slug}`);
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, { class: "mx-auto max-w-xl p-12" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex flex-col"${_scopeId2}><h1 class="text-xl uppercase"${_scopeId2}>${ssrInterpolate(__props.room.name)}</h1><p class="mt-6 flex items-center text-orange-400"${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"${_scopeId2}></path></svg> Cette room est protégée par un mot de passe </p></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("h1", { class: "text-xl uppercase" }, toDisplayString(__props.room.name), 1),
                      createVNode("p", { class: "mt-6 flex items-center text-orange-400" }, [
                        (openBlock(), createBlock("svg", {
                          xmlns: "http://www.w3.org/2000/svg",
                          fill: "none",
                          viewBox: "0 0 24 24",
                          "stroke-width": "1.5",
                          stroke: "currentColor",
                          class: "mr-2 h-5 w-5"
                        }, [
                          createVNode("path", {
                            "stroke-linecap": "round",
                            "stroke-linejoin": "round",
                            d: "M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                          })
                        ])),
                        createTextVNode(" Cette room est protégée par un mot de passe ")
                      ])
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    type: "passsword",
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    error: unref(form).errors.password,
                    label: _ctx.__("Password")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary mt-4 ml-auto"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5"${_scopeId3}><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"${_scopeId3}></path></svg> ${ssrInterpolate(_ctx.__("Login"))}`);
                      } else {
                        return [
                          (openBlock(), createBlock("svg", {
                            xmlns: "http://www.w3.org/2000/svg",
                            fill: "none",
                            viewBox: "0 0 24 24",
                            "stroke-width": "1.5",
                            stroke: "currentColor",
                            class: "mr-2 h-5 w-5"
                          }, [
                            createVNode("path", {
                              "stroke-linecap": "round",
                              "stroke-linejoin": "round",
                              d: "M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                            })
                          ])),
                          createTextVNode(" " + toDisplayString(_ctx.__("Login")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(submit, ["prevent"])
                    }, [
                      createVNode(_sfc_main$2, {
                        type: "passsword",
                        modelValue: unref(form).password,
                        "onUpdate:modelValue": ($event) => unref(form).password = $event,
                        error: unref(form).errors.password,
                        label: _ctx.__("Password")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary mt-4 ml-auto"
                      }, {
                        default: withCtx(() => [
                          (openBlock(), createBlock("svg", {
                            xmlns: "http://www.w3.org/2000/svg",
                            fill: "none",
                            viewBox: "0 0 24 24",
                            "stroke-width": "1.5",
                            stroke: "currentColor",
                            class: "mr-2 h-5 w-5"
                          }, [
                            createVNode("path", {
                              "stroke-linecap": "round",
                              "stroke-linejoin": "round",
                              d: "M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                            })
                          ])),
                          createTextVNode(" " + toDisplayString(_ctx.__("Login")), 1)
                        ]),
                        _: 1
                      }, 8, ["loading"])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(Tip, { class: "mx-auto max-w-xl" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Si vous êtes le propriétaire de la room et que vous avez oublié le mot de passe, vous pouvez le modifier sur `);
                  _push3(ssrRenderComponent(unref(Link), {
                    href: _ctx.route("rooms.index"),
                    class: "underline"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`la page d&#39;édition de la room.`);
                      } else {
                        return [
                          createTextVNode("la page d'édition de la room.")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createTextVNode("Si vous êtes le propriétaire de la room et que vous avez oublié le mot de passe, vous pouvez le modifier sur "),
                    createVNode(unref(Link), {
                      href: _ctx.route("rooms.index"),
                      class: "underline"
                    }, {
                      default: withCtx(() => [
                        createTextVNode("la page d'édition de la room.")
                      ]),
                      _: 1
                    }, 8, ["href"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, { class: "mx-auto max-w-xl p-12" }, {
                header: withCtx(() => [
                  createVNode("div", { class: "flex flex-col" }, [
                    createVNode("h1", { class: "text-xl uppercase" }, toDisplayString(__props.room.name), 1),
                    createVNode("p", { class: "mt-6 flex items-center text-orange-400" }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        fill: "none",
                        viewBox: "0 0 24 24",
                        "stroke-width": "1.5",
                        stroke: "currentColor",
                        class: "mr-2 h-5 w-5"
                      }, [
                        createVNode("path", {
                          "stroke-linecap": "round",
                          "stroke-linejoin": "round",
                          d: "M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                        })
                      ])),
                      createTextVNode(" Cette room est protégée par un mot de passe ")
                    ])
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("form", {
                    onSubmit: withModifiers(submit, ["prevent"])
                  }, [
                    createVNode(_sfc_main$2, {
                      type: "passsword",
                      modelValue: unref(form).password,
                      "onUpdate:modelValue": ($event) => unref(form).password = $event,
                      error: unref(form).errors.password,
                      label: _ctx.__("Password")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary mt-4 ml-auto"
                    }, {
                      default: withCtx(() => [
                        (openBlock(), createBlock("svg", {
                          xmlns: "http://www.w3.org/2000/svg",
                          fill: "none",
                          viewBox: "0 0 24 24",
                          "stroke-width": "1.5",
                          stroke: "currentColor",
                          class: "mr-2 h-5 w-5"
                        }, [
                          createVNode("path", {
                            "stroke-linecap": "round",
                            "stroke-linejoin": "round",
                            d: "M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                          })
                        ])),
                        createTextVNode(" " + toDisplayString(_ctx.__("Login")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ], 40, ["onSubmit"])
                ]),
                _: 1
              }),
              createVNode(Tip, { class: "mx-auto max-w-xl" }, {
                default: withCtx(() => [
                  createTextVNode("Si vous êtes le propriétaire de la room et que vous avez oublié le mot de passe, vous pouvez le modifier sur "),
                  createVNode(unref(Link), {
                    href: _ctx.route("rooms.index"),
                    class: "underline"
                  }, {
                    default: withCtx(() => [
                      createTextVNode("la page d'édition de la room.")
                    ]),
                    _: 1
                  }, 8, ["href"])
                ]),
                _: 1
              })
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/Password.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
