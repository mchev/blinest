import { watch, unref, withCtx, createTextVNode, toDisplayString, openBlock, createBlock, createVNode, withDirectives, vShow, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr } from "vue/server-renderer";
import { usePage, useForm, router, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import pickBy from "lodash/pickBy.js";
import throttle from "lodash/throttle.js";
import "uuid";
import "./Icon-4a47e6e0.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "./SocialIcon-bb2fc3a0.mjs";
const _sfc_main = {
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    teams: Object,
    filters: Object
  },
  setup(__props) {
    const props = __props;
    const user = usePage().props.auth.user;
    const form = useForm({
      search: props.filters.search
    });
    watch(
      form,
      throttle(() => {
        router.get("/teams", pickBy(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    const sendRequest = (team) => {
      router.post(`/teams/${team.id}/request`);
    };
    const cancelRequest = (team) => {
      router.post(`/teams/${team.id}/request/cancel`);
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: _ctx.__("Teams")
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<section${_scopeId}><div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6"${_scopeId}><div class="mx-auto mb-8 max-w-screen-sm lg:mb-16"${_scopeId}><h2 class="mb-4 text-4xl font-extrabold tracking-tight"${_scopeId}>${ssrInterpolate(_ctx.__("Teams"))}</h2><p class="font-light sm:text-xl"${_scopeId}>Rejoins une team et partages tes scores avec les autres membres pour exploser les compteurs!</p><div class="my-6 flex justify-center"${_scopeId}>`);
            if (!unref(user).team) {
              _push2(ssrRenderComponent(unref(Link), {
                href: "/teams/create",
                class: "btn-primary btn-lg"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Create a team"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Create a team")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("teams.show", unref(user).team.id),
                class: "btn-primary btn-lg"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Show my team"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Show my team")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            }
            _push2(`</div><div class="mt-16 flex justify-center"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).search,
              "onUpdate:modelValue": ($event) => unref(form).search = $event,
              placeholder: _ctx.__("Search a team")
            }, null, _parent2, _scopeId));
            _push2(`</div></div>`);
            if (__props.teams.data.length) {
              _push2(`<div class="flex items-center gap-8"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                style: __props.teams.prev_page_url ? null : { display: "none" },
                href: __props.teams.prev_page_url
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" viewBox="0 0 20 20" fill="currentColor"${_scopeId2}><title${_scopeId2}>Previous team list</title><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"${_scopeId2}></path></svg>`);
                  } else {
                    return [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "h-20 w-20",
                        viewBox: "0 0 20 20",
                        fill: "currentColor"
                      }, [
                        createVNode("title", null, "Previous team list"),
                        createVNode("path", {
                          "fill-rule": "evenodd",
                          d: "M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z",
                          "clip-rule": "evenodd"
                        })
                      ]))
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`<div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4 lg:gap-16"${_scopeId}><!--[-->`);
              ssrRenderList(__props.teams.data, (team) => {
                _push2(`<div class="text-center"${_scopeId}><div class="relative"${_scopeId}><span class="absolute top-1 right-1 rounded-full bg-teal-500 py-1 px-2 font-bold text-white"${_scopeId}><sup${_scopeId}>${ssrInterpolate(team.members_count)}</sup>⁄<sub${_scopeId}>${ssrInterpolate(team.seats)}</sub></span><img class="mx-auto mb-4 h-36 w-36 rounded-full"${ssrRenderAttr("src", team.photo)}${ssrRenderAttr("alt", team.name)}${_scopeId}></div><h3 class="mb-1 truncate text-2xl font-bold tracking-tight"${_scopeId}>`);
                _push2(ssrRenderComponent(unref(Link), {
                  href: _ctx.route("teams.show", team.id)
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`${ssrInterpolate(team.name)}`);
                    } else {
                      return [
                        createTextVNode(toDisplayString(team.name), 1)
                      ];
                    }
                  }),
                  _: 2
                }, _parent2, _scopeId));
                _push2(`</h3><p${_scopeId}>@${ssrInterpolate(team.owner.name)}</p>`);
                if (unref(user).declined_requests.includes(team.id)) {
                  _push2(`<button type="button" class="btn-danger mx-auto my-6"${_scopeId}>${ssrInterpolate(_ctx.__("Declined request"))}</button>`);
                } else if (unref(user).pending_requests.includes(team.id)) {
                  _push2(`<button type="button" class="btn-danger mx-auto my-6"${_scopeId}>${ssrInterpolate(_ctx.__("Cancel join request"))}</button>`);
                } else {
                  _push2(`<button type="button" class="btn-secondary mx-auto my-6"${_scopeId}>${ssrInterpolate(_ctx.__("Send a join request"))}</button>`);
                }
                _push2(`</div>`);
              });
              _push2(`<!--]--></div>`);
              _push2(ssrRenderComponent(unref(Link), {
                style: __props.teams.next_page_url ? null : { display: "none" },
                href: __props.teams.next_page_url
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" viewBox="0 0 20 20" fill="currentColor"${_scopeId2}><title${_scopeId2}>Next team list</title><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"${_scopeId2}></path></svg>`);
                  } else {
                    return [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "h-20 w-20",
                        viewBox: "0 0 20 20",
                        fill: "currentColor"
                      }, [
                        createVNode("title", null, "Next team list"),
                        createVNode("path", {
                          "fill-rule": "evenodd",
                          d: "M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z",
                          "clip-rule": "evenodd"
                        })
                      ]))
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              _push2(`<div${_scopeId}>${ssrInterpolate(_ctx.__("No result"))}</div>`);
            }
            _push2(`</div></section>`);
          } else {
            return [
              createVNode("section", null, [
                createVNode("div", { class: "mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6" }, [
                  createVNode("div", { class: "mx-auto mb-8 max-w-screen-sm lg:mb-16" }, [
                    createVNode("h2", { class: "mb-4 text-4xl font-extrabold tracking-tight" }, toDisplayString(_ctx.__("Teams")), 1),
                    createVNode("p", { class: "font-light sm:text-xl" }, "Rejoins une team et partages tes scores avec les autres membres pour exploser les compteurs!"),
                    createVNode("div", { class: "my-6 flex justify-center" }, [
                      !unref(user).team ? (openBlock(), createBlock(unref(Link), {
                        key: 0,
                        href: "/teams/create",
                        class: "btn-primary btn-lg"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Create a team")), 1)
                        ]),
                        _: 1
                      })) : (openBlock(), createBlock(unref(Link), {
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
                    createVNode("div", { class: "mt-16 flex justify-center" }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).search,
                        "onUpdate:modelValue": ($event) => unref(form).search = $event,
                        placeholder: _ctx.__("Search a team")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "placeholder"])
                    ])
                  ]),
                  __props.teams.data.length ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "flex items-center gap-8"
                  }, [
                    withDirectives(createVNode(unref(Link), {
                      href: __props.teams.prev_page_url
                    }, {
                      default: withCtx(() => [
                        (openBlock(), createBlock("svg", {
                          xmlns: "http://www.w3.org/2000/svg",
                          class: "h-20 w-20",
                          viewBox: "0 0 20 20",
                          fill: "currentColor"
                        }, [
                          createVNode("title", null, "Previous team list"),
                          createVNode("path", {
                            "fill-rule": "evenodd",
                            d: "M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z",
                            "clip-rule": "evenodd"
                          })
                        ]))
                      ]),
                      _: 1
                    }, 8, ["href"]), [
                      [vShow, __props.teams.prev_page_url]
                    ]),
                    createVNode("div", { class: "grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4 lg:gap-16" }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(__props.teams.data, (team) => {
                        return openBlock(), createBlock("div", {
                          class: "text-center",
                          key: team.id
                        }, [
                          createVNode("div", { class: "relative" }, [
                            createVNode("span", { class: "absolute top-1 right-1 rounded-full bg-teal-500 py-1 px-2 font-bold text-white" }, [
                              createVNode("sup", null, toDisplayString(team.members_count), 1),
                              createTextVNode("⁄"),
                              createVNode("sub", null, toDisplayString(team.seats), 1)
                            ]),
                            createVNode("img", {
                              class: "mx-auto mb-4 h-36 w-36 rounded-full",
                              src: team.photo,
                              alt: team.name
                            }, null, 8, ["src", "alt"])
                          ]),
                          createVNode("h3", { class: "mb-1 truncate text-2xl font-bold tracking-tight" }, [
                            createVNode(unref(Link), {
                              href: _ctx.route("teams.show", team.id)
                            }, {
                              default: withCtx(() => [
                                createTextVNode(toDisplayString(team.name), 1)
                              ]),
                              _: 2
                            }, 1032, ["href"])
                          ]),
                          createVNode("p", null, "@" + toDisplayString(team.owner.name), 1),
                          unref(user).declined_requests.includes(team.id) ? (openBlock(), createBlock("button", {
                            key: 0,
                            type: "button",
                            onClick: ($event) => cancelRequest(team),
                            class: "btn-danger mx-auto my-6"
                          }, toDisplayString(_ctx.__("Declined request")), 9, ["onClick"])) : unref(user).pending_requests.includes(team.id) ? (openBlock(), createBlock("button", {
                            key: 1,
                            type: "button",
                            onClick: ($event) => cancelRequest(team),
                            class: "btn-danger mx-auto my-6"
                          }, toDisplayString(_ctx.__("Cancel join request")), 9, ["onClick"])) : (openBlock(), createBlock("button", {
                            key: 2,
                            type: "button",
                            onClick: ($event) => sendRequest(team),
                            class: "btn-secondary mx-auto my-6"
                          }, toDisplayString(_ctx.__("Send a join request")), 9, ["onClick"]))
                        ]);
                      }), 128))
                    ]),
                    withDirectives(createVNode(unref(Link), {
                      href: __props.teams.next_page_url
                    }, {
                      default: withCtx(() => [
                        (openBlock(), createBlock("svg", {
                          xmlns: "http://www.w3.org/2000/svg",
                          class: "h-20 w-20",
                          viewBox: "0 0 20 20",
                          fill: "currentColor"
                        }, [
                          createVNode("title", null, "Next team list"),
                          createVNode("path", {
                            "fill-rule": "evenodd",
                            d: "M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z",
                            "clip-rule": "evenodd"
                          })
                        ]))
                      ]),
                      _: 1
                    }, 8, ["href"]), [
                      [vShow, __props.teams.next_page_url]
                    ])
                  ])) : (openBlock(), createBlock("div", { key: 1 }, toDisplayString(_ctx.__("No result")), 1))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Teams/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
