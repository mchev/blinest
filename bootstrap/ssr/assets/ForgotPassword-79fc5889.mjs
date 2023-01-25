import { withCtx, unref, createTextVNode, toDisplayString, createVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { useForm, Head } from "@inertiajs/vue3";
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
  __name: "ForgotPassword",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      email: ""
    });
    const submit = () => {
      form.post(route("password.email"));
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Head), {
              title: _ctx.__("Forgot your password?")
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(Card, { class: "mx-auto max-w-xl" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Forgot your password?"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Forgot your password?")), 1)
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<p class="mb-4"${_scopeId2}>${ssrInterpolate(_ctx.__("Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one."))}</p><form${_scopeId2}><div${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    type: "email",
                    label: "Email",
                    modelValue: unref(form).email,
                    "onUpdate:modelValue": ($event) => unref(form).email = $event,
                    error: unref(form).errors.email,
                    required: "",
                    autofocus: ""
                  }, null, _parent3, _scopeId2));
                  _push3(`</div><div class="mt-6 mb-4 flex items-center justify-end"${_scopeId2}>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    class: [{ "opacity-25": unref(form).processing }, "btn-primary"],
                    disabled: unref(form).processing,
                    loading: unref(form).processing
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Email Password Reset Link"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Email Password Reset Link")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</div></form>`);
                } else {
                  return [
                    createVNode("p", { class: "mb-4" }, toDisplayString(_ctx.__("Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.")), 1),
                    createVNode("form", {
                      onSubmit: withModifiers(submit, ["prevent"])
                    }, [
                      createVNode("div", null, [
                        createVNode(_sfc_main$2, {
                          type: "email",
                          label: "Email",
                          modelValue: unref(form).email,
                          "onUpdate:modelValue": ($event) => unref(form).email = $event,
                          error: unref(form).errors.email,
                          required: "",
                          autofocus: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
                      ]),
                      createVNode("div", { class: "mt-6 mb-4 flex items-center justify-end" }, [
                        createVNode(LoadingButton, {
                          class: [{ "opacity-25": unref(form).processing }, "btn-primary"],
                          disabled: unref(form).processing,
                          loading: unref(form).processing
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Email Password Reset Link")), 1)
                          ]),
                          _: 1
                        }, 8, ["class", "disabled", "loading"])
                      ])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(Head), {
                title: _ctx.__("Forgot your password?")
              }, null, 8, ["title"]),
              createVNode(Card, { class: "mx-auto max-w-xl" }, {
                header: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Forgot your password?")), 1)
                ]),
                default: withCtx(() => [
                  createVNode("p", { class: "mb-4" }, toDisplayString(_ctx.__("Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.")), 1),
                  createVNode("form", {
                    onSubmit: withModifiers(submit, ["prevent"])
                  }, [
                    createVNode("div", null, [
                      createVNode(_sfc_main$2, {
                        type: "email",
                        label: "Email",
                        modelValue: unref(form).email,
                        "onUpdate:modelValue": ($event) => unref(form).email = $event,
                        error: unref(form).errors.email,
                        required: "",
                        autofocus: ""
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
                    ]),
                    createVNode("div", { class: "mt-6 mb-4 flex items-center justify-end" }, [
                      createVNode(LoadingButton, {
                        class: [{ "opacity-25": unref(form).processing }, "btn-primary"],
                        disabled: unref(form).processing,
                        loading: unref(form).processing
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Email Password Reset Link")), 1)
                        ]),
                        _: 1
                      }, 8, ["class", "disabled", "loading"])
                    ])
                  ], 40, ["onSubmit"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/ForgotPassword.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
