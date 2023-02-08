import { J, P, c as createElementBlock, b as createBaseVNode, d as createTextVNode, t as toDisplayString, u as unref, z as withDirectives, V as vModelText, a as createVNode, N as vShow, w as withCtx, e as withModifiers, o as openBlock } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./TextInput-541fe8b5.js";
import { C as CheckboxInput } from "./CheckboxInput-45662aca.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = ["onSubmit"];
const _hoisted_2 = { class: "flex w-full flex-wrap" };
const _hoisted_3 = {
  for: "tracks_by_round-range",
  class: "mb-2 block text-sm font-medium"
};
const _hoisted_4 = { class: "font-bold" };
const _hoisted_5 = ["error"];
const _hoisted_6 = {
  for: "track_duration-range",
  class: "mb-2 block text-sm font-medium"
};
const _hoisted_7 = { class: "font-bold" };
const _hoisted_8 = ["error"];
const _hoisted_9 = {
  for: "pause_between_tracks-range",
  class: "mb-2 block text-sm font-medium"
};
const _hoisted_10 = { class: "font-bold" };
const _hoisted_11 = ["error"];
const _hoisted_12 = {
  for: "pause_between_rounds-range",
  class: "mb-2 block text-sm font-medium"
};
const _hoisted_13 = { class: "font-bold" };
const _hoisted_14 = ["error"];
const _hoisted_15 = { class: "flex w-full flex-wrap" };
const _hoisted_16 = { class: "flex w-full flex-wrap" };
const _hoisted_17 = { class: "ml-auto flex items-center gap-2" };
const _sfc_main = {
  __name: "EditOptionsForm",
  props: {
    room: Object,
    modal: {
      type: Boolean,
      default: false
    }
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    J().props.auth.user;
    const form = P({
      is_chat_active: props.room.is_chat_active,
      is_autostart: props.room.is_autostart,
      color: props.room.color,
      has_password: props.room.password ? true : false,
      password: props.room.password,
      tracks_by_round: props.room.tracks_by_round,
      track_duration: props.room.track_duration,
      pause_between_tracks: props.room.pause_between_tracks,
      pause_between_rounds: props.room.pause_between_rounds
    });
    const update = () => {
      form.put(route("rooms.options", props.room.id), {
        preserveScroll: true,
        onSuccess: () => {
          emit("close");
        }
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("form", {
        onSubmit: withModifiers(update, ["prevent"]),
        id: "optionsForm",
        class: "flex flex-wrap"
      }, [
        createBaseVNode("div", _hoisted_2, [
          createBaseVNode("label", _hoisted_3, [
            createTextVNode(toDisplayString(_ctx.__("Tracks by round")) + " : ", 1),
            createBaseVNode("span", _hoisted_4, toDisplayString(unref(form).tracks_by_round), 1)
          ]),
          withDirectives(createBaseVNode("input", {
            id: "tracks_by_round-range",
            type: "range",
            min: "1",
            max: "100",
            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).tracks_by_round = $event),
            error: unref(form).errors.tracks_by_round,
            step: "1",
            class: "mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"
          }, null, 8, _hoisted_5), [
            [vModelText, unref(form).tracks_by_round]
          ]),
          createBaseVNode("label", _hoisted_6, [
            createTextVNode(toDisplayString(_ctx.__("Track duration")) + " : ", 1),
            createBaseVNode("span", _hoisted_7, toDisplayString(unref(form).track_duration) + " " + toDisplayString(_ctx.__("seconds")), 1)
          ]),
          withDirectives(createBaseVNode("input", {
            id: "track_duration-range",
            type: "range",
            min: "5",
            max: "30",
            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).track_duration = $event),
            error: unref(form).errors.track_duration,
            step: "1",
            class: "mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"
          }, null, 8, _hoisted_8), [
            [vModelText, unref(form).track_duration]
          ]),
          createBaseVNode("label", _hoisted_9, [
            createTextVNode(toDisplayString(_ctx.__("Pause between tracks")) + " : ", 1),
            createBaseVNode("span", _hoisted_10, toDisplayString(unref(form).pause_between_tracks) + " " + toDisplayString(_ctx.__("seconds")), 1)
          ]),
          withDirectives(createBaseVNode("input", {
            id: "pause_between_tracks-range",
            type: "range",
            min: "0",
            max: "30",
            "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).pause_between_tracks = $event),
            error: unref(form).errors.pause_between_tracks,
            step: "1",
            class: "mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"
          }, null, 8, _hoisted_11), [
            [vModelText, unref(form).pause_between_tracks]
          ]),
          createBaseVNode("label", _hoisted_12, [
            createTextVNode(toDisplayString(_ctx.__("Pause between rounds")) + " : ", 1),
            createBaseVNode("span", _hoisted_13, toDisplayString(unref(form).pause_between_rounds) + " " + toDisplayString(_ctx.__("seconds")), 1)
          ]),
          withDirectives(createBaseVNode("input", {
            id: "pause_between_rounds-range",
            type: "range",
            min: "0",
            max: "60",
            "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).pause_between_rounds = $event),
            error: unref(form).errors.pause_between_rounds,
            step: "1",
            class: "mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"
          }, null, 8, _hoisted_14), [
            [vModelText, unref(form).pause_between_rounds]
          ])
        ]),
        createBaseVNode("div", _hoisted_15, [
          createVNode(CheckboxInput, {
            modelValue: unref(form).is_chat_active,
            "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => unref(form).is_chat_active = $event),
            error: unref(form).errors.is_chat_active,
            class: "pr-4 pb-4",
            label: _ctx.__("Chatbox")
          }, null, 8, ["modelValue", "error", "label"]),
          createVNode(CheckboxInput, {
            modelValue: unref(form).is_autostart,
            "onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => unref(form).is_autostart = $event),
            error: unref(form).errors.is_autostart,
            class: "pr-4 pb-4",
            label: _ctx.__("Autostart")
          }, null, 8, ["modelValue", "error", "label"]),
          createVNode(CheckboxInput, {
            modelValue: unref(form).has_password,
            "onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => unref(form).has_password = $event),
            class: "w-full pr-4 pb-4 md:w-1/2",
            label: _ctx.__("Password")
          }, null, 8, ["modelValue", "label"])
        ]),
        createBaseVNode("div", _hoisted_16, [
          withDirectives(createVNode(_sfc_main$1, {
            modelValue: unref(form).password,
            "onUpdate:modelValue": _cache[7] || (_cache[7] = ($event) => unref(form).password = $event),
            error: unref(form).errors.password,
            class: "pb-6",
            type: "password",
            autocomplete: "new-password",
            label: _ctx.__("Password")
          }, null, 8, ["modelValue", "error", "label"]), [
            [vShow, unref(form).has_password]
          ])
        ]),
        createBaseVNode("div", _hoisted_17, [
          createBaseVNode("button", {
            onClick: _cache[8] || (_cache[8] = ($event) => _ctx.$emit("close")),
            class: "btn-secondary",
            type: "button"
          }, toDisplayString(_ctx.__("Close")), 1),
          createVNode(LoadingButton, {
            loading: unref(form).processing,
            class: "btn-primary",
            form: "optionsForm",
            type: "submit"
          }, {
            default: withCtx(() => [
              createTextVNode(toDisplayString(_ctx.__("Update")), 1)
            ]),
            _: 1
          }, 8, ["loading"])
        ])
      ], 40, _hoisted_1);
    };
  }
};
export {
  _sfc_main as default
};
