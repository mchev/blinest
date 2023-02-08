import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, d as createTextVNode, t as toDisplayString, g as createCommentVNode, e as withModifiers, v as renderList, z as withDirectives, N as vShow } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$5 } from "./FileInput-ca2b29b7.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$3 } from "./TextareaInput-b614dddb.js";
import { _ as _sfc_main$4 } from "./SelectInput-5ecccdd8.js";
import { C as CheckboxInput } from "./CheckboxInput-45662aca.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _sfc_main$6, a as _sfc_main$7 } from "./PlaylistsManager-0e2e7e5e.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
import "./debounce-89ee665e.js";
import "./Tip-da87eaf5.js";
const _hoisted_1 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_2 = { class: "flex flex-wrap gap-4" };
const _hoisted_3 = { class: "flex w-full flex-col xl:w-1/4" };
const _hoisted_4 = { class: "text-xl font-bold" };
const _hoisted_5 = ["onSubmit"];
const _hoisted_6 = { class: "flex" };
const _hoisted_7 = { class: "flex flex-wrap" };
const _hoisted_8 = ["value"];
const _hoisted_9 = { class: "flex-1" };
const _hoisted_10 = { class: "text-xl font-bold" };
const _hoisted_11 = ["onSubmit"];
const _hoisted_12 = { class: "flex w-full flex-wrap" };
const _hoisted_13 = { class: "flex w-full flex-wrap" };
const _hoisted_14 = { class: "text-xl font-bold" };
const _hoisted_15 = ["onSubmit"];
const _sfc_main = {
  __name: "Edit",
  props: {
    room: Object,
    categories: Array,
    available_playlists: Array
  },
  setup(__props) {
    const props = __props;
    const form = P({
      _method: "put",
      name: props.room.name,
      description: props.room.description,
      category_id: props.room.category_id,
      is_public: props.room.is_public,
      is_pro: props.room.is_pro,
      is_random: props.room.is_random,
      is_active: props.room.is_active,
      is_chat_active: props.room.is_chat_active,
      discord_webhook_url: props.room.discord_webhook_url,
      color: props.room.color,
      password: props.room.password,
      tracks_by_round: props.room.tracks_by_round,
      track_duration: props.room.track_duration,
      pause_between_tracks: props.room.pause_between_tracks,
      pause_between_rounds: props.room.pause_between_rounds,
      photo: null,
      playlists: props.room_playlists
    });
    const update = () => {
      form.post(route("admin.rooms.update", props.room.id));
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Edit Room")
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, [
              createVNode(unref(ne), {
                href: _ctx.route("admin.rooms")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                ]),
                _: 1
              }, 8, ["href"]),
              createTextVNode(" / " + toDisplayString(__props.room.name), 1)
            ]),
            createBaseVNode("div", _hoisted_2, [
              createBaseVNode("div", _hoisted_3, [
                createVNode(Card, { class: "mb-4" }, {
                  header: withCtx(() => [
                    createBaseVNode("h3", _hoisted_4, toDisplayString(_ctx.__("Room")), 1)
                  ]),
                  footer: withCtx(() => [
                    !__props.room.deleted_at ? (openBlock(), createElementBlock("button", {
                      key: 0,
                      class: "text-sm text-red-600 hover:underline",
                      tabindex: "-1",
                      type: "button",
                      onClick: _cache[4] || (_cache[4] = (...args) => _ctx.destroy && _ctx.destroy(...args))
                    }, toDisplayString(_ctx.__("Delete")), 1)) : createCommentVNode("", true),
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto",
                      form: "roomForm",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ]),
                  default: withCtx(() => [
                    createBaseVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "roomForm"
                    }, [
                      createBaseVNode("div", _hoisted_6, [
                        createBaseVNode("div", _hoisted_7, [
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).name,
                            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                            error: unref(form).errors.name,
                            class: "mb-4 w-full",
                            label: _ctx.__("Name")
                          }, null, 8, ["modelValue", "error", "label"]),
                          createVNode(_sfc_main$3, {
                            modelValue: unref(form).description,
                            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).description = $event),
                            error: unref(form).errors.description,
                            class: "mb-4 w-full",
                            label: _ctx.__("Description")
                          }, null, 8, ["modelValue", "error", "label"]),
                          createVNode(_sfc_main$4, {
                            modelValue: unref(form).category_id,
                            "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).category_id = $event),
                            error: unref(form).errors.category_id,
                            class: "mb-4 w-full",
                            label: _ctx.__("Category")
                          }, {
                            default: withCtx(() => [
                              (openBlock(true), createElementBlock(Fragment, null, renderList(__props.categories, (category) => {
                                return openBlock(), createElementBlock("option", {
                                  key: category.id,
                                  value: category.id
                                }, toDisplayString(category.name), 9, _hoisted_8);
                              }), 128))
                            ]),
                            _: 1
                          }, 8, ["modelValue", "error", "label"]),
                          createVNode(_sfc_main$5, {
                            modelValue: unref(form).photo,
                            "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).photo = $event),
                            error: unref(form).errors.photo,
                            class: "mb-4 w-full",
                            type: "file",
                            accept: "image/*",
                            label: _ctx.__("Photo")
                          }, null, 8, ["modelValue", "error", "label"])
                        ])
                      ])
                    ], 40, _hoisted_5)
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$6, { room: __props.room }, null, 8, ["room"])
              ]),
              createBaseVNode("div", _hoisted_9, [
                createVNode(Card, { class: "mb-4" }, {
                  header: withCtx(() => [
                    createBaseVNode("h2", _hoisted_10, toDisplayString(_ctx.__("Options")), 1)
                  ]),
                  footer: withCtx(() => [
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto",
                      form: "optionsForm",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ]),
                  default: withCtx(() => [
                    createBaseVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "optionsForm",
                      class: "flex flex-wrap"
                    }, [
                      createBaseVNode("div", _hoisted_12, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).tracks_by_round,
                          "onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => unref(form).tracks_by_round = $event),
                          error: unref(form).errors.tracks_by_round,
                          type: "number",
                          step: "1",
                          min: "1",
                          max: "100",
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Tracks by round")
                        }, null, 8, ["modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).track_duration,
                          "onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => unref(form).track_duration = $event),
                          error: unref(form).errors.track_duration,
                          type: "number",
                          step: "1",
                          min: "5",
                          max: "30",
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Track duration")
                        }, null, 8, ["modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).pause_between_tracks,
                          "onUpdate:modelValue": _cache[7] || (_cache[7] = ($event) => unref(form).pause_between_tracks = $event),
                          error: unref(form).errors.pause_between_tracks,
                          type: "number",
                          step: "1",
                          min: "0",
                          max: "60",
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Pause between tracks")
                        }, null, 8, ["modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).pause_between_rounds,
                          "onUpdate:modelValue": _cache[8] || (_cache[8] = ($event) => unref(form).pause_between_rounds = $event),
                          error: unref(form).errors.pause_between_rounds,
                          type: "number",
                          step: "1",
                          min: "0",
                          max: "60",
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Pause between rounds")
                        }, null, 8, ["modelValue", "error", "label"])
                      ]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).color,
                        "onUpdate:modelValue": _cache[9] || (_cache[9] = ($event) => unref(form).color = $event),
                        type: "color",
                        error: unref(form).errors.color,
                        class: "w-full pr-4 pb-4 md:w-1/2",
                        label: _ctx.__("Color")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createBaseVNode("div", _hoisted_13, [
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_public,
                          "onUpdate:modelValue": _cache[10] || (_cache[10] = ($event) => unref(form).is_public = $event),
                          error: unref(form).errors.is_public,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Public")
                        }, null, 8, ["modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_pro,
                          "onUpdate:modelValue": _cache[11] || (_cache[11] = ($event) => unref(form).is_pro = $event),
                          error: unref(form).errors.is_pro,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Pro")
                        }, null, 8, ["modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_active,
                          "onUpdate:modelValue": _cache[12] || (_cache[12] = ($event) => unref(form).is_active = $event),
                          error: unref(form).errors.is_active,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Active")
                        }, null, 8, ["modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_chat_active,
                          "onUpdate:modelValue": _cache[13] || (_cache[13] = ($event) => unref(form).is_chat_active = $event),
                          error: unref(form).errors.is_chat_active,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Chatbox")
                        }, null, 8, ["modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).password,
                          "onUpdate:modelValue": _cache[14] || (_cache[14] = ($event) => unref(form).password = $event),
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Password")
                        }, null, 8, ["modelValue", "label"])
                      ]),
                      withDirectives(createVNode(_sfc_main$2, {
                        modelValue: unref(form).password,
                        "onUpdate:modelValue": _cache[15] || (_cache[15] = ($event) => unref(form).password = $event),
                        error: unref(form).errors.password,
                        class: "pb-6",
                        type: "password",
                        autocomplete: "new-password",
                        label: _ctx.__("Password")
                      }, null, 8, ["modelValue", "error", "label"]), [
                        [vShow, unref(form).password]
                      ])
                    ], 40, _hoisted_11)
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$7, {
                  class: "mb-4",
                  room: __props.room,
                  playlists: __props.available_playlists
                }, null, 8, ["room", "playlists"]),
                createVNode(Card, { class: "mb-4" }, {
                  header: withCtx(() => [
                    createBaseVNode("h2", _hoisted_14, toDisplayString(_ctx.__("Discord")), 1)
                  ]),
                  footer: withCtx(() => [
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto",
                      form: "discordForm",
                      type: "submit"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ]),
                  default: withCtx(() => [
                    createBaseVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "discordForm",
                      class: "flex flex-wrap"
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).discord_webhook_url,
                        "onUpdate:modelValue": _cache[16] || (_cache[16] = ($event) => unref(form).discord_webhook_url = $event),
                        type: "url",
                        error: unref(form).errors.discord_webhook_url,
                        class: "mb-4 w-full",
                        label: _ctx.__("Webhook")
                      }, null, 8, ["modelValue", "error", "label"])
                    ], 40, _hoisted_15)
                  ]),
                  _: 1
                })
              ])
            ])
          ]),
          _: 1
        })
      ], 64);
    };
  }
};
export {
  _sfc_main as default
};
