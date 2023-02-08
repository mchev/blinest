import { mergeProps, unref, withCtx, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrInterpolate, ssrRenderAttr, ssrRenderComponent } from "vue/server-renderer";
import { usePage, useForm } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./TextInput-cddc091b.mjs";
import { C as CheckboxInput } from "./CheckboxInput-934baa4b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import "uuid";
import "./Icon-4a47e6e0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "EditOptionsForm",
  __ssrInlineRender: true,
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
    usePage().props.auth.user;
    const form = useForm({
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<form${ssrRenderAttrs(mergeProps({
        id: "optionsForm",
        class: "flex flex-wrap"
      }, _attrs))}><div class="flex w-full flex-wrap"><label for="tracks_by_round-range" class="mb-2 block text-sm font-medium">${ssrInterpolate(_ctx.__("Tracks by round"))} : <span class="font-bold">${ssrInterpolate(unref(form).tracks_by_round)}</span></label><input id="tracks_by_round-range" type="range" min="1" max="100"${ssrRenderAttr("value", unref(form).tracks_by_round)}${ssrRenderAttr("error", unref(form).errors.tracks_by_round)} step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"><label for="track_duration-range" class="mb-2 block text-sm font-medium">${ssrInterpolate(_ctx.__("Track duration"))} : <span class="font-bold">${ssrInterpolate(unref(form).track_duration)} ${ssrInterpolate(_ctx.__("seconds"))}</span></label><input id="track_duration-range" type="range" min="5" max="30"${ssrRenderAttr("value", unref(form).track_duration)}${ssrRenderAttr("error", unref(form).errors.track_duration)} step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"><label for="pause_between_tracks-range" class="mb-2 block text-sm font-medium">${ssrInterpolate(_ctx.__("Pause between tracks"))} : <span class="font-bold">${ssrInterpolate(unref(form).pause_between_tracks)} ${ssrInterpolate(_ctx.__("seconds"))}</span></label><input id="pause_between_tracks-range" type="range" min="0" max="30"${ssrRenderAttr("value", unref(form).pause_between_tracks)}${ssrRenderAttr("error", unref(form).errors.pause_between_tracks)} step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"><label for="pause_between_rounds-range" class="mb-2 block text-sm font-medium">${ssrInterpolate(_ctx.__("Pause between rounds"))} : <span class="font-bold">${ssrInterpolate(unref(form).pause_between_rounds)} ${ssrInterpolate(_ctx.__("seconds"))}</span></label><input id="pause_between_rounds-range" type="range" min="0" max="60"${ssrRenderAttr("value", unref(form).pause_between_rounds)}${ssrRenderAttr("error", unref(form).errors.pause_between_rounds)} step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700"></div><div class="flex w-full flex-wrap">`);
      _push(ssrRenderComponent(CheckboxInput, {
        modelValue: unref(form).is_chat_active,
        "onUpdate:modelValue": ($event) => unref(form).is_chat_active = $event,
        error: unref(form).errors.is_chat_active,
        class: "pr-4 pb-4",
        label: _ctx.__("Chatbox")
      }, null, _parent));
      _push(ssrRenderComponent(CheckboxInput, {
        modelValue: unref(form).is_autostart,
        "onUpdate:modelValue": ($event) => unref(form).is_autostart = $event,
        error: unref(form).errors.is_autostart,
        class: "pr-4 pb-4",
        label: _ctx.__("Autostart")
      }, null, _parent));
      _push(ssrRenderComponent(CheckboxInput, {
        modelValue: unref(form).has_password,
        "onUpdate:modelValue": ($event) => unref(form).has_password = $event,
        class: "w-full pr-4 pb-4 md:w-1/2",
        label: _ctx.__("Password")
      }, null, _parent));
      _push(`</div><div class="flex w-full flex-wrap">`);
      _push(ssrRenderComponent(_sfc_main$1, {
        style: unref(form).has_password ? null : { display: "none" },
        modelValue: unref(form).password,
        "onUpdate:modelValue": ($event) => unref(form).password = $event,
        error: unref(form).errors.password,
        class: "pb-6",
        type: "password",
        autocomplete: "new-password",
        label: _ctx.__("Password")
      }, null, _parent));
      _push(`</div><div class="ml-auto flex items-center gap-2"><button class="btn-secondary" type="button">${ssrInterpolate(_ctx.__("Close"))}</button>`);
      _push(ssrRenderComponent(LoadingButton, {
        loading: unref(form).processing,
        class: "btn-primary",
        form: "optionsForm",
        type: "submit"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(_ctx.__("Update"))}`);
          } else {
            return [
              createTextVNode(toDisplayString(_ctx.__("Update")), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></form>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/EditOptionsForm.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
