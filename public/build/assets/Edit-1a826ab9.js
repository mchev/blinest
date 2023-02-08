import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, g as createCommentVNode, n as ne, d as createTextVNode, t as toDisplayString, h as createBlock, e as withModifiers, v as renderList, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$3 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$4 } from "./SelectInput-5ecccdd8.js";
import { _ as _sfc_main$5 } from "./FileInput-ca2b29b7.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _sfc_main$2 } from "./TrashedMessage-920d02d9.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = { class: "mb-8 flex items-center" };
const _hoisted_2 = ["src"];
const _hoisted_3 = { class: "text-3xl font-bold" };
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium text-indigo-400" }, "/", -1);
const _hoisted_5 = { class: "max-w-3xl overflow-hidden rounded-md bg-white shadow" };
const _hoisted_6 = ["onSubmit"];
const _hoisted_7 = { class: "-mb-8 -mr-6 flex flex-wrap p-8" };
const _hoisted_8 = ["value"];
const _hoisted_9 = { class: "flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4" };
const _sfc_main = {
  __name: "Edit",
  props: {
    team: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
      _method: "put",
      // required to post files
      name: props.team.name,
      user_id: props.team.user_id,
      photo: null
    });
    const update = () => {
      form.post(`/admin/teams/${props.team.id}`);
    };
    const destroy = () => {
      if (confirm("Are you sure you want to delete this team?")) {
        Je.delete(`/admin/teams/${props.team.id}`);
      }
    };
    const restore = () => {
      if (confirm("Are you sure you want to restore this team?")) {
        Je.put(`/admin/teams/${props.team.id}/restore`);
      }
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: unref(form).name
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              __props.team.photo ? (openBlock(), createElementBlock("img", {
                key: 0,
                class: "mr-4 block h-8 w-8 rounded-full",
                src: __props.team.photo
              }, null, 8, _hoisted_2)) : createCommentVNode("", true),
              createBaseVNode("h1", _hoisted_3, [
                createVNode(unref(ne), {
                  class: "text-indigo-400 hover:text-indigo-600",
                  href: "/admin/teams"
                }, {
                  default: withCtx(() => [
                    createTextVNode("Teams")
                  ]),
                  _: 1
                }),
                _hoisted_4,
                createTextVNode(" " + toDisplayString(unref(form).name), 1)
              ])
            ]),
            __props.team.deleted_at ? (openBlock(), createBlock(_sfc_main$2, {
              key: 0,
              class: "mb-6",
              onRestore: restore
            }, {
              default: withCtx(() => [
                createTextVNode(" This team has been deleted. ")
              ]),
              _: 1
            })) : createCommentVNode("", true),
            createBaseVNode("div", _hoisted_5, [
              createBaseVNode("form", {
                onSubmit: withModifiers(update, ["prevent"])
              }, [
                createBaseVNode("div", _hoisted_7, [
                  createVNode(_sfc_main$3, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                    error: unref(form).errors.name,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: "Name"
                  }, null, 8, ["modelValue", "error"]),
                  createVNode(_sfc_main$4, {
                    modelValue: unref(form).user_id,
                    "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).user_id = $event),
                    error: unref(form).errors.user_id,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: "Owner"
                  }, {
                    default: withCtx(() => [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(__props.team.members, (member) => {
                        return openBlock(), createElementBlock("option", {
                          key: member.id,
                          value: member.id
                        }, toDisplayString(member.name), 9, _hoisted_8);
                      }), 128))
                    ]),
                    _: 1
                  }, 8, ["modelValue", "error"]),
                  createVNode(_sfc_main$5, {
                    modelValue: unref(form).photo,
                    "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).photo = $event),
                    error: unref(form).errors.photo,
                    class: "w-full pb-8 pr-6",
                    type: "file",
                    accept: "image/*",
                    label: _ctx.__("Photo")
                  }, null, 8, ["modelValue", "error", "label"])
                ]),
                createBaseVNode("div", _hoisted_9, [
                  !__props.team.deleted_at ? (openBlock(), createElementBlock("button", {
                    key: 0,
                    class: "text-red-600 hover:underline",
                    tabindex: "-1",
                    type: "button",
                    onClick: destroy
                  }, "Delete Team")) : createCommentVNode("", true),
                  createVNode(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    type: "submit"
                  }, {
                    default: withCtx(() => [
                      createTextVNode("Update Team")
                    ]),
                    _: 1
                  }, 8, ["loading"])
                ])
              ], 40, _hoisted_6)
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
