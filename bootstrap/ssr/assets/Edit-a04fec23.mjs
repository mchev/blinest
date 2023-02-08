import { onUnmounted, unref, withCtx, createTextVNode, toDisplayString, createVNode, openBlock, createBlock, createCommentVNode, Fragment, renderList, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr } from "vue/server-renderer";
import { usePage, useForm, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import { _ as _sfc_main$6 } from "./FileInput-ae863f9e.mjs";
import { _ as _sfc_main$3 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$4 } from "./TextareaInput-989d56d6.mjs";
import { _ as _sfc_main$5 } from "./SelectInput-279cfc81.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { _ as _sfc_main$7, a as _sfc_main$9 } from "./PlaylistsManager-a79a3170.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import { T as Tip } from "./Tip-027e2e8e.mjs";
import { _ as _sfc_main$2 } from "./Share-6d7e5e1b.mjs";
import "lodash/pickBy.js";
import "lodash/throttle.js";
import "lodash/mapValues.js";
import _sfc_main$8 from "./EditOptionsForm-78776de2.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
import "lodash/debounce.js";
import "./Modal-61e7836d.mjs";
import "./CheckboxInput-934baa4b.mjs";
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
    const user = usePage().props.auth.user;
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
        router.delete(route("rooms.destroy", props.room.id));
      }
    };
    onUnmounted(() => {
      Echo.leave(`rooms.${props.room.id}`);
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: _ctx.__("Edit Room")
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h1 class="mb-8 flex items-center gap-2 text-3xl font-bold"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("rooms.index")
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
            _push2(` / ${ssrInterpolate(__props.room.name)} `);
            _push2(ssrRenderComponent(_sfc_main$2, {
              url: _ctx.route("rooms.show", __props.room.slug),
              class: "h-6 w-6"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              class: "btn-primary ml-auto",
              href: _ctx.route("rooms.show", __props.room.slug)
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Play"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Play")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</h1><div class="flex flex-wrap gap-4"${_scopeId}><div class="flex w-full flex-col xl:w-1/4"${_scopeId}>`);
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
                    _push3(`<button class="text-sm text-red-500 hover:underline" tabindex="-1" type="button"${_scopeId2}>${ssrInterpolate(_ctx.__("Delete"))}</button>`);
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
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form id="roomForm"${_scopeId2}><div class="flex"${_scopeId2}><div class="flex flex-wrap"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    class: "mb-4 w-full",
                    label: _ctx.__("Name")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    modelValue: unref(form).description,
                    "onUpdate:modelValue": ($event) => unref(form).description = $event,
                    error: unref(form).errors.description,
                    class: "mb-4 w-full",
                    label: _ctx.__("Description")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$5, {
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
                  if (__props.room.is_pro || __props.room.is_public || unref(user).permissions.canUploadImage) {
                    _push3(ssrRenderComponent(_sfc_main$6, {
                      modelValue: unref(form).photo,
                      "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                      error: unref(form).errors.photo,
                      class: "mb-4 w-full",
                      type: "file",
                      accept: "image/*",
                      label: _ctx.__("Photo")
                    }, null, _parent3, _scopeId2));
                  } else {
                    _push3(`<!---->`);
                  }
                  if (!__props.room.is_pro && !__props.room.is_public && !unref(user).permissions.canUploadImage) {
                    _push3(ssrRenderComponent(Tip, null, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(` Pour changer l&#39;image de la room vous devez avoir un score total de 2000 minimum et 3 mois d&#39;ancienneté. `);
                        } else {
                          return [
                            createTextVNode(" Pour changer l'image de la room vous devez avoir un score total de 2000 minimum et 3 mois d'ancienneté. ")
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`</div></div></form>`);
                } else {
                  return [
                    createVNode("form", {
                      onSubmit: withModifiers(update, ["prevent"]),
                      id: "roomForm"
                    }, [
                      createVNode("div", { class: "flex" }, [
                        createVNode("div", { class: "flex flex-wrap" }, [
                          createVNode(_sfc_main$3, {
                            modelValue: unref(form).name,
                            "onUpdate:modelValue": ($event) => unref(form).name = $event,
                            error: unref(form).errors.name,
                            class: "mb-4 w-full",
                            label: _ctx.__("Name")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$4, {
                            modelValue: unref(form).description,
                            "onUpdate:modelValue": ($event) => unref(form).description = $event,
                            error: unref(form).errors.description,
                            class: "mb-4 w-full",
                            label: _ctx.__("Description")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                          createVNode(_sfc_main$5, {
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
                          __props.room.is_pro || __props.room.is_public || unref(user).permissions.canUploadImage ? (openBlock(), createBlock(_sfc_main$6, {
                            key: 0,
                            modelValue: unref(form).photo,
                            "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                            error: unref(form).errors.photo,
                            class: "mb-4 w-full",
                            type: "file",
                            accept: "image/*",
                            label: _ctx.__("Photo")
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])) : createCommentVNode("", true),
                          !__props.room.is_pro && !__props.room.is_public && !unref(user).permissions.canUploadImage ? (openBlock(), createBlock(Tip, { key: 1 }, {
                            default: withCtx(() => [
                              createTextVNode(" Pour changer l'image de la room vous devez avoir un score total de 2000 minimum et 3 mois d'ancienneté. ")
                            ]),
                            _: 1
                          })) : createCommentVNode("", true)
                        ])
                      ])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$7, { room: __props.room }, null, _parent2, _scopeId));
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
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$8, { room: __props.room }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$8, { room: __props.room }, null, 8, ["room"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$9, {
              room: __props.room,
              playlists: __props.available_playlists
            }, null, _parent2, _scopeId));
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("h1", { class: "mb-8 flex items-center gap-2 text-3xl font-bold" }, [
                createVNode(unref(Link), {
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
                createVNode(unref(Link), {
                  class: "btn-primary ml-auto",
                  href: _ctx.route("rooms.show", __props.room.slug)
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(_ctx.__("Play")), 1)
                  ]),
                  _: 1
                }, 8, ["href"])
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
                      createVNode("form", {
                        onSubmit: withModifiers(update, ["prevent"]),
                        id: "roomForm"
                      }, [
                        createVNode("div", { class: "flex" }, [
                          createVNode("div", { class: "flex flex-wrap" }, [
                            createVNode(_sfc_main$3, {
                              modelValue: unref(form).name,
                              "onUpdate:modelValue": ($event) => unref(form).name = $event,
                              error: unref(form).errors.name,
                              class: "mb-4 w-full",
                              label: _ctx.__("Name")
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                            createVNode(_sfc_main$4, {
                              modelValue: unref(form).description,
                              "onUpdate:modelValue": ($event) => unref(form).description = $event,
                              error: unref(form).errors.description,
                              class: "mb-4 w-full",
                              label: _ctx.__("Description")
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                            createVNode(_sfc_main$5, {
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
                            __props.room.is_pro || __props.room.is_public || unref(user).permissions.canUploadImage ? (openBlock(), createBlock(_sfc_main$6, {
                              key: 0,
                              modelValue: unref(form).photo,
                              "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                              error: unref(form).errors.photo,
                              class: "mb-4 w-full",
                              type: "file",
                              accept: "image/*",
                              label: _ctx.__("Photo")
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])) : createCommentVNode("", true),
                            !__props.room.is_pro && !__props.room.is_public && !unref(user).permissions.canUploadImage ? (openBlock(), createBlock(Tip, { key: 1 }, {
                              default: withCtx(() => [
                                createTextVNode(" Pour changer l'image de la room vous devez avoir un score total de 2000 minimum et 3 mois d'ancienneté. ")
                              ]),
                              _: 1
                            })) : createCommentVNode("", true)
                          ])
                        ])
                      ], 40, ["onSubmit"])
                    ]),
                    _: 1
                  }),
                  createVNode(_sfc_main$7, { room: __props.room }, null, 8, ["room"])
                ]),
                createVNode("div", { class: "flex-1" }, [
                  createVNode(Card, { class: "mb-4" }, {
                    header: withCtx(() => [
                      createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Options")), 1)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
