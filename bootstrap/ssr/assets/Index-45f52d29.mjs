import { unref, withCtx, createVNode, toDisplayString, createTextVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import { _ as _sfc_main$2 } from "./TextareaInput-989d56d6.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { T as Tip } from "./Tip-9a139e5c.mjs";
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
  __name: "Index",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      message: ""
    });
    const send = () => {
      form.post(route("contact.send"));
    };
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
                  _push3(`<h3 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Send a message to"))} Blinest</h3>`);
                } else {
                  return [
                    createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Send a message to")) + " Blinest", 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    form: "roomForm",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Submit"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Submit")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto",
                      form: "roomForm",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Submit")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(Tip, { class: "mb-2" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Pour toute demande d&#39;ajout/modification des extraits dans les playlists publiques merci de contacter directement les modérateurs.rices.`);
                      } else {
                        return [
                          createTextVNode("Pour toute demande d'ajout/modification des extraits dans les playlists publiques merci de contacter directement les modérateurs.rices.")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(Tip, null, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`<p${_scopeId3}>Pour rapporter un bug ou proposer une évolution sur le site merci de passer par là : <a class="underline" target="_blank" href="https://github.com/mchev/blinest/issues/new"${_scopeId3}>${ssrInterpolate(_ctx.__("Report a bug"))}</a></p>`);
                      } else {
                        return [
                          createVNode("p", null, [
                            createTextVNode("Pour rapporter un bug ou proposer une évolution sur le site merci de passer par là : "),
                            createVNode("a", {
                              class: "underline",
                              target: "_blank",
                              href: "https://github.com/mchev/blinest/issues/new"
                            }, toDisplayString(_ctx.__("Report a bug")), 1)
                          ])
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`<form id="roomForm" class="mt-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).message,
                    "onUpdate:modelValue": ($event) => unref(form).message = $event,
                    error: unref(form).errors.message,
                    rows: "10",
                    class: "mb-4 w-full",
                    label: _ctx.__("Message")
                  }, null, _parent3, _scopeId2));
                  _push3(`</form>`);
                } else {
                  return [
                    createVNode(Tip, { class: "mb-2" }, {
                      default: withCtx(() => [
                        createTextVNode("Pour toute demande d'ajout/modification des extraits dans les playlists publiques merci de contacter directement les modérateurs.rices.")
                      ]),
                      _: 1
                    }),
                    createVNode(Tip, null, {
                      default: withCtx(() => [
                        createVNode("p", null, [
                          createTextVNode("Pour rapporter un bug ou proposer une évolution sur le site merci de passer par là : "),
                          createVNode("a", {
                            class: "underline",
                            target: "_blank",
                            href: "https://github.com/mchev/blinest/issues/new"
                          }, toDisplayString(_ctx.__("Report a bug")), 1)
                        ])
                      ]),
                      _: 1
                    }),
                    createVNode("form", {
                      onSubmit: withModifiers(send, ["prevent"]),
                      id: "roomForm",
                      class: "mt-4"
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).message,
                        "onUpdate:modelValue": ($event) => unref(form).message = $event,
                        error: unref(form).errors.message,
                        rows: "10",
                        class: "mb-4 w-full",
                        label: _ctx.__("Message")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                    ], 40, ["onSubmit"])
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
                    createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Send a message to")) + " Blinest", 1)
                  ]),
                  footer: withCtx(() => [
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto",
                      form: "roomForm",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Submit")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ]),
                  default: withCtx(() => [
                    createVNode(Tip, { class: "mb-2" }, {
                      default: withCtx(() => [
                        createTextVNode("Pour toute demande d'ajout/modification des extraits dans les playlists publiques merci de contacter directement les modérateurs.rices.")
                      ]),
                      _: 1
                    }),
                    createVNode(Tip, null, {
                      default: withCtx(() => [
                        createVNode("p", null, [
                          createTextVNode("Pour rapporter un bug ou proposer une évolution sur le site merci de passer par là : "),
                          createVNode("a", {
                            class: "underline",
                            target: "_blank",
                            href: "https://github.com/mchev/blinest/issues/new"
                          }, toDisplayString(_ctx.__("Report a bug")), 1)
                        ])
                      ]),
                      _: 1
                    }),
                    createVNode("form", {
                      onSubmit: withModifiers(send, ["prevent"]),
                      id: "roomForm",
                      class: "mt-4"
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).message,
                        "onUpdate:modelValue": ($event) => unref(form).message = $event,
                        error: unref(form).errors.message,
                        rows: "10",
                        class: "mb-4 w-full",
                        label: _ctx.__("Message")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                    ], 40, ["onSubmit"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Contact/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
