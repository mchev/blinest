import { watch, mergeProps, unref, useSSRContext, withCtx, createTextVNode, toDisplayString, ref, onMounted, createVNode, openBlock, createBlock, createCommentVNode, Fragment, renderList } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { useForm, usePage, router, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$9, L as LanguageSwitcher, a as _sfc_main$b } from "./LanguageSwitcher-18bdae21.mjs";
import { _ as _sfc_main$a } from "./Icon-4a47e6e0.mjs";
import { _ as _sfc_main$8 } from "./Dropdown-6785e0d2.mjs";
import { _ as _sfc_main$7 } from "./TextInput-cddc091b.mjs";
import throttle from "lodash/throttle.js";
import pickBy from "lodash/pickBy.js";
import { S as SocialIcon } from "./SocialIcon-bb2fc3a0.mjs";
const _sfc_main$6 = {
  __name: "SearchRooms",
  __ssrInlineRender: true,
  props: {
    filters: Object
  },
  setup(__props) {
    var _a, _b;
    const form = useForm({
      search: (_b = (_a = usePage().props) == null ? void 0 : _a.filters) == null ? void 0 : _b.search
    });
    watch(
      form,
      throttle(() => {
        router.get("/", pickBy(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: _ctx.$attrs.class
      }, _attrs))}><form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction"><meta itemprop="target" content="https://blinest.com/?search={search_term_string}">`);
      _push(ssrRenderComponent(_sfc_main$7, {
        class: "text-sm",
        modelValue: unref(form).search,
        "onUpdate:modelValue": ($event) => unref(form).search = $event,
        spellcheck: "false",
        "prepend-icon": "search",
        placeholder: _ctx.__("Search") + "..."
      }, null, _parent));
      _push(`<input class="hidden" itemprop="query-input" type="text" name="search_term_string"${ssrRenderAttr("value", unref(form).search)} required></form></div>`);
    };
  }
};
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/SearchRooms.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const _sfc_main$5 = {
  __name: "NewTeamRequest",
  __ssrInlineRender: true,
  props: {
    notification: Object
  },
  setup(__props) {
    const props = __props;
    useForm({
      notification_id: props.notification.id
    });
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.notification) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex-grow" }, _attrs))}><h4 class="mb-1 border-b pb-1 uppercase text-neutral-500">${ssrInterpolate(_ctx.__("Team"))}</h4><div class="my-2 text-sm font-medium">${ssrInterpolate(__props.notification.data.message)}</div><div class="mt-1 flex items-center justify-end"><button type="button" class="btn-danger btn-sm mr-2 opacity-80">${ssrInterpolate(_ctx.__("Decline"))}</button><button type="button" class="btn-primary btn-sm opacity-80">${ssrInterpolate(_ctx.__("Accept"))}</button></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Notifications/NewTeamRequest.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const _sfc_main$4 = {
  __name: "TeamRequestApproved",
  __ssrInlineRender: true,
  props: {
    notification: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.notification) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex-grow" }, _attrs))}><h4 class="mb-1 border-b pb-1 uppercase text-neutral-500">${ssrInterpolate(_ctx.__("Team"))} ${ssrInterpolate(__props.notification.data.team.name)}</h4><div class="my-2 text-sm font-medium">${ssrInterpolate(_ctx.__("Ta demande a été acceptée!"))}<br></div><div class="mt-1 flex items-center justify-end"><button type="button" class="btn-primary btn-sm opacity-80">${ssrInterpolate(_ctx.__("Mark as read"))}</button></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Notifications/TeamRequestApproved.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const _sfc_main$3 = {
  __name: "NewRoomAlert",
  __ssrInlineRender: true,
  props: {
    notification: Object
  },
  emits: ["markedAsdone"],
  setup(__props, { emit }) {
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.notification) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex-grow" }, _attrs))}><div class="flex justify-between items-center mb-1 border-b pb-1"><h4 class="uppercase text-red-500 flex items-center font-bold"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg> ${ssrInterpolate(_ctx.__("Alerte modération"))}</h4><span class="text-xs text-neutral-400">${ssrInterpolate(__props.notification.data.created_at)}</span></div><div class="my-2 text-sm font-medium">`);
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("rooms.show", __props.notification.data.room.slug)
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(__props.notification.data.message)}`);
            } else {
              return [
                createTextVNode(toDisplayString(__props.notification.data.message), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div><button type="button" class="btn-primary btn-sm mt-2 ml-auto"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg> ${ssrInterpolate(_ctx.__("Done"))}</button></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Notifications/NewRoomAlert.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = {
  __name: "NewSuggestion",
  __ssrInlineRender: true,
  props: {
    notification: Object
  },
  emits: ["markedAsdone"],
  setup(__props, { emit }) {
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.notification) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex-grow" }, _attrs))}><div class="mb-1 flex items-center justify-between border-b pb-1"><h4 class="flex items-center font-bold uppercase">Suggestion</h4><span class="text-xs text-neutral-400">${ssrInterpolate(__props.notification.data.created_at)}</span></div><div class="my-2 whitespace-pre-line text-sm font-medium">${ssrInterpolate(__props.notification.data.message)}</div><span class="flex text-xs">Envoyée par ${ssrInterpolate(__props.notification.data.user.name)} sur <span class="font-bold ml-1">${ssrInterpolate(__props.notification.data.room.name)}</span></span>`);
        if (__props.notification.data.room.playlists) {
          _push(`<span class="text-xs">Playlists : ${ssrInterpolate(__props.notification.data.room.playlists)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<button type="button" class="btn-primary btn-sm mt-2 ml-auto"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg> ${ssrInterpolate(_ctx.__("Done"))}</button></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Notifications/NewSuggestion.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = {
  __name: "Notifications",
  __ssrInlineRender: true,
  setup(__props) {
    const user = usePage().props.auth.user;
    const notifications = ref(user.notifications);
    const popup = ref(null);
    onMounted(() => {
      Echo.private("App.Models.User." + user.id).notification((notification) => {
        popup.value = notification;
        setTimeout(() => {
          popup.value = null;
        }, 3e3);
        notifications.value.push(...[notification]);
      });
    });
    const markAsRead = (notification) => {
      hideItemBeforeRefresh(notification);
      axios.post(`/users/notifications/${notification.id}/read`).then(() => {
        hideItemBeforeRefresh(notification);
      });
    };
    const markAsDone = (notification) => {
      hideItemBeforeRefresh(notification);
      router.post(`/users/notifications/${notification.id}/done`, {
        preserveScroll: true
      });
    };
    const hideItemBeforeRefresh = (notification) => {
      notifications.value = notifications.value.filter((x) => x.id !== notification.id);
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(_attrs)}>`);
      if (popup.value) {
        _push(`<div class="absolute top-0 left-0 right-0 flex justify-center z-30 w-full"><div class="my-2 rounded bg-neutral-700 p-2 flex max-w-2xl">`);
        if (popup.value.type === "App\\Notifications\\NewRoomAlert") {
          _push(ssrRenderComponent(_sfc_main$3, {
            notification: popup.value,
            onMarkedAsdone: ($event) => markAsDone(popup.value)
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(_sfc_main$8, {
        placement: "bottom-end",
        autoClose: false
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="relative"${_scopeId}>`);
            if (notifications.value.length) {
              _push2(`<div class="absolute -top-2 -right-2 flex h-4 w-4 items-center justify-center truncate rounded-full bg-red-500 p-1 text-xs"${_scopeId}>${ssrInterpolate(notifications.value.length)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"${_scopeId}></path></svg></div>`);
          } else {
            return [
              createVNode("div", { class: "relative" }, [
                notifications.value.length ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "absolute -top-2 -right-2 flex h-4 w-4 items-center justify-center truncate rounded-full bg-red-500 p-1 text-xs"
                }, toDisplayString(notifications.value.length), 1)) : createCommentVNode("", true),
                (openBlock(), createBlock("svg", {
                  xmlns: "http://www.w3.org/2000/svg",
                  class: "h-4 w-4",
                  fill: "none",
                  viewBox: "0 0 24 24",
                  stroke: "currentColor",
                  "stroke-width": "2"
                }, [
                  createVNode("path", {
                    "stroke-linecap": "round",
                    "stroke-linejoin": "round",
                    d: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                  })
                ]))
              ])
            ];
          }
        }),
        dropdown: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="p-2 font-light"${_scopeId}>`);
            if (notifications.value.length) {
              _push2(`<ul class="max-w-xl max-h-96 overflow-y-scroll pr-2"${_scopeId}><!--[-->`);
              ssrRenderList(notifications.value, (notification) => {
                _push2(`<li class="my-2 rounded bg-neutral-700 p-2 flex"${_scopeId}>`);
                if (notification.type === "App\\Notifications\\NewTeamRequest") {
                  _push2(ssrRenderComponent(_sfc_main$5, { notification }, null, _parent2, _scopeId));
                } else {
                  _push2(`<!---->`);
                }
                if (notification.type === "App\\Notifications\\TeamRequestApproved") {
                  _push2(ssrRenderComponent(_sfc_main$4, { notification }, null, _parent2, _scopeId));
                } else {
                  _push2(`<!---->`);
                }
                if (notification.type === "App\\Notifications\\NewRoomAlert") {
                  _push2(ssrRenderComponent(_sfc_main$3, {
                    notification,
                    onMarkedAsdone: ($event) => markAsDone(notification)
                  }, null, _parent2, _scopeId));
                } else {
                  _push2(`<!---->`);
                }
                if (notification.type === "App\\Notifications\\NewSuggestion") {
                  _push2(ssrRenderComponent(_sfc_main$2, {
                    notification,
                    onMarkedAsdone: ($event) => markAsDone(notification)
                  }, null, _parent2, _scopeId));
                } else {
                  _push2(`<!---->`);
                }
                _push2(`<div class="justify-end"${_scopeId}><button class="pl-4 text-neutral-400"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"${_scopeId}></path></svg></button></div></li>`);
              });
              _push2(`<!--]--></ul>`);
            } else {
              _push2(`<span${_scopeId}>${ssrInterpolate(_ctx.__("No notification"))}</span>`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "p-2 font-light" }, [
                notifications.value.length ? (openBlock(), createBlock("ul", {
                  key: 0,
                  class: "max-w-xl max-h-96 overflow-y-scroll pr-2"
                }, [
                  (openBlock(true), createBlock(Fragment, null, renderList(notifications.value, (notification) => {
                    return openBlock(), createBlock("li", {
                      key: notification.id,
                      class: "my-2 rounded bg-neutral-700 p-2 flex"
                    }, [
                      notification.type === "App\\Notifications\\NewTeamRequest" ? (openBlock(), createBlock(_sfc_main$5, {
                        key: 0,
                        notification
                      }, null, 8, ["notification"])) : createCommentVNode("", true),
                      notification.type === "App\\Notifications\\TeamRequestApproved" ? (openBlock(), createBlock(_sfc_main$4, {
                        key: 1,
                        notification
                      }, null, 8, ["notification"])) : createCommentVNode("", true),
                      notification.type === "App\\Notifications\\NewRoomAlert" ? (openBlock(), createBlock(_sfc_main$3, {
                        key: 2,
                        notification,
                        onMarkedAsdone: ($event) => markAsDone(notification)
                      }, null, 8, ["notification", "onMarkedAsdone"])) : createCommentVNode("", true),
                      notification.type === "App\\Notifications\\NewSuggestion" ? (openBlock(), createBlock(_sfc_main$2, {
                        key: 3,
                        notification,
                        onMarkedAsdone: ($event) => markAsDone(notification)
                      }, null, 8, ["notification", "onMarkedAsdone"])) : createCommentVNode("", true),
                      createVNode("div", { class: "justify-end" }, [
                        createVNode("button", {
                          onClick: ($event) => markAsRead(notification),
                          class: "pl-4 text-neutral-400"
                        }, [
                          (openBlock(), createBlock("svg", {
                            xmlns: "http://www.w3.org/2000/svg",
                            class: "h-4 w-4",
                            fill: "none",
                            viewBox: "0 0 24 24",
                            stroke: "currentColor",
                            "stroke-width": "2"
                          }, [
                            createVNode("path", {
                              "stroke-linecap": "round",
                              "stroke-linejoin": "round",
                              d: "M6 18L18 6M6 6l12 12"
                            })
                          ]))
                        ], 8, ["onClick"])
                      ])
                    ]);
                  }), 128))
                ])) : (openBlock(), createBlock("span", { key: 1 }, toDisplayString(_ctx.__("No notification")), 1))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Notifications/Notifications.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Navbar",
  __ssrInlineRender: true,
  setup(__props) {
    var _a;
    const user = (_a = usePage().props.auth) == null ? void 0 : _a.user;
    const showingSearchbar = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "md:flex md:flex-shrink-0" }, _attrs))}><div class="flex w-full items-center justify-between px-4 py-2 md:flex-shrink-0 md:px-12">`);
      _push(ssrRenderComponent(unref(Link), {
        class: "",
        href: _ctx.route("home"),
        title: "Blinest"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$9, { class: "fill-inherit w-24 lg:w-32" }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$9, { class: "fill-inherit w-24 lg:w-32" })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$6, { class: "mt-1 hidden lg:block" }, null, _parent));
      _push(`<div class="flex items-center justify-end"><button type="button" class="lg:hidden">`);
      _push(ssrRenderComponent(_sfc_main$a, {
        name: "search",
        class: "h-5 w-5 mr-4"
      }, null, _parent));
      _push(`</button>`);
      if (unref(user)) {
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("rankings.index"),
          title: _ctx.__("Rankings")
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(_sfc_main$a, {
                name: "podium",
                class: "h-5 w-5 mr-4"
              }, null, _parent2, _scopeId));
            } else {
              return [
                createVNode(_sfc_main$a, {
                  name: "podium",
                  class: "h-5 w-5 mr-4"
                })
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<a href="https://discord.com/invite/uKyVgcxcFa" target="_blank"${ssrRenderAttr("title", _ctx.__("Join the Blinest community on Discord"))} class="mr-4 umami--click--discord-button">`);
      _push(ssrRenderComponent(SocialIcon, {
        name: "discord",
        class: "h-5 w-5"
      }, null, _parent));
      _push(`</a>`);
      if (unref(user)) {
        _push(ssrRenderComponent(_sfc_main$1, { class: "mr-4" }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(LanguageSwitcher, { class: "mr-4" }, null, _parent));
      if (unref(user)) {
        _push(ssrRenderComponent(_sfc_main$b, null, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (!unref(user)) {
        _push(`<div class="flex gap-4 uppercase">`);
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("login"),
          title: _ctx.__("Login")
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(_ctx.__("Login"))}`);
            } else {
              return [
                createTextVNode(toDisplayString(_ctx.__("Login")), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("register"),
          class: "hidden lg:block",
          title: _ctx.__("Register")
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(_ctx.__("Register"))}`);
            } else {
              return [
                createTextVNode(toDisplayString(_ctx.__("Register")), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div>`);
      _push(ssrRenderComponent(_sfc_main$6, {
        style: showingSearchbar.value ? null : { display: "none" },
        class: "m-2"
      }, null, _parent));
      _push(`</div></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Navbar.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
