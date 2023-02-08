import { unref, withCtx, createTextVNode, createVNode, toDisplayString, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-c9eae547.mjs";
import { _ as _sfc_main$2 } from "./SelectInput-279cfc81.mjs";
import { _ as _sfc_main$3 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$4 } from "./TextareaInput-989d56d6.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
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
    faq: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      locale: props.faq.locale,
      question: props.faq.question,
      answer: props.faq.answer
    });
    const update = () => {
      form.put(`/admin/faqs/${props.faq.id}`);
    };
    const destroy = () => {
      if (confirm("Are you sure you want to delete this FAQ?")) {
        router.delete(`/admin/faqs/${props.faq.id}`);
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Edit FAQ" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h1 class="text-xl font-bold"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(Link), {
                    class: "text-indigo-400 hover:text-indigo-600",
                    href: "/admin/faqs"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`FAQ`);
                      } else {
                        return [
                          createTextVNode("FAQ")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`<span class="font-medium text-indigo-400"${_scopeId2}> /</span> ${ssrInterpolate(_ctx.__("Create"))}</h1>`);
                } else {
                  return [
                    createVNode("h1", { class: "text-xl font-bold" }, [
                      createVNode(unref(Link), {
                        class: "text-indigo-400 hover:text-indigo-600",
                        href: "/admin/faqs"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("FAQ")
                        ]),
                        _: 1
                      }),
                      createVNode("span", { class: "font-medium text-indigo-400" }, " /"),
                      createTextVNode(" " + toDisplayString(_ctx.__("Create")), 1)
                    ])
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<button class="text-red-600 hover:underline" tabindex="-1" type="button"${_scopeId2}>${ssrInterpolate(_ctx.__("Delete"))}</button>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto mr-8",
                    type: "submit",
                    form: "editForm"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Update"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("button", {
                      class: "text-red-600 hover:underline",
                      tabindex: "-1",
                      type: "button",
                      onClick: destroy
                    }, toDisplayString(_ctx.__("Delete")), 1),
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto mr-8",
                      type: "submit",
                      form: "editForm"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="editForm"${_scopeId2}><div class="p-8"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).locale,
                    "onUpdate:modelValue": ($event) => unref(form).locale = $event,
                    error: unref(form).errors.locale,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: "Locale",
                    required: ""
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`<option value="fr"${_scopeId3}>FR</option><option value="en"${_scopeId3}>EN</option>`);
                      } else {
                        return [
                          createVNode("option", { value: "fr" }, "FR"),
                          createVNode("option", { value: "en" }, "EN")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).question,
                    "onUpdate:modelValue": ($event) => unref(form).question = $event,
                    error: unref(form).errors.question,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: "Question",
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    rows: "10",
                    modelValue: unref(form).answer,
                    "onUpdate:modelValue": ($event) => unref(form).answer = $event,
                    error: unref(form).errors.answer,
                    class: "w-full pb-8 pr-6",
                    label: "Réponse",
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(`</div></form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "editForm"
                    }, [
                      createVNode("div", { class: "p-8" }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).locale,
                          "onUpdate:modelValue": ($event) => unref(form).locale = $event,
                          error: unref(form).errors.locale,
                          class: "w-full pb-8 pr-6 lg:w-1/2",
                          label: "Locale",
                          required: ""
                        }, {
                          default: withCtx(() => [
                            createVNode("option", { value: "fr" }, "FR"),
                            createVNode("option", { value: "en" }, "EN")
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                        createVNode(_sfc_main$3, {
                          modelValue: unref(form).question,
                          "onUpdate:modelValue": ($event) => unref(form).question = $event,
                          error: unref(form).errors.question,
                          class: "w-full pb-8 pr-6 lg:w-1/2",
                          label: "Question",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                        createVNode(_sfc_main$4, {
                          rows: "10",
                          modelValue: unref(form).answer,
                          "onUpdate:modelValue": ($event) => unref(form).answer = $event,
                          error: unref(form).errors.answer,
                          class: "w-full pb-8 pr-6",
                          label: "Réponse",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
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
                  createVNode("h1", { class: "text-xl font-bold" }, [
                    createVNode(unref(Link), {
                      class: "text-indigo-400 hover:text-indigo-600",
                      href: "/admin/faqs"
                    }, {
                      default: withCtx(() => [
                        createTextVNode("FAQ")
                      ]),
                      _: 1
                    }),
                    createVNode("span", { class: "font-medium text-indigo-400" }, " /"),
                    createTextVNode(" " + toDisplayString(_ctx.__("Create")), 1)
                  ])
                ]),
                footer: withCtx(() => [
                  createVNode("button", {
                    class: "text-red-600 hover:underline",
                    tabindex: "-1",
                    type: "button",
                    onClick: destroy
                  }, toDisplayString(_ctx.__("Delete")), 1),
                  createVNode(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto mr-8",
                    type: "submit",
                    form: "editForm"
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                    ]),
                    _: 1
                  }, 8, ["loading"])
                ]),
                default: withCtx(() => [
                  createVNode("form", {
                    onSubmit: withModifiers(update, ["prevent"]),
                    id: "editForm"
                  }, [
                    createVNode("div", { class: "p-8" }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).locale,
                        "onUpdate:modelValue": ($event) => unref(form).locale = $event,
                        error: unref(form).errors.locale,
                        class: "w-full pb-8 pr-6 lg:w-1/2",
                        label: "Locale",
                        required: ""
                      }, {
                        default: withCtx(() => [
                          createVNode("option", { value: "fr" }, "FR"),
                          createVNode("option", { value: "en" }, "EN")
                        ]),
                        _: 1
                      }, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).question,
                        "onUpdate:modelValue": ($event) => unref(form).question = $event,
                        error: unref(form).errors.question,
                        class: "w-full pb-8 pr-6 lg:w-1/2",
                        label: "Question",
                        required: ""
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                      createVNode(_sfc_main$4, {
                        rows: "10",
                        modelValue: unref(form).answer,
                        "onUpdate:modelValue": ($event) => unref(form).answer = $event,
                        error: unref(form).errors.answer,
                        class: "w-full pb-8 pr-6",
                        label: "Réponse",
                        required: ""
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/FAQ/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
