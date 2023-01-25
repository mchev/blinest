import { mergeProps, withCtx, createVNode, toDisplayString, unref, createTextVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./Modal-e38f3366.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { T as Tip } from "./Tip-9a139e5c.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { _ as _sfc_main$2 } from "./TextareaInput-989d56d6.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "uuid";
const _sfc_main = {
  __name: "SendSuggestionModal",
  __ssrInlineRender: true,
  props: {
    show: Boolean,
    room: Object
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    const form = useForm({
      suggestion: ""
    });
    const submit = () => {
      form.post(route("rooms.suggestions", props.room.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          form.reset();
          emit("close");
        }
      });
    };
    const close = () => {
      emit("close");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, mergeProps({
        show: __props.show,
        onClose: close
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h2${_scopeId2}>${ssrInterpolate(_ctx.__("Send a suggestion"))}</h2>`);
                } else {
                  return [
                    createVNode("h2", null, toDisplayString(_ctx.__("Send a suggestion")), 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex items-center ml-auto"${_scopeId2}><button type="button" class="btn-secondary mr-2"${_scopeId2}>${ssrInterpolate(_ctx.__("Cancel"))}</button>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary",
                    form: "suggestionForm",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Send"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Send")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex items-center ml-auto" }, [
                      createVNode("button", {
                        type: "button",
                        class: "btn-secondary mr-2",
                        onClick: ($event) => _ctx.$emit("close")
                      }, toDisplayString(_ctx.__("Cancel")), 9, ["onClick"]),
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary",
                        form: "suggestionForm",
                        type: "submit"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Send")), 1)
                        ]),
                        _: 1
                      }, 8, ["loading"])
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="suggestionForm"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).suggestion,
                    "onUpdate:modelValue": ($event) => unref(form).suggestion = $event,
                    error: unref(form).errors.suggestion,
                    class: "mb-4 w-full",
                    label: _ctx.__("What would you like to suggest?"),
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(Tip, null, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Inutile d&#39;envoyer les suggestions séparement. Regrouper au maximum les idées sous forme de liste dans une seule suggestion, ce sera plus lisible pour les modérateurs.`);
                      } else {
                        return [
                          createTextVNode("Inutile d'envoyer les suggestions séparement. Regrouper au maximum les idées sous forme de liste dans une seule suggestion, ce sera plus lisible pour les modérateurs.")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(submit, ["prevent"]),
                      id: "suggestionForm"
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).suggestion,
                        "onUpdate:modelValue": ($event) => unref(form).suggestion = $event,
                        error: unref(form).errors.suggestion,
                        class: "mb-4 w-full",
                        label: _ctx.__("What would you like to suggest?"),
                        required: ""
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(Tip, null, {
                        default: withCtx(() => [
                          createTextVNode("Inutile d'envoyer les suggestions séparement. Regrouper au maximum les idées sous forme de liste dans une seule suggestion, ce sera plus lisible pour les modérateurs.")
                        ]),
                        _: 1
                      })
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
                  createVNode("h2", null, toDisplayString(_ctx.__("Send a suggestion")), 1)
                ]),
                footer: withCtx(() => [
                  createVNode("div", { class: "flex items-center ml-auto" }, [
                    createVNode("button", {
                      type: "button",
                      class: "btn-secondary mr-2",
                      onClick: ($event) => _ctx.$emit("close")
                    }, toDisplayString(_ctx.__("Cancel")), 9, ["onClick"]),
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary",
                      form: "suggestionForm",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Send")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("form", {
                    onSubmit: withModifiers(submit, ["prevent"]),
                    id: "suggestionForm"
                  }, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).suggestion,
                      "onUpdate:modelValue": ($event) => unref(form).suggestion = $event,
                      error: unref(form).errors.suggestion,
                      class: "mb-4 w-full",
                      label: _ctx.__("What would you like to suggest?"),
                      required: ""
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                    createVNode(Tip, null, {
                      default: withCtx(() => [
                        createTextVNode("Inutile d'envoyer les suggestions séparement. Regrouper au maximum les idées sous forme de liste dans une seule suggestion, ce sera plus lisible pour les modérateurs.")
                      ]),
                      _: 1
                    })
                  ], 40, ["onSubmit"])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/SendSuggestionModal.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
