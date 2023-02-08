import { unref, withCtx, createTextVNode, createVNode, toDisplayString, withModifiers, openBlock, createBlock, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-c9eae547.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./TextEditor-b9003510.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
import "@tiptap/vue-3";
import "@tiptap/starter-kit";
const _sfc_main = {
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    page: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      title: props.page.title,
      content: props.page.content
    });
    const update = () => {
      form.put(`/admin/pages/${props.page.id}`);
    };
    const destroy = () => {
      if (confirm("Are you sure you want to delete this page?")) {
        router.delete(`/admin/pages/${props.page.id}`);
      }
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
                  _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h1 class="text-xl font-bold"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(Link), {
                    class: "text-indigo-400 hover:text-indigo-600",
                    href: "/admin/pages"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Pages`);
                      } else {
                        return [
                          createTextVNode("Pages")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`<span class="font-medium text-indigo-400"${_scopeId2}>/</span> ${ssrInterpolate(_ctx.__("Update"))}</h1><small class="text-xs text-neutral-500"${_scopeId2}>${ssrInterpolate(_ctx.__("Last revision"))} : ${ssrInterpolate(__props.page.date)}</small></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex w-full items-center justify-between" }, [
                      createVNode("h1", { class: "text-xl font-bold" }, [
                        createVNode(unref(Link), {
                          class: "text-indigo-400 hover:text-indigo-600",
                          href: "/admin/pages"
                        }, {
                          default: withCtx(() => [
                            createTextVNode("Pages")
                          ]),
                          _: 1
                        }),
                        createVNode("span", { class: "font-medium text-indigo-400" }, "/"),
                        createTextVNode(" " + toDisplayString(_ctx.__("Update")), 1)
                      ]),
                      createVNode("small", { class: "text-xs text-neutral-500" }, toDisplayString(_ctx.__("Last revision")) + " : " + toDisplayString(__props.page.date), 1)
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
                  _push3(`<form id="editForm"${_scopeId2}>`);
                  if (__props.page.url) {
                    _push3(ssrRenderComponent(unref(Link), {
                      href: __props.page.url,
                      class: "px-8 underline"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(__props.page.url)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(__props.page.url), 1)
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`<div class="p-8"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).title,
                    "onUpdate:modelValue": ($event) => unref(form).title = $event,
                    error: unref(form).errors.title,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: "Title"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).content,
                    "onUpdate:modelValue": ($event) => unref(form).content = $event,
                    error: unref(form).errors.content,
                    class: "w-full pb-8 pr-6"
                  }, null, _parent3, _scopeId2));
                  _push3(`</div></form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "editForm"
                    }, [
                      __props.page.url ? (openBlock(), createBlock(unref(Link), {
                        key: 0,
                        href: __props.page.url,
                        class: "px-8 underline"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(__props.page.url), 1)
                        ]),
                        _: 1
                      }, 8, ["href"])) : createCommentVNode("", true),
                      createVNode("div", { class: "p-8" }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).title,
                          "onUpdate:modelValue": ($event) => unref(form).title = $event,
                          error: unref(form).errors.title,
                          class: "w-full pb-8 pr-6 lg:w-1/2",
                          label: "Title"
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                        createVNode(_sfc_main$3, {
                          modelValue: unref(form).content,
                          "onUpdate:modelValue": ($event) => unref(form).content = $event,
                          error: unref(form).errors.content,
                          class: "w-full pb-8 pr-6"
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
                  createVNode("div", { class: "flex w-full items-center justify-between" }, [
                    createVNode("h1", { class: "text-xl font-bold" }, [
                      createVNode(unref(Link), {
                        class: "text-indigo-400 hover:text-indigo-600",
                        href: "/admin/pages"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("Pages")
                        ]),
                        _: 1
                      }),
                      createVNode("span", { class: "font-medium text-indigo-400" }, "/"),
                      createTextVNode(" " + toDisplayString(_ctx.__("Update")), 1)
                    ]),
                    createVNode("small", { class: "text-xs text-neutral-500" }, toDisplayString(_ctx.__("Last revision")) + " : " + toDisplayString(__props.page.date), 1)
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
                    __props.page.url ? (openBlock(), createBlock(unref(Link), {
                      key: 0,
                      href: __props.page.url,
                      class: "px-8 underline"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(__props.page.url), 1)
                      ]),
                      _: 1
                    }, 8, ["href"])) : createCommentVNode("", true),
                    createVNode("div", { class: "p-8" }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).title,
                        "onUpdate:modelValue": ($event) => unref(form).title = $event,
                        error: unref(form).errors.title,
                        class: "w-full pb-8 pr-6 lg:w-1/2",
                        label: "Title"
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).content,
                        "onUpdate:modelValue": ($event) => unref(form).content = $event,
                        error: unref(form).errors.content,
                        class: "w-full pb-8 pr-6"
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Pages/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
