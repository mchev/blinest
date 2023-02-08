import { P, h as createBlock, w as withCtx, o as openBlock, a as createVNode, u as unref, X, b as createBaseVNode, t as toDisplayString, e as withModifiers, d as createTextVNode } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { C as Card } from "./Card-7ef4ce68.js";
import _sfc_main$3 from "./Socialite-eb75d06f.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = { class: "mx-auto flex flex-wrap justify-center gap-4 lg:max-w-3xl" };
const _hoisted_2 = { class: "text-center text-xl font-bold" };
const _hoisted_3 = ["onSubmit"];
const _hoisted_4 = { class: "p-4" };
const _hoisted_5 = { class: "flex px-10 py-4" };
const _sfc_main = {
  __name: "Register",
  setup(__props) {
    const form = P({
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, null, {
        default: withCtx(() => [
          createVNode(unref(X), {
            title: _ctx.__("Login")
          }, null, 8, ["title"]),
          createBaseVNode("div", _hoisted_1, [
            createVNode(Card, { class: "flex flex-grow" }, {
              header: withCtx(() => [
                createBaseVNode("h1", _hoisted_2, toDisplayString(_ctx.__("Register")), 1)
              ]),
              default: withCtx(() => [
                createBaseVNode("form", {
                  onSubmit: withModifiers(register, ["prevent"])
                }, [
                  createBaseVNode("div", _hoisted_4, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                      error: unref(form).errors.name,
                      label: _ctx.__("Name") + " / " + _ctx.__("Nickname"),
                      autofocus: "",
                      autocapitalize: "off",
                      required: ""
                    }, null, 8, ["modelValue", "error", "label"]),
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).email,
                      "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).email = $event),
                      error: unref(form).errors.email,
                      class: "mt-10",
                      label: _ctx.__("Email"),
                      type: "email",
                      autofocus: "",
                      autocapitalize: "off",
                      required: ""
                    }, null, 8, ["modelValue", "error", "label"]),
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).password,
                      "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).password = $event),
                      error: unref(form).errors.password,
                      class: "mt-10",
                      label: _ctx.__("Password"),
                      type: "password",
                      required: ""
                    }, null, 8, ["modelValue", "error", "label"]),
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).password_confirmation,
                      "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).password_confirmation = $event),
                      error: unref(form).errors.password_confirmation,
                      class: "mt-10",
                      label: _ctx.__("Confirm password"),
                      type: "password",
                      required: "",
                      autocomplete: "new-password"
                    }, null, 8, ["modelValue", "error", "label"])
                  ]),
                  createBaseVNode("div", _hoisted_5, [
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
                ], 40, _hoisted_3)
              ]),
              _: 1
            }),
            createVNode(_sfc_main$3)
          ])
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
