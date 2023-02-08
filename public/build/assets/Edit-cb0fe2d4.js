import { J, P, L as onUnmounted, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, d as createTextVNode, t as toDisplayString, g as createCommentVNode, e as withModifiers, v as renderList, h as createBlock, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$6 } from "./FileInput-ca2b29b7.js";
import { _ as _sfc_main$3 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$4 } from "./TextareaInput-b614dddb.js";
import { _ as _sfc_main$5 } from "./SelectInput-5ecccdd8.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _sfc_main$7, a as _sfc_main$9 } from "./PlaylistsManager-0e2e7e5e.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { T as Tip } from "./Tip-da87eaf5.js";
import { _ as _sfc_main$2 } from "./Share-8d298486.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import _sfc_main$8 from "./EditOptionsForm-80793cf6.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./throttle-19ac5bdb.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
import "./v4-e7604ebc.js";
import "./Modal-f990bd3c.js";
import "./CheckboxInput-45662aca.js";
const _hoisted_1 = { class: "mb-8 flex items-center gap-2 text-3xl font-bold" };
const _hoisted_2 = { class: "flex flex-wrap gap-4" };
const _hoisted_3 = { class: "flex w-full flex-col xl:w-1/4" };
const _hoisted_4 = { class: "text-xl font-bold" };
const _hoisted_5 = ["onSubmit"];
const _hoisted_6 = { class: "flex" };
const _hoisted_7 = { class: "flex flex-wrap" };
const _hoisted_8 = ["value"];
const _hoisted_9 = { class: "flex-1" };
const _hoisted_10 = { class: "text-xl font-bold" };
const _sfc_main = {
  __name: "Edit",
  props: {
    room: Object,
    categories: Array,
    available_playlists: Array
  },
  setup(__props) {
    const props = __props;
    const user = J().props.auth.user;
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
      is_autostart: props.room.is_autostart,
      discord_webhook_url: props.room.discord_webhook_url,
      color: props.room.color,
      has_password: props.room.password ? true : false,
      password: props.room.password,
      tracks_by_round: props.room.tracks_by_round,
      track_duration: props.room.track_duration,
      pause_between_tracks: props.room.pause_between_tracks,
      pause_between_rounds: props.room.pause_between_rounds,
      photo: null,
      playlists: props.room_playlists
    });
    const update = () => {
      form.post(route("rooms.update", props.room.id));
    };
    const deleteRoom = () => {
      if (confirm("Voules-vous vraiment supprimer cette room ?")) {
        Je.delete(route("rooms.destroy", props.room.id));
      }
    };
    onUnmounted(() => {
      Echo.leave(`rooms.${props.room.id}`);
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Edit Room")
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, [
              createVNode(unref(ne), {
                href: _ctx.route("rooms.index")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                ]),
                _: 1
              }, 8, ["href"]),
              createTextVNode(" / " + toDisplayString(__props.room.name) + " ", 1),
              createVNode(_sfc_main$2, {
                url: _ctx.route("rooms.show", __props.room.slug),
                class: "h-6 w-6"
              }, null, 8, ["url"]),
              createVNode(unref(ne), {
                class: "btn-primary ml-auto",
                href: _ctx.route("rooms.show", __props.room.slug)
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Play")), 1)
                ]),
                _: 1
              }, 8, ["href"])
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
                      class: "text-sm text-red-500 hover:underline",
                      tabindex: "-1",
                      type: "button",
                      onClick: deleteRoom
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
                          createVNode(_sfc_main$3, {
                            modelValue: unref(form).name,
                            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                            error: unref(form).errors.name,
                            class: "mb-4 w-full",
                            label: _ctx.__("Name")
                          }, null, 8, ["modelValue", "error", "label"]),
                          createVNode(_sfc_main$4, {
                            modelValue: unref(form).description,
                            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).description = $event),
                            error: unref(form).errors.description,
                            class: "mb-4 w-full",
                            label: _ctx.__("Description")
                          }, null, 8, ["modelValue", "error", "label"]),
                          createVNode(_sfc_main$5, {
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
                          __props.room.is_pro || __props.room.is_public || unref(user).permissions.canUploadImage ? (openBlock(), createBlock(_sfc_main$6, {
                            key: 0,
                            modelValue: unref(form).photo,
                            "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).photo = $event),
                            error: unref(form).errors.photo,
                            class: "mb-4 w-full",
                            type: "file",
                            accept: "image/*",
                            label: _ctx.__("Photo")
                          }, null, 8, ["modelValue", "error", "label"])) : createCommentVNode("", true),
                          !__props.room.is_pro && !__props.room.is_public && !unref(user).permissions.canUploadImage ? (openBlock(), createBlock(Tip, { key: 1 }, {
                            default: withCtx(() => [
                              createTextVNode(" Pour changer l'image de la room vous devez avoir un score total de 2000 minimum et 3 mois d'ancienneté. ")
                            ]),
                            _: 1
                          })) : createCommentVNode("", true)
                        ])
                      ])
                    ], 40, _hoisted_5)
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$7, { room: __props.room }, null, 8, ["room"])
              ]),
              createBaseVNode("div", _hoisted_9, [
                createVNode(Card, { class: "mb-4" }, {
                  header: withCtx(() => [
                    createBaseVNode("h2", _hoisted_10, toDisplayString(_ctx.__("Options")), 1)
                  ]),
                  default: withCtx(() => [
                    createVNode(_sfc_main$8, { room: __props.room }, null, 8, ["room"])
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$9, {
                  room: __props.room,
                  playlists: __props.available_playlists
                }, null, 8, ["room", "playlists"])
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
