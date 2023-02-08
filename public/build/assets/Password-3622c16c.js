import { P, h as createBlock, w as withCtx, o as openBlock, a as createVNode, b as createBaseVNode, t as toDisplayString, u as unref, d as createTextVNode, e as withModifiers, n as ne } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { T as Tip } from "./Tip-da87eaf5.js";
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
const _hoisted_1 = { class: "flex flex-col" };
const _hoisted_2 = { class: "text-xl uppercase" };
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("p", { class: "mt-6 flex items-center text-orange-400" }, [
  /* @__PURE__ */ createBaseVNode("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    fill: "none",
    viewBox: "0 0 24 24",
    "stroke-width": "1.5",
    stroke: "currentColor",
    class: "mr-2 h-5 w-5"
  }, [
    /* @__PURE__ */ createBaseVNode("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      d: "M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
    })
  ]),
  /* @__PURE__ */ createTextVNode(" Cette room est protégée par un mot de passe ")
], -1);
const _hoisted_4 = ["onSubmit"];
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "mr-2 h-5 w-5"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
  })
], -1);
const _sfc_main = {
  __name: "Password",
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
      password: null
    });
    const submit = () => {
      if (form.password && form.password.length > 0)
        form.get(`/rooms/${props.room.slug}`);
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, null, {
        default: withCtx(() => [
          createVNode(Card, { class: "mx-auto max-w-xl p-12" }, {
            header: withCtx(() => [
              createBaseVNode("div", _hoisted_1, [
                createBaseVNode("h1", _hoisted_2, toDisplayString(__props.room.name), 1),
                _hoisted_3
              ])
            ]),
            default: withCtx(() => [
              createBaseVNode("form", {
                onSubmit: withModifiers(submit, ["prevent"])
              }, [
                createVNode(_sfc_main$2, {
                  type: "passsword",
                  modelValue: unref(form).password,
                  "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).password = $event),
                  error: unref(form).errors.password,
                  label: _ctx.__("Password")
                }, null, 8, ["modelValue", "error", "label"]),
                createVNode(LoadingButton, {
                  loading: unref(form).processing,
                  class: "btn-primary mt-4 ml-auto"
                }, {
                  default: withCtx(() => [
                    _hoisted_5,
                    createTextVNode(" " + toDisplayString(_ctx.__("Login")), 1)
                  ]),
                  _: 1
                }, 8, ["loading"])
              ], 40, _hoisted_4)
            ]),
            _: 1
          }),
          createVNode(Tip, { class: "mx-auto max-w-xl" }, {
            default: withCtx(() => [
              createTextVNode("Si vous êtes le propriétaire de la room et que vous avez oublié le mot de passe, vous pouvez le modifier sur "),
              createVNode(unref(ne), {
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
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
