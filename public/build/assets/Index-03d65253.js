import { J, P, l as watch, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, h as createBlock, d as createTextVNode, n as ne, z as withDirectives, N as vShow, v as renderList, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Navbar-cadf2428.js";
import "./SocialIcon-5ed77127.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
const _hoisted_1 = { class: "mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6" };
const _hoisted_2 = { class: "mx-auto mb-8 max-w-screen-sm lg:mb-16" };
const _hoisted_3 = { class: "mb-4 text-4xl font-extrabold tracking-tight" };
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("p", { class: "font-light sm:text-xl" }, "Rejoins une team et partages tes scores avec les autres membres pour exploser les compteurs!", -1);
const _hoisted_5 = { class: "my-6 flex justify-center" };
const _hoisted_6 = { class: "mt-16 flex justify-center" };
const _hoisted_7 = {
  key: 0,
  class: "flex items-center gap-8"
};
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "h-20 w-20",
  viewBox: "0 0 20 20",
  fill: "currentColor"
}, [
  /* @__PURE__ */ createBaseVNode("title", null, "Previous team list"),
  /* @__PURE__ */ createBaseVNode("path", {
    "fill-rule": "evenodd",
    d: "M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z",
    "clip-rule": "evenodd"
  })
], -1);
const _hoisted_9 = { class: "grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4 lg:gap-16" };
const _hoisted_10 = { class: "relative" };
const _hoisted_11 = { class: "absolute top-1 right-1 rounded-full bg-teal-500 py-1 px-2 font-bold text-white" };
const _hoisted_12 = ["src", "alt"];
const _hoisted_13 = { class: "mb-1 truncate text-2xl font-bold tracking-tight" };
const _hoisted_14 = ["onClick"];
const _hoisted_15 = ["onClick"];
const _hoisted_16 = ["onClick"];
const _hoisted_17 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "h-20 w-20",
  viewBox: "0 0 20 20",
  fill: "currentColor"
}, [
  /* @__PURE__ */ createBaseVNode("title", null, "Next team list"),
  /* @__PURE__ */ createBaseVNode("path", {
    "fill-rule": "evenodd",
    d: "M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z",
    "clip-rule": "evenodd"
  })
], -1);
const _hoisted_18 = { key: 1 };
const _sfc_main = {
  __name: "Index",
  props: {
    teams: Object,
    filters: Object
  },
  setup(__props) {
    const props = __props;
    const user = J().props.auth.user;
    const form = P({
      search: props.filters.search
    });
    watch(
      form,
      throttle(() => {
        Je.get("/teams", pickBy_1(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const sendRequest = (team) => {
      Je.post(`/teams/${team.id}/request`);
    };
    const cancelRequest = (team) => {
      Je.post(`/teams/${team.id}/request/cancel`);
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Teams")
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("section", null, [
              createBaseVNode("div", _hoisted_1, [
                createBaseVNode("div", _hoisted_2, [
                  createBaseVNode("h2", _hoisted_3, toDisplayString(_ctx.__("Teams")), 1),
                  _hoisted_4,
                  createBaseVNode("div", _hoisted_5, [
                    !unref(user).team ? (openBlock(), createBlock(unref(ne), {
                      key: 0,
                      href: "/teams/create",
                      class: "btn-primary btn-lg"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Create a team")), 1)
                      ]),
                      _: 1
                    })) : (openBlock(), createBlock(unref(ne), {
                      key: 1,
                      href: _ctx.route("teams.show", unref(user).team.id),
                      class: "btn-primary btn-lg"
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Show my team")), 1)
                      ]),
                      _: 1
                    }, 8, ["href"]))
                  ]),
                  createBaseVNode("div", _hoisted_6, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).search,
                      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).search = $event),
                      placeholder: _ctx.__("Search a team")
                    }, null, 8, ["modelValue", "placeholder"])
                  ])
                ]),
                __props.teams.data.length ? (openBlock(), createElementBlock("div", _hoisted_7, [
                  withDirectives(createVNode(unref(ne), {
                    href: __props.teams.prev_page_url
                  }, {
                    default: withCtx(() => [
                      _hoisted_8
                    ]),
                    _: 1
                  }, 8, ["href"]), [
                    [vShow, __props.teams.prev_page_url]
                  ]),
                  createBaseVNode("div", _hoisted_9, [
                    (openBlock(true), createElementBlock(Fragment, null, renderList(__props.teams.data, (team) => {
                      return openBlock(), createElementBlock("div", {
                        class: "text-center",
                        key: team.id
                      }, [
                        createBaseVNode("div", _hoisted_10, [
                          createBaseVNode("span", _hoisted_11, [
                            createBaseVNode("sup", null, toDisplayString(team.members_count), 1),
                            createTextVNode("⁄"),
                            createBaseVNode("sub", null, toDisplayString(team.seats), 1)
                          ]),
                          createBaseVNode("img", {
                            class: "mx-auto mb-4 h-36 w-36 rounded-full",
                            src: team.photo,
                            alt: team.name
                          }, null, 8, _hoisted_12)
                        ]),
                        createBaseVNode("h3", _hoisted_13, [
                          createVNode(unref(ne), {
                            href: _ctx.route("teams.show", team.id)
                          }, {
                            default: withCtx(() => [
                              createTextVNode(toDisplayString(team.name), 1)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("p", null, "@" + toDisplayString(team.owner.name), 1),
                        unref(user).declined_requests.includes(team.id) ? (openBlock(), createElementBlock("button", {
                          key: 0,
                          type: "button",
                          onClick: ($event) => cancelRequest(team),
                          class: "btn-danger mx-auto my-6"
                        }, toDisplayString(_ctx.__("Declined request")), 9, _hoisted_14)) : unref(user).pending_requests.includes(team.id) ? (openBlock(), createElementBlock("button", {
                          key: 1,
                          type: "button",
                          onClick: ($event) => cancelRequest(team),
                          class: "btn-danger mx-auto my-6"
                        }, toDisplayString(_ctx.__("Cancel join request")), 9, _hoisted_15)) : (openBlock(), createElementBlock("button", {
                          key: 2,
                          type: "button",
                          onClick: ($event) => sendRequest(team),
                          class: "btn-secondary mx-auto my-6"
                        }, toDisplayString(_ctx.__("Send a join request")), 9, _hoisted_16))
                      ]);
                    }), 128))
                  ]),
                  withDirectives(createVNode(unref(ne), {
                    href: __props.teams.next_page_url
                  }, {
                    default: withCtx(() => [
                      _hoisted_17
                    ]),
                    _: 1
                  }, 8, ["href"]), [
                    [vShow, __props.teams.next_page_url]
                  ])
                ])) : (openBlock(), createElementBlock("div", _hoisted_18, toDisplayString(_ctx.__("No result")), 1))
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
