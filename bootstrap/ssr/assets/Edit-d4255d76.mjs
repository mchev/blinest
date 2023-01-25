import { Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$4 } from "./AdminLayout-a34dfeea.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { F as FileInput } from "./FileInput-916118e3.mjs";
import { _ as _sfc_main$1 } from "./SelectInput-279cfc81.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { _ as _sfc_main$3 } from "./TrashedMessage-d7b7bcff.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { resolveComponent, withCtx, createTextVNode, createVNode, withModifiers, openBlock, createBlock, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
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
    TextInput: _sfc_main$2,
    TrashedMessage: _sfc_main$3,
    Card
  },
  layout: _sfc_main$4,
  props: {
    user: Object
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        name: this.user.name,
        email: this.user.email,
        password: "",
        team_id: this.user.team_id,
        is_administrator: this.user.is_administrator,
        photo: null
      })
    };
  },
  methods: {
    update() {
      this.form.post(route("admin.users.update", this.user.id), {
        onSuccess: () => this.form.reset("password", "photo")
      });
    },
    destroy() {
      if (confirm("Are you sure you want to delete this user?")) {
        this.$inertia.delete(route("admin.users.destroy", this.user.id));
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this user?")) {
        this.$inertia.put(route("admin.users.restore", this.user.id));
      }
    }
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_Link = resolveComponent("Link");
  const _component_trashed_message = resolveComponent("trashed-message");
  const _component_card = resolveComponent("card");
  const _component_text_input = resolveComponent("text-input");
  const _component_select_input = resolveComponent("select-input");
  const _component_file_input = resolveComponent("file-input");
  const _component_loading_button = resolveComponent("loading-button");
  _push(`<div${ssrRenderAttrs(_attrs)}>`);
  _push(ssrRenderComponent(_component_Head, {
    title: `${$data.form.name}`
  }, null, _parent));
  _push(`<div class="mb-8 flex max-w-3xl justify-start"><h1 class="text-3xl font-bold">`);
  _push(ssrRenderComponent(_component_Link, {
    class: "text-blinest-400 hover:text-blinest-600",
    href: _ctx.route("admin.users")
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
  _push(`<span class="font-medium text-blinest-400">/</span> ${ssrInterpolate($data.form.name)}</h1>`);
  if ($props.user.photo) {
    _push(`<img class="ml-4 block h-8 w-8 rounded-full"${ssrRenderAttr("src", $props.user.photo)}>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div>`);
  if ($props.user.deleted_at) {
    _push(ssrRenderComponent(_component_trashed_message, {
      class: "mb-6",
      onRestore: $options.restore
    }, {
      default: withCtx((_, _push2, _parent2, _scopeId) => {
        if (_push2) {
          _push2(` This user has been deleted. `);
        } else {
          return [
            createTextVNode(" This user has been deleted. ")
          ];
        }
      }),
      _: 1
    }, _parent));
  } else {
    _push(`<!---->`);
  }
  _push(ssrRenderComponent(_component_card, null, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<form${_scopeId}><div class="-mb-8 -mr-6 flex flex-wrap p-8"${_scopeId}>`);
        _push2(ssrRenderComponent(_component_text_input, {
          modelValue: $data.form.name,
          "onUpdate:modelValue": ($event) => $data.form.name = $event,
          error: $data.form.errors.name,
          class: "w-full pb-8 pr-6 lg:w-1/2",
          label: "Name"
        }, null, _parent2, _scopeId));
        _push2(ssrRenderComponent(_component_text_input, {
          modelValue: $data.form.email,
          "onUpdate:modelValue": ($event) => $data.form.email = $event,
          error: $data.form.errors.email,
          class: "w-full pb-8 pr-6 lg:w-1/2",
          label: "Email"
        }, null, _parent2, _scopeId));
        _push2(ssrRenderComponent(_component_text_input, {
          modelValue: $data.form.password,
          "onUpdate:modelValue": ($event) => $data.form.password = $event,
          error: $data.form.errors.password,
          class: "w-full pb-8 pr-6 lg:w-1/2",
          type: "password",
          autocomplete: "new-password",
          label: "Password"
        }, null, _parent2, _scopeId));
        _push2(ssrRenderComponent(_component_select_input, {
          modelValue: $data.form.team_id,
          "onUpdate:modelValue": ($event) => $data.form.team_id = $event,
          error: $data.form.errors.team_id,
          class: "w-full pb-8 pr-6 lg:w-1/2",
          label: "Team"
        }, {
          default: withCtx((_2, _push3, _parent3, _scopeId2) => {
            if (_push3) {
              _push3(`<option${ssrRenderAttr("value", 0)}${_scopeId2}>Yes</option><option${ssrRenderAttr("value", 1)}${_scopeId2}>No</option>`);
            } else {
              return [
                createVNode("option", { value: 0 }, "Yes"),
                createVNode("option", { value: 1 }, "No")
              ];
            }
          }),
          _: 1
        }, _parent2, _scopeId));
        _push2(ssrRenderComponent(_component_select_input, {
          modelValue: $data.form.is_administrator,
          "onUpdate:modelValue": ($event) => $data.form.is_administrator = $event,
          error: $data.form.errors.is_administrator,
          class: "w-full pb-8 pr-6 lg:w-1/2",
          label: "Admin"
        }, {
          default: withCtx((_2, _push3, _parent3, _scopeId2) => {
            if (_push3) {
              _push3(`<option${ssrRenderAttr("value", 0)}${_scopeId2}>No</option><option${ssrRenderAttr("value", 1)}${_scopeId2}>Yes</option>`);
            } else {
              return [
                createVNode("option", { value: 0 }, "No"),
                createVNode("option", { value: 1 }, "Yes")
              ];
            }
          }),
          _: 1
        }, _parent2, _scopeId));
        _push2(ssrRenderComponent(_component_file_input, {
          modelValue: $data.form.photo,
          "onUpdate:modelValue": ($event) => $data.form.photo = $event,
          error: $data.form.errors.photo,
          class: "w-full pb-8 pr-6 lg:w-1/2",
          type: "file",
          accept: "image/*",
          label: "Photo"
        }, null, _parent2, _scopeId));
        _push2(`</div><div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4"${_scopeId}>`);
        if (!$props.user.deleted_at) {
          _push2(`<button class="text-red-600 hover:underline" tabindex="-1" type="button"${_scopeId}>Delete User</button>`);
        } else {
          _push2(`<!---->`);
        }
        _push2(ssrRenderComponent(_component_loading_button, {
          loading: $data.form.processing,
          class: "btn-primary ml-auto",
          type: "submit"
        }, {
          default: withCtx((_2, _push3, _parent3, _scopeId2) => {
            if (_push3) {
              _push3(`Update User`);
            } else {
              return [
                createTextVNode("Update User")
              ];
            }
          }),
          _: 1
        }, _parent2, _scopeId));
        _push2(`</div></form>`);
      } else {
        return [
          createVNode("form", {
            onSubmit: withModifiers($options.update, ["prevent"])
          }, [
            createVNode("div", { class: "-mb-8 -mr-6 flex flex-wrap p-8" }, [
              createVNode(_component_text_input, {
                modelValue: $data.form.name,
                "onUpdate:modelValue": ($event) => $data.form.name = $event,
                error: $data.form.errors.name,
                class: "w-full pb-8 pr-6 lg:w-1/2",
                label: "Name"
              }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
              createVNode(_component_text_input, {
                modelValue: $data.form.email,
                "onUpdate:modelValue": ($event) => $data.form.email = $event,
                error: $data.form.errors.email,
                class: "w-full pb-8 pr-6 lg:w-1/2",
                label: "Email"
              }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
              createVNode(_component_text_input, {
                modelValue: $data.form.password,
                "onUpdate:modelValue": ($event) => $data.form.password = $event,
                error: $data.form.errors.password,
                class: "w-full pb-8 pr-6 lg:w-1/2",
                type: "password",
                autocomplete: "new-password",
                label: "Password"
              }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
              createVNode(_component_select_input, {
                modelValue: $data.form.team_id,
                "onUpdate:modelValue": ($event) => $data.form.team_id = $event,
                error: $data.form.errors.team_id,
                class: "w-full pb-8 pr-6 lg:w-1/2",
                label: "Team"
              }, {
                default: withCtx(() => [
                  createVNode("option", { value: 0 }, "Yes"),
                  createVNode("option", { value: 1 }, "No")
                ]),
                _: 1
              }, 8, ["modelValue", "onUpdate:modelValue", "error"]),
              createVNode(_component_select_input, {
                modelValue: $data.form.is_administrator,
                "onUpdate:modelValue": ($event) => $data.form.is_administrator = $event,
                error: $data.form.errors.is_administrator,
                class: "w-full pb-8 pr-6 lg:w-1/2",
                label: "Admin"
              }, {
                default: withCtx(() => [
                  createVNode("option", { value: 0 }, "No"),
                  createVNode("option", { value: 1 }, "Yes")
                ]),
                _: 1
              }, 8, ["modelValue", "onUpdate:modelValue", "error"]),
              createVNode(_component_file_input, {
                modelValue: $data.form.photo,
                "onUpdate:modelValue": ($event) => $data.form.photo = $event,
                error: $data.form.errors.photo,
                class: "w-full pb-8 pr-6 lg:w-1/2",
                type: "file",
                accept: "image/*",
                label: "Photo"
              }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
            ]),
            createVNode("div", { class: "flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
              !$props.user.deleted_at ? (openBlock(), createBlock("button", {
                key: 0,
                class: "text-red-600 hover:underline",
                tabindex: "-1",
                type: "button",
                onClick: $options.destroy
              }, "Delete User", 8, ["onClick"])) : createCommentVNode("", true),
              createVNode(_component_loading_button, {
                loading: $data.form.processing,
                class: "btn-primary ml-auto",
                type: "submit"
              }, {
                default: withCtx(() => [
                  createTextVNode("Update User")
                ]),
                _: 1
              }, 8, ["loading"])
            ])
          ], 40, ["onSubmit"])
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(`</div>`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const Edit = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
export {
  Edit as default
};
