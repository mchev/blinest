import { mergeProps, unref, withCtx, createVNode, toDisplayString, openBlock, createBlock, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList } from "vue/server-renderer";
import { useForm } from "@inertiajs/vue3";
import "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$1 } from "./SelectInput-279cfc81.mjs";
const _sfc_main = {
  __name: "BanForm",
  __ssrInlineRender: true,
  props: {
    user: Object
  },
  emits: ["userBanned"],
  setup(__props, { emit }) {
    const form = useForm({
      comment: "",
      expired_at: null
    });
    const reasons = ["Pseudonyme inapproprié.", "Langage inapproprié.", "Propos injurieux, sexistes ou racistes.", "Menace ou harcèle d'autres joueurs.", "Donne les réponses dans le chat.", "Utilise un nouveau compte alors que le joueur a déjà été banni.", "Troll, spam.", "Triche."];
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<form${ssrRenderAttrs(mergeProps({ class: "m-4 rounded border p-4 text-sm" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$1, {
        modelValue: unref(form).expired_at,
        "onUpdate:modelValue": ($event) => unref(form).expired_at = $event,
        class: "mb-2",
        label: _ctx.__("Duration"),
        required: ""
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<option value="+1 day"${_scopeId}>${ssrInterpolate(_ctx.__("One day"))}</option><option value="+1 week"${_scopeId}>${ssrInterpolate(_ctx.__("One week"))}</option><option value="+1 month"${_scopeId}>${ssrInterpolate(_ctx.__("One month"))}</option><option${ssrRenderAttr("value", null)}${_scopeId}>${ssrInterpolate(_ctx.__("Unlimited"))}</option>`);
          } else {
            return [
              createVNode("option", { value: "+1 day" }, toDisplayString(_ctx.__("One day")), 1),
              createVNode("option", { value: "+1 week" }, toDisplayString(_ctx.__("One week")), 1),
              createVNode("option", { value: "+1 month" }, toDisplayString(_ctx.__("One month")), 1),
              createVNode("option", { value: null }, toDisplayString(_ctx.__("Unlimited")), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        modelValue: unref(form).comment,
        "onUpdate:modelValue": ($event) => unref(form).comment = $event,
        label: _ctx.__("Reason"),
        required: ""
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<!--[-->`);
            ssrRenderList(reasons, (reason) => {
              _push2(`<option${ssrRenderAttr("value", reason)}${_scopeId}>${ssrInterpolate(_ctx.__(reason))}</option>`);
            });
            _push2(`<!--]-->`);
          } else {
            return [
              (openBlock(), createBlock(Fragment, null, renderList(reasons, (reason) => {
                return createVNode("option", { value: reason }, toDisplayString(_ctx.__(reason)), 9, ["value"]);
              }), 64))
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<button type="submit" class="btn-danger btn-sm my-4">${ssrInterpolate(_ctx.__("Confirm and ban"))} ${ssrInterpolate(__props.user.name)}</button></form>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Moderation/BanForm.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
