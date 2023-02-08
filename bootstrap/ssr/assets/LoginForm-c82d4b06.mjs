import { mergeProps, withCtx, createVNode, toDisplayString, unref, createTextVNode, withModifiers, openBlock, createBlock, createCommentVNode, withDirectives, vModelCheckbox, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrIncludeBooleanAttr, ssrLooseContain } from "vue/server-renderer";
import { useForm, Link } from "@inertiajs/vue3";
import { C as Card } from "./Card-ee13c838.mjs";
import { _ as _sfc_main$1 } from "./TextInput-cddc091b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import _sfc_main$2 from "./Socialite-2bbbed23.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "uuid";
import "./Icon-4a47e6e0.mjs";
import "./SocialIcon-bb2fc3a0.mjs";
const _sfc_main = {
  __name: "LoginForm",
  __ssrInlineRender: true,
  props: {
    isFromModal: { type: Boolean, required: false, default: false }
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      email: "",
      password: "",
      isFromModal: props.isFromModal,
      remember: true
    });
    const login = () => {
      form.post("/login", {
        preserveState: false
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "mx-auto flex flex-wrap justify-center gap-4 lg:max-w-3xl" }, _attrs))}>`);
      _push(ssrRenderComponent(Card, { class: "flex flex-grow" }, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="text-center text-xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Login"))}</h1>`);
          } else {
            return [
              createVNode("h1", { class: "text-center text-xl font-bold" }, toDisplayString(_ctx.__("Login")), 1)
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<form${_scopeId}><div class="p-4"${_scopeId}>`);
            if (_ctx.$page.props.errors.email) {
              _push2(`<div class="my-2 max-w-xs text-sm text-red-600"${_scopeId}>${ssrInterpolate(_ctx.$page.props.errors.email)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(ssrRenderComponent(_sfc_main$1, {
              modelValue: unref(form).email,
              "onUpdate:modelValue": ($event) => unref(form).email = $event,
              error: unref(form).errors.email,
              class: "mt-6",
              label: _ctx.__("Email"),
              type: "email",
              autofocus: "",
              autocapitalize: "off",
              required: ""
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$1, {
              modelValue: unref(form).password,
              "onUpdate:modelValue": ($event) => unref(form).password = $event,
              error: unref(form).errors.password,
              class: "mt-6",
              label: _ctx.__("Password"),
              type: "password",
              required: ""
            }, null, _parent2, _scopeId));
            _push2(`<label class="mt-6 flex select-none items-center" for="remember"${_scopeId}><input id="remember"${ssrIncludeBooleanAttr(Array.isArray(unref(form).remember) ? ssrLooseContain(unref(form).remember, null) : unref(form).remember) ? " checked" : ""} class="mr-1" type="checkbox"${_scopeId}><span class="text-sm"${_scopeId}>${ssrInterpolate(_ctx.__("Remember Me"))}</span></label></div><div class="flex px-6 py-4"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              class: "btn-secondary ml-auto mr-2",
              href: _ctx.route("register")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Register"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Register")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(LoadingButton, {
              loading: unref(form).processing,
              class: "btn-primary",
              type: "submit"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Login"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Login")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div><div class="mb-2 flex gap-2 px-6"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              class: "ml-auto text-sm underline",
              href: _ctx.route("password.request")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Forgot your password?"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Forgot your password?")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              class: "text-sm underline",
              href: _ctx.route("home")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Retourner à l&#39;accueil`);
                } else {
                  return [
                    createTextVNode("Retourner à l'accueil")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></form>`);
          } else {
            return [
              createVNode("form", {
                onSubmit: withModifiers(login, ["prevent"])
              }, [
                createVNode("div", { class: "p-4" }, [
                  _ctx.$page.props.errors.email ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "my-2 max-w-xs text-sm text-red-600"
                  }, toDisplayString(_ctx.$page.props.errors.email), 1)) : createCommentVNode("", true),
                  createVNode(_sfc_main$1, {
                    modelValue: unref(form).email,
                    "onUpdate:modelValue": ($event) => unref(form).email = $event,
                    error: unref(form).errors.email,
                    class: "mt-6",
                    label: _ctx.__("Email"),
                    type: "email",
                    autofocus: "",
                    autocapitalize: "off",
                    required: ""
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                  createVNode(_sfc_main$1, {
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    error: unref(form).errors.password,
                    class: "mt-6",
                    label: _ctx.__("Password"),
                    type: "password",
                    required: ""
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                  createVNode("label", {
                    class: "mt-6 flex select-none items-center",
                    for: "remember"
                  }, [
                    withDirectives(createVNode("input", {
                      id: "remember",
                      "onUpdate:modelValue": ($event) => unref(form).remember = $event,
                      class: "mr-1",
                      type: "checkbox"
                    }, null, 8, ["onUpdate:modelValue"]), [
                      [vModelCheckbox, unref(form).remember]
                    ]),
                    createVNode("span", { class: "text-sm" }, toDisplayString(_ctx.__("Remember Me")), 1)
                  ])
                ]),
                createVNode("div", { class: "flex px-6 py-4" }, [
                  createVNode(unref(Link), {
                    class: "btn-secondary ml-auto mr-2",
                    href: _ctx.route("register")
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Register")), 1)
                    ]),
                    _: 1
                  }, 8, ["href"]),
                  createVNode(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary",
                    type: "submit"
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Login")), 1)
                    ]),
                    _: 1
                  }, 8, ["loading"])
                ]),
                createVNode("div", { class: "mb-2 flex gap-2 px-6" }, [
                  createVNode(unref(Link), {
                    class: "ml-auto text-sm underline",
                    href: _ctx.route("password.request")
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Forgot your password?")), 1)
                    ]),
                    _: 1
                  }, 8, ["href"]),
                  createVNode(unref(Link), {
                    class: "text-sm underline",
                    href: _ctx.route("home")
                  }, {
                    default: withCtx(() => [
                      createTextVNode("Retourner à l'accueil")
                    ]),
                    _: 1
                  }, 8, ["href"])
                ])
              ], 40, ["onSubmit"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$2, null, null, _parent));
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/LoginForm.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
