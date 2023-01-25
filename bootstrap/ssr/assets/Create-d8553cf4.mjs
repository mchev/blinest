import { unref, withCtx, createTextVNode, createVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent } from "vue/server-renderer";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-a34dfeea.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
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
      name: null
    });
    const store = () => {
      form.post("/admin/categories");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Create Category" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              class: "text-indigo-400 hover:text-indigo-600",
              href: "/admin/categories"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Categories`);
                } else {
                  return [
                    createTextVNode("Categories")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<span class="font-medium text-indigo-400"${_scopeId}>/</span> Create </h1><div class="max-w-3xl overflow-hidden rounded-md bg-white shadow"${_scopeId}><form${_scopeId}><div class="-mb-8 -mr-6 flex flex-wrap p-8"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).name,
              "onUpdate:modelValue": ($event) => unref(form).name = $event,
              error: unref(form).errors.name,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: "Name"
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4"${_scopeId}>`);
            _push2(ssrRenderComponent(LoadingButton, {
              loading: unref(form).processing,
              class: "btn-primary",
              type: "submit"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Create Category`);
                } else {
                  return [
                    createTextVNode("Create Category")
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
                  href: "/admin/categories"
                }, {
                  default: withCtx(() => [
                    createTextVNode("Categories")
                  ]),
                  _: 1
                }),
                createVNode("span", { class: "font-medium text-indigo-400" }, "/"),
                createTextVNode(" Create ")
              ]),
              createVNode("div", { class: "max-w-3xl overflow-hidden rounded-md bg-white shadow" }, [
                createVNode("form", {
                  onSubmit: withModifiers(store, ["prevent"])
                }, [
                  createVNode("div", { class: "-mb-8 -mr-6 flex flex-wrap p-8" }, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": ($event) => unref(form).name = $event,
                      error: unref(form).errors.name,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: "Name"
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
                  ]),
                  createVNode("div", { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Create Category")
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Categories/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
