import { unref, withCtx, createTextVNode, openBlock, createBlock, Fragment, renderList, toDisplayString, createVNode, createCommentVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-c9eae547.mjs";
import { _ as _sfc_main$3 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$4 } from "./SelectInput-279cfc81.mjs";
import { _ as _sfc_main$5 } from "./FileInput-ae863f9e.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { _ as _sfc_main$2 } from "./TrashedMessage-ea9646dd.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
const _sfc_main = {
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    team: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
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
        router.delete(`/admin/teams/${props.team.id}`);
      }
    };
    const restore = () => {
      if (confirm("Are you sure you want to restore this team?")) {
        router.put(`/admin/teams/${props.team.id}/restore`);
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: unref(form).name
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="mb-8 flex items-center"${_scopeId}>`);
            if (__props.team.photo) {
              _push2(`<img class="mr-4 block h-8 w-8 rounded-full"${ssrRenderAttr("src", __props.team.photo)}${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<h1 class="text-3xl font-bold"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              class: "text-indigo-400 hover:text-indigo-600",
              href: "/admin/teams"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Teams`);
                } else {
                  return [
                    createTextVNode("Teams")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<span class="font-medium text-indigo-400"${_scopeId}>/</span> ${ssrInterpolate(unref(form).name)}</h1></div>`);
            if (__props.team.deleted_at) {
              _push2(ssrRenderComponent(_sfc_main$2, {
                class: "mb-6",
                onRestore: restore
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(` This team has been deleted. `);
                  } else {
                    return [
                      createTextVNode(" This team has been deleted. ")
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="max-w-3xl overflow-hidden rounded-md bg-white shadow"${_scopeId}><form${_scopeId}><div class="-mb-8 -mr-6 flex flex-wrap p-8"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$3, {
              modelValue: unref(form).name,
              "onUpdate:modelValue": ($event) => unref(form).name = $event,
              error: unref(form).errors.name,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: "Name"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$4, {
              modelValue: unref(form).user_id,
              "onUpdate:modelValue": ($event) => unref(form).user_id = $event,
              error: unref(form).errors.user_id,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: "Owner"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<!--[-->`);
                  ssrRenderList(__props.team.members, (member) => {
                    _push3(`<option${ssrRenderAttr("value", member.id)}${_scopeId2}>${ssrInterpolate(member.name)}</option>`);
                  });
                  _push3(`<!--]-->`);
                } else {
                  return [
                    (openBlock(true), createBlock(Fragment, null, renderList(__props.team.members, (member) => {
                      return openBlock(), createBlock("option", {
                        key: member.id,
                        value: member.id
                      }, toDisplayString(member.name), 9, ["value"]);
                    }), 128))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$5, {
              modelValue: unref(form).photo,
              "onUpdate:modelValue": ($event) => unref(form).photo = $event,
              error: unref(form).errors.photo,
              class: "w-full pb-8 pr-6",
              type: "file",
              accept: "image/*",
              label: _ctx.__("Photo")
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4"${_scopeId}>`);
            if (!__props.team.deleted_at) {
              _push2(`<button class="text-red-600 hover:underline" tabindex="-1" type="button"${_scopeId}>Delete Team</button>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(ssrRenderComponent(LoadingButton, {
              loading: unref(form).processing,
              class: "btn-primary ml-auto",
              type: "submit"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Update Team`);
                } else {
                  return [
                    createTextVNode("Update Team")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></form></div>`);
          } else {
            return [
              createVNode("div", { class: "mb-8 flex items-center" }, [
                __props.team.photo ? (openBlock(), createBlock("img", {
                  key: 0,
                  class: "mr-4 block h-8 w-8 rounded-full",
                  src: __props.team.photo
                }, null, 8, ["src"])) : createCommentVNode("", true),
                createVNode("h1", { class: "text-3xl font-bold" }, [
                  createVNode(unref(Link), {
                    class: "text-indigo-400 hover:text-indigo-600",
                    href: "/admin/teams"
                  }, {
                    default: withCtx(() => [
                      createTextVNode("Teams")
                    ]),
                    _: 1
                  }),
                  createVNode("span", { class: "font-medium text-indigo-400" }, "/"),
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
              createVNode("div", { class: "max-w-3xl overflow-hidden rounded-md bg-white shadow" }, [
                createVNode("form", {
                  onSubmit: withModifiers(update, ["prevent"])
                }, [
                  createVNode("div", { class: "-mb-8 -mr-6 flex flex-wrap p-8" }, [
                    createVNode(_sfc_main$3, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": ($event) => unref(form).name = $event,
                      error: unref(form).errors.name,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: "Name"
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                    createVNode(_sfc_main$4, {
                      modelValue: unref(form).user_id,
                      "onUpdate:modelValue": ($event) => unref(form).user_id = $event,
                      error: unref(form).errors.user_id,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: "Owner"
                    }, {
                      default: withCtx(() => [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.team.members, (member) => {
                          return openBlock(), createBlock("option", {
                            key: member.id,
                            value: member.id
                          }, toDisplayString(member.name), 9, ["value"]);
                        }), 128))
                      ]),
                      _: 1
                    }, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                    createVNode(_sfc_main$5, {
                      modelValue: unref(form).photo,
                      "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                      error: unref(form).errors.photo,
                      class: "w-full pb-8 pr-6",
                      type: "file",
                      accept: "image/*",
                      label: _ctx.__("Photo")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                  ]),
                  createVNode("div", { class: "flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                    !__props.team.deleted_at ? (openBlock(), createBlock("button", {
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
                ], 40, ["onSubmit"])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Teams/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
