import { unref, withCtx, createVNode, toDisplayString, createTextVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
const _sfc_main = {
  __name: "Create",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      name: ""
    });
    const store = () => {
      form.post("/teams");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Create Team" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, { class: "mx-auto max-w-xl" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h1 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Creating a team"))}</h1>`);
                } else {
                  return [
                    createVNode("h1", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Creating a team")), 1)
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
                    class: "w-full pb-8",
                    label: _ctx.__("Name")
                  }, null, _parent3, _scopeId2));
                  _push3(`</div><div class="flex items-center justify-end px-8 py-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Create Team"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Create Team")), 1)
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
                          class: "w-full pb-8",
                          label: _ctx.__("Name")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ]),
                      createVNode("div", { class: "flex items-center justify-end px-8 py-4" }, [
                        createVNode(LoadingButton, {
                          loading: unref(form).processing,
                          class: "btn-primary",
                          type: "submit"
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Create Team")), 1)
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
              createVNode(Card, { class: "mx-auto max-w-xl" }, {
                header: withCtx(() => [
                  createVNode("h1", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Creating a team")), 1)
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
                        class: "w-full pb-8",
                        label: _ctx.__("Name")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                    ]),
                    createVNode("div", { class: "flex items-center justify-end px-8 py-4" }, [
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary",
                        type: "submit"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Create Team")), 1)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Teams/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};