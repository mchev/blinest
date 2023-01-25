import { unref, withCtx, createTextVNode, toDisplayString, createVNode, openBlock, createBlock, createCommentVNode, Fragment, renderList, withModifiers, withDirectives, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr } from "vue/server-renderer";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-a34dfeea.mjs";
import { F as FileInput } from "./FileInput-916118e3.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./TextareaInput-989d56d6.mjs";
import { _ as _sfc_main$4 } from "./SelectInput-279cfc81.mjs";
import { C as CheckboxInput } from "./CheckboxInput-934baa4b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { _ as _sfc_main$5, a as _sfc_main$6 } from "./PlaylistsManager-f0ca0bd6.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import "lodash/pickBy.js";
import "lodash/throttle.js";
import "lodash/mapValues.js";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
import "lodash/debounce.js";
import "./Tip-9a139e5c.mjs";
const _sfc_main = {
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    room: Object,
    categories: Array,
    available_playlists: Array
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: _ctx.__("Edit Room")
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 text-3xl font-bold"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
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
            _push2(` / ${ssrInterpolate(__props.room.name)}</h1><div class="flex flex-wrap gap-4"${_scopeId}><div class="flex w-full flex-col xl:w-1/4"${_scopeId}>`);
            _push2(ssrRenderComponent(Card, { class: "mb-4" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h3 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Room"))}</h3>`);
                } else {
                  return [
                    createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Room")), 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (!__props.room.deleted_at) {
                    _push3(`<button class="text-sm text-red-600 hover:underline" tabindex="-1" type="button"${_scopeId2}>${ssrInterpolate(_ctx.__("Delete"))}</button>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    form: "roomForm",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Update"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    !__props.room.deleted_at ? (openBlock(), createBlock("button", {
                      key: 0,
                      class: "text-sm text-red-600 hover:underline",
                      tabindex: "-1",
                      type: "button",
                      onClick: _ctx.destroy
                    }, toDisplayString(_ctx.__("Delete")), 9, ["onClick"])) : createCommentVNode("", true),
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
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="roomForm"${_scopeId2}><div class="flex"${_scopeId2}><div class="flex flex-wrap"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    class: "mb-4 w-full",
                    label: _ctx.__("Name")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).description,
                    "onUpdate:modelValue": ($event) => unref(form).description = $event,
                    error: unref(form).errors.description,
                    class: "mb-4 w-full",
                    label: _ctx.__("Description")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    modelValue: unref(form).category_id,
                    "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                    error: unref(form).errors.category_id,
                    class: "mb-4 w-full",
                    label: _ctx.__("Category")
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`<!--[-->`);
                        ssrRenderList(__props.categories, (category) => {
                          _push4(`<option${ssrRenderAttr("value", category.id)}${_scopeId3}>${ssrInterpolate(category.name)}</option>`);
                        });
                        _push4(`<!--]-->`);
                      } else {
                        return [
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.categories, (category) => {
                            return openBlock(), createBlock("option", {
                              key: category.id,
                              value: category.id
                            }, toDisplayString(category.name), 9, ["value"]);
                          }), 128))
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(FileInput, {
                    modelValue: unref(form).photo,
                    "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                    error: unref(form).errors.photo,
                    class: "mb-4 w-full",
                    type: "file",
                    accept: "image/*",
                    label: _ctx.__("Photo")
                  }, null, _parent3, _scopeId2));
                  _push3(`</div></div></form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "roomForm"
                    }, [
                      createVNode("div", { class: "flex" }, [
                        createVNode("div", { class: "flex flex-wrap" }, [
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).name,
                            "onUpdate:modelValue": ($event) => unref(form).name = $event,
                            error: unref(form).errors.name,
                            class: "mb-4 w-full",
                            label: _ctx.__("Name")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$3, {
                            modelValue: unref(form).description,
                            "onUpdate:modelValue": ($event) => unref(form).description = $event,
                            error: unref(form).errors.description,
                            class: "mb-4 w-full",
                            label: _ctx.__("Description")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$4, {
                            modelValue: unref(form).category_id,
                            "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                            error: unref(form).errors.category_id,
                            class: "mb-4 w-full",
                            label: _ctx.__("Category")
                          }, {
                            default: withCtx(() => [
                              (openBlock(true), createBlock(Fragment, null, renderList(__props.categories, (category) => {
                                return openBlock(), createBlock("option", {
                                  key: category.id,
                                  value: category.id
                                }, toDisplayString(category.name), 9, ["value"]);
                              }), 128))
                            ]),
                            _: 1
                          }, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(FileInput, {
                            modelValue: unref(form).photo,
                            "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                            error: unref(form).errors.photo,
                            class: "mb-4 w-full",
                            type: "file",
                            accept: "image/*",
                            label: _ctx.__("Photo")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                        ])
                      ])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$5, { room: __props.room }, null, _parent2, _scopeId));
            _push2(`</div><div class="flex-1"${_scopeId}>`);
            _push2(ssrRenderComponent(Card, { class: "mb-4" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h2 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Options"))}</h2>`);
                } else {
                  return [
                    createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Options")), 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    form: "optionsForm",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Update"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
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
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="optionsForm" class="flex flex-wrap"${_scopeId2}><div class="flex w-full flex-wrap"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).tracks_by_round,
                    "onUpdate:modelValue": ($event) => unref(form).tracks_by_round = $event,
                    error: unref(form).errors.tracks_by_round,
                    type: "number",
                    step: "1",
                    min: "1",
                    max: "100",
                    class: "w-full pr-4 pb-4 md:w-1/2",
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
                    class: "w-full pr-4 pb-4 md:w-1/2",
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
                    class: "w-full pr-4 pb-4 md:w-1/2",
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
                    class: "w-full pr-4 pb-4 md:w-1/2",
                    label: _ctx.__("Pause between rounds")
                  }, null, _parent3, _scopeId2));
                  _push3(`</div>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).color,
                    "onUpdate:modelValue": ($event) => unref(form).color = $event,
                    type: "color",
                    error: unref(form).errors.color,
                    class: "w-full pr-4 pb-4 md:w-1/2",
                    label: _ctx.__("Color")
                  }, null, _parent3, _scopeId2));
                  _push3(`<div class="flex w-full flex-wrap"${_scopeId2}>`);
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_public,
                    "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                    error: unref(form).errors.is_public,
                    class: "w-full pr-4 pb-4 md:w-1/2",
                    label: _ctx.__("Public")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_pro,
                    "onUpdate:modelValue": ($event) => unref(form).is_pro = $event,
                    error: unref(form).errors.is_pro,
                    class: "w-full pr-4 pb-4 md:w-1/2",
                    label: _ctx.__("Pro")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_active,
                    "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                    error: unref(form).errors.is_active,
                    class: "w-full pr-4 pb-4 md:w-1/2",
                    label: _ctx.__("Active")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).is_chat_active,
                    "onUpdate:modelValue": ($event) => unref(form).is_chat_active = $event,
                    error: unref(form).errors.is_chat_active,
                    class: "w-full pr-4 pb-4 md:w-1/2",
                    label: _ctx.__("Chatbox")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(CheckboxInput, {
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    class: "w-full pr-4 pb-4 md:w-1/2",
                    label: _ctx.__("Password")
                  }, null, _parent3, _scopeId2));
                  _push3(`</div>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    style: unref(form).password ? null : { display: "none" },
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    error: unref(form).errors.password,
                    class: "pb-6",
                    type: "password",
                    autocomplete: "new-password",
                    label: _ctx.__("Password")
                  }, null, _parent3, _scopeId2));
                  _push3(`</form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "optionsForm",
                      class: "flex flex-wrap"
                    }, [
                      createVNode("div", { class: "flex w-full flex-wrap" }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).tracks_by_round,
                          "onUpdate:modelValue": ($event) => unref(form).tracks_by_round = $event,
                          error: unref(form).errors.tracks_by_round,
                          type: "number",
                          step: "1",
                          min: "1",
                          max: "100",
                          class: "w-full pr-4 pb-4 md:w-1/2",
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
                          class: "w-full pr-4 pb-4 md:w-1/2",
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
                          class: "w-full pr-4 pb-4 md:w-1/2",
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
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Pause between rounds")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).color,
                        "onUpdate:modelValue": ($event) => unref(form).color = $event,
                        type: "color",
                        error: unref(form).errors.color,
                        class: "w-full pr-4 pb-4 md:w-1/2",
                        label: _ctx.__("Color")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode("div", { class: "flex w-full flex-wrap" }, [
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_public,
                          "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                          error: unref(form).errors.is_public,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Public")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_pro,
                          "onUpdate:modelValue": ($event) => unref(form).is_pro = $event,
                          error: unref(form).errors.is_pro,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Pro")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_active,
                          "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                          error: unref(form).errors.is_active,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Active")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).is_chat_active,
                          "onUpdate:modelValue": ($event) => unref(form).is_chat_active = $event,
                          error: unref(form).errors.is_chat_active,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Chatbox")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(CheckboxInput, {
                          modelValue: unref(form).password,
                          "onUpdate:modelValue": ($event) => unref(form).password = $event,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Password")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "label"])
                      ]),
                      withDirectives(createVNode(_sfc_main$2, {
                        modelValue: unref(form).password,
                        "onUpdate:modelValue": ($event) => unref(form).password = $event,
                        error: unref(form).errors.password,
                        class: "pb-6",
                        type: "password",
                        autocomplete: "new-password",
                        label: _ctx.__("Password")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]), [
                        [vShow, unref(form).password]
                      ])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$6, {
              class: "mb-4",
              room: __props.room,
              playlists: __props.available_playlists
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(Card, { class: "mb-4" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h2 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Discord"))}</h2>`);
                } else {
                  return [
                    createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Discord")), 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    form: "discordForm",
                    type: "submit"
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(_ctx.__("Update"))}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
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
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="discordForm" class="flex flex-wrap"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).discord_webhook_url,
                    "onUpdate:modelValue": ($event) => unref(form).discord_webhook_url = $event,
                    type: "url",
                    error: unref(form).errors.discord_webhook_url,
                    class: "mb-4 w-full",
                    label: _ctx.__("Webhook")
                  }, null, _parent3, _scopeId2));
                  _push3(`</form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "discordForm",
                      class: "flex flex-wrap"
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).discord_webhook_url,
                        "onUpdate:modelValue": ($event) => unref(form).discord_webhook_url = $event,
                        type: "url",
                        error: unref(form).errors.discord_webhook_url,
                        class: "mb-4 w-full",
                        label: _ctx.__("Webhook")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("h1", { class: "mb-8 text-3xl font-bold" }, [
                createVNode(unref(Link), {
                  href: _ctx.route("admin.rooms")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                  ]),
                  _: 1
                }, 8, ["href"]),
                createTextVNode(" / " + toDisplayString(__props.room.name), 1)
              ]),
              createVNode("div", { class: "flex flex-wrap gap-4" }, [
                createVNode("div", { class: "flex w-full flex-col xl:w-1/4" }, [
                  createVNode(Card, { class: "mb-4" }, {
                    header: withCtx(() => [
                      createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Room")), 1)
                    ]),
                    footer: withCtx(() => [
                      !__props.room.deleted_at ? (openBlock(), createBlock("button", {
                        key: 0,
                        class: "text-sm text-red-600 hover:underline",
                        tabindex: "-1",
                        type: "button",
                        onClick: _ctx.destroy
                      }, toDisplayString(_ctx.__("Delete")), 9, ["onClick"])) : createCommentVNode("", true),
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
                      createVNode("form", {
                        onSubmit: withModifiers(update, ["prevent"]),
                        id: "roomForm"
                      }, [
                        createVNode("div", { class: "flex" }, [
                          createVNode("div", { class: "flex flex-wrap" }, [
                            createVNode(_sfc_main$2, {
                              modelValue: unref(form).name,
                              "onUpdate:modelValue": ($event) => unref(form).name = $event,
                              error: unref(form).errors.name,
                              class: "mb-4 w-full",
                              label: _ctx.__("Name")
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                            createVNode(_sfc_main$3, {
                              modelValue: unref(form).description,
                              "onUpdate:modelValue": ($event) => unref(form).description = $event,
                              error: unref(form).errors.description,
                              class: "mb-4 w-full",
                              label: _ctx.__("Description")
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                            createVNode(_sfc_main$4, {
                              modelValue: unref(form).category_id,
                              "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                              error: unref(form).errors.category_id,
                              class: "mb-4 w-full",
                              label: _ctx.__("Category")
                            }, {
                              default: withCtx(() => [
                                (openBlock(true), createBlock(Fragment, null, renderList(__props.categories, (category) => {
                                  return openBlock(), createBlock("option", {
                                    key: category.id,
                                    value: category.id
                                  }, toDisplayString(category.name), 9, ["value"]);
                                }), 128))
                              ]),
                              _: 1
                            }, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                            createVNode(FileInput, {
                              modelValue: unref(form).photo,
                              "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                              error: unref(form).errors.photo,
                              class: "mb-4 w-full",
                              type: "file",
                              accept: "image/*",
                              label: _ctx.__("Photo")
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                          ])
                        ])
                      ], 40, ["onSubmit"])
                    ]),
                    _: 1
                  }),
                  createVNode(_sfc_main$5, { room: __props.room }, null, 8, ["room"])
                ]),
                createVNode("div", { class: "flex-1" }, [
                  createVNode(Card, { class: "mb-4" }, {
                    header: withCtx(() => [
                      createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Options")), 1)
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
                      createVNode("form", {
                        onSubmit: withModifiers(update, ["prevent"]),
                        id: "optionsForm",
                        class: "flex flex-wrap"
                      }, [
                        createVNode("div", { class: "flex w-full flex-wrap" }, [
                          createVNode(_sfc_main$2, {
                            modelValue: unref(form).tracks_by_round,
                            "onUpdate:modelValue": ($event) => unref(form).tracks_by_round = $event,
                            error: unref(form).errors.tracks_by_round,
                            type: "number",
                            step: "1",
                            min: "1",
                            max: "100",
                            class: "w-full pr-4 pb-4 md:w-1/2",
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
                            class: "w-full pr-4 pb-4 md:w-1/2",
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
                            class: "w-full pr-4 pb-4 md:w-1/2",
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
                            class: "w-full pr-4 pb-4 md:w-1/2",
                            label: _ctx.__("Pause between rounds")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                        ]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).color,
                          "onUpdate:modelValue": ($event) => unref(form).color = $event,
                          type: "color",
                          error: unref(form).errors.color,
                          class: "w-full pr-4 pb-4 md:w-1/2",
                          label: _ctx.__("Color")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode("div", { class: "flex w-full flex-wrap" }, [
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_public,
                            "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                            error: unref(form).errors.is_public,
                            class: "w-full pr-4 pb-4 md:w-1/2",
                            label: _ctx.__("Public")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_pro,
                            "onUpdate:modelValue": ($event) => unref(form).is_pro = $event,
                            error: unref(form).errors.is_pro,
                            class: "w-full pr-4 pb-4 md:w-1/2",
                            label: _ctx.__("Pro")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_active,
                            "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                            error: unref(form).errors.is_active,
                            class: "w-full pr-4 pb-4 md:w-1/2",
                            label: _ctx.__("Active")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).is_chat_active,
                            "onUpdate:modelValue": ($event) => unref(form).is_chat_active = $event,
                            error: unref(form).errors.is_chat_active,
                            class: "w-full pr-4 pb-4 md:w-1/2",
                            label: _ctx.__("Chatbox")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(CheckboxInput, {
                            modelValue: unref(form).password,
                            "onUpdate:modelValue": ($event) => unref(form).password = $event,
                            class: "w-full pr-4 pb-4 md:w-1/2",
                            label: _ctx.__("Password")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "label"])
                        ]),
                        withDirectives(createVNode(_sfc_main$2, {
                          modelValue: unref(form).password,
                          "onUpdate:modelValue": ($event) => unref(form).password = $event,
                          error: unref(form).errors.password,
                          class: "pb-6",
                          type: "password",
                          autocomplete: "new-password",
                          label: _ctx.__("Password")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]), [
                          [vShow, unref(form).password]
                        ])
                      ], 40, ["onSubmit"])
                    ]),
                    _: 1
                  }),
                  createVNode(_sfc_main$6, {
                    class: "mb-4",
                    room: __props.room,
                    playlists: __props.available_playlists
                  }, null, 8, ["room", "playlists"]),
                  createVNode(Card, { class: "mb-4" }, {
                    header: withCtx(() => [
                      createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Discord")), 1)
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
                      createVNode("form", {
                        onSubmit: withModifiers(update, ["prevent"]),
                        id: "discordForm",
                        class: "flex flex-wrap"
                      }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).discord_webhook_url,
                          "onUpdate:modelValue": ($event) => unref(form).discord_webhook_url = $event,
                          type: "url",
                          error: unref(form).errors.discord_webhook_url,
                          class: "mb-4 w-full",
                          label: _ctx.__("Webhook")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ], 40, ["onSubmit"])
                    ]),
                    _: 1
                  })
                ])
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Rooms/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
