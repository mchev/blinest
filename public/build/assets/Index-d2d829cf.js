import { P, l as watch, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, z as withDirectives, A as vModelSelect, n as ne, v as renderList, g as createCommentVNode, d as createTextVNode, h as createBlock, f as normalizeClass, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$3 } from "./Icon-86c99edc.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import { _ as _sfc_main$4 } from "./Pagination-cf5f2783.js";
import { _ as _sfc_main$2 } from "./SearchFilter-74587dd6.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
const _hoisted_1 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_2 = { class: "mb-6 flex items-center justify-between" };
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("label", { class: "mt-4 block text-gray-700" }, "Trashed:", -1);
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("option", { value: null }, null, -1);
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("option", { value: "with" }, "With Trashed", -1);
const _hoisted_6 = /* @__PURE__ */ createBaseVNode("option", { value: "only" }, "Only Trashed", -1);
const _hoisted_7 = [
  _hoisted_4,
  _hoisted_5,
  _hoisted_6
];
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("span", null, "Create", -1);
const _hoisted_9 = /* @__PURE__ */ createBaseVNode("span", { class: "hidden md:inline" }, " Room", -1);
const _hoisted_10 = { class: "overflow-x-auto" };
const _hoisted_11 = { class: "w-full whitespace-nowrap" };
const _hoisted_12 = { class: "text-left font-bold" };
const _hoisted_13 = { class: "px-2 pb-4 pt-6" };
const _hoisted_14 = { class: "px-2 pb-4 pt-6" };
const _hoisted_15 = { class: "px-2 pb-4 pt-6" };
const _hoisted_16 = { class: "px-2 pb-4 pt-6" };
const _hoisted_17 = {
  class: "px-2 pb-4 pt-6",
  colspan: "2"
};
const _hoisted_18 = { class: "border-t" };
const _hoisted_19 = ["src"];
const _hoisted_20 = { class: "flex flex-col" };
const _hoisted_21 = { class: "max-w-[18rem] truncate text-gray-500" };
const _hoisted_22 = { class: "border-t" };
const _hoisted_23 = ["src"];
const _hoisted_24 = { class: "border-t" };
const _hoisted_25 = { class: "flex items-center px-2 py-4 text-sm" };
const _hoisted_26 = { class: "border-t" };
const _hoisted_27 = { class: "flex items-center px-2 py-4 text-sm" };
const _hoisted_28 = { class: "border-t" };
const _hoisted_29 = { class: "w-px border-t" };
const _hoisted_30 = { key: 0 };
const _hoisted_31 = {
  class: "border-t px-2 py-4",
  colspan: "6"
};
const _sfc_main = {
  __name: "Index",
  props: {
    filters: Object,
    rooms: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
      search: props.filters.search,
      trashed: props.filters.trashed
    });
    watch(
      form,
      throttle(() => {
        Je.get("/admin/rooms", pickBy_1(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Rooms" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, "Rooms (" + toDisplayString(__props.rooms.total) + ")", 1),
            createBaseVNode("div", _hoisted_2, [
              createVNode(_sfc_main$2, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).search = $event),
                class: "mr-4 w-full max-w-md",
                onReset: reset
              }, {
                default: withCtx(() => [
                  _hoisted_3,
                  withDirectives(createBaseVNode("select", {
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).trashed = $event),
                    class: "form-select mt-1 w-full"
                  }, _hoisted_7, 512), [
                    [vModelSelect, unref(form).trashed]
                  ])
                ]),
                _: 1
              }, 8, ["modelValue"]),
              createVNode(unref(ne), {
                class: "btn-primary",
                href: _ctx.route("admin.rooms.create")
              }, {
                default: withCtx(() => [
                  _hoisted_8,
                  _hoisted_9
                ]),
                _: 1
              }, 8, ["href"])
            ]),
            createVNode(Card, null, {
              default: withCtx(() => [
                createBaseVNode("div", _hoisted_10, [
                  createBaseVNode("table", _hoisted_11, [
                    createBaseVNode("tr", _hoisted_12, [
                      createBaseVNode("th", _hoisted_13, toDisplayString(_ctx.__("Name")), 1),
                      createBaseVNode("th", _hoisted_14, toDisplayString(_ctx.__("Owner")), 1),
                      createBaseVNode("th", _hoisted_15, toDisplayString(_ctx.__("Moderators")), 1),
                      createBaseVNode("th", _hoisted_16, toDisplayString(_ctx.__("Playlists")), 1),
                      createBaseVNode("th", _hoisted_17, toDisplayString(_ctx.__("Public")), 1)
                    ]),
                    (openBlock(true), createElementBlock(Fragment, null, renderList(__props.rooms.data, (room) => {
                      return openBlock(), createElementBlock("tr", {
                        key: room.id,
                        class: "hover:bg-neutral-200"
                      }, [
                        createBaseVNode("td", _hoisted_18, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-2 py-4",
                            href: _ctx.route("admin.rooms.edit", room.id)
                          }, {
                            default: withCtx(() => [
                              room.photo ? (openBlock(), createElementBlock("img", {
                                key: 0,
                                class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                                src: room.photo
                              }, null, 8, _hoisted_19)) : createCommentVNode("", true),
                              createBaseVNode("div", _hoisted_20, [
                                createTextVNode(toDisplayString(room.name) + " ", 1),
                                createBaseVNode("small", _hoisted_21, toDisplayString(room.description), 1)
                              ]),
                              room.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                                key: 1,
                                name: "trash",
                                class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                              })) : createCommentVNode("", true)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_22, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-2 py-4",
                            href: _ctx.route("admin.rooms.edit", room.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              room.owner.photo ? (openBlock(), createElementBlock("img", {
                                key: 0,
                                class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                                src: room.owner.photo
                              }, null, 8, _hoisted_23)) : createCommentVNode("", true),
                              createTextVNode(" " + toDisplayString(room.owner.name), 1)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_24, [
                          createBaseVNode("ul", _hoisted_25, [
                            (openBlock(true), createElementBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                              return openBlock(), createElementBlock("li", {
                                key: moderator.id
                              }, [
                                createVNode(unref(ne), {
                                  class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                                  href: _ctx.route("admin.users.edit", moderator.id),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(moderator.name), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]);
                            }), 128))
                          ])
                        ]),
                        createBaseVNode("td", _hoisted_26, [
                          createBaseVNode("ul", _hoisted_27, [
                            (openBlock(true), createElementBlock(Fragment, null, renderList(room.playlists, (playlist) => {
                              return openBlock(), createElementBlock("li", {
                                key: playlist.id
                              }, [
                                createVNode(unref(ne), {
                                  class: "m-1 rounded bg-neutral-300 p-1 hover:underline",
                                  href: _ctx.route("admin.playlists.edit", playlist.id),
                                  tabindex: "-1"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode(toDisplayString(playlist.name), 1)
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ]);
                            }), 128))
                          ])
                        ]),
                        createBaseVNode("td", _hoisted_28, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-2 py-4",
                            href: _ctx.route("admin.rooms.edit", room.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              createBaseVNode("span", {
                                class: normalizeClass(["m-1 rounded px-2 py-1", room.is_public ? "bg-teal-600  text-neutral-100" : "bg-neutral-300"])
                              }, toDisplayString(room.is_public ? _ctx.__("Yes") : _ctx.__("No")), 3)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_29, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-4",
                            href: _ctx.route("admin.rooms.edit", room.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              createVNode(_sfc_main$3, {
                                name: "cheveron-right",
                                class: "block h-6 w-6 fill-gray-400"
                              })
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ])
                      ]);
                    }), 128)),
                    __props.rooms.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_30, [
                      createBaseVNode("td", _hoisted_31, toDisplayString(_ctx.__("No rooms found.")), 1)
                    ])) : createCommentVNode("", true)
                  ])
                ]),
                createVNode(_sfc_main$4, {
                  links: __props.rooms.links
                }, null, 8, ["links"])
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
