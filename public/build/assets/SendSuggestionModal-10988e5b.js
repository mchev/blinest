import { P, h as createBlock, w as withCtx, o as openBlock, a as createVNode, b as createBaseVNode, t as toDisplayString, u as unref, d as createTextVNode, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./Modal-f990bd3c.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { T as Tip } from "./Tip-da87eaf5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _sfc_main$2 } from "./TextareaInput-b614dddb.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = ["onSubmit"];
const _hoisted_2 = { class: "ml-auto flex items-center" };
const _sfc_main = {
  __name: "SendSuggestionModal",
  props: {
    show: Boolean,
    room: Object
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    const form = P({
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, {
        show: __props.show,
        onClose: close
      }, {
        default: withCtx(() => [
          createVNode(Card, null, {
            header: withCtx(() => [
              createBaseVNode("h2", null, toDisplayString(_ctx.__("Send a suggestion")), 1)
            ]),
            footer: withCtx(() => [
              createBaseVNode("div", _hoisted_2, [
                createBaseVNode("button", {
                  type: "button",
                  class: "btn-secondary mr-2",
                  onClick: _cache[1] || (_cache[1] = ($event) => _ctx.$emit("close"))
                }, toDisplayString(_ctx.__("Cancel")), 1),
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
              createBaseVNode("form", {
                onSubmit: withModifiers(submit, ["prevent"]),
                id: "suggestionForm"
              }, [
                createVNode(_sfc_main$2, {
                  modelValue: unref(form).suggestion,
                  "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).suggestion = $event),
                  error: unref(form).errors.suggestion,
                  class: "mb-4 w-full",
                  label: _ctx.__("What would you like to suggest?"),
                  required: ""
                }, null, 8, ["modelValue", "error", "label"]),
                createVNode(Tip, null, {
                  default: withCtx(() => [
                    createTextVNode("Inutile d'envoyer les suggestions séparement. Regrouper au maximum les idées sous forme de liste dans une seule suggestion, ce sera plus lisible pour les modérateurs.")
                  ]),
                  _: 1
                })
              ], 40, _hoisted_1)
            ]),
            _: 1
          })
        ]),
        _: 1
      }, 8, ["show"]);
    };
  }
};
export {
  _sfc_main as default
};
