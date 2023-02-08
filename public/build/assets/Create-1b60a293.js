import { X, n as ne, c as createElementBlock, a as createVNode, b as createBaseVNode, w as withCtx, d as createTextVNode, e as withModifiers, j as resolveComponent, o as openBlock } from "./app-910e457d.js";
import { _ as _sfc_main$4 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$1 } from "./FileInput-ca2b29b7.js";
import { _ as _sfc_main$3 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$2 } from "./SelectInput-5ecccdd8.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.js";
import "./LanguageSwitcher-da420d03.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
const _sfc_main = {
  components: {
    FileInput: _sfc_main$1,
    Head: X,
    Link: ne,
    LoadingButton,
    SelectInput: _sfc_main$2,
    TextInput: _sfc_main$3
  },
  layout: _sfc_main$4,
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        name: "",
        last_name: "",
        email: "",
        password: "",
        team_id: null,
        is_admin: false,
        photo: null
      })
    };
  },
  methods: {
    store() {
      this.form.post(route("admin.users.store"));
    }
  }
};
const _hoisted_1 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium text-blinest-400" }, "/", -1);
const _hoisted_3 = { class: "max-w-3xl overflow-hidden rounded-md bg-white shadow" };
const _hoisted_4 = { class: "-mb-8 -mr-6 flex flex-wrap p-8" };
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("option", { value: true }, "Yes", -1);
const _hoisted_6 = /* @__PURE__ */ createBaseVNode("option", { value: false }, "No", -1);
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("option", { value: 0 }, "No", -1);
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("option", { value: 1 }, "Yes", -1);
const _hoisted_9 = { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" };
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_Link = resolveComponent("Link");
  const _component_text_input = resolveComponent("text-input");
  const _component_select_input = resolveComponent("select-input");
  const _component_file_input = resolveComponent("file-input");
  const _component_loading_button = resolveComponent("loading-button");
  return openBlock(), createElementBlock("div", null, [
    createVNode(_component_Head, { title: "Create User" }),
    createBaseVNode("h1", _hoisted_1, [
      createVNode(_component_Link, {
        class: "text-blinest-400 hover:text-blinest-600",
        href: "/users"
      }, {
        default: withCtx(() => [
          createTextVNode("Users")
        ]),
        _: 1
      }),
      _hoisted_2,
      createTextVNode(" Create ")
    ]),
    createBaseVNode("div", _hoisted_3, [
      createBaseVNode("form", {
        onSubmit: _cache[6] || (_cache[6] = withModifiers((...args) => $options.store && $options.store(...args), ["prevent"]))
      }, [
        createBaseVNode("div", _hoisted_4, [
          createVNode(_component_text_input, {
            modelValue: $data.form.name,
            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => $data.form.name = $event),
            error: $data.form.errors.name,
            class: "w-full pb-8 pr-6 lg:w-1/2",
            label: "Name"
          }, null, 8, ["modelValue", "error"]),
          createVNode(_component_text_input, {
            modelValue: $data.form.email,
            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => $data.form.email = $event),
            error: $data.form.errors.email,
            class: "w-full pb-8 pr-6 lg:w-1/2",
            label: "Email"
          }, null, 8, ["modelValue", "error"]),
          createVNode(_component_text_input, {
            modelValue: $data.form.password,
            "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => $data.form.password = $event),
            error: $data.form.errors.password,
            class: "w-full pb-8 pr-6 lg:w-1/2",
            type: "password",
            autocomplete: "new-password",
            label: "Password"
          }, null, 8, ["modelValue", "error"]),
          createVNode(_component_select_input, {
            modelValue: $data.form.team_id,
            "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => $data.form.team_id = $event),
            error: $data.form.errors.team_id,
            class: "w-full pb-8 pr-6 lg:w-1/2",
            label: "Team"
          }, {
            default: withCtx(() => [
              _hoisted_5,
              _hoisted_6
            ]),
            _: 1
          }, 8, ["modelValue", "error"]),
          createVNode(_component_select_input, {
            modelValue: $data.form.is_admin,
            "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => $data.form.is_admin = $event),
            error: $data.form.errors.is_admin,
            class: "w-full pb-8 pr-6 lg:w-1/2",
            label: "Admin"
          }, {
            default: withCtx(() => [
              _hoisted_7,
              _hoisted_8
            ]),
            _: 1
          }, 8, ["modelValue", "error"]),
          createVNode(_component_file_input, {
            modelValue: $data.form.photo,
            "onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => $data.form.photo = $event),
            error: $data.form.errors.photo,
            class: "w-full pb-8 pr-6 lg:w-1/2",
            type: "file",
            accept: "image/*",
            label: "Photo"
          }, null, 8, ["modelValue", "error"])
        ]),
        createBaseVNode("div", _hoisted_9, [
          createVNode(_component_loading_button, {
            loading: $data.form.processing,
            class: "btn-primary",
            type: "submit"
          }, {
            default: withCtx(() => [
              createTextVNode("Create User")
            ]),
            _: 1
          }, 8, ["loading"])
        ])
      ], 32)
    ])
  ]);
}
const Create = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export {
  Create as default
};
