import { P, l as watch, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, z as withDirectives, A as vModelSelect, n as ne, v as renderList, d as createTextVNode, t as toDisplayString, h as createBlock, g as createCommentVNode, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$3 } from "./Icon-86c99edc.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$4 } from "./Pagination-cf5f2783.js";
import { _ as _sfc_main$2 } from "./SearchFilter-74587dd6.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
const _hoisted_1 = /* @__PURE__ */ createBaseVNode("h1", { class: "mb-8 text-3xl font-bold" }, "Teams", -1);
const _hoisted_2 = { class: "mb-6 flex items-center justify-between" };
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("option", { value: null }, null, -1);
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("option", { value: "with" }, "With Trashed", -1);
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("option", { value: "only" }, "Only Trashed", -1);
const _hoisted_6 = [
  _hoisted_3,
  _hoisted_4,
  _hoisted_5
];
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("span", null, "Create", -1);
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("span", { class: "hidden md:inline" }, " Team", -1);
const _hoisted_9 = { class: "overflow-x-auto" };
const _hoisted_10 = { class: "w-full whitespace-nowrap" };
const _hoisted_11 = /* @__PURE__ */ createBaseVNode("thead", null, [
  /* @__PURE__ */ createBaseVNode("tr", { class: "text-left font-bold" }, [
    /* @__PURE__ */ createBaseVNode("th", { class: "px-6 pb-4 pt-6" }, "Name"),
    /* @__PURE__ */ createBaseVNode("th", {
      class: "px-6 pb-4 pt-6",
      colspan: "2"
    }, "Members")
  ])
], -1);
const _hoisted_12 = { class: "border-t" };
const _hoisted_13 = { class: "border-t" };
const _hoisted_14 = { class: "w-px border-t" };
const _hoisted_15 = { key: 0 };
const _hoisted_16 = /* @__PURE__ */ createBaseVNode("td", {
  class: "border-t px-6 py-4",
  colspan: "4"
}, "No teams found.", -1);
const _hoisted_17 = [
  _hoisted_16
];
const _sfc_main = {
  __name: "Index",
  props: {
    filters: Object,
    teams: Object
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
        Je.get("/admin/teams", pickBy_1(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const reset = () => {
      form.reset();
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Teams" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            _hoisted_1,
            createBaseVNode("div", _hoisted_2, [
              createVNode(_sfc_main$2, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).search = $event),
                class: "mr-4 w-full max-w-md",
                onReset: reset
              }, {
                default: withCtx(() => [
                  withDirectives(createBaseVNode("select", {
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).trashed = $event),
                    class: "form-select mt-1 w-full"
                  }, _hoisted_6, 512), [
                    [vModelSelect, unref(form).trashed]
                  ])
                ]),
                _: 1
              }, 8, ["modelValue"]),
              createVNode(unref(ne), {
                class: "btn-primary",
                href: "/admin/teams/create"
              }, {
                default: withCtx(() => [
                  _hoisted_7,
                  _hoisted_8
                ]),
                _: 1
              })
            ]),
            createVNode(Card, null, {
              default: withCtx(() => [
                createBaseVNode("div", _hoisted_9, [
                  createBaseVNode("table", _hoisted_10, [
                    _hoisted_11,
                    createBaseVNode("tbody", null, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(__props.teams.data, (team) => {
                        return openBlock(), createElementBlock("tr", {
                          key: team.id,
                          class: "hover:bg-neutral-200"
                        }, [
                          createBaseVNode("td", _hoisted_12, [
                            createVNode(unref(ne), {
                              class: "flex items-center px-6 py-4 focus:text-indigo-500",
                              href: _ctx.route("admin.teams.edit", team)
                            }, {
                              default: withCtx(() => [
                                createTextVNode(toDisplayString(team.name) + " ", 1),
                                team.deleted_at ? (openBlock(), createBlock(_sfc_main$3, {
                                  key: 0,
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
                              href: _ctx.route("admin.teams.edit", team),
                              tabindex: "-1"
                            }, {
                              default: withCtx(() => [
                                createTextVNode(toDisplayString(team.members_count), 1)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createBaseVNode("td", _hoisted_14, [
                            createVNode(unref(ne), {
                              class: "flex items-center px-4",
                              href: _ctx.route("admin.teams.edit", team),
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
                      __props.teams.data.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_15, _hoisted_17)) : createCommentVNode("", true)
                    ])
                  ])
                ]),
                createVNode(_sfc_main$4, {
                  links: __props.teams.links
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
