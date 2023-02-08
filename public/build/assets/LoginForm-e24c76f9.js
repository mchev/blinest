import { P, c as createElementBlock, a as createVNode, w as withCtx, o as openBlock, b as createBaseVNode, t as toDisplayString, g as createCommentVNode, u as unref, z as withDirectives, O as vModelCheckbox, n as ne, d as createTextVNode, e as withModifiers } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$1 } from "./TextInput-541fe8b5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import _sfc_main$2 from "./Socialite-eb75d06f.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
import "./SocialIcon-5ed77127.js";
const _hoisted_1 = { class: "mx-auto flex flex-wrap justify-center gap-4 lg:max-w-3xl" };
const _hoisted_2 = { class: "text-center text-xl font-bold" };
const _hoisted_3 = ["onSubmit"];
const _hoisted_4 = { class: "p-4" };
const _hoisted_5 = {
  key: 0,
  class: "my-2 max-w-xs text-sm text-red-600"
};
const _hoisted_6 = {
  class: "mt-6 flex select-none items-center",
  for: "remember"
};
const _hoisted_7 = { class: "text-sm" };
const _hoisted_8 = { class: "flex px-6 py-4" };
const _hoisted_9 = { class: "mb-2 flex gap-2 px-6" };
const _sfc_main = {
  __name: "LoginForm",
  props: {
    isFromModal: { type: Boolean, required: false, default: false }
  },
  setup(__props) {
    const props = __props;
    const form = P({
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
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        createVNode(Card, { class: "flex flex-grow" }, {
          header: withCtx(() => [
            createBaseVNode("h1", _hoisted_2, toDisplayString(_ctx.__("Login")), 1)
          ]),
          default: withCtx(() => [
            createBaseVNode("form", {
              onSubmit: withModifiers(login, ["prevent"])
            }, [
              createBaseVNode("div", _hoisted_4, [
                _ctx.$page.props.errors.email ? (openBlock(), createElementBlock("div", _hoisted_5, toDisplayString(_ctx.$page.props.errors.email), 1)) : createCommentVNode("", true),
                createVNode(_sfc_main$1, {
                  modelValue: unref(form).email,
                  "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).email = $event),
                  error: unref(form).errors.email,
                  class: "mt-6",
                  label: _ctx.__("Email"),
                  type: "email",
                  autofocus: "",
                  autocapitalize: "off",
                  required: ""
                }, null, 8, ["modelValue", "error", "label"]),
                createVNode(_sfc_main$1, {
                  modelValue: unref(form).password,
                  "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).password = $event),
                  error: unref(form).errors.password,
                  class: "mt-6",
                  label: _ctx.__("Password"),
                  type: "password",
                  required: ""
                }, null, 8, ["modelValue", "error", "label"]),
                createBaseVNode("label", _hoisted_6, [
                  withDirectives(createBaseVNode("input", {
                    id: "remember",
                    "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).remember = $event),
                    class: "mr-1",
                    type: "checkbox"
                  }, null, 512), [
                    [vModelCheckbox, unref(form).remember]
                  ]),
                  createBaseVNode("span", _hoisted_7, toDisplayString(_ctx.__("Remember Me")), 1)
                ])
              ]),
              createBaseVNode("div", _hoisted_8, [
                createVNode(unref(ne), {
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
              createBaseVNode("div", _hoisted_9, [
                createVNode(unref(ne), {
                  class: "ml-auto text-sm underline",
                  href: _ctx.route("password.request")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("Forgot your password?")), 1)
                  ]),
                  _: 1
                }, 8, ["href"]),
                createVNode(unref(ne), {
                  class: "text-sm underline",
                  href: _ctx.route("home")
                }, {
                  default: withCtx(() => [
                    createTextVNode("Retourner à l'accueil")
                  ]),
                  _: 1
                }, 8, ["href"])
              ])
            ], 40, _hoisted_3)
          ]),
          _: 1
        }),
        createVNode(_sfc_main$2)
      ]);
    };
  }
};
export {
  _sfc_main as default
};
