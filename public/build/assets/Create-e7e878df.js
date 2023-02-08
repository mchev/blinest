import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, d as createTextVNode, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium text-blinest-400" }, "/", -1);
const _hoisted_3 = { class: "max-w-3xl overflow-hidden rounded-md bg-white shadow" };
const _hoisted_4 = ["onSubmit"];
const _hoisted_5 = { class: "-mb-8 -mr-6 flex flex-wrap p-8" };
const _hoisted_6 = { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" };
const _sfc_main = {
  __name: "Create",
  setup(__props) {
    const form = P({
      name: ""
    });
    const store = () => {
      form.post(route("admin.teams.store"));
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Create Team" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, [
              createVNode(unref(ne), {
                class: "text-blinest-400 hover:text-blinest-600",
                href: _ctx.route("admin.teams")
              }, {
                default: withCtx(() => [
                  createTextVNode("Teams")
                ]),
                _: 1
              }, 8, ["href"]),
              _hoisted_2,
              createTextVNode(" Create ")
            ]),
            createBaseVNode("div", _hoisted_3, [
              createBaseVNode("form", {
                onSubmit: withModifiers(store, ["prevent"])
              }, [
                createBaseVNode("div", _hoisted_5, [
                  createVNode(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                    error: unref(form).errors.name,
                    class: "w-full pb-8",
                    label: "Name"
                  }, null, 8, ["modelValue", "error"])
                ]),
                createBaseVNode("div", _hoisted_6, [
                  createVNode(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary",
                    type: "submit"
                  }, {
                    default: withCtx(() => [
                      createTextVNode("Create Team")
                    ]),
                    _: 1
                  }, 8, ["loading"])
                ])
              ], 40, _hoisted_4)
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
