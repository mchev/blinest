import { X, n as ne, c as createElementBlock, a as createVNode, b as createBaseVNode, t as toDisplayString, w as withCtx, j as resolveComponent, o as openBlock, z as withDirectives, A as vModelSelect, F as Fragment, v as renderList, g as createCommentVNode, d as createTextVNode, h as createBlock } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./Icon-86c99edc.js";
import { k as keys_1, _ as _baseIteratee, a as _baseAssignValue, t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import { _ as _sfc_main$4 } from "./AdminLayout-f56d4654.js";
import { _ as _sfc_main$2 } from "./SearchFilter-74587dd6.js";
import { _ as _sfc_main$3 } from "./Pagination-cf5f2783.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./LanguageSwitcher-da420d03.js";
import "./Dropdown-f0e2d937.js";
function createBaseFor$1(fromRight) {
  return function(object, iteratee, keysFunc) {
    var index = -1, iterable = Object(object), props = keysFunc(object), length = props.length;
    while (length--) {
      var key = props[fromRight ? length : ++index];
      if (iteratee(iterable[key], key, iterable) === false) {
        break;
      }
    }
    return object;
  };
}
var _createBaseFor = createBaseFor$1;
var createBaseFor = _createBaseFor;
var baseFor$1 = createBaseFor();
var _baseFor = baseFor$1;
var baseFor = _baseFor, keys = keys_1;
function baseForOwn$1(object, iteratee) {
  return object && baseFor(object, iteratee, keys);
}
var _baseForOwn = baseForOwn$1;
var baseAssignValue = _baseAssignValue, baseForOwn = _baseForOwn, baseIteratee = _baseIteratee;
function mapValues(object, iteratee) {
  var result = {};
  iteratee = baseIteratee(iteratee);
  baseForOwn(object, function(value, key, object2) {
    baseAssignValue(result, key, iteratee(value, key, object2));
  });
  return result;
}
var mapValues_1 = mapValues;
const _sfc_main = {
  components: {
    Head: X,
    Icon: _sfc_main$1,
    Link: ne,
    SearchFilter: _sfc_main$2,
    Pagination: _sfc_main$3,
    Card
  },
  layout: _sfc_main$4,
  props: {
    filters: Object,
    users: Object
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        role: this.filters.role,
        trashed: this.filters.trashed
      }
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get(route("admin.users"), pickBy_1(this.form), { preserveState: true });
      }, 150)
    }
  },
  methods: {
    reset() {
      this.form = mapValues_1(this.form, () => null);
    }
  }
};
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
const _hoisted_9 = /* @__PURE__ */ createBaseVNode("span", { class: "hidden md:inline" }, " User", -1);
const _hoisted_10 = { class: "w-full whitespace-nowrap" };
const _hoisted_11 = /* @__PURE__ */ createBaseVNode("tr", { class: "text-left font-bold" }, [
  /* @__PURE__ */ createBaseVNode("th", { class: "px-6 pb-4 pt-6" }, "Name"),
  /* @__PURE__ */ createBaseVNode("th", { class: "px-6 pb-4 pt-6" }, "Email"),
  /* @__PURE__ */ createBaseVNode("th", { class: "px-6 pb-4 pt-6" }, "Provider"),
  /* @__PURE__ */ createBaseVNode("th", { class: "px-6 pb-4 pt-6" }, "Inscription"),
  /* @__PURE__ */ createBaseVNode("th", {
    class: "px-6 pb-4 pt-6",
    colspan: "2"
  }, "Role")
], -1);
const _hoisted_12 = { class: "border-t" };
const _hoisted_13 = ["src"];
const _hoisted_14 = { class: "border-t" };
const _hoisted_15 = { class: "border-t" };
const _hoisted_16 = { class: "border-t" };
const _hoisted_17 = { class: "border-t" };
const _hoisted_18 = { class: "w-px border-t" };
const _hoisted_19 = { key: 0 };
const _hoisted_20 = /* @__PURE__ */ createBaseVNode("td", {
  class: "border-t px-6 py-4",
  colspan: "4"
}, "No users found.", -1);
const _hoisted_21 = [
  _hoisted_20
];
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_search_filter = resolveComponent("search-filter");
  const _component_Link = resolveComponent("Link");
  const _component_icon = resolveComponent("icon");
  const _component_Pagination = resolveComponent("Pagination");
  const _component_card = resolveComponent("card");
  return openBlock(), createElementBlock("div", null, [
    createVNode(_component_Head, { title: "Users" }),
    createBaseVNode("h1", _hoisted_1, "Users (" + toDisplayString($props.users.total) + ")", 1),
    createBaseVNode("div", _hoisted_2, [
      createVNode(_component_search_filter, {
        modelValue: $data.form.search,
        "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => $data.form.search = $event),
        class: "mr-4 w-full max-w-md",
        onReset: $options.reset
      }, {
        default: withCtx(() => [
          _hoisted_3,
          withDirectives(createBaseVNode("select", {
            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => $data.form.trashed = $event),
            class: "form-select mt-1 w-full"
          }, _hoisted_7, 512), [
            [vModelSelect, $data.form.trashed]
          ])
        ]),
        _: 1
      }, 8, ["modelValue", "onReset"]),
      createVNode(_component_Link, {
        class: "btn-primary",
        href: _ctx.route("admin.users.create")
      }, {
        default: withCtx(() => [
          _hoisted_8,
          _hoisted_9
        ]),
        _: 1
      }, 8, ["href"])
    ]),
    createVNode(_component_card, null, {
      default: withCtx(() => [
        createBaseVNode("table", _hoisted_10, [
          _hoisted_11,
          (openBlock(true), createElementBlock(Fragment, null, renderList($props.users.data, (user) => {
            return openBlock(), createElementBlock("tr", {
              key: user.id,
              class: "focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
            }, [
              createBaseVNode("td", _hoisted_12, [
                createVNode(_component_Link, {
                  class: "flex items-center px-6 py-4 focus:text-blinest-500",
                  href: _ctx.route("admin.users.edit", user.id)
                }, {
                  default: withCtx(() => [
                    user.photo ? (openBlock(), createElementBlock("img", {
                      key: 0,
                      class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                      src: user.photo
                    }, null, 8, _hoisted_13)) : createCommentVNode("", true),
                    createTextVNode(" " + toDisplayString(user.name) + " ", 1),
                    user.deleted_at ? (openBlock(), createBlock(_component_icon, {
                      key: 1,
                      name: "trash",
                      class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                    })) : createCommentVNode("", true)
                  ]),
                  _: 2
                }, 1032, ["href"])
              ]),
              createBaseVNode("td", _hoisted_14, [
                createVNode(_component_Link, {
                  class: "flex items-center px-6 py-4",
                  href: _ctx.route("admin.users.edit", user.id),
                  tabindex: "-1"
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(user.email), 1)
                  ]),
                  _: 2
                }, 1032, ["href"])
              ]),
              createBaseVNode("td", _hoisted_15, [
                createVNode(_component_Link, {
                  class: "flex items-center px-6 py-4",
                  href: _ctx.route("admin.users.edit", user.id),
                  tabindex: "-1"
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(user.provider), 1)
                  ]),
                  _: 2
                }, 1032, ["href"])
              ]),
              createBaseVNode("td", _hoisted_16, [
                createVNode(_component_Link, {
                  class: "flex items-center px-6 py-4",
                  href: _ctx.route("admin.users.edit", user.id),
                  tabindex: "-1"
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(user.created_at), 1)
                  ]),
                  _: 2
                }, 1032, ["href"])
              ]),
              createBaseVNode("td", _hoisted_17, [
                createVNode(_component_Link, {
                  class: "flex items-center px-6 py-4",
                  href: _ctx.route("admin.users.edit", user.id),
                  tabindex: "-1"
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(user.is_admin ? "Admin" : "User"), 1)
                  ]),
                  _: 2
                }, 1032, ["href"])
              ]),
              createBaseVNode("td", _hoisted_18, [
                createVNode(_component_Link, {
                  class: "flex items-center px-4",
                  href: _ctx.route("admin.users.edit", user.id),
                  tabindex: "-1"
                }, {
                  default: withCtx(() => [
                    createVNode(_component_icon, {
                      name: "cheveron-right",
                      class: "block h-6 w-6 fill-gray-400"
                    })
                  ]),
                  _: 2
                }, 1032, ["href"])
              ])
            ]);
          }), 128)),
          $props.users.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_19, _hoisted_21)) : createCommentVNode("", true)
        ]),
        createVNode(_component_Pagination, {
          links: $props.users.links
        }, null, 8, ["links"])
      ]),
      _: 1
    })
  ]);
}
const Index = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export {
  Index as default
};
