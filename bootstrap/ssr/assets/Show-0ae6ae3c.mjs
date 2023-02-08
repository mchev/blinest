import { unref, withCtx, createVNode, toDisplayString, createTextVNode, withModifiers, openBlock, createBlock, Fragment, renderList, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./FileInput-ae863f9e.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { _ as _sfc_main$4 } from "./Pagination-6aa0e1ca.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
const _sfc_main = {
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    user: Object
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      name: props.user.name,
      email: props.user.email,
      photo: null
    });
    const update = () => {
      form.post(route("users.update", props.user.id), {
        onSuccess: () => {
          form.reset("photo");
        }
      });
    };
    const deleteUser = () => {
      if (confirm("Attention, cette action est irréversible. Voulez-vous vraiment supprimer votre compte et tous les scores associés?")) {
        router.delete(route("users.destroy", props.user.id));
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: __props.user.name
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex flex-wrap"${_scopeId}><div class="lg:mr-4 pr-4 text-center w-full lg:w-auto mt-4"${_scopeId}><figure class="mb-6 flex justify-center"${_scopeId}><img${ssrRenderAttr("src", __props.user.photo)}${ssrRenderAttr("alt", __props.user.name)} class="w-60 rounded-lg"${_scopeId}></figure><h2 class="text-xl font-bold"${_scopeId}>${ssrInterpolate(__props.user.name)}</h2><p class="text-xs text-neutral-400"${_scopeId}>${ssrInterpolate(__props.user.email)}</p><ul class="my-8"${_scopeId}><li class="mb-4 flex flex-col"${_scopeId}><span class="font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Registered at"))}</span><span${_scopeId}>${ssrInterpolate(__props.user.created_at)}<br${_scopeId}><small${_scopeId}>${ssrInterpolate(__props.user.created_at_from_now)}</small></span></li><li class="mb-4 flex flex-col"${_scopeId}><span class="font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Id"))}</span> ${ssrInterpolate(__props.user.id)}</li>`);
            if (__props.user.latest_round_at) {
              _push2(`<li class="mb-4 flex flex-col"${_scopeId}><span class="font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Last round played at"))}</span> ${ssrInterpolate(__props.user.latest_round_at)}</li>`);
            } else {
              _push2(`<li class="mb-4 flex flex-col"${_scopeId}>${ssrInterpolate(_ctx.__("No round played yet"))}</li>`);
            }
            _push2(`<li class="mb-4 flex flex-col"${_scopeId}><span class="font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Score"))}</span><span${_scopeId}>${ssrInterpolate(__props.user.total_score)}<sup class="ml-1"${_scopeId}>PTS</sup></span></li></ul><button class="btn-danger btn-sm mx-auto"${_scopeId}>${ssrInterpolate(_ctx.__("Delete my account"))}</button></div><div class="flex-1"${_scopeId}>`);
            _push2(ssrRenderComponent(Card, { class: "my-4" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h2 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Informations"))}</h2>`);
                } else {
                  return [
                    createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Informations")), 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(LoadingButton, {
                    loading: unref(form).processing,
                    class: "btn-primary ml-auto",
                    form: "editUserForm",
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
                      form: "editUserForm",
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
                  _push3(`<form id="editUserForm"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    error: unref(form).errors.name,
                    class: "mb-4 w-full",
                    label: _ctx.__("Name"),
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).email,
                    "onUpdate:modelValue": ($event) => unref(form).email = $event,
                    type: "email",
                    error: unref(form).errors.email,
                    class: "mb-4 w-full",
                    label: _ctx.__("Email"),
                    required: ""
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    modelValue: unref(form).photo,
                    "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                    error: unref(form).errors.photo,
                    class: "mb-4 w-full",
                    type: "file",
                    accept: "image/*",
                    label: _ctx.__("Photo")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    type: "password",
                    error: unref(form).errors.password,
                    class: "mb-4 w-full",
                    label: _ctx.__("New password") + " (" + _ctx.__("Optional") + ")",
                    autocomplete: "new-password",
                    name: "new-password"
                  }, null, _parent3, _scopeId2));
                  _push3(`</form>`);
                } else {
                  return [
                    createVNode("form", {
                      id: "editUserForm",
                      onSubmit: withModifiers(update, ["prevent"])
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).name,
                        "onUpdate:modelValue": ($event) => unref(form).name = $event,
                        error: unref(form).errors.name,
                        class: "mb-4 w-full",
                        label: _ctx.__("Name"),
                        required: ""
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).email,
                        "onUpdate:modelValue": ($event) => unref(form).email = $event,
                        type: "email",
                        error: unref(form).errors.email,
                        class: "mb-4 w-full",
                        label: _ctx.__("Email"),
                        required: ""
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).photo,
                        "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                        error: unref(form).errors.photo,
                        class: "mb-4 w-full",
                        type: "file",
                        accept: "image/*",
                        label: _ctx.__("Photo")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).password,
                        "onUpdate:modelValue": ($event) => unref(form).password = $event,
                        type: "password",
                        error: unref(form).errors.password,
                        class: "mb-4 w-full",
                        label: _ctx.__("New password") + " (" + _ctx.__("Optional") + ")",
                        autocomplete: "new-password",
                        name: "new-password"
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                    ], 40, ["onSubmit"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<div class="grid grid-cols-1 lg:grid-cols-2 gap-4"${_scopeId}>`);
            _push2(ssrRenderComponent(Card, { class: "" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h2 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Playlists"))}</h2>`);
                } else {
                  return [
                    createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Playlists")), 1)
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (__props.user.playlists.length) {
                    _push3(`<ul class="flex flex-wrap items-center"${_scopeId2}><!--[-->`);
                    ssrRenderList(__props.user.playlists, (playlist) => {
                      _push3(`<li class="${ssrRenderClass(["bg-teal-900", playlist.user_id === __props.user.id, "badge"])}"${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        href: _ctx.route("playlists.edit", playlist.id)
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`${ssrInterpolate(playlist.name)}`);
                          } else {
                            return [
                              createTextVNode(toDisplayString(playlist.name), 1)
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</li>`);
                    });
                    _push3(`<!--]--></ul>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    __props.user.playlists.length ? (openBlock(), createBlock("ul", {
                      key: 0,
                      class: "flex flex-wrap items-center"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(__props.user.playlists, (playlist) => {
                        return openBlock(), createBlock("li", {
                          key: "playlist-" + playlist.id,
                          class: ["badge", "bg-teal-900", playlist.user_id === __props.user.id]
                        }, [
                          createVNode(unref(Link), {
                            href: _ctx.route("playlists.edit", playlist.id)
                          }, {
                            default: withCtx(() => [
                              createTextVNode(toDisplayString(playlist.name), 1)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ], 2);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(Card, { class: "" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h2 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Rooms"))}</h2>`);
                } else {
                  return [
                    createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Rooms")), 1)
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (__props.user.rooms.length) {
                    _push3(`<ul class="flex flex-wrap items-center"${_scopeId2}><!--[-->`);
                    ssrRenderList(__props.user.rooms, (room) => {
                      _push3(`<li class="${ssrRenderClass(["bg-teal-900", room.user_id === __props.user.id, "badge"])}"${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        href: _ctx.route("rooms.edit", room.id)
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`${ssrInterpolate(room.name)}`);
                          } else {
                            return [
                              createTextVNode(toDisplayString(room.name), 1)
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</li>`);
                    });
                    _push3(`<!--]--></ul>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    __props.user.rooms.length ? (openBlock(), createBlock("ul", {
                      key: 0,
                      class: "flex flex-wrap items-center"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(__props.user.rooms, (room) => {
                        return openBlock(), createBlock("li", {
                          key: "room-" + room.id,
                          class: ["badge", "bg-teal-900", room.user_id === __props.user.id]
                        }, [
                          createVNode(unref(Link), {
                            href: _ctx.route("rooms.edit", room.id)
                          }, {
                            default: withCtx(() => [
                              createTextVNode(toDisplayString(room.name), 1)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ], 2);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(Card, { class: "my-4 hidden lg:flex" }, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex w-full justify-between items-center"${_scopeId2}><h2 class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Scores"))}</h2><span${_scopeId2}>${ssrInterpolate(__props.user.total_score)}<sup class="ml-1"${_scopeId2}>PTS</sup></span></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex w-full justify-between items-center" }, [
                      createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Scores")), 1),
                      createVNode("span", null, [
                        createTextVNode(toDisplayString(__props.user.total_score), 1),
                        createVNode("sup", { class: "ml-1" }, "PTS")
                      ])
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="overflow-x-auto relative"${_scopeId2}><table class="w-full whitespace-nowrap"${_scopeId2}><thead${_scopeId2}><tr class="text-left font-bold"${_scopeId2}><th class="px-6 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Room"))}</th><th class="px-6 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Last played game"))}</th><th class="px-6 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Score"))}</th><th class="px-6 pb-4 pt-6"${_scopeId2}>${ssrInterpolate(_ctx.__("Score"))} Max</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                  ssrRenderList(__props.user.scores.data, (score) => {
                    _push3(`<tr${_scopeId2}><td class="border-t border-neutral-500"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4 focus:text-blinest-500",
                      href: _ctx.route("rooms.show", score.room_slug)
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`<div class="flex flex-col"${_scopeId3}>${ssrInterpolate(score.name)}</div>`);
                        } else {
                          return [
                            createVNode("div", { class: "flex flex-col" }, toDisplayString(score.name), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t border-neutral-500"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: _ctx.route("rooms.show", score.room_slug),
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(score.date)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(score.date), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t border-neutral-500"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: _ctx.route("rooms.show", score.room_slug),
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(score.total)}<sup class="ml-1"${_scopeId3}>PTS</sup>`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(score.total), 1),
                            createVNode("sup", { class: "ml-1" }, "PTS")
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td><td class="border-t border-neutral-500"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      class: "flex items-center px-6 py-4",
                      href: _ctx.route("rooms.show", score.room_slug),
                      tabindex: "-1"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(score.max)}<sup class="ml-1"${_scopeId3}>PTS</sup>`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(score.max), 1),
                            createVNode("sup", { class: "ml-1" }, "PTS")
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                    _push3(`</td></tr>`);
                  });
                  _push3(`<!--]-->`);
                  if (__props.user.scores.length === 0) {
                    _push3(`<tr${_scopeId2}><td class="border-t border-neutral-500 px-6 py-4" colspan="3"${_scopeId2}>${ssrInterpolate(_ctx.__("No scores"))}</td></tr>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`</tbody></table>`);
                  _push3(ssrRenderComponent(_sfc_main$4, {
                    links: __props.user.scores.links
                  }, null, _parent3, _scopeId2));
                  _push3(`</div>`);
                } else {
                  return [
                    createVNode("div", { class: "overflow-x-auto relative" }, [
                      createVNode("table", { class: "w-full whitespace-nowrap" }, [
                        createVNode("thead", null, [
                          createVNode("tr", { class: "text-left font-bold" }, [
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Room")), 1),
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Last played game")), 1),
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Score")), 1),
                            createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Score")) + " Max", 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.user.scores.data, (score) => {
                            return openBlock(), createBlock("tr", {
                              key: score.room_id
                            }, [
                              createVNode("td", { class: "border-t border-neutral-500" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4 focus:text-blinest-500",
                                  href: _ctx.route("rooms.show", score.room_slug)
                                }, {
                                  default: withCtx(() => [
                                    createVNode("div", { class: "flex flex-col" }, toDisplayString(score.name), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t border-neutral-500" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: _ctx.route("rooms.show", score.room_slug),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(score.date), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t border-neutral-500" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: _ctx.route("rooms.show", score.room_slug),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(score.total), 1),
                                    createVNode("sup", { class: "ml-1" }, "PTS")
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createVNode("td", { class: "border-t border-neutral-500" }, [
                                createVNode(unref(Link), {
                                  class: "flex items-center px-6 py-4",
                                  href: _ctx.route("rooms.show", score.room_slug),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(score.max), 1),
                                    createVNode("sup", { class: "ml-1" }, "PTS")
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ])
                            ]);
                          }), 128)),
                          __props.user.scores.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                            createVNode("td", {
                              class: "border-t border-neutral-500 px-6 py-4",
                              colspan: "3"
                            }, toDisplayString(_ctx.__("No scores")), 1)
                          ])) : createCommentVNode("", true)
                        ])
                      ]),
                      createVNode(_sfc_main$4, {
                        links: __props.user.scores.links
                      }, null, 8, ["links"])
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "flex flex-wrap" }, [
                createVNode("div", { class: "lg:mr-4 pr-4 text-center w-full lg:w-auto mt-4" }, [
                  createVNode("figure", { class: "mb-6 flex justify-center" }, [
                    createVNode("img", {
                      src: __props.user.photo,
                      alt: __props.user.name,
                      class: "w-60 rounded-lg"
                    }, null, 8, ["src", "alt"])
                  ]),
                  createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(__props.user.name), 1),
                  createVNode("p", { class: "text-xs text-neutral-400" }, toDisplayString(__props.user.email), 1),
                  createVNode("ul", { class: "my-8" }, [
                    createVNode("li", { class: "mb-4 flex flex-col" }, [
                      createVNode("span", { class: "font-bold" }, toDisplayString(_ctx.__("Registered at")), 1),
                      createVNode("span", null, [
                        createTextVNode(toDisplayString(__props.user.created_at), 1),
                        createVNode("br"),
                        createVNode("small", null, toDisplayString(__props.user.created_at_from_now), 1)
                      ])
                    ]),
                    createVNode("li", { class: "mb-4 flex flex-col" }, [
                      createVNode("span", { class: "font-bold" }, toDisplayString(_ctx.__("Id")), 1),
                      createTextVNode(" " + toDisplayString(__props.user.id), 1)
                    ]),
                    __props.user.latest_round_at ? (openBlock(), createBlock("li", {
                      key: 0,
                      class: "mb-4 flex flex-col"
                    }, [
                      createVNode("span", { class: "font-bold" }, toDisplayString(_ctx.__("Last round played at")), 1),
                      createTextVNode(" " + toDisplayString(__props.user.latest_round_at), 1)
                    ])) : (openBlock(), createBlock("li", {
                      key: 1,
                      class: "mb-4 flex flex-col"
                    }, toDisplayString(_ctx.__("No round played yet")), 1)),
                    createVNode("li", { class: "mb-4 flex flex-col" }, [
                      createVNode("span", { class: "font-bold" }, toDisplayString(_ctx.__("Score")), 1),
                      createVNode("span", null, [
                        createTextVNode(toDisplayString(__props.user.total_score), 1),
                        createVNode("sup", { class: "ml-1" }, "PTS")
                      ])
                    ])
                  ]),
                  createVNode("button", {
                    onClick: deleteUser,
                    class: "btn-danger btn-sm mx-auto"
                  }, toDisplayString(_ctx.__("Delete my account")), 1)
                ]),
                createVNode("div", { class: "flex-1" }, [
                  createVNode(Card, { class: "my-4" }, {
                    header: withCtx(() => [
                      createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Informations")), 1)
                    ]),
                    footer: withCtx(() => [
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary ml-auto",
                        form: "editUserForm",
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
                        id: "editUserForm",
                        onSubmit: withModifiers(update, ["prevent"])
                      }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).name,
                          "onUpdate:modelValue": ($event) => unref(form).name = $event,
                          error: unref(form).errors.name,
                          class: "mb-4 w-full",
                          label: _ctx.__("Name"),
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).email,
                          "onUpdate:modelValue": ($event) => unref(form).email = $event,
                          type: "email",
                          error: unref(form).errors.email,
                          class: "mb-4 w-full",
                          label: _ctx.__("Email"),
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$3, {
                          modelValue: unref(form).photo,
                          "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                          error: unref(form).errors.photo,
                          class: "mb-4 w-full",
                          type: "file",
                          accept: "image/*",
                          label: _ctx.__("Photo")
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).password,
                          "onUpdate:modelValue": ($event) => unref(form).password = $event,
                          type: "password",
                          error: unref(form).errors.password,
                          class: "mb-4 w-full",
                          label: _ctx.__("New password") + " (" + _ctx.__("Optional") + ")",
                          autocomplete: "new-password",
                          name: "new-password"
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                      ], 40, ["onSubmit"])
                    ]),
                    _: 1
                  }),
                  createVNode("div", { class: "grid grid-cols-1 lg:grid-cols-2 gap-4" }, [
                    createVNode(Card, { class: "" }, {
                      header: withCtx(() => [
                        createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Playlists")), 1)
                      ]),
                      default: withCtx(() => [
                        __props.user.playlists.length ? (openBlock(), createBlock("ul", {
                          key: 0,
                          class: "flex flex-wrap items-center"
                        }, [
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.user.playlists, (playlist) => {
                            return openBlock(), createBlock("li", {
                              key: "playlist-" + playlist.id,
                              class: ["badge", "bg-teal-900", playlist.user_id === __props.user.id]
                            }, [
                              createVNode(unref(Link), {
                                href: _ctx.route("playlists.edit", playlist.id)
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(playlist.name), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ], 2);
                          }), 128))
                        ])) : createCommentVNode("", true)
                      ]),
                      _: 1
                    }),
                    createVNode(Card, { class: "" }, {
                      header: withCtx(() => [
                        createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Rooms")), 1)
                      ]),
                      default: withCtx(() => [
                        __props.user.rooms.length ? (openBlock(), createBlock("ul", {
                          key: 0,
                          class: "flex flex-wrap items-center"
                        }, [
                          (openBlock(true), createBlock(Fragment, null, renderList(__props.user.rooms, (room) => {
                            return openBlock(), createBlock("li", {
                              key: "room-" + room.id,
                              class: ["badge", "bg-teal-900", room.user_id === __props.user.id]
                            }, [
                              createVNode(unref(Link), {
                                href: _ctx.route("rooms.edit", room.id)
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(room.name), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ], 2);
                          }), 128))
                        ])) : createCommentVNode("", true)
                      ]),
                      _: 1
                    })
                  ]),
                  createVNode(Card, { class: "my-4 hidden lg:flex" }, {
                    header: withCtx(() => [
                      createVNode("div", { class: "flex w-full justify-between items-center" }, [
                        createVNode("h2", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Scores")), 1),
                        createVNode("span", null, [
                          createTextVNode(toDisplayString(__props.user.total_score), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])
                      ])
                    ]),
                    default: withCtx(() => [
                      createVNode("div", { class: "overflow-x-auto relative" }, [
                        createVNode("table", { class: "w-full whitespace-nowrap" }, [
                          createVNode("thead", null, [
                            createVNode("tr", { class: "text-left font-bold" }, [
                              createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Room")), 1),
                              createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Last played game")), 1),
                              createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Score")), 1),
                              createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Score")) + " Max", 1)
                            ])
                          ]),
                          createVNode("tbody", null, [
                            (openBlock(true), createBlock(Fragment, null, renderList(__props.user.scores.data, (score) => {
                              return openBlock(), createBlock("tr", {
                                key: score.room_id
                              }, [
                                createVNode("td", { class: "border-t border-neutral-500" }, [
                                  createVNode(unref(Link), {
                                    class: "flex items-center px-6 py-4 focus:text-blinest-500",
                                    href: _ctx.route("rooms.show", score.room_slug)
                                  }, {
                                    default: withCtx(() => [
                                      createVNode("div", { class: "flex flex-col" }, toDisplayString(score.name), 1)
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ]),
                                createVNode("td", { class: "border-t border-neutral-500" }, [
                                  createVNode(unref(Link), {
                                    class: "flex items-center px-6 py-4",
                                    href: _ctx.route("rooms.show", score.room_slug),
                                    tabindex: "-1"
                                  }, {
                                    default: withCtx(() => [
                                      createTextVNode(toDisplayString(score.date), 1)
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ]),
                                createVNode("td", { class: "border-t border-neutral-500" }, [
                                  createVNode(unref(Link), {
                                    class: "flex items-center px-6 py-4",
                                    href: _ctx.route("rooms.show", score.room_slug),
                                    tabindex: "-1"
                                  }, {
                                    default: withCtx(() => [
                                      createTextVNode(toDisplayString(score.total), 1),
                                      createVNode("sup", { class: "ml-1" }, "PTS")
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ]),
                                createVNode("td", { class: "border-t border-neutral-500" }, [
                                  createVNode(unref(Link), {
                                    class: "flex items-center px-6 py-4",
                                    href: _ctx.route("rooms.show", score.room_slug),
                                    tabindex: "-1"
                                  }, {
                                    default: withCtx(() => [
                                      createTextVNode(toDisplayString(score.max), 1),
                                      createVNode("sup", { class: "ml-1" }, "PTS")
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ])
                              ]);
                            }), 128)),
                            __props.user.scores.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                              createVNode("td", {
                                class: "border-t border-neutral-500 px-6 py-4",
                                colspan: "3"
                              }, toDisplayString(_ctx.__("No scores")), 1)
                            ])) : createCommentVNode("", true)
                          ])
                        ]),
                        createVNode(_sfc_main$4, {
                          links: __props.user.scores.links
                        }, null, 8, ["links"])
                      ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Me/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
