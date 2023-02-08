import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, d as createTextVNode, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$2 } from "./TextareaInput-b614dddb.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { T as Tip } from "./Tip-da87eaf5.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./TextInput-541fe8b5.js";
import "./v4-e7604ebc.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
const _hoisted_1 = { class: "mx-auto flex w-full max-w-2xl flex-col" };
const _hoisted_2 = { class: "text-xl font-bold" };
const _hoisted_3 = {
  class: "underline",
  target: "_blank",
  href: "https://github.com/mchev/blinest/issues/new"
};
const _hoisted_4 = ["onSubmit"];
const _sfc_main = {
  __name: "Index",
  setup(__props) {
    const form = P({
      message: ""
    });
    const send = () => {
      form.post(route("contact.send"));
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Contact")
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              createVNode(Card, { class: "mb-4" }, {
                header: withCtx(() => [
                  createBaseVNode("h3", _hoisted_2, toDisplayString(_ctx.__("Send a message to")) + " Blinest", 1)
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
                      createBaseVNode("p", null, [
                        createTextVNode(" Pour rapporter un bug ou proposer une évolution sur le site merci de passer par là : "),
                        createBaseVNode("a", _hoisted_3, toDisplayString(_ctx.__("Report a bug")), 1)
                      ])
                    ]),
                    _: 1
                  }),
                  createBaseVNode("form", {
                    onSubmit: withModifiers(send, ["prevent"]),
                    id: "roomForm",
                    class: "mt-4"
                  }, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).message,
                      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).message = $event),
                      error: unref(form).errors.message,
                      rows: "10",
                      class: "mb-4 w-full",
                      label: _ctx.__("Message")
                    }, null, 8, ["modelValue", "error", "label"])
                  ], 40, _hoisted_4)
                ]),
                _: 1
              })
            ])
          ]),
          _: 1
        })
      ], 64);
    };
  }
};
export {
  _sfc_main as default
};
