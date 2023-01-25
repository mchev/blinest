import { Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./Icon-4a47e6e0.mjs";
import pickBy from "lodash/pickBy.js";
import { _ as _sfc_main$4 } from "./AdminLayout-a34dfeea.mjs";
import throttle from "lodash/throttle.js";
import mapValues from "lodash/mapValues.js";
import { _ as _sfc_main$2 } from "./SearchFilter-26a0a59f.mjs";
import { _ as _sfc_main$3 } from "./Pagination-d517bd16.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { resolveComponent, withCtx, createVNode, withDirectives, vModelSelect, openBlock, createBlock, createCommentVNode, createTextVNode, toDisplayString, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
const _sfc_main = {
  components: {
    Head,
    Icon: _sfc_main$1,
    Link,
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
        this.$inertia.get(route("admin.users"), pickBy(this.form), { preserveState: true });
      }, 150)
    }
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    }
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_search_filter = resolveComponent("search-filter");
  const _component_Link = resolveComponent("Link");
  const _component_card = resolveComponent("card");
  const _component_icon = resolveComponent("icon");
  const _component_Pagination = resolveComponent("Pagination");
  _push(`<div${ssrRenderAttrs(_attrs)}>`);
  _push(ssrRenderComponent(_component_Head, { title: "Users" }, null, _parent));
  _push(`<h1 class="mb-8 text-3xl font-bold">Users (${ssrInterpolate($props.users.total)})</h1><div class="mb-6 flex items-center justify-between">`);
  _push(ssrRenderComponent(_component_search_filter, {
    modelValue: $data.form.search,
    "onUpdate:modelValue": ($event) => $data.form.search = $event,
    class: "mr-4 w-full max-w-md",
    onReset: $options.reset
  }, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<label class="mt-4 block text-gray-700"${_scopeId}>Trashed:</label><select class="form-select mt-1 w-full"${_scopeId}><option${ssrRenderAttr("value", null)}${_scopeId}></option><option value="with"${_scopeId}>With Trashed</option><option value="only"${_scopeId}>Only Trashed</option></select>`);
      } else {
        return [
          createVNode("label", { class: "mt-4 block text-gray-700" }, "Trashed:"),
          withDirectives(createVNode("select", {
            "onUpdate:modelValue": ($event) => $data.form.trashed = $event,
            class: "form-select mt-1 w-full"
          }, [
            createVNode("option", { value: null }),
            createVNode("option", { value: "with" }, "With Trashed"),
            createVNode("option", { value: "only" }, "Only Trashed")
          ], 8, ["onUpdate:modelValue"]), [
            [vModelSelect, $data.form.trashed]
          ])
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(ssrRenderComponent(_component_Link, {
    class: "btn-primary",
    href: _ctx.route("admin.users.create")
  }, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<span${_scopeId}>Create</span><span class="hidden md:inline"${_scopeId}> User</span>`);
      } else {
        return [
          createVNode("span", null, "Create"),
          createVNode("span", { class: "hidden md:inline" }, " User")
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(`</div>`);
  _push(ssrRenderComponent(_component_card, null, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<table class="w-full whitespace-nowrap"${_scopeId}><tr class="text-left font-bold"${_scopeId}><th class="px-6 pb-4 pt-6"${_scopeId}>Name</th><th class="px-6 pb-4 pt-6"${_scopeId}>Email</th><th class="px-6 pb-4 pt-6"${_scopeId}>Provider</th><th class="px-6 pb-4 pt-6"${_scopeId}>Inscription</th><th class="px-6 pb-4 pt-6" colspan="2"${_scopeId}>Role</th></tr><!--[-->`);
        ssrRenderList($props.users.data, (user) => {
          _push2(`<tr class="focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"${_scopeId}><td class="border-t"${_scopeId}>`);
          _push2(ssrRenderComponent(_component_Link, {
            class: "flex items-center px-6 py-4 focus:text-blinest-500",
            href: _ctx.route("admin.users.edit", user.id)
          }, {
            default: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3) {
                if (user.photo) {
                  _push3(`<img class="-my-2 mr-2 block h-10 w-10 rounded-full"${ssrRenderAttr("src", user.photo)}${_scopeId2}>`);
                } else {
                  _push3(`<!---->`);
                }
                _push3(` ${ssrInterpolate(user.name)} `);
                if (user.deleted_at) {
                  _push3(ssrRenderComponent(_component_icon, {
                    name: "trash",
                    class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                  }, null, _parent3, _scopeId2));
                } else {
                  _push3(`<!---->`);
                }
              } else {
                return [
                  user.photo ? (openBlock(), createBlock("img", {
                    key: 0,
                    class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                    src: user.photo
                  }, null, 8, ["src"])) : createCommentVNode("", true),
                  createTextVNode(" " + toDisplayString(user.name) + " ", 1),
                  user.deleted_at ? (openBlock(), createBlock(_component_icon, {
                    key: 1,
                    name: "trash",
                    class: "ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"
                  })) : createCommentVNode("", true)
                ];
              }
            }),
            _: 2
          }, _parent2, _scopeId));
          _push2(`</td><td class="border-t"${_scopeId}>`);
          _push2(ssrRenderComponent(_component_Link, {
            class: "flex items-center px-6 py-4",
            href: _ctx.route("admin.users.edit", user.id),
            tabindex: "-1"
          }, {
            default: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3) {
                _push3(`${ssrInterpolate(user.email)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(user.email), 1)
                ];
              }
            }),
            _: 2
          }, _parent2, _scopeId));
          _push2(`</td><td class="border-t"${_scopeId}>`);
          _push2(ssrRenderComponent(_component_Link, {
            class: "flex items-center px-6 py-4",
            href: _ctx.route("admin.users.edit", user.id),
            tabindex: "-1"
          }, {
            default: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3) {
                _push3(`${ssrInterpolate(user.provider)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(user.provider), 1)
                ];
              }
            }),
            _: 2
          }, _parent2, _scopeId));
          _push2(`</td><td class="border-t"${_scopeId}>`);
          _push2(ssrRenderComponent(_component_Link, {
            class: "flex items-center px-6 py-4",
            href: _ctx.route("admin.users.edit", user.id),
            tabindex: "-1"
          }, {
            default: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3) {
                _push3(`${ssrInterpolate(user.created_at)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(user.created_at), 1)
                ];
              }
            }),
            _: 2
          }, _parent2, _scopeId));
          _push2(`</td><td class="border-t"${_scopeId}>`);
          _push2(ssrRenderComponent(_component_Link, {
            class: "flex items-center px-6 py-4",
            href: _ctx.route("admin.users.edit", user.id),
            tabindex: "-1"
          }, {
            default: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3) {
                _push3(`${ssrInterpolate(user.is_admin ? "Admin" : "User")}`);
              } else {
                return [
                  createTextVNode(toDisplayString(user.is_admin ? "Admin" : "User"), 1)
                ];
              }
            }),
            _: 2
          }, _parent2, _scopeId));
          _push2(`</td><td class="w-px border-t"${_scopeId}>`);
          _push2(ssrRenderComponent(_component_Link, {
            class: "flex items-center px-4",
            href: _ctx.route("admin.users.edit", user.id),
            tabindex: "-1"
          }, {
            default: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3) {
                _push3(ssrRenderComponent(_component_icon, {
                  name: "cheveron-right",
                  class: "block h-6 w-6 fill-gray-400"
                }, null, _parent3, _scopeId2));
              } else {
                return [
                  createVNode(_component_icon, {
                    name: "cheveron-right",
                    class: "block h-6 w-6 fill-gray-400"
                  })
                ];
              }
            }),
            _: 2
          }, _parent2, _scopeId));
          _push2(`</td></tr>`);
        });
        _push2(`<!--]-->`);
        if ($props.users.length === 0) {
          _push2(`<tr${_scopeId}><td class="border-t px-6 py-4" colspan="4"${_scopeId}>No users found.</td></tr>`);
        } else {
          _push2(`<!---->`);
        }
        _push2(`</table>`);
        _push2(ssrRenderComponent(_component_Pagination, {
          links: $props.users.links
        }, null, _parent2, _scopeId));
      } else {
        return [
          createVNode("table", { class: "w-full whitespace-nowrap" }, [
            createVNode("tr", { class: "text-left font-bold" }, [
              createVNode("th", { class: "px-6 pb-4 pt-6" }, "Name"),
              createVNode("th", { class: "px-6 pb-4 pt-6" }, "Email"),
              createVNode("th", { class: "px-6 pb-4 pt-6" }, "Provider"),
              createVNode("th", { class: "px-6 pb-4 pt-6" }, "Inscription"),
              createVNode("th", {
                class: "px-6 pb-4 pt-6",
                colspan: "2"
              }, "Role")
            ]),
            (openBlock(true), createBlock(Fragment, null, renderList($props.users.data, (user) => {
              return openBlock(), createBlock("tr", {
                key: user.id,
                class: "focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
              }, [
                createVNode("td", { class: "border-t" }, [
                  createVNode(_component_Link, {
                    class: "flex items-center px-6 py-4 focus:text-blinest-500",
                    href: _ctx.route("admin.users.edit", user.id)
                  }, {
                    default: withCtx(() => [
                      user.photo ? (openBlock(), createBlock("img", {
                        key: 0,
                        class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                        src: user.photo
                      }, null, 8, ["src"])) : createCommentVNode("", true),
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
                createVNode("td", { class: "border-t" }, [
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
                createVNode("td", { class: "border-t" }, [
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
                createVNode("td", { class: "border-t" }, [
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
                createVNode("td", { class: "border-t" }, [
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
                createVNode("td", { class: "w-px border-t" }, [
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
            $props.users.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
              createVNode("td", {
                class: "border-t px-6 py-4",
                colspan: "4"
              }, "No users found.")
            ])) : createCommentVNode("", true)
          ]),
          createVNode(_component_Pagination, {
            links: $props.users.links
          }, null, 8, ["links"])
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(`</div>`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const Index = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
export {
  Index as default
};
