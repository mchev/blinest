import { P, o as openBlock, c as createElementBlock, a as createVNode, w as withCtx, b as createBaseVNode, t as toDisplayString, u as unref, F as Fragment, v as renderList, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./SelectInput-5ecccdd8.js";
const _hoisted_1 = ["onSubmit"];
const _hoisted_2 = { value: "+1 day" };
const _hoisted_3 = { value: "+1 week" };
const _hoisted_4 = { value: "+1 month" };
const _hoisted_5 = { value: null };
const _hoisted_6 = ["value"];
const _sfc_main = {
  __name: "BanForm",
  props: {
    user: Object
  },
  emits: ["userBanned"],
  setup(__props, { emit }) {
    const props = __props;
    const form = P({
      comment: "",
      expired_at: null
    });
    const reasons = ["Pseudonyme inapproprié.", "Langage inapproprié.", "Propos injurieux, sexistes ou racistes.", "Menace ou harcèle d'autres joueurs.", "Donne les réponses dans le chat.", "Utilise un nouveau compte alors que le joueur a déjà été banni.", "Troll, spam.", "Triche."];
    const banUser = () => {
      form.post(`/moderation/users/${props.user.id}/ban`, {
        preserveScroll: false,
        preserveState: true,
        onSuccess: () => {
          emit("userBanned", true);
        }
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("form", {
        onSubmit: withModifiers(banUser, ["prevent"]),
        class: "m-4 rounded border p-4 text-sm"
      }, [
        createVNode(_sfc_main$1, {
          modelValue: unref(form).expired_at,
          "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).expired_at = $event),
          class: "mb-2",
          label: _ctx.__("Duration"),
          required: ""
        }, {
          default: withCtx(() => [
            createBaseVNode("option", _hoisted_2, toDisplayString(_ctx.__("One day")), 1),
            createBaseVNode("option", _hoisted_3, toDisplayString(_ctx.__("One week")), 1),
            createBaseVNode("option", _hoisted_4, toDisplayString(_ctx.__("One month")), 1),
            createBaseVNode("option", _hoisted_5, toDisplayString(_ctx.__("Unlimited")), 1)
          ]),
          _: 1
        }, 8, ["modelValue", "label"]),
        createVNode(_sfc_main$1, {
          modelValue: unref(form).comment,
          "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).comment = $event),
          label: _ctx.__("Reason"),
          required: ""
        }, {
          default: withCtx(() => [
            (openBlock(), createElementBlock(Fragment, null, renderList(reasons, (reason) => {
              return createBaseVNode("option", { value: reason }, toDisplayString(_ctx.__(reason)), 9, _hoisted_6);
            }), 64))
          ]),
          _: 1
        }, 8, ["modelValue", "label"]),
        createBaseVNode("button", {
          type: "submit",
          class: "btn-danger btn-sm my-4",
          onClick: banUser
        }, toDisplayString(_ctx.__("Confirm and ban")) + " " + toDisplayString(__props.user.name), 1)
      ], 40, _hoisted_1);
    };
  }
};
export {
  _sfc_main as _
};
