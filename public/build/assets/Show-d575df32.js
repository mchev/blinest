import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, d as createTextVNode, e as withModifiers, v as renderList, f as normalizeClass, n as ne, g as createCommentVNode, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$3 } from "./FileInput-ca2b29b7.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { _ as _sfc_main$4 } from "./Pagination-cf5f2783.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
import "./v4-e7604ebc.js";
const _hoisted_1 = { class: "flex flex-wrap" };
const _hoisted_2 = { class: "lg:mr-4 pr-4 text-center w-full lg:w-auto mt-4" };
const _hoisted_3 = { class: "mb-6 flex justify-center" };
const _hoisted_4 = ["src", "alt"];
const _hoisted_5 = { class: "text-xl font-bold" };
const _hoisted_6 = { class: "text-xs text-neutral-400" };
const _hoisted_7 = { class: "my-8" };
const _hoisted_8 = { class: "mb-4 flex flex-col" };
const _hoisted_9 = { class: "font-bold" };
const _hoisted_10 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_11 = { class: "mb-4 flex flex-col" };
const _hoisted_12 = { class: "font-bold" };
const _hoisted_13 = {
  key: 0,
  class: "mb-4 flex flex-col"
};
const _hoisted_14 = { class: "font-bold" };
const _hoisted_15 = {
  key: 1,
  class: "mb-4 flex flex-col"
};
const _hoisted_16 = { class: "mb-4 flex flex-col" };
const _hoisted_17 = { class: "font-bold" };
const _hoisted_18 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_19 = { class: "flex-1" };
const _hoisted_20 = { class: "text-xl font-bold" };
const _hoisted_21 = ["onSubmit"];
const _hoisted_22 = { class: "grid grid-cols-1 lg:grid-cols-2 gap-4" };
const _hoisted_23 = { class: "text-xl font-bold" };
const _hoisted_24 = {
  key: 0,
  class: "flex flex-wrap items-center"
};
const _hoisted_25 = { class: "text-xl font-bold" };
const _hoisted_26 = {
  key: 0,
  class: "flex flex-wrap items-center"
};
const _hoisted_27 = { class: "flex w-full justify-between items-center" };
const _hoisted_28 = { class: "text-xl font-bold" };
const _hoisted_29 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_30 = { class: "overflow-x-auto relative" };
const _hoisted_31 = { class: "w-full whitespace-nowrap" };
const _hoisted_32 = { class: "text-left font-bold" };
const _hoisted_33 = { class: "px-6 pb-4 pt-6" };
const _hoisted_34 = { class: "px-6 pb-4 pt-6" };
const _hoisted_35 = { class: "px-6 pb-4 pt-6" };
const _hoisted_36 = { class: "px-6 pb-4 pt-6" };
const _hoisted_37 = { class: "border-t border-neutral-500" };
const _hoisted_38 = { class: "flex flex-col" };
const _hoisted_39 = { class: "border-t border-neutral-500" };
const _hoisted_40 = { class: "border-t border-neutral-500" };
const _hoisted_41 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_42 = { class: "border-t border-neutral-500" };
const _hoisted_43 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_44 = { key: 0 };
const _hoisted_45 = {
  class: "border-t border-neutral-500 px-6 py-4",
  colspan: "3"
};
const _sfc_main = {
  __name: "Show",
  props: {
    user: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
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
        Je.delete(route("users.destroy", props.user.id));
      }
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: __props.user.name
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              createBaseVNode("div", _hoisted_2, [
                createBaseVNode("figure", _hoisted_3, [
                  createBaseVNode("img", {
                    src: __props.user.photo,
                    alt: __props.user.name,
                    class: "w-60 rounded-lg"
                  }, null, 8, _hoisted_4)
                ]),
                createBaseVNode("h2", _hoisted_5, toDisplayString(__props.user.name), 1),
                createBaseVNode("p", _hoisted_6, toDisplayString(__props.user.email), 1),
                createBaseVNode("ul", _hoisted_7, [
                  createBaseVNode("li", _hoisted_8, [
                    createBaseVNode("span", _hoisted_9, toDisplayString(_ctx.__("Registered at")), 1),
                    createBaseVNode("span", null, [
                      createTextVNode(toDisplayString(__props.user.created_at), 1),
                      _hoisted_10,
                      createBaseVNode("small", null, toDisplayString(__props.user.created_at_from_now), 1)
                    ])
                  ]),
                  createBaseVNode("li", _hoisted_11, [
                    createBaseVNode("span", _hoisted_12, toDisplayString(_ctx.__("Id")), 1),
                    createTextVNode(" " + toDisplayString(__props.user.id), 1)
                  ]),
                  __props.user.latest_round_at ? (openBlock(), createElementBlock("li", _hoisted_13, [
                    createBaseVNode("span", _hoisted_14, toDisplayString(_ctx.__("Last round played at")), 1),
                    createTextVNode(" " + toDisplayString(__props.user.latest_round_at), 1)
                  ])) : (openBlock(), createElementBlock("li", _hoisted_15, toDisplayString(_ctx.__("No round played yet")), 1)),
                  createBaseVNode("li", _hoisted_16, [
                    createBaseVNode("span", _hoisted_17, toDisplayString(_ctx.__("Score")), 1),
                    createBaseVNode("span", null, [
                      createTextVNode(toDisplayString(__props.user.total_score), 1),
                      _hoisted_18
                    ])
                  ])
                ]),
                createBaseVNode("button", {
                  onClick: deleteUser,
                  class: "btn-danger btn-sm mx-auto"
                }, toDisplayString(_ctx.__("Delete my account")), 1)
              ]),
              createBaseVNode("div", _hoisted_19, [
                createVNode(Card, { class: "my-4" }, {
                  header: withCtx(() => [
                    createBaseVNode("h2", _hoisted_20, toDisplayString(_ctx.__("Informations")), 1)
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
                    createBaseVNode("form", {
                      id: "editUserForm",
                      onSubmit: withModifiers(update, ["prevent"])
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).name,
                        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                        error: unref(form).errors.name,
                        class: "mb-4 w-full",
                        label: _ctx.__("Name"),
                        required: ""
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).email,
                        "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).email = $event),
                        type: "email",
                        error: unref(form).errors.email,
                        class: "mb-4 w-full",
                        label: _ctx.__("Email"),
                        required: ""
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$3, {
                        modelValue: unref(form).photo,
                        "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).photo = $event),
                        error: unref(form).errors.photo,
                        class: "mb-4 w-full",
                        type: "file",
                        accept: "image/*",
                        label: _ctx.__("Photo")
                      }, null, 8, ["modelValue", "error", "label"]),
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).password,
                        "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).password = $event),
                        type: "password",
                        error: unref(form).errors.password,
                        class: "mb-4 w-full",
                        label: _ctx.__("New password") + " (" + _ctx.__("Optional") + ")",
                        autocomplete: "new-password",
                        name: "new-password"
                      }, null, 8, ["modelValue", "error", "label"])
                    ], 40, _hoisted_21)
                  ]),
                  _: 1
                }),
                createBaseVNode("div", _hoisted_22, [
                  createVNode(Card, { class: "" }, {
                    header: withCtx(() => [
                      createBaseVNode("h2", _hoisted_23, toDisplayString(_ctx.__("Playlists")), 1)
                    ]),
                    default: withCtx(() => [
                      __props.user.playlists.length ? (openBlock(), createElementBlock("ul", _hoisted_24, [
                        (openBlock(true), createElementBlock(Fragment, null, renderList(__props.user.playlists, (playlist) => {
                          return openBlock(), createElementBlock("li", {
                            key: "playlist-" + playlist.id,
                            class: normalizeClass(["badge", "bg-teal-900", playlist.user_id === __props.user.id])
                          }, [
                            createVNode(unref(ne), {
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
                      createBaseVNode("h2", _hoisted_25, toDisplayString(_ctx.__("Rooms")), 1)
                    ]),
                    default: withCtx(() => [
                      __props.user.rooms.length ? (openBlock(), createElementBlock("ul", _hoisted_26, [
                        (openBlock(true), createElementBlock(Fragment, null, renderList(__props.user.rooms, (room) => {
                          return openBlock(), createElementBlock("li", {
                            key: "room-" + room.id,
                            class: normalizeClass(["badge", "bg-teal-900", room.user_id === __props.user.id])
                          }, [
                            createVNode(unref(ne), {
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
                    createBaseVNode("div", _hoisted_27, [
                      createBaseVNode("h2", _hoisted_28, toDisplayString(_ctx.__("Scores")), 1),
                      createBaseVNode("span", null, [
                        createTextVNode(toDisplayString(__props.user.total_score), 1),
                        _hoisted_29
                      ])
                    ])
                  ]),
                  default: withCtx(() => [
                    createBaseVNode("div", _hoisted_30, [
                      createBaseVNode("table", _hoisted_31, [
                        createBaseVNode("thead", null, [
                          createBaseVNode("tr", _hoisted_32, [
                            createBaseVNode("th", _hoisted_33, toDisplayString(_ctx.__("Room")), 1),
                            createBaseVNode("th", _hoisted_34, toDisplayString(_ctx.__("Last played game")), 1),
                            createBaseVNode("th", _hoisted_35, toDisplayString(_ctx.__("Score")), 1),
                            createBaseVNode("th", _hoisted_36, toDisplayString(_ctx.__("Score")) + " Max", 1)
                          ])
                        ]),
                        createBaseVNode("tbody", null, [
                          (openBlock(true), createElementBlock(Fragment, null, renderList(__props.user.scores.data, (score) => {
                            return openBlock(), createElementBlock("tr", {
                              key: score.room_id
                            }, [
                              createBaseVNode("td", _hoisted_37, [
                                createVNode(unref(ne), {
                                  class: "flex items-center px-6 py-4 focus:text-blinest-500",
                                  href: _ctx.route("rooms.show", score.room_slug)
                                }, {
                                  default: withCtx(() => [
                                    createBaseVNode("div", _hoisted_38, toDisplayString(score.name), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createBaseVNode("td", _hoisted_39, [
                                createVNode(unref(ne), {
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
                              createBaseVNode("td", _hoisted_40, [
                                createVNode(unref(ne), {
                                  class: "flex items-center px-6 py-4",
                                  href: _ctx.route("rooms.show", score.room_slug),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(score.total), 1),
                                    _hoisted_41
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]),
                              createBaseVNode("td", _hoisted_42, [
                                createVNode(unref(ne), {
                                  class: "flex items-center px-6 py-4",
                                  href: _ctx.route("rooms.show", score.room_slug),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(score.max), 1),
                                    _hoisted_43
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ])
                            ]);
                          }), 128)),
                          __props.user.scores.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_44, [
                            createBaseVNode("td", _hoisted_45, toDisplayString(_ctx.__("No scores")), 1)
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
