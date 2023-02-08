import { unref, withCtx, createTextVNode, toDisplayString, createVNode, openBlock, createBlock, createCommentVNode, Fragment, renderList, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList } from "vue/server-renderer";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminLayout-c9eae547.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./TextareaInput-989d56d6.mjs";
import { _ as _sfc_main$4 } from "./SelectInput-279cfc81.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { _ as _sfc_main$7 } from "./TrashedMessage-ea9646dd.mjs";
import { _ as _sfc_main$5, a as _sfc_main$6, b as _sfc_main$8 } from "./RoomsList-85523d2b.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "uuid";
import "./Pagination-6aa0e1ca.mjs";
import "./Modal-61e7836d.mjs";
import "./Spinner-ec1c59c5.mjs";
import "lodash/pickBy.js";
import "lodash/debounce.js";
import "lodash/throttle.js";
import "./Tip-027e2e8e.mjs";
const _sfc_main = {
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    playlist: Object,
    filters: Object,
    answer_types: Object,
    tracks: Object,
    moderators: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm(props.playlist);
    const update = () => {
      form.put(`/admin/playlists/${props.playlist.id}`, {
        onSuccess: () => form.reset("password", "photo")
      });
    };
    const destroy = () => {
      if (confirm("Are you sure you want to delete this playlist?")) {
        router.delete(`/admin/playlists/${props.playlist.id}`);
      }
    };
    const restore = () => {
      if (confirm("Are you sure you want to restore this playlist?")) {
        router.put(`/admin/playlists/${props.playlist.id}/restore`);
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: `${unref(form).name}`
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-4 text-3xl font-bold text-teal-600"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("admin.playlists")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Playlists"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Playlists")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<span class="font-medium"${_scopeId}> / </span> ${ssrInterpolate(unref(form).name)}</h1><div class="flex flex-wrap gap-4"${_scopeId}><div class="flex w-full flex-col xl:w-1/4"${_scopeId}>`);
            _push2(ssrRenderComponent(Card, { class: "mb-4" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h3 class="text-xl font-bold"${_scopeId2}>Playlist</h3>`);
                } else {
                  return [
                    createVNode("h3", { class: "text-xl font-bold" }, "Playlist")
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (!__props.playlist.deleted_at) {
                    _push3(`<button class="text-sm text-red-600 hover:underline" tabindex="-1" type="button"${_scopeId2}>${ssrInterpolate(_ctx.__("Delete"))}</button>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    form: "playlistForm",
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
                    !__props.playlist.deleted_at ? (openBlock(), createBlock("button", {
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
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="playlistForm" class="p-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    class: "mb-4 w-full",
                    label: _ctx.__("Title")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).description,
                    "onUpdate:modelValue": ($event) => unref(form).description = $event,
                    error: unref(form).errors.description,
                    class: "mb-4 w-full",
                    label: _ctx.__("Description")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    modelValue: unref(form).is_public,
                    "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                    error: unref(form).errors.is_public,
                    class: "mb-4 w-full",
                    label: _ctx.__("Public")
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`<option${ssrRenderAttr("value", 1)}${_scopeId3}>${ssrInterpolate(_ctx.__("Yes"))}</option><option${ssrRenderAttr("value", 0)}${_scopeId3}>${ssrInterpolate(_ctx.__("No"))}</option>`);
                      } else {
                        return [
                          createVNode("option", { value: 1 }, toDisplayString(_ctx.__("Yes")), 1),
                          createVNode("option", { value: 0 }, toDisplayString(_ctx.__("No")), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    modelValue: unref(form).user_id,
                    "onUpdate:modelValue": ($event) => unref(form).user_id = $event,
                    error: unref(form).errors.user_id,
                    class: "w-full",
                    label: _ctx.__("Owner")
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`<option${ssrRenderAttr("value", __props.playlist.owner.id)}${_scopeId3}>${ssrInterpolate(__props.playlist.owner.name)}</option><!--[-->`);
                        ssrRenderList(__props.playlist.moderators, (moderator) => {
                          _push4(`<option${ssrRenderAttr("value", moderator.id)}${_scopeId3}>${ssrInterpolate(moderator.name)}</option>`);
                        });
                        _push4(`<!--]-->`);
                      } else {
                        return [
                          createVNode("option", {
                            value: __props.playlist.owner.id
                          }, toDisplayString(__props.playlist.owner.name), 9, ["value"]),
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.playlist.moderators, (moderator) => {
                            return openBlock(), createBlock("option", {
                              value: moderator.id
                            }, toDisplayString(moderator.name), 9, ["value"]);
                          }), 256))
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`<small${_scopeId2}>${ssrInterpolate(_ctx.__("You can transfer the playlist management to a moderator."))}</small></form>`);
                } else {
                  return [
                    createVNode("form", {
                      id: "playlistForm",
                      class: "p-4",
                      onSubmit: withModifiers(update, ["prevent"])
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).name,
                        "onUpdate:modelValue": ($event) => unref(form).name = $event,
                        error: unref(form).errors.name,
                        class: "mb-4 w-full",
                        label: _ctx.__("Title")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).description,
                        "onUpdate:modelValue": ($event) => unref(form).description = $event,
                        error: unref(form).errors.description,
                        class: "mb-4 w-full",
                        label: _ctx.__("Description")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(_sfc_main$4, {
                        modelValue: unref(form).is_public,
                        "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                        error: unref(form).errors.is_public,
                        class: "mb-4 w-full",
                        label: _ctx.__("Public")
                      }, {
                        default: withCtx(() => [
                          createVNode("option", { value: 1 }, toDisplayString(_ctx.__("Yes")), 1),
                          createVNode("option", { value: 0 }, toDisplayString(_ctx.__("No")), 1)
                        ]),
                        _: 1
                      }, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(_sfc_main$4, {
                        modelValue: unref(form).user_id,
                        "onUpdate:modelValue": ($event) => unref(form).user_id = $event,
                        error: unref(form).errors.user_id,
                        class: "w-full",
                        label: _ctx.__("Owner")
                      }, {
                        default: withCtx(() => [
                          createVNode("option", {
                            value: __props.playlist.owner.id
                          }, toDisplayString(__props.playlist.owner.name), 9, ["value"]),
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.playlist.moderators, (moderator) => {
                            return openBlock(), createBlock("option", {
                              value: moderator.id
                            }, toDisplayString(moderator.name), 9, ["value"]);
                          }), 256))
                        ]),
                        _: 1
                      }, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode("small", null, toDisplayString(_ctx.__("You can transfer the playlist management to a moderator.")), 1)
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$5, {
              playlist: __props.playlist,
              class: "mb-4"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$6, { playlist: __props.playlist }, null, _parent2, _scopeId));
            _push2(`</div><div class="flex-1"${_scopeId}>`);
            if (__props.playlist.deleted_at) {
              _push2(ssrRenderComponent(_sfc_main$7, {
                class: "mb-6",
                onRestore: restore
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("This playlist has been deleted."))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("This playlist has been deleted.")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(ssrRenderComponent(_sfc_main$8, {
              playlist: __props.playlist,
              filters: __props.filters,
              tracks: __props.tracks,
              answer_types: __props.answer_types
            }, null, _parent2, _scopeId));
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("h1", { class: "mb-4 text-3xl font-bold text-teal-600" }, [
                createVNode(unref(Link), {
                  href: _ctx.route("admin.playlists")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("Playlists")), 1)
                  ]),
                  _: 1
                }, 8, ["href"]),
                createVNode("span", { class: "font-medium" }, " / "),
                createTextVNode(" " + toDisplayString(unref(form).name), 1)
              ]),
              createVNode("div", { class: "flex flex-wrap gap-4" }, [
                createVNode("div", { class: "flex w-full flex-col xl:w-1/4" }, [
                  createVNode(Card, { class: "mb-4" }, {
                    header: withCtx(() => [
                      createVNode("h3", { class: "text-xl font-bold" }, "Playlist")
                    ]),
                    footer: withCtx(() => [
                      !__props.playlist.deleted_at ? (openBlock(), createBlock("button", {
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
                      createVNode("form", {
                        id: "playlistForm",
                        class: "p-4",
                        onSubmit: withModifiers(update, ["prevent"])
                      }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).name,
                          "onUpdate:modelValue": ($event) => unref(form).name = $event,
                          error: unref(form).errors.name,
                          class: "mb-4 w-full",
                          label: _ctx.__("Title")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$3, {
                          modelValue: unref(form).description,
                          "onUpdate:modelValue": ($event) => unref(form).description = $event,
                          error: unref(form).errors.description,
                          class: "mb-4 w-full",
                          label: _ctx.__("Description")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$4, {
                          modelValue: unref(form).is_public,
                          "onUpdate:modelValue": ($event) => unref(form).is_public = $event,
                          error: unref(form).errors.is_public,
                          class: "mb-4 w-full",
                          label: _ctx.__("Public")
                        }, {
                          default: withCtx(() => [
                            createVNode("option", { value: 1 }, toDisplayString(_ctx.__("Yes")), 1),
                            createVNode("option", { value: 0 }, toDisplayString(_ctx.__("No")), 1)
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$4, {
                          modelValue: unref(form).user_id,
                          "onUpdate:modelValue": ($event) => unref(form).user_id = $event,
                          error: unref(form).errors.user_id,
                          class: "w-full",
                          label: _ctx.__("Owner")
                        }, {
                          default: withCtx(() => [
                            createVNode("option", {
                              value: __props.playlist.owner.id
                            }, toDisplayString(__props.playlist.owner.name), 9, ["value"]),
                            (openBlock(true), createBlock(Fragment, null, renderList(__props.playlist.moderators, (moderator) => {
                              return openBlock(), createBlock("option", {
                                value: moderator.id
                              }, toDisplayString(moderator.name), 9, ["value"]);
                            }), 256))
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode("small", null, toDisplayString(_ctx.__("You can transfer the playlist management to a moderator.")), 1)
                      ], 40, ["onSubmit"])
                    ]),
                    _: 1
                  }),
                  createVNode(_sfc_main$5, {
                    playlist: __props.playlist,
                    class: "mb-4"
                  }, null, 8, ["playlist"]),
                  createVNode(_sfc_main$6, { playlist: __props.playlist }, null, 8, ["playlist"])
                ]),
                createVNode("div", { class: "flex-1" }, [
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Playlists/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
