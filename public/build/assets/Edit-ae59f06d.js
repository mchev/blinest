import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, n as ne, d as createTextVNode, t as toDisplayString, g as createCommentVNode, e as withModifiers, v as renderList, h as createBlock, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$3 } from "./TextareaInput-b614dddb.js";
import { _ as _sfc_main$4 } from "./SelectInput-5ecccdd8.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _sfc_main$7 } from "./TrashedMessage-920d02d9.js";
import { _ as _sfc_main$5, a as _sfc_main$6, b as _sfc_main$8 } from "./RoomsList-8f7994c0.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
import "./Pagination-cf5f2783.js";
import "./Modal-f990bd3c.js";
import "./Spinner-bfddfb59.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./Tip-da87eaf5.js";
const _hoisted_1 = { class: "mb-4 text-3xl font-bold text-teal-600" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-medium" }, " / ", -1);
const _hoisted_3 = { class: "flex flex-wrap gap-4" };
const _hoisted_4 = { class: "flex w-full flex-col xl:w-1/4" };
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("h3", { class: "text-xl font-bold" }, "Playlist", -1);
const _hoisted_6 = ["onSubmit"];
const _hoisted_7 = { value: 1 };
const _hoisted_8 = { value: 0 };
const _hoisted_9 = ["value"];
const _hoisted_10 = ["value"];
const _hoisted_11 = { class: "flex-1" };
const _sfc_main = {
  __name: "Edit",
  props: {
    playlist: Object,
    filters: Object,
    answer_types: Object,
    tracks: Object,
    moderators: Object
  },
  setup(__props) {
    const props = __props;
    const form = P(props.playlist);
    const update = () => {
      form.put(`/admin/playlists/${props.playlist.id}`, {
        onSuccess: () => form.reset("password", "photo")
      });
    };
    const destroy = () => {
      if (confirm("Are you sure you want to delete this playlist?")) {
        Je.delete(`/admin/playlists/${props.playlist.id}`);
      }
    };
    const restore = () => {
      if (confirm("Are you sure you want to restore this playlist?")) {
        Je.put(`/admin/playlists/${props.playlist.id}/restore`);
      }
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: `${unref(form).name}`
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, [
              createVNode(unref(ne), {
                href: _ctx.route("admin.playlists")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Playlists")), 1)
                ]),
                _: 1
              }, 8, ["href"]),
              _hoisted_2,
              createTextVNode(" " + toDisplayString(unref(form).name), 1)
            ]),
            createBaseVNode("div", _hoisted_3, [
              createBaseVNode("div", _hoisted_4, [
                createVNode(Card, { class: "mb-4" }, {
                  header: withCtx(() => [
                    _hoisted_5
                  ]),
                  footer: withCtx(() => [
                    !__props.playlist.deleted_at ? (openBlock(), createElementBlock("button", {
                      key: 0,
                      class: "text-sm text-red-600 hover:underline",
                      tabindex: "-1",
                      type: "button",
                      onClick: destroy
                    }, toDisplayString(_ctx.__("Delete")), 1)) : createCommentVNode("", true),
                    createVNode(LoadingButton, {
                      loading: unref(form).processing,
                      class: "btn-primary ml-auto",
                      form: "playlistForm",
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
                      id: "playlistForm",
                      class: "p-4",
                      onSubmit: withModifiers(update, ["prevent"])
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).name,
                        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                        error: unref(form).errors.name,
                        class: "mb-4 w-full",
                        label: _ctx.__("Title")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).description,
                        "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).description = $event),
                        error: unref(form).errors.description,
                        class: "mb-4 w-full",
                        label: _ctx.__("Description")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$4, {
                        modelValue: unref(form).is_public,
                        "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).is_public = $event),
                        error: unref(form).errors.is_public,
                        class: "mb-4 w-full",
                        label: _ctx.__("Public")
                      }, {
                        default: withCtx(() => [
                          createBaseVNode("option", _hoisted_7, toDisplayString(_ctx.__("Yes")), 1),
                          createBaseVNode("option", _hoisted_8, toDisplayString(_ctx.__("No")), 1)
                        ]),
                        _: 1
                      }, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$4, {
                        modelValue: unref(form).user_id,
                        "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).user_id = $event),
                        error: unref(form).errors.user_id,
                        class: "w-full",
                        label: _ctx.__("Owner")
                      }, {
                        default: withCtx(() => [
                          createBaseVNode("option", {
                            value: __props.playlist.owner.id
                          }, toDisplayString(__props.playlist.owner.name), 9, _hoisted_9),
                          (openBlock(true), createElementBlock(Fragment, null, renderList(__props.playlist.moderators, (moderator) => {
                            return openBlock(), createElementBlock("option", {
                              value: moderator.id
                            }, toDisplayString(moderator.name), 9, _hoisted_10);
                          }), 256))
                        ]),
                        _: 1
                      }, 8, ["modelValue", "error", "label"]),
                      createBaseVNode("small", null, toDisplayString(_ctx.__("You can transfer the playlist management to a moderator.")), 1)
                    ], 40, _hoisted_6)
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$5, {
                  playlist: __props.playlist,
                  class: "mb-4"
                }, null, 8, ["playlist"]),
                createVNode(_sfc_main$6, { playlist: __props.playlist }, null, 8, ["playlist"])
              ]),
              createBaseVNode("div", _hoisted_11, [
                __props.playlist.deleted_at ? (openBlock(), createBlock(_sfc_main$7, {
                  key: 0,
                  class: "mb-6",
                  onRestore: restore
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("This playlist has been deleted.")), 1)
                  ]),
                  _: 1
                })) : createCommentVNode("", true),
                createVNode(_sfc_main$8, {
                  playlist: __props.playlist,
                  filters: __props.filters,
                  tracks: __props.tracks,
                  answer_types: __props.answer_types
                }, null, 8, ["playlist", "filters", "tracks", "answer_types"])
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
