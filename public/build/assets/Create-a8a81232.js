import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, d as createTextVNode, t as toDisplayString, z as withDirectives, N as vShow, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$4 } from "./FileInput-ca2b29b7.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$3 } from "./TextareaInput-b614dddb.js";
import { C as CheckboxInput } from "./CheckboxInput-45662aca.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium text-blinest-400" }, "/", -1);
const _hoisted_3 = ["onSubmit"];
const _hoisted_4 = { class: "flex" };
const _hoisted_5 = { class: "flex w-1/3 flex-col border-r p-8" };
const _hoisted_6 = { class: "mb-6 font-bold" };
const _hoisted_7 = { class: "flex flex-wrap p-8" };
const _hoisted_8 = { class: "flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4" };
const _sfc_main = {
  __name: "Create",
  setup(__props) {
    const form = P({
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
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Create Room" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, [
              createVNode(unref(ne), {
                class: "text-blinest-400 hover:text-blinest-600",
                href: _ctx.route("admin.rooms")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                ]),
                _: 1
              }, 8, ["href"]),
              _hoisted_2,
              createTextVNode(" " + toDisplayString(_ctx.__("Create")), 1)
            ]),
            createVNode(Card, null, {
              default: withCtx(() => [
                createBaseVNode("form", {
                  onSubmit: withModifiers(store, ["prevent"])
                }, [
                  createBaseVNode("div", _hoisted_4, [
                    createBaseVNode("div", _hoisted_5, [
                      createBaseVNode("p", _hoisted_6, toDisplayString(_ctx.__("Options")), 1),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).tracks_by_round,
                        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).tracks_by_round = $event),
                        error: unref(form).errors.tracks_by_round,
                        type: "number",
                        step: "1",
                        min: "1",
                        max: "100",
                        class: "pb-6",
                        label: _ctx.__("Tracks by round")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).track_duration,
                        "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).track_duration = $event),
                        error: unref(form).errors.track_duration,
                        type: "number",
                        step: "1",
                        min: "5",
                        max: "30",
                        class: "pb-6",
                        label: _ctx.__("Track duration")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).pause_between_tracks,
                        "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).pause_between_tracks = $event),
                        error: unref(form).errors.pause_between_tracks,
                        type: "number",
                        step: "1",
                        min: "0",
                        max: "60",
                        class: "pb-6",
                        label: _ctx.__("Pause between tracks")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).pause_between_rounds,
                        "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).pause_between_rounds = $event),
                        error: unref(form).errors.pause_between_rounds,
                        type: "number",
                        step: "1",
                        min: "0",
                        max: "60",
                        class: "pb-6",
                        label: _ctx.__("Pause between rounds")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).color,
                        "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => unref(form).color = $event),
                        type: "color",
                        error: unref(form).errors.color,
                        class: "pb-6",
                        label: _ctx.__("Color")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(CheckboxInput, {
                        modelValue: unref(form).is_public,
                        "onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => unref(form).is_public = $event),
                        error: unref(form).errors.is_public,
                        class: "pb-6",
                        label: _ctx.__("Public")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(CheckboxInput, {
                        modelValue: unref(form).is_pro,
                        "onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => unref(form).is_pro = $event),
                        error: unref(form).errors.is_pro,
                        class: "pb-6",
                        label: _ctx.__("Pro")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(CheckboxInput, {
                        modelValue: unref(form).is_random,
                        "onUpdate:modelValue": _cache[7] || (_cache[7] = ($event) => unref(form).is_random = $event),
                        error: unref(form).errors.is_random,
                        class: "pb-6",
                        label: _ctx.__("Random")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(CheckboxInput, {
                        modelValue: unref(form).is_active,
                        "onUpdate:modelValue": _cache[8] || (_cache[8] = ($event) => unref(form).is_active = $event),
                        error: unref(form).errors.is_active,
                        class: "pb-6",
                        label: _ctx.__("Active")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(CheckboxInput, {
                        modelValue: unref(form).is_chat_active,
                        "onUpdate:modelValue": _cache[9] || (_cache[9] = ($event) => unref(form).is_chat_active = $event),
                        error: unref(form).errors.is_chat_active,
                        class: "pb-6",
                        label: _ctx.__("Chatbox")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(CheckboxInput, {
                        modelValue: unref(form).is_password,
                        "onUpdate:modelValue": _cache[10] || (_cache[10] = ($event) => unref(form).is_password = $event),
                        class: "pb-6",
                        label: _ctx.__("Password")
                      }, null, 8, ["modelValue", "label"]),
                      withDirectives(createVNode(_sfc_main$2, {
                        modelValue: unref(form).password,
                        "onUpdate:modelValue": _cache[11] || (_cache[11] = ($event) => unref(form).password = $event),
                        error: unref(form).errors.password,
                        class: "pb-6",
                        type: "password",
                        autocomplete: "new-password",
                        label: _ctx.__("Password")
                      }, null, 8, ["modelValue", "error", "label"]), [
                        [vShow, unref(form).is_password]
                      ])
                    ]),
                    createBaseVNode("div", _hoisted_7, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).name,
                        "onUpdate:modelValue": _cache[12] || (_cache[12] = ($event) => unref(form).name = $event),
                        error: unref(form).errors.name,
                        class: "w-full pb-8 pr-6",
                        label: _ctx.__("Name")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).description,
                        "onUpdate:modelValue": _cache[13] || (_cache[13] = ($event) => unref(form).description = $event),
                        error: unref(form).errors.description,
                        class: "w-full pb-8 pr-6",
                        label: _ctx.__("Description")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$4, {
                        modelValue: unref(form).photo,
                        "onUpdate:modelValue": _cache[14] || (_cache[14] = ($event) => unref(form).photo = $event),
                        error: unref(form).errors.photo,
                        class: "w-full pb-8 pr-6",
                        type: "file",
                        accept: "image/*",
                        label: _ctx.__("Photo")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).discord_webhook_url,
                        "onUpdate:modelValue": _cache[15] || (_cache[15] = ($event) => unref(form).discord_webhook_url = $event),
                        type: "url",
                        error: unref(form).errors.discord_webhook_url,
                        class: "w-full pb-8 pr-6",
                        label: _ctx.__("Discord Webhook")
                      }, null, 8, ["modelValue", "error", "label"])
                    ])
                  ]),
                  createBaseVNode("div", _hoisted_8, [
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
                ], 40, _hoisted_3)
              ]),
              _: 1
            })
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
