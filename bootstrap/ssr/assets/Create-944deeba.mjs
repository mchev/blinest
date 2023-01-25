import { unref, withCtx, createTextVNode, createVNode, toDisplayString, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-a34dfeea.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./TextEditor-7766120c.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
import "@tiptap/vue-3";
import "@tiptap/starter-kit";
const _sfc_main = {
  __name: "Create",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      title: "",
      content: ""
    });
    const store = () => {
      form.post("/admin/pages");
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
                  _push3(`<h1 class="text-xl font-bold"${_scopeId2}>`);
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
                  _push3(`<span class="font-medium text-indigo-400"${_scopeId2}>/</span> ${ssrInterpolate(_ctx.__("Create"))}</h1>`);
                } else {
                  return [
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
                      createTextVNode(" " + toDisplayString(_ctx.__("Create")), 1)
                    ])
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto mr-8",
                    type: "submit",
                    form: "createForm"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Create"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Create")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto mr-8",
                      type: "submit",
                      form: "createForm"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Create")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="createForm"${_scopeId2}><div class="p-8"${_scopeId2}>`);
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
                      onSubmit: withModifiers(store, ["prevent"]),
                      id: "createForm"
                    }, [
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
                    createTextVNode(" " + toDisplayString(_ctx.__("Create")), 1)
                  ])
                ]),
                footer: withCtx(() => [
                  createVNode(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto mr-8",
                    type: "submit",
                    form: "createForm"
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Create")), 1)
                    ]),
                    _: 1
                  }, 8, ["loading"])
                ]),
                default: withCtx(() => [
                  createVNode("form", {
                    onSubmit: withModifiers(store, ["prevent"]),
                    id: "createForm"
                  }, [
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Pages/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
