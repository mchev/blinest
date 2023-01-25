import { Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$3 } from "./AdminLayout-a34dfeea.mjs";
import { F as FileInput } from "./FileInput-916118e3.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$1 } from "./SelectInput-279cfc81.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { resolveComponent, withCtx, createTextVNode, createVNode, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderAttr } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
const _sfc_main = {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput: _sfc_main$1,
    TextInput: _sfc_main$2
  },
  layout: _sfc_main$3,
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
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_Link = resolveComponent("Link");
  const _component_text_input = resolveComponent("text-input");
  const _component_select_input = resolveComponent("select-input");
  const _component_file_input = resolveComponent("file-input");
  const _component_loading_button = resolveComponent("loading-button");
  _push(`<div${ssrRenderAttrs(_attrs)}>`);
  _push(ssrRenderComponent(_component_Head, { title: "Create User" }, null, _parent));
  _push(`<h1 class="mb-8 text-3xl font-bold">`);
  _push(ssrRenderComponent(_component_Link, {
    class: "text-blinest-400 hover:text-blinest-600",
    href: "/users"
  }, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`Users`);
      } else {
        return [
          createTextVNode("Users")
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(`<span class="font-medium text-blinest-400">/</span> Create </h1><div class="max-w-3xl overflow-hidden rounded-md bg-white shadow"><form><div class="-mb-8 -mr-6 flex flex-wrap p-8">`);
  _push(ssrRenderComponent(_component_text_input, {
    modelValue: $data.form.name,
    "onUpdate:modelValue": ($event) => $data.form.name = $event,
    error: $data.form.errors.name,
    class: "w-full pb-8 pr-6 lg:w-1/2",
    label: "Name"
  }, null, _parent));
  _push(ssrRenderComponent(_component_text_input, {
    modelValue: $data.form.email,
    "onUpdate:modelValue": ($event) => $data.form.email = $event,
    error: $data.form.errors.email,
    class: "w-full pb-8 pr-6 lg:w-1/2",
    label: "Email"
  }, null, _parent));
  _push(ssrRenderComponent(_component_text_input, {
    modelValue: $data.form.password,
    "onUpdate:modelValue": ($event) => $data.form.password = $event,
    error: $data.form.errors.password,
    class: "w-full pb-8 pr-6 lg:w-1/2",
    type: "password",
    autocomplete: "new-password",
    label: "Password"
  }, null, _parent));
  _push(ssrRenderComponent(_component_select_input, {
    modelValue: $data.form.team_id,
    "onUpdate:modelValue": ($event) => $data.form.team_id = $event,
    error: $data.form.errors.team_id,
    class: "w-full pb-8 pr-6 lg:w-1/2",
    label: "Team"
  }, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<option${ssrRenderAttr("value", true)}${_scopeId}>Yes</option><option${ssrRenderAttr("value", false)}${_scopeId}>No</option>`);
      } else {
        return [
          createVNode("option", { value: true }, "Yes"),
          createVNode("option", { value: false }, "No")
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(ssrRenderComponent(_component_select_input, {
    modelValue: $data.form.is_admin,
    "onUpdate:modelValue": ($event) => $data.form.is_admin = $event,
    error: $data.form.errors.is_admin,
    class: "w-full pb-8 pr-6 lg:w-1/2",
    label: "Admin"
  }, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<option${ssrRenderAttr("value", 0)}${_scopeId}>No</option><option${ssrRenderAttr("value", 1)}${_scopeId}>Yes</option>`);
      } else {
        return [
          createVNode("option", { value: 0 }, "No"),
          createVNode("option", { value: 1 }, "Yes")
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(ssrRenderComponent(_component_file_input, {
    modelValue: $data.form.photo,
    "onUpdate:modelValue": ($event) => $data.form.photo = $event,
    error: $data.form.errors.photo,
    class: "w-full pb-8 pr-6 lg:w-1/2",
    type: "file",
    accept: "image/*",
    label: "Photo"
  }, null, _parent));
  _push(`</div><div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">`);
  _push(ssrRenderComponent(_component_loading_button, {
    loading: $data.form.processing,
    class: "btn-primary",
    type: "submit"
  }, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`Create User`);
      } else {
        return [
          createTextVNode("Create User")
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(`</div></form></div></div>`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const Create = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
export {
  Create as default
};
