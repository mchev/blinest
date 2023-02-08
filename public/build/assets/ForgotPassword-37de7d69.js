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
const _hoisted_1 = { class: "mb-4" };
const _hoisted_2 = ["onSubmit"];
const _hoisted_3 = { class: "mt-6 mb-4 flex items-center justify-end" };
const _sfc_main = {
  __name: "ForgotPassword",
  setup(__props) {
    const form = P({
      email: ""
    });
    const submit = () => {
      form.post(route("password.email"));
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, null, {
        default: withCtx(() => [
          createVNode(unref(X), {
            title: _ctx.__("Forgot your password?")
          }, null, 8, ["title"]),
          createVNode(Card, { class: "mx-auto max-w-xl" }, {
            header: withCtx(() => [
              createTextVNode(toDisplayString(_ctx.__("Forgot your password?")), 1)
            ]),
            default: withCtx(() => [
              createBaseVNode("p", _hoisted_1, toDisplayString(_ctx.__("Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.")), 1),
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
                  }, null, 8, ["modelValue", "error"])
                ]),
                createBaseVNode("div", _hoisted_3, [
                  createVNode(LoadingButton, {
                    class: normalizeClass([{ "opacity-25": unref(form).processing }, "btn-primary"]),
                    disabled: unref(form).processing,
                    loading: unref(form).processing
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Email Password Reset Link")), 1)
                    ]),
                    _: 1
                  }, 8, ["class", "disabled", "loading"])
                ])
              ], 40, _hoisted_2)
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
