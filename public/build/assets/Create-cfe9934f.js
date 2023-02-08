import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, d as createTextVNode, e as withModifiers } from "./app-910e457d.js";
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
const _hoisted_1 = { class: "text-xl font-bold" };
const _hoisted_2 = ["onSubmit"];
const _hoisted_3 = { class: "-mb-8 -mr-6 flex flex-wrap p-8" };
const _hoisted_4 = { class: "flex items-center justify-end px-8 py-4" };
const _sfc_main = {
  __name: "Create",
  setup(__props) {
    const form = P({
      name: ""
    });
    const store = () => {
      form.post("/teams");
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Create Team" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createVNode(Card, { class: "mx-auto max-w-xl" }, {
              header: withCtx(() => [
                createBaseVNode("h1", _hoisted_1, toDisplayString(_ctx.__("Creating a team")), 1)
              ]),
              default: withCtx(() => [
                createBaseVNode("form", {
                  onSubmit: withModifiers(store, ["prevent"])
                }, [
                  createBaseVNode("div", _hoisted_3, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                      error: unref(form).errors.name,
                      class: "w-full pb-8",
                      label: _ctx.__("Name")
                    }, null, 8, ["modelValue", "error", "label"])
                  ]),
                  createBaseVNode("div", _hoisted_4, [
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Create Team")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ])
                ], 40, _hoisted_2)
              ]),
              _: 1
            })
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
