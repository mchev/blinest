import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, d as createTextVNode, t as toDisplayString, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$3 } from "./TextEditor-ac4969f0.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = { class: "text-xl font-bold" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium text-indigo-400" }, "/", -1);
const _hoisted_3 = ["onSubmit"];
const _hoisted_4 = { class: "p-8" };
const _sfc_main = {
  __name: "Create",
  setup(__props) {
    const form = P({
      title: "",
      content: ""
    });
    const store = () => {
      form.post("/admin/pages");
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Create Category" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createVNode(Card, null, {
              header: withCtx(() => [
                createBaseVNode("h1", _hoisted_1, [
                  createVNode(unref(ne), {
                    class: "text-indigo-400 hover:text-indigo-600",
                    href: "/admin/pages"
                  }, {
                    default: withCtx(() => [
                      createTextVNode("Pages")
                    ]),
                    _: 1
                  }),
                  _hoisted_2,
                  createTextVNode(" " + toDisplayString(_ctx.__("Create")), 1)
                ])
              ]),
              footer: withCtx(() => [
                createVNode(LoadingButton, {
                  loading: unref(form).processing,
                  class: "btn-primary ml-auto mr-8",
                  type: "submit",
                  form: "createForm"
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("Create")), 1)
                  ]),
                  _: 1
                }, 8, ["loading"])
              ]),
              default: withCtx(() => [
                createBaseVNode("form", {
                  onSubmit: withModifiers(store, ["prevent"]),
                  id: "createForm"
                }, [
                  createBaseVNode("div", _hoisted_4, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).title,
                      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).title = $event),
                      error: unref(form).errors.title,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: "Title"
                    }, null, 8, ["modelValue", "error"]),
                    createVNode(_sfc_main$3, {
                      modelValue: unref(form).content,
                      "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).content = $event),
                      error: unref(form).errors.content,
                      class: "w-full pb-8 pr-6"
                    }, null, 8, ["modelValue", "error"])
                  ])
                ], 40, _hoisted_3)
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
