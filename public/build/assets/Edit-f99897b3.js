import { X, n as ne, c as createElementBlock, a as createVNode, b as createBaseVNode, w as withCtx, d as createTextVNode, t as toDisplayString, g as createCommentVNode, h as createBlock, j as resolveComponent, o as openBlock, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$5 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$3 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$1 } from "./FileInput-ca2b29b7.js";
import { _ as _sfc_main$2 } from "./SelectInput-5ecccdd8.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _sfc_main$4 } from "./TrashedMessage-920d02d9.js";
import { C as Card } from "./Card-7ef4ce68.js";
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
    TextInput: _sfc_main$3,
    TrashedMessage: _sfc_main$4,
    Card
  },
  layout: _sfc_main$5,
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
const _hoisted_1 = { class: "mb-8 flex max-w-3xl justify-start" };
const _hoisted_2 = { class: "text-3xl font-bold" };
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium text-blinest-400" }, "/", -1);
const _hoisted_4 = ["src"];
const _hoisted_5 = { class: "-mb-8 -mr-6 flex flex-wrap p-8" };
const _hoisted_6 = /* @__PURE__ */ createBaseVNode("option", { value: 0 }, "Yes", -1);
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("option", { value: 1 }, "No", -1);
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("option", { value: 0 }, "No", -1);
const _hoisted_9 = /* @__PURE__ */ createBaseVNode("option", { value: 1 }, "Yes", -1);
const _hoisted_10 = { class: "flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4" };
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_Link = resolveComponent("Link");
  const _component_trashed_message = resolveComponent("trashed-message");
  const _component_text_input = resolveComponent("text-input");
  const _component_select_input = resolveComponent("select-input");
  const _component_file_input = resolveComponent("file-input");
  const _component_loading_button = resolveComponent("loading-button");
  const _component_card = resolveComponent("card");
  return openBlock(), createElementBlock("div", null, [
    createVNode(_component_Head, {
      title: `${$data.form.name}`
    }, null, 8, ["title"]),
    createBaseVNode("div", _hoisted_1, [
      createBaseVNode("h1", _hoisted_2, [
        createVNode(_component_Link, {
          class: "text-blinest-400 hover:text-blinest-600",
          href: _ctx.route("admin.users")
        }, {
          default: withCtx(() => [
            createTextVNode("Users")
          ]),
          _: 1
        }, 8, ["href"]),
        _hoisted_3,
        createTextVNode(" " + toDisplayString($data.form.name), 1)
      ]),
      $props.user.photo ? (openBlock(), createElementBlock("img", {
        key: 0,
        class: "ml-4 block h-8 w-8 rounded-full",
        src: $props.user.photo
      }, null, 8, _hoisted_4)) : createCommentVNode("", true)
    ]),
    $props.user.deleted_at ? (openBlock(), createBlock(_component_trashed_message, {
      key: 0,
      class: "mb-6",
      onRestore: $options.restore
    }, {
      default: withCtx(() => [
        createTextVNode(" This user has been deleted. ")
      ]),
      _: 1
    }, 8, ["onRestore"])) : createCommentVNode("", true),
    createVNode(_component_card, null, {
      default: withCtx(() => [
        createBaseVNode("form", {
          onSubmit: _cache[7] || (_cache[7] = withModifiers((...args) => $options.update && $options.update(...args), ["prevent"]))
        }, [
          createBaseVNode("div", _hoisted_5, [
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
                _hoisted_6,
                _hoisted_7
              ]),
              _: 1
            }, 8, ["modelValue", "error"]),
            createVNode(_component_select_input, {
              modelValue: $data.form.is_administrator,
              "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => $data.form.is_administrator = $event),
              error: $data.form.errors.is_administrator,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: "Admin"
            }, {
              default: withCtx(() => [
                _hoisted_8,
                _hoisted_9
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
          createBaseVNode("div", _hoisted_10, [
            !$props.user.deleted_at ? (openBlock(), createElementBlock("button", {
              key: 0,
              class: "text-red-600 hover:underline",
              tabindex: "-1",
              type: "button",
              onClick: _cache[6] || (_cache[6] = (...args) => $options.destroy && $options.destroy(...args))
            }, "Delete User")) : createCommentVNode("", true),
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
        ], 32)
      ]),
      _: 1
    })
  ]);
}
const Edit = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export {
  Edit as default
};
