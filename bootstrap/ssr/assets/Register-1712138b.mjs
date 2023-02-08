import { withCtx, unref, createVNode, toDisplayString, createTextVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import _sfc_main$3 from "./Socialite-2bbbed23.mjs";
import "./Navbar-4b87aa4b.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "uuid";
const _sfc_main = {
  __name: "Register",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      terms: false
    });
    const register = () => {
      form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation")
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Head), {
              title: _ctx.__("Login")
            }, null, _parent2, _scopeId));
            _push2(`<div class="mx-auto flex flex-wrap justify-center gap-4 lg:max-w-3xl"${_scopeId}>`);
            _push2(ssrRenderComponent(Card, { class: "flex flex-grow" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h1 class="text-center text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Register"))}</h1>`);
                } else {
                  return [
                    createVNode("h1", { class: "text-center text-xl font-bold" }, toDisplayString(_ctx.__("Register")), 1)
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form${_scopeId2}><div class="p-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    label: _ctx.__("Name") + " / " + _ctx.__("Nickname"),
                    autofocus: "",
                    autocapitalize: "off",
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).email,
                    "onUpdate:modelValue": ($event) => unref(form).email = $event,
                    error: unref(form).errors.email,
                    class: "mt-10",
                    label: _ctx.__("Email"),
                    type: "email",
                    autofocus: "",
                    autocapitalize: "off",
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    error: unref(form).errors.password,
                    class: "mt-10",
                    label: _ctx.__("Password"),
                    type: "password",
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).password_confirmation,
                    "onUpdate:modelValue": ($event) => unref(form).password_confirmation = $event,
                    error: unref(form).errors.password_confirmation,
                    class: "mt-10",
                    label: _ctx.__("Confirm password"),
                    type: "password",
                    required: "",
                    autocomplete: "new-password"
                  }, null, _parent3, _scopeId2));
                  _push3(`</div><div class="flex px-10 py-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Register"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Register")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</div></form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(register, ["prevent"])
                    }, [
                      createVNode("div", { class: "p-4" }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).name,
                          "onUpdate:modelValue": ($event) => unref(form).name = $event,
                          error: unref(form).errors.name,
                          label: _ctx.__("Name") + " / " + _ctx.__("Nickname"),
                          autofocus: "",
                          autocapitalize: "off",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).email,
                          "onUpdate:modelValue": ($event) => unref(form).email = $event,
                          error: unref(form).errors.email,
                          class: "mt-10",
                          label: _ctx.__("Email"),
                          type: "email",
                          autofocus: "",
                          autocapitalize: "off",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).password,
                          "onUpdate:modelValue": ($event) => unref(form).password = $event,
                          error: unref(form).errors.password,
                          class: "mt-10",
                          label: _ctx.__("Password"),
                          type: "password",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).password_confirmation,
                          "onUpdate:modelValue": ($event) => unref(form).password_confirmation = $event,
                          error: unref(form).errors.password_confirmation,
                          class: "mt-10",
                          label: _ctx.__("Confirm password"),
                          type: "password",
                          required: "",
                          autocomplete: "new-password"
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ]),
                      createVNode("div", { class: "flex px-10 py-4" }, [
                        createVNode(LoadingButton, {
                          loading: unref(form).processing,
                          class: "btn-primary ml-auto",
                          type: "submit"
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Register")), 1)
                          ]),
                          _: 1
                        }, 8, ["loading"])
                      ])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$3, null, null, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode(unref(Head), {
                title: _ctx.__("Login")
              }, null, 8, ["title"]),
              createVNode("div", { class: "mx-auto flex flex-wrap justify-center gap-4 lg:max-w-3xl" }, [
                createVNode(Card, { class: "flex flex-grow" }, {
                  header: withCtx(() => [
                    createVNode("h1", { class: "text-center text-xl font-bold" }, toDisplayString(_ctx.__("Register")), 1)
                  ]),
                  default: withCtx(() => [
                    createVNode("form", {
                      onSubmit: withModifiers(register, ["prevent"])
                    }, [
                      createVNode("div", { class: "p-4" }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).name,
                          "onUpdate:modelValue": ($event) => unref(form).name = $event,
                          error: unref(form).errors.name,
                          label: _ctx.__("Name") + " / " + _ctx.__("Nickname"),
                          autofocus: "",
                          autocapitalize: "off",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).email,
                          "onUpdate:modelValue": ($event) => unref(form).email = $event,
                          error: unref(form).errors.email,
                          class: "mt-10",
                          label: _ctx.__("Email"),
                          type: "email",
                          autofocus: "",
                          autocapitalize: "off",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).password,
                          "onUpdate:modelValue": ($event) => unref(form).password = $event,
                          error: unref(form).errors.password,
                          class: "mt-10",
                          label: _ctx.__("Password"),
                          type: "password",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).password_confirmation,
                          "onUpdate:modelValue": ($event) => unref(form).password_confirmation = $event,
                          error: unref(form).errors.password_confirmation,
                          class: "mt-10",
                          label: _ctx.__("Confirm password"),
                          type: "password",
                          required: "",
                          autocomplete: "new-password"
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ]),
                      createVNode("div", { class: "flex px-10 py-4" }, [
                        createVNode(LoadingButton, {
                          loading: unref(form).processing,
                          class: "btn-primary ml-auto",
                          type: "submit"
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Register")), 1)
                          ]),
                          _: 1
                        }, 8, ["loading"])
                      ])
                    ], 40, ["onSubmit"])
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$3)
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/Register.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
