import { unref, withCtx, createTextVNode, createVNode, toDisplayString, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-a34dfeea.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./TextareaInput-989d56d6.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
const _sfc_main = {
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    answer_type: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      name: props.answer_type.name,
      pronoun: props.answer_type.pronoun,
      svg_icon: props.answer_type.svg_icon
    });
    const update = () => {
      form.put(`/admin/answer_types/${props.answer_type.id}`);
    };
    const destroy = () => {
      if (confirm("Are you sure you want to delete this answer_type?")) {
        router.delete(`/admin/answer_types/${props.answer_type.id}`);
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
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              class: "text-indigo-400 hover:text-indigo-600",
              href: "/admin/answer_types"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Answer Types`);
                } else {
                  return [
                    createTextVNode("Answer Types")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<span class="font-medium text-indigo-400"${_scopeId}>/</span> ${ssrInterpolate(unref(form).name)}</h1><div class="max-w-3xl overflow-hidden rounded-md bg-white shadow"${_scopeId}><form${_scopeId}><div class="-mb-8 -mr-6 flex flex-wrap p-8"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).name,
              "onUpdate:modelValue": ($event) => unref(form).name = $event,
              error: unref(form).errors.name,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: _ctx.__("Name")
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).pronoun,
              "onUpdate:modelValue": ($event) => unref(form).pronoun = $event,
              error: unref(form).errors.pronoun,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: _ctx.__("Pronoun")
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$3, {
              modelValue: unref(form).svg_icon,
              "onUpdate:modelValue": ($event) => unref(form).svg_icon = $event,
              error: unref(form).errors.svg_icon,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: _ctx.__("SVG Icon")
            }, null, _parent2, _scopeId));
            _push2(`<span${_scopeId}>${unref(form).svg_icon}</span></div><div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4"${_scopeId}><button class="text-red-600 hover:underline" tabindex="-1" type="button"${_scopeId}>Delete Answer Type</button>`);
            _push2(ssrRenderComponent(LoadingButton, {
              loading: unref(form).processing,
              class: "btn-primary ml-auto",
              type: "submit"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Update Answer Type`);
                } else {
                  return [
                    createTextVNode("Update Answer Type")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></form></div>`);
          } else {
            return [
              createVNode("h1", { class: "mb-8 text-3xl font-bold" }, [
                createVNode(unref(Link), {
                  class: "text-indigo-400 hover:text-indigo-600",
                  href: "/admin/answer_types"
                }, {
                  default: withCtx(() => [
                    createTextVNode("Answer Types")
                  ]),
                  _: 1
                }),
                createVNode("span", { class: "font-medium text-indigo-400" }, "/"),
                createTextVNode(" " + toDisplayString(unref(form).name), 1)
              ]),
              createVNode("div", { class: "max-w-3xl overflow-hidden rounded-md bg-white shadow" }, [
                createVNode("form", {
                  onSubmit: withModifiers(update, ["prevent"])
                }, [
                  createVNode("div", { class: "-mb-8 -mr-6 flex flex-wrap p-8" }, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": ($event) => unref(form).name = $event,
                      error: unref(form).errors.name,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: _ctx.__("Name")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).pronoun,
                      "onUpdate:modelValue": ($event) => unref(form).pronoun = $event,
                      error: unref(form).errors.pronoun,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: _ctx.__("Pronoun")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                    createVNode(_sfc_main$3, {
                      modelValue: unref(form).svg_icon,
                      "onUpdate:modelValue": ($event) => unref(form).svg_icon = $event,
                      error: unref(form).errors.svg_icon,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: _ctx.__("SVG Icon")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                    createVNode("span", {
                      innerHTML: unref(form).svg_icon
                    }, null, 8, ["innerHTML"])
                  ]),
                  createVNode("div", { class: "flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                    createVNode("button", {
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/AnswerTypes/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
