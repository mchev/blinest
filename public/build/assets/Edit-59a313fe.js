import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, d as createTextVNode, t as toDisplayString, e as withModifiers, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$3 } from "./TextareaInput-b614dddb.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium text-indigo-400" }, "/", -1);
const _hoisted_3 = { class: "max-w-3xl overflow-hidden rounded-md bg-white shadow" };
const _hoisted_4 = ["onSubmit"];
const _hoisted_5 = { class: "-mb-8 -mr-6 flex flex-wrap p-8" };
const _hoisted_6 = ["innerHTML"];
const _hoisted_7 = { class: "flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4" };
const _sfc_main = {
  __name: "Edit",
  props: {
    answer_type: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
      name: props.answer_type.name,
      pronoun: props.answer_type.pronoun,
      svg_icon: props.answer_type.svg_icon
    });
    const update = () => {
      form.put(`/admin/answer_types/${props.answer_type.id}`);
    };
    const destroy = () => {
      if (confirm("Are you sure you want to delete this answer_type?")) {
        Je.delete(`/admin/answer_types/${props.answer_type.id}`);
      }
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: unref(form).name
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, [
              createVNode(unref(ne), {
                class: "text-indigo-400 hover:text-indigo-600",
                href: "/admin/answer_types"
              }, {
                default: withCtx(() => [
                  createTextVNode("Answer Types")
                ]),
                _: 1
              }),
              _hoisted_2,
              createTextVNode(" " + toDisplayString(unref(form).name), 1)
            ]),
            createBaseVNode("div", _hoisted_3, [
              createBaseVNode("form", {
                onSubmit: withModifiers(update, ["prevent"])
              }, [
                createBaseVNode("div", _hoisted_5, [
                  createVNode(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                    error: unref(form).errors.name,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: _ctx.__("Name")
                  }, null, 8, ["modelValue", "error", "label"]),
                  createVNode(_sfc_main$2, {
                    modelValue: unref(form).pronoun,
                    "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).pronoun = $event),
                    error: unref(form).errors.pronoun,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: _ctx.__("Pronoun")
                  }, null, 8, ["modelValue", "error", "label"]),
                  createVNode(_sfc_main$3, {
                    modelValue: unref(form).svg_icon,
                    "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).svg_icon = $event),
                    error: unref(form).errors.svg_icon,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: _ctx.__("SVG Icon")
                  }, null, 8, ["modelValue", "error", "label"]),
                  createBaseVNode("span", {
                    innerHTML: unref(form).svg_icon
                  }, null, 8, _hoisted_6)
                ]),
                createBaseVNode("div", _hoisted_7, [
                  createBaseVNode("button", {
                    class: "text-red-600 hover:underline",
                    tabindex: "-1",
                    type: "button",
                    onClick: destroy
                  }, "Delete Answer Type"),
                  createVNode(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    type: "submit"
                  }, {
                    default: withCtx(() => [
                      createTextVNode("Update Answer Type")
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