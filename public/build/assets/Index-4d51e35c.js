import { P, l as watch, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, n as ne, v as renderList, g as createCommentVNode, d as createTextVNode, h as createBlock, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$3 } from "./Icon-86c99edc.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import { _ as _sfc_main$4 } from "./Pagination-cf5f2783.js";
import { _ as _sfc_main$2 } from "./SearchFilter-74587dd6.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Navbar-cadf2428.js";
import "./TextInput-541fe8b5.js";
import "./v4-e7604ebc.js";
import "./SocialIcon-5ed77127.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
const _hoisted_1 = { class: "mb-8 text-3xl font-bold" };
const _hoisted_2 = { class: "mb-6 flex items-center justify-between" };
const _hoisted_3 = { class: "overflow-x-auto" };
const _hoisted_4 = { class: "w-full whitespace-nowrap" };
const _hoisted_5 = { class: "text-left font-bold" };
const _hoisted_6 = { class: "px-6 pb-4 pt-6" };
const _hoisted_7 = { class: "px-6 pb-4 pt-6" };
const _hoisted_8 = {
  class: "px-6 pb-4 pt-6",
  colspan: "2"
};
const _hoisted_9 = { class: "border-t border-neutral-500" };
const _hoisted_10 = ["src"];
const _hoisted_11 = { class: "flex flex-col" };
const _hoisted_12 = { class: "max-w-md truncate text-xs" };
const _hoisted_13 = { class: "border-t border-neutral-500" };
const _hoisted_14 = { class: "border-t border-neutral-500" };
const _hoisted_15 = { class: "flex items-center px-6 py-4 text-sm" };
const _hoisted_16 = { class: "w-px border-t border-neutral-500" };
const _hoisted_17 = { key: 0 };
const _hoisted_18 = /* @__PURE__ */ createBaseVNode("td", {
  class: "border-t border-neutral-500 px-6 py-4",
  colspan: "4"
}, "No playlists found.", -1);
const _hoisted_19 = [
  _hoisted_18
];
const _sfc_main = {
  __name: "Index",
  props: {
    filters: Object,
    playlists: Object
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
        Je.get("/playlists", pickBy_1(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Playlists")
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("h1", _hoisted_1, toDisplayString(_ctx.__("Playlists")), 1),
            createBaseVNode("div", _hoisted_2, [
              createVNode(_sfc_main$2, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).search = $event),
                class: "mr-4 w-full max-w-md",
                onReset: reset
              }, null, 8, ["modelValue"]),
              createVNode(unref(ne), {
                class: "btn-primary",
                href: _ctx.route("playlists.create")
              }, {
                default: withCtx(() => [
                  createBaseVNode("span", null, toDisplayString(_ctx.__("Create a playlist")), 1)
                ]),
                _: 1
              }, 8, ["href"])
            ]),
            createVNode(Card, { class: "my-4" }, {
              default: withCtx(() => [
                createBaseVNode("div", _hoisted_3, [
                  createBaseVNode("table", _hoisted_4, [
                    createBaseVNode("tr", _hoisted_5, [
                      createBaseVNode("th", _hoisted_6, toDisplayString(_ctx.__("Name")), 1),
                      createBaseVNode("th", _hoisted_7, toDisplayString(_ctx.__("Owner")), 1),
                      createBaseVNode("th", _hoisted_8, toDisplayString(_ctx.__("Moderators")), 1)
                    ]),
                    (openBlock(true), createElementBlock(Fragment, null, renderList(__props.playlists.data, (playlist) => {
                      return openBlock(), createElementBlock("tr", {
                        key: playlist.id
                      }, [
                        createBaseVNode("td", _hoisted_9, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-6 py-4 focus:text-blinest-500",
                            href: _ctx.route("playlists.edit", playlist.id)
                          }, {
                            default: withCtx(() => [
                              playlist.photo ? (openBlock(), createElementBlock("img", {
                                key: 0,
                                class: "-my-2 mr-2 block h-5 w-5 rounded-full",
                                src: playlist.photo
                              }, null, 8, _hoisted_10)) : createCommentVNode("", true),
                              createBaseVNode("div", _hoisted_11, [
                                createTextVNode(toDisplayString(playlist.name) + " ", 1),
                                createBaseVNode("small", _hoisted_12, toDisplayString(playlist.description), 1)
                              ]),
                              playlist.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                                key: 1,
                                name: "trash",
                                class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                              })) : createCommentVNode("", true)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_13, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-6 py-4",
                            href: _ctx.route("playlists.edit", playlist.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              createTextVNode(toDisplayString(playlist.owner.name), 1)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_14, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-6 py-4",
                            href: _ctx.route("playlists.edit", playlist.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              createBaseVNode("ul", _hoisted_15, [
                                (openBlock(true), createElementBlock(Fragment, null, renderList(playlist.moderators, (moderator) => {
                                  return openBlock(), createElementBlock("li", {
                                    key: moderator.id,
                                    class: "badge"
                                  }, toDisplayString(moderator.name), 1);
                                }), 128))
                              ])
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("td", _hoisted_16, [
                          createVNode(unref(ne), {
                            class: "flex items-center px-4",
                            href: _ctx.route("playlists.edit", playlist.id),
                            tabindex: "-1"
                          }, {
                            default: withCtx(() => [
                              createVNode(_sfc_main$3, {
                                name: "cheveron-right",
                                class: "block h-6 w-6"
                              })
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ])
                      ]);
                    }), 128)),
                    __props.playlists.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_17, _hoisted_19)) : createCommentVNode("", true)
                  ]),
                  createVNode(_sfc_main$4, {
                    links: __props.playlists.links
                  }, null, 8, ["links"])
                ])
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
