import { unref, withCtx, createTextVNode, toDisplayString, createVNode, withModifiers, withDirectives, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-c9eae547.mjs";
import { _ as _sfc_main$4 } from "./FileInput-ae863f9e.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./TextareaInput-989d56d6.mjs";
import "./SelectInput-279cfc81.mjs";
import { C as CheckboxInput } from "./CheckboxInput-934baa4b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import "lodash/pickBy.js";
import "lodash/throttle.js";
import "lodash/mapValues.js";
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
      description: "",
      is_public: false,
      is_pro: false,
      is_random: true,
      is_active: true,
      is_chat_active: true,
      discord_webhook_url: "",
      color: "",
      password: "",
      tracks_by_round: 15,
      track_duration: 30,
      pause_between_tracks: 0,
      pause_between_rounds: 10,
      photo: null
    });
    const store = () => {
      form.post(route("admin.rooms.store"));
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Create Room" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              class: "text-blinest-400 hover:text-blinest-600",
              href: _ctx.route("admin.rooms")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Rooms"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<span class="font-medium text-blinest-400"${_scopeId}>/</span> ${ssrInterpolate(_ctx.__("Create"))}</h1>`);
            _push2(ssrRenderComponent(Card, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form${_scopeId2}><div class="flex"${_scopeId2}><div class="flex w-1/3 flex-col border-r p-8"${_scopeId2}><p class="mb-6 font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Options"))}</p>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).tracks_by_round,
                    "onUpdate:modelValue": ($event) => unref(form).tracks_by_round = $event,
                    error: unref(form).errors.tracks_by_round,
                    type: "number",
                    step: "1",
                    min: "1",
                    max: "100",
                    class: "pb-6",
                    label: _ctx.__("Tracks by round")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).track_duration,
                    "onUpdate:modelValue": ($event) => unref(form).track_duration = $event,
                    error: unref(form).errors.track_duration,
                    type: "number",
                    step: "1",
                    min: "5",
                    max: "30",
                    class: "pb-6",
                    label: _ctx.__("Track duration")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).pause_between_tracks,
                    "onUpdate:modelValue": ($event) => unref(form).pause_between_tracks = $event,
                    error: unref(form).errors.pause_between_tracks,
                    type: "number",
                    step: "1",
                    min: "0",
                    max: "60",
                    class: "pb-6",
                    label: _ctx.__("Pause between tracks")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).pause_between_rounds,
                    "onUpdate:modelValue": ($event) => unref(form).pause_between_rounds = $event,
                    error: unref(form).errors.pause_between_rounds,
                    type: "number",
                    step: "1",
                    min: "0",
                    max: "60",
                    class: "pb-6",
                    label: _ctx.__("Pause between rounds")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).color,
                    "onUpdate:modelValue": ($event) => unref(form).color = $event,
                    type: "color",
                    error: unref(form).errors.color,
                    class: "pb-6",
                    label: _ctx.__("Color")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_public,
                    "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                    error: unref(form).errors.is_public,
                    class: "pb-6",
                    label: _ctx.__("Public")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_pro,
                    "onUpdate:modelValue": ($event) => unref(form).is_pro = $event,
                    error: unref(form).errors.is_pro,
                    class: "pb-6",
                    label: _ctx.__("Pro")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_random,
                    "onUpdate:modelValue": ($event) => unref(form).is_random = $event,
                    error: unref(form).errors.is_random,
                    class: "pb-6",
                    label: _ctx.__("Random")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_active,
                    "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                    error: unref(form).errors.is_active,
                    class: "pb-6",
                    label: _ctx.__("Active")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_chat_active,
                    "onUpdate:modelValue": ($event) => unref(form).is_chat_active = $event,
                    error: unref(form).errors.is_chat_active,
                    class: "pb-6",
                    label: _ctx.__("Chatbox")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_password,
                    "onUpdate:modelValue": ($event) => unref(form).is_password = $event,
                    class: "pb-6",
                    label: _ctx.__("Password")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    style: unref(form).is_password ? null : { display: "none" },
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    error: unref(form).errors.password,
                    class: "pb-6",
                    type: "password",
                    autocomplete: "new-password",
                    label: _ctx.__("Password")
                  }, null, _parent3, _scopeId2));
                  _push3(`</div><div class="flex flex-wrap p-8"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    class: "w-full pb-8 pr-6",
                    label: _ctx.__("Name")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).description,
                    "onUpdate:modelValue": ($event) => unref(form).description = $event,
                    error: unref(form).errors.description,
                    class: "w-full pb-8 pr-6",
                    label: _ctx.__("Description")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    modelValue: unref(form).photo,
                    "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                    error: unref(form).errors.photo,
                    class: "w-full pb-8 pr-6",
                    type: "file",
                    accept: "image/*",
                    label: _ctx.__("Photo")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).discord_webhook_url,
                    "onUpdate:modelValue": ($event) => unref(form).discord_webhook_url = $event,
                    type: "url",
                    error: unref(form).errors.discord_webhook_url,
                    class: "w-full pb-8 pr-6",
                    label: _ctx.__("Discord Webhook")
                  }, null, _parent3, _scopeId2));
                  _push3(`</div></div><div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary",
                    type: "submit"
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
                  _push3(`</div></form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(store, ["prevent"])
                    }, [
                      createVNode("div", { class: "flex" }, [
                        createVNode("div", { class: "flex w-1/3 flex-col border-r p-8" }, [
                          createVNode("p", { class: "mb-6 font-bold" }, toDisplayString(_ctx.__("Options")), 1),
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).tracks_by_round,
                            "onUpdate:modelValue": ($event) => unref(form).tracks_by_round = $event,
                            error: unref(form).errors.tracks_by_round,
                            type: "number",
                            step: "1",
                            min: "1",
                            max: "100",
                            class: "pb-6",
                            label: _ctx.__("Tracks by round")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).track_duration,
                            "onUpdate:modelValue": ($event) => unref(form).track_duration = $event,
                            error: unref(form).errors.track_duration,
                            type: "number",
                            step: "1",
                            min: "5",
                            max: "30",
                            class: "pb-6",
                            label: _ctx.__("Track duration")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).pause_between_tracks,
                            "onUpdate:modelValue": ($event) => unref(form).pause_between_tracks = $event,
                            error: unref(form).errors.pause_between_tracks,
                            type: "number",
                            step: "1",
                            min: "0",
                            max: "60",
                            class: "pb-6",
                            label: _ctx.__("Pause between tracks")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).pause_between_rounds,
                            "onUpdate:modelValue": ($event) => unref(form).pause_between_rounds = $event,
                            error: unref(form).errors.pause_between_rounds,
                            type: "number",
                            step: "1",
                            min: "0",
                            max: "60",
                            class: "pb-6",
                            label: _ctx.__("Pause between rounds")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).color,
                            "onUpdate:modelValue": ($event) => unref(form).color = $event,
                            type: "color",
                            error: unref(form).errors.color,
                            class: "pb-6",
                            label: _ctx.__("Color")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_public,
                            "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                            error: unref(form).errors.is_public,
                            class: "pb-6",
                            label: _ctx.__("Public")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_pro,
                            "onUpdate:modelValue": ($event) => unref(form).is_pro = $event,
                            error: unref(form).errors.is_pro,
                            class: "pb-6",
                            label: _ctx.__("Pro")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_random,
                            "onUpdate:modelValue": ($event) => unref(form).is_random = $event,
                            error: unref(form).errors.is_random,
                            class: "pb-6",
                            label: _ctx.__("Random")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_active,
                            "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                            error: unref(form).errors.is_active,
                            class: "pb-6",
                            label: _ctx.__("Active")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_chat_active,
                            "onUpdate:modelValue": ($event) => unref(form).is_chat_active = $event,
                            error: unref(form).errors.is_chat_active,
                            class: "pb-6",
                            label: _ctx.__("Chatbox")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_password,
                            "onUpdate:modelValue": ($event) => unref(form).is_password = $event,
                            class: "pb-6",
                            label: _ctx.__("Password")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "label"]),
                          withDirectives(createVNode(_sfc_main$2, {
                            modelValue: unref(form).password,
                            "onUpdate:modelValue": ($event) => unref(form).password = $event,
                            error: unref(form).errors.password,
                            class: "pb-6",
                            type: "password",
                            autocomplete: "new-password",
                            label: _ctx.__("Password")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]), [
                            [vShow, unref(form).is_password]
                          ])
                        ]),
                        createVNode("div", { class: "flex flex-wrap p-8" }, [
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).name,
                            "onUpdate:modelValue": ($event) => unref(form).name = $event,
                            error: unref(form).errors.name,
                            class: "w-full pb-8 pr-6",
                            label: _ctx.__("Name")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$3, {
                            modelValue: unref(form).description,
                            "onUpdate:modelValue": ($event) => unref(form).description = $event,
                            error: unref(form).errors.description,
                            class: "w-full pb-8 pr-6",
                            label: _ctx.__("Description")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$4, {
                            modelValue: unref(form).photo,
                            "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                            error: unref(form).errors.photo,
                            class: "w-full pb-8 pr-6",
                            type: "file",
                            accept: "image/*",
                            label: _ctx.__("Photo")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).discord_webhook_url,
                            "onUpdate:modelValue": ($event) => unref(form).discord_webhook_url = $event,
                            type: "url",
                            error: unref(form).errors.discord_webhook_url,
                            class: "w-full pb-8 pr-6",
                            label: _ctx.__("Discord Webhook")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                        ])
                      ]),
                      createVNode("div", { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                        createVNode(LoadingButton, {
                          loading: unref(form).processing,
                          class: "btn-primary",
                          type: "submit"
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Create")), 1)
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
                  href: _ctx.route("admin.rooms")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                  ]),
                  _: 1
                }, 8, ["href"]),
                createVNode("span", { class: "font-medium text-blinest-400" }, "/"),
                createTextVNode(" " + toDisplayString(_ctx.__("Create")), 1)
              ]),
              createVNode(Card, null, {
                default: withCtx(() => [
                  createVNode("form", {
                    onSubmit: withModifiers(store, ["prevent"])
                  }, [
                    createVNode("div", { class: "flex" }, [
                      createVNode("div", { class: "flex w-1/3 flex-col border-r p-8" }, [
                        createVNode("p", { class: "mb-6 font-bold" }, toDisplayString(_ctx.__("Options")), 1),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).tracks_by_round,
                          "onUpdate:modelValue": ($event) => unref(form).tracks_by_round = $event,
                          error: unref(form).errors.tracks_by_round,
                          type: "number",
                          step: "1",
                          min: "1",
                          max: "100",
                          class: "pb-6",
                          label: _ctx.__("Tracks by round")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).track_duration,
                          "onUpdate:modelValue": ($event) => unref(form).track_duration = $event,
                          error: unref(form).errors.track_duration,
                          type: "number",
                          step: "1",
                          min: "5",
                          max: "30",
                          class: "pb-6",
                          label: _ctx.__("Track duration")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).pause_between_tracks,
                          "onUpdate:modelValue": ($event) => unref(form).pause_between_tracks = $event,
                          error: unref(form).errors.pause_between_tracks,
                          type: "number",
                          step: "1",
                          min: "0",
                          max: "60",
                          class: "pb-6",
                          label: _ctx.__("Pause between tracks")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).pause_between_rounds,
                          "onUpdate:modelValue": ($event) => unref(form).pause_between_rounds = $event,
                          error: unref(form).errors.pause_between_rounds,
                          type: "number",
                          step: "1",
                          min: "0",
                          max: "60",
                          class: "pb-6",
                          label: _ctx.__("Pause between rounds")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).color,
                          "onUpdate:modelValue": ($event) => unref(form).color = $event,
                          type: "color",
                          error: unref(form).errors.color,
                          class: "pb-6",
                          label: _ctx.__("Color")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_public,
                          "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                          error: unref(form).errors.is_public,
                          class: "pb-6",
                          label: _ctx.__("Public")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_pro,
                          "onUpdate:modelValue": ($event) => unref(form).is_pro = $event,
                          error: unref(form).errors.is_pro,
                          class: "pb-6",
                          label: _ctx.__("Pro")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_random,
                          "onUpdate:modelValue": ($event) => unref(form).is_random = $event,
                          error: unref(form).errors.is_random,
                          class: "pb-6",
                          label: _ctx.__("Random")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_active,
                          "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                          error: unref(form).errors.is_active,
                          class: "pb-6",
                          label: _ctx.__("Active")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_chat_active,
                          "onUpdate:modelValue": ($event) => unref(form).is_chat_active = $event,
                          error: unref(form).errors.is_chat_active,
                          class: "pb-6",
                          label: _ctx.__("Chatbox")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_password,
                          "onUpdate:modelValue": ($event) => unref(form).is_password = $event,
                          class: "pb-6",
                          label: _ctx.__("Password")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "label"]),
                        withDirectives(createVNode(_sfc_main$2, {
                          modelValue: unref(form).password,
                          "onUpdate:modelValue": ($event) => unref(form).password = $event,
                          error: unref(form).errors.password,
                          class: "pb-6",
                          type: "password",
                          autocomplete: "new-password",
                          label: _ctx.__("Password")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]), [
                          [vShow, unref(form).is_password]
                        ])
                      ]),
                      createVNode("div", { class: "flex flex-wrap p-8" }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).name,
                          "onUpdate:modelValue": ($event) => unref(form).name = $event,
                          error: unref(form).errors.name,
                          class: "w-full pb-8 pr-6",
                          label: _ctx.__("Name")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$3, {
                          modelValue: unref(form).description,
                          "onUpdate:modelValue": ($event) => unref(form).description = $event,
                          error: unref(form).errors.description,
                          class: "w-full pb-8 pr-6",
                          label: _ctx.__("Description")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$4, {
                          modelValue: unref(form).photo,
                          "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                          error: unref(form).errors.photo,
                          class: "w-full pb-8 pr-6",
                          type: "file",
                          accept: "image/*",
                          label: _ctx.__("Photo")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).discord_webhook_url,
                          "onUpdate:modelValue": ($event) => unref(form).discord_webhook_url = $event,
                          type: "url",
                          error: unref(form).errors.discord_webhook_url,
                          class: "w-full pb-8 pr-6",
                          label: _ctx.__("Discord Webhook")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ])
                    ]),
                    createVNode("div", { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" }, [
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary",
                        type: "submit"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Create")), 1)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Rooms/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
