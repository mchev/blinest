import { P, h as createBlock, w as withCtx, o as openBlock, a as createVNode, u as unref, X, d as createTextVNode, t as toDisplayString, b as createBaseVNode, f as normalizeClass, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { C as Card } from "./Card-7ef4ce68.js";
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
const _hoisted_1 = ["onSubmit"];
const _hoisted_2 = { class: "mt-6 mb-4 flex items-center justify-end" };
const _sfc_main = {
  __name: "ResetPassword",
  props: {
    email: String,
    token: String
  },
  setup(__props) {
    const props = __props;
    const form = P({
      token: props.token,
      email: props.email,
      password: "",
      password_confirmation: ""
    });
    const submit = () => {
      form.post(route("password.update"), {
        onFinish: () => form.reset("password", "password_confirmation")
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, null, {
        default: withCtx(() => [
          createVNode(unref(X), {
            title: _ctx.__("Reset Password")
          }, null, 8, ["title"]),
          createVNode(Card, { class: "mx-auto max-w-xl" }, {
            header: withCtx(() => [
              createTextVNode(toDisplayString(_ctx.__("Reset Password")), 1)
            ]),
            default: withCtx(() => [
              createBaseVNode("form", {
                onSubmit: withModifiers(submit, ["prevent"])
              }, [
                createBaseVNode("div", null, [
                  createVNode(_sfc_main$2, {
                    type: "email",
                    label: "Email",
                    modelValue: unref(form).email,
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).email = $event),
                    error: unref(form).errors.email,
                    required: "",
                    autofocus: ""
                  }, null, 8, ["modelValue", "error"]),
                  createVNode(_sfc_main$2, {
                    type: "password",
                    label: _ctx.__("Password"),
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).password = $event),
                    error: unref(form).errors.password,
                    required: "",
                    autofocus: ""
                  }, null, 8, ["label", "modelValue", "error"]),
                  createVNode(_sfc_main$2, {
                    type: "password",
                    label: _ctx.__("Confirm Password"),
                    modelValue: unref(form).password_confirmation,
                    "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).password_confirmation = $event),
                    error: unref(form).errors.password_confirmation,
                    required: "",
                    autofocus: ""
                  }, null, 8, ["label", "modelValue", "error"])
                ]),
                createBaseVNode("div", _hoisted_2, [
                  createVNode(LoadingButton, {
                    class: normalizeClass([{ "opacity-25": unref(form).processing }, "btn-primary"]),
                    disabled: unref(form).processing,
                    loading: unref(form).processing
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Reset Password")), 1)
                    ]),
                    _: 1
                  }, 8, ["class", "disabled", "loading"])
                ])
              ], 40, _hoisted_1)
            ]),
            _: 1
          })
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
