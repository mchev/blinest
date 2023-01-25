import { unref, withCtx, createTextVNode, createVNode, toDisplayString, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-a34dfeea.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./TextareaInput-989d56d6.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
const _sfc_main = {
  __name: "Create",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      name: null,
      pronoun: null,
      svg_icon: null
    });
    const store = () => {
      form.post("/admin/answer_types");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Create Category" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h1 class="text-3xl font-bold"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(Link), {
                    class: "text-blue-400 hover:text-blue-600",
                    href: "/admin/answer_types"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Answer Types`);
                      } else {
                        return [
                          createTextVNode("Answer Types")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`<span class="font-medium text-blue-400"${_scopeId2}> /</span> Create </h1>`);
                } else {
                  return [
                    createVNode("h1", { class: "text-3xl font-bold" }, [
                      createVNode(unref(Link), {
                        class: "text-blue-400 hover:text-blue-600",
                        href: "/admin/answer_types"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("Answer Types")
                        ]),
                        _: 1
                      }),
                      createVNode("span", { class: "font-medium text-blue-400" }, " /"),
                      createTextVNode(" Create ")
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form${_scopeId2}><div class="-mb-8 -mr-6 flex flex-wrap p-8"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: _ctx.__("Name")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).pronoun,
                    "onUpdate:modelValue": ($event) => unref(form).pronoun = $event,
                    error: unref(form).errors.pronoun,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: _ctx.__("Pronoun")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).svg_icon,
                    "onUpdate:modelValue": ($event) => unref(form).svg_icon = $event,
                    error: unref(form).errors.svg_icon,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: _ctx.__("SVG Icon")
                  }, null, _parent3, _scopeId2));
                  _push3(`</div><div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Create Answer Type"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Create Answer Type")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</div></form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(store, ["prevent"])
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
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ]),
                      createVNode("div", { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                        createVNode(LoadingButton, {
                          loading: unref(form).processing,
                          class: "btn-primary",
                          type: "submit"
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Create Answer Type")), 1)
                          ]),
                          _: 1
                        }, 8, ["loading"])
                      ])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, null, {
                header: withCtx(() => [
                  createVNode("h1", { class: "text-3xl font-bold" }, [
                    createVNode(unref(Link), {
                      class: "text-blue-400 hover:text-blue-600",
                      href: "/admin/answer_types"
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Answer Types")
                      ]),
                      _: 1
                    }),
                    createVNode("span", { class: "font-medium text-blue-400" }, " /"),
                    createTextVNode(" Create ")
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("form", {
                    onSubmit: withModifiers(store, ["prevent"])
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
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                    ]),
                    createVNode("div", { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary",
                        type: "submit"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Create Answer Type")), 1)
                        ]),
                        _: 1
                      }, 8, ["loading"])
                    ])
                  ], 40, ["onSubmit"])
                ]),
                _: 1
              })
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/AnswerTypes/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
