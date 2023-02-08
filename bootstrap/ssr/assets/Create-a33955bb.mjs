import { unref, withCtx, createTextVNode, createVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr } from "vue/server-renderer";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-c9eae547.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./SelectInput-279cfc81.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
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
      name: "",
      is_public: 0
    });
    const store = () => {
      form.post("/admin/playlists");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Create Playlist" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              class: "text-blinest-400 hover:text-blinest-600",
              href: _ctx.route("admin.playlists")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Playlists`);
                } else {
                  return [
                    createTextVNode("Playlists")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<span class="font-medium text-blinest-400"${_scopeId}>/</span> Create </h1>`);
            _push2(ssrRenderComponent(Card, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form${_scopeId2}><div class="-mb-8 -mr-6 flex flex-wrap p-8"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: "Name"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).is_public,
                    "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                    error: unref(form).errors.is_public,
                    class: "w-full pb-8 pr-6 lg:w-1/2",
                    label: "Public"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`<option${ssrRenderAttr("value", 1)}${_scopeId3}>Yes</option><option${ssrRenderAttr("value", 0)}${_scopeId3}>No</option>`);
                      } else {
                        return [
                          createVNode("option", { value: 1 }, "Yes"),
                          createVNode("option", { value: 0 }, "No")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</div><div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Create Playlist`);
                      } else {
                        return [
                          createTextVNode("Create Playlist")
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
                          label: "Name"
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                        createVNode(_sfc_main$3, {
                          modelValue: unref(form).is_public,
                          "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                          error: unref(form).errors.is_public,
                          class: "w-full pb-8 pr-6 lg:w-1/2",
                          label: "Public"
                        }, {
                          default: withCtx(() => [
                            createVNode("option", { value: 1 }, "Yes"),
                            createVNode("option", { value: 0 }, "No")
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue", "error"])
                      ]),
                      createVNode("div", { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                        createVNode(LoadingButton, {
                          loading: unref(form).processing,
                          class: "btn-primary",
                          type: "submit"
                        }, {
                          default: withCtx(() => [
                            createTextVNode("Create Playlist")
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
              createVNode("h1", { class: "mb-8 text-3xl font-bold" }, [
                createVNode(unref(Link), {
                  class: "text-blinest-400 hover:text-blinest-600",
                  href: _ctx.route("admin.playlists")
                }, {
                  default: withCtx(() => [
                    createTextVNode("Playlists")
                  ]),
                  _: 1
                }, 8, ["href"]),
                createVNode("span", { class: "font-medium text-blinest-400" }, "/"),
                createTextVNode(" Create ")
              ]),
              createVNode(Card, null, {
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
                        label: "Name"
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).is_public,
                        "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                        error: unref(form).errors.is_public,
                        class: "w-full pb-8 pr-6 lg:w-1/2",
                        label: "Public"
                      }, {
                        default: withCtx(() => [
                          createVNode("option", { value: 1 }, "Yes"),
                          createVNode("option", { value: 0 }, "No")
                        ]),
                        _: 1
                      }, 8, ["modelValue", "onUpdate:modelValue", "error"])
                    ]),
                    createVNode("div", { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary",
                        type: "submit"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("Create Playlist")
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Playlists/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
