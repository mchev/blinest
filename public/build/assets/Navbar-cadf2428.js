import { P, J, l as watch, s as Je, o as openBlock, c as createElementBlock, b as createBaseVNode, a as createVNode, u as unref, z as withDirectives, V as vModelText, f as normalizeClass, t as toDisplayString, g as createCommentVNode, d as createTextVNode, w as withCtx, n as ne, k as ref, q as onMounted, h as createBlock, F as Fragment, v as renderList } from "./app-910e457d.js";
import { _ as _sfc_main$9, L as LanguageSwitcher, a as _sfc_main$b } from "./LanguageSwitcher-da420d03.js";
import { _ as _sfc_main$a } from "./Icon-86c99edc.js";
import { _ as _sfc_main$7 } from "./TextInput-541fe8b5.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import { _ as _sfc_main$8 } from "./Dropdown-f0e2d937.js";
import { S as SocialIcon } from "./SocialIcon-5ed77127.js";
const _sfc_main$6 = {
  __name: "SearchRooms",
  props: {
    filters: Object
  },
  setup(__props) {
    var _a, _b;
    const form = P({
      search: (_b = (_a = J().props) == null ? void 0 : _a.filters) == null ? void 0 : _b.search
    });
    watch(
      form,
      throttle(() => {
        Je.get("/", pickBy_1(form), { remember: "forget", preserveState: true });
      }, 150),
      { deep: true }
    );
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        class: normalizeClass(_ctx.$attrs.class)
      }, [
        createBaseVNode("form", null, [
          createVNode(_sfc_main$7, {
            class: "text-sm",
            modelValue: unref(form).search,
            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).search = $event),
            spellcheck: "false",
            "prepend-icon": "search",
            placeholder: _ctx.__("Search a room") + "..."
          }, null, 8, ["modelValue", "placeholder"]),
          withDirectives(createBaseVNode("input", {
            class: "hidden",
            type: "text",
            name: "search_term_string",
            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).search = $event),
            required: ""
          }, null, 512), [
            [vModelText, unref(form).search]
          ])
        ])
      ], 2);
    };
  }
};
const _hoisted_1$5 = {
  key: 0,
  class: "flex-grow"
};
const _hoisted_2$5 = { class: "mb-1 border-b pb-1 uppercase text-neutral-500" };
const _hoisted_3$5 = { class: "my-2 text-sm font-medium" };
const _hoisted_4$5 = { class: "mt-1 flex items-center justify-end" };
const _sfc_main$5 = {
  __name: "NewTeamRequest",
  props: {
    notification: Object
  },
  setup(__props) {
    const props = __props;
    const form = P({
      notification_id: props.notification.id
    });
    const decline = (team) => {
      form.post(`/teams/requests/${props.notification.data.teamRequest.id}/decline`);
    };
    const accept = (team) => {
      form.post(`/teams/requests/${props.notification.data.teamRequest.id}/accept`);
    };
    return (_ctx, _cache) => {
      return __props.notification ? (openBlock(), createElementBlock("div", _hoisted_1$5, [
        createBaseVNode("h4", _hoisted_2$5, toDisplayString(_ctx.__("Team")), 1),
        createBaseVNode("div", _hoisted_3$5, toDisplayString(__props.notification.data.message), 1),
        createBaseVNode("div", _hoisted_4$5, [
          createBaseVNode("button", {
            type: "button",
            onClick: decline,
            class: "btn-danger btn-sm mr-2 opacity-80"
          }, toDisplayString(_ctx.__("Decline")), 1),
          createBaseVNode("button", {
            type: "button",
            onClick: accept,
            class: "btn-primary btn-sm opacity-80"
          }, toDisplayString(_ctx.__("Accept")), 1)
        ])
      ])) : createCommentVNode("", true);
    };
  }
};
const _hoisted_1$4 = {
  key: 0,
  class: "flex-grow"
};
const _hoisted_2$4 = { class: "mb-1 border-b pb-1 uppercase text-neutral-500" };
const _hoisted_3$4 = { class: "my-2 text-sm font-medium" };
const _hoisted_4$4 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_5$4 = { class: "mt-1 flex items-center justify-end" };
const _sfc_main$4 = {
  __name: "TeamRequestApproved",
  props: {
    notification: Object
  },
  setup(__props) {
    const props = __props;
    const read = (team) => {
      Je.post(`/users/notifications/${props.notification.id}/read`);
    };
    return (_ctx, _cache) => {
      return __props.notification ? (openBlock(), createElementBlock("div", _hoisted_1$4, [
        createBaseVNode("h4", _hoisted_2$4, toDisplayString(_ctx.__("Team")) + " " + toDisplayString(__props.notification.data.team.name), 1),
        createBaseVNode("div", _hoisted_3$4, [
          createTextVNode(toDisplayString(_ctx.__("Ta demande a été acceptée!")), 1),
          _hoisted_4$4
        ]),
        createBaseVNode("div", _hoisted_5$4, [
          createBaseVNode("button", {
            type: "button",
            onClick: read,
            class: "btn-primary btn-sm opacity-80"
          }, toDisplayString(_ctx.__("Mark as read")), 1)
        ])
      ])) : createCommentVNode("", true);
    };
  }
};
const _hoisted_1$3 = {
  key: 0,
  class: "flex-grow"
};
const _hoisted_2$3 = { class: "mb-1 flex items-center justify-between border-b pb-1" };
const _hoisted_3$3 = { class: "flex items-center font-bold uppercase text-red-500" };
const _hoisted_4$3 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "mr-2 h-4 w-4",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
  })
], -1);
const _hoisted_5$3 = { class: "text-xs text-neutral-400" };
const _hoisted_6$3 = { class: "my-2 text-sm font-medium" };
const _hoisted_7$2 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "mr-1 h-4 w-4"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M4.5 12.75l6 6 9-13.5"
  })
], -1);
const _sfc_main$3 = {
  __name: "NewRoomAlert",
  props: {
    notification: Object
  },
  emits: ["markedAsdone"],
  setup(__props, { emit }) {
    return (_ctx, _cache) => {
      return __props.notification ? (openBlock(), createElementBlock("div", _hoisted_1$3, [
        createBaseVNode("div", _hoisted_2$3, [
          createBaseVNode("h4", _hoisted_3$3, [
            _hoisted_4$3,
            createTextVNode(" " + toDisplayString(_ctx.__("Alerte modération")), 1)
          ]),
          createBaseVNode("span", _hoisted_5$3, toDisplayString(__props.notification.data.created_at), 1)
        ]),
        createBaseVNode("div", _hoisted_6$3, [
          createVNode(unref(ne), {
            href: _ctx.route("rooms.show", __props.notification.data.room.slug)
          }, {
            default: withCtx(() => [
              createTextVNode(toDisplayString(__props.notification.data.message), 1)
            ]),
            _: 1
          }, 8, ["href"])
        ]),
        createBaseVNode("button", {
          type: "button",
          class: "btn-primary btn-sm mt-2 ml-auto",
          onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("markedAsdone"))
        }, [
          _hoisted_7$2,
          createTextVNode(" " + toDisplayString(_ctx.__("Done")), 1)
        ])
      ])) : createCommentVNode("", true);
    };
  }
};
const _hoisted_1$2 = {
  key: 0,
  class: "flex-grow"
};
const _hoisted_2$2 = { class: "mb-1 flex items-center justify-between border-b pb-1" };
const _hoisted_3$2 = /* @__PURE__ */ createBaseVNode("h4", { class: "flex items-center font-bold uppercase" }, "Suggestion", -1);
const _hoisted_4$2 = { class: "text-xs text-neutral-400" };
const _hoisted_5$2 = { class: "my-2 whitespace-pre-line text-sm font-medium" };
const _hoisted_6$2 = { class: "flex text-xs" };
const _hoisted_7$1 = { class: "ml-1 font-bold" };
const _hoisted_8$1 = {
  key: 0,
  class: "text-xs"
};
const _hoisted_9$1 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "mr-1 h-4 w-4"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M4.5 12.75l6 6 9-13.5"
  })
], -1);
const _sfc_main$2 = {
  __name: "NewSuggestion",
  props: {
    notification: Object
  },
  emits: ["markedAsdone"],
  setup(__props, { emit }) {
    return (_ctx, _cache) => {
      return __props.notification ? (openBlock(), createElementBlock("div", _hoisted_1$2, [
        createBaseVNode("div", _hoisted_2$2, [
          _hoisted_3$2,
          createBaseVNode("span", _hoisted_4$2, toDisplayString(__props.notification.data.created_at), 1)
        ]),
        createBaseVNode("div", _hoisted_5$2, toDisplayString(__props.notification.data.message), 1),
        createBaseVNode("span", _hoisted_6$2, [
          createTextVNode("Envoyée par " + toDisplayString(__props.notification.data.user.name) + " sur ", 1),
          createBaseVNode("span", _hoisted_7$1, toDisplayString(__props.notification.data.room.name), 1)
        ]),
        __props.notification.data.room.playlists ? (openBlock(), createElementBlock("span", _hoisted_8$1, "Playlists : " + toDisplayString(__props.notification.data.room.playlists), 1)) : createCommentVNode("", true),
        createBaseVNode("button", {
          type: "button",
          class: "btn-primary btn-sm mt-2 ml-auto",
          onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("markedAsdone"))
        }, [
          _hoisted_9$1,
          createTextVNode(" " + toDisplayString(_ctx.__("Done")), 1)
        ])
      ])) : createCommentVNode("", true);
    };
  }
};
const _hoisted_1$1 = {
  key: 0,
  class: "absolute top-0 left-0 right-0 z-30 flex w-full justify-center"
};
const _hoisted_2$1 = { class: "my-2 flex max-w-2xl rounded bg-neutral-700 p-2" };
const _hoisted_3$1 = {
  class: "relative",
  title: "Notifications"
};
const _hoisted_4$1 = {
  key: 0,
  class: "absolute -top-2 -right-2 flex h-4 w-4 items-center justify-center truncate rounded-full bg-red-500 p-1 text-xs"
};
const _hoisted_5$1 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "h-4 w-4",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
  })
], -1);
const _hoisted_6$1 = { class: "p-2 font-light" };
const _hoisted_7 = {
  key: 0,
  class: "max-h-96 max-w-xl overflow-y-scroll pr-2"
};
const _hoisted_8 = { class: "justify-end" };
const _hoisted_9 = ["onClick"];
const _hoisted_10 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "h-4 w-4",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M6 18L18 6M6 6l12 12"
  })
], -1);
const _hoisted_11 = [
  _hoisted_10
];
const _hoisted_12 = { key: 1 };
const _sfc_main$1 = {
  __name: "Notifications",
  setup(__props) {
    const user = J().props.auth.user;
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
      Je.post(`/users/notifications/${notification.id}/done`, {
        preserveScroll: true
      });
    };
    const hideItemBeforeRefresh = (notification) => {
      notifications.value = notifications.value.filter((x) => x.id !== notification.id);
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", null, [
        popup.value ? (openBlock(), createElementBlock("div", _hoisted_1$1, [
          createBaseVNode("div", _hoisted_2$1, [
            popup.value.type === "App\\Notifications\\NewRoomAlert" ? (openBlock(), createBlock(_sfc_main$3, {
              key: 0,
              notification: popup.value,
              onMarkedAsdone: _cache[0] || (_cache[0] = ($event) => markAsDone(popup.value))
            }, null, 8, ["notification"])) : createCommentVNode("", true)
          ])
        ])) : createCommentVNode("", true),
        createVNode(_sfc_main$8, {
          placement: "bottom-end",
          autoClose: false
        }, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_3$1, [
              notifications.value.length ? (openBlock(), createElementBlock("div", _hoisted_4$1, toDisplayString(notifications.value.length), 1)) : createCommentVNode("", true),
              _hoisted_5$1
            ])
          ]),
          dropdown: withCtx(() => [
            createBaseVNode("div", _hoisted_6$1, [
              notifications.value.length ? (openBlock(), createElementBlock("ul", _hoisted_7, [
                (openBlock(true), createElementBlock(Fragment, null, renderList(notifications.value, (notification) => {
                  return openBlock(), createElementBlock("li", {
                    key: notification.id,
                    class: "my-2 flex rounded bg-neutral-700 p-2"
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
                    createBaseVNode("div", _hoisted_8, [
                      createBaseVNode("button", {
                        onClick: ($event) => markAsRead(notification),
                        class: "pl-4 text-neutral-400"
                      }, _hoisted_11, 8, _hoisted_9)
                    ])
                  ]);
                }), 128))
              ])) : (openBlock(), createElementBlock("span", _hoisted_12, toDisplayString(_ctx.__("No notification")), 1))
            ])
          ]),
          _: 1
        })
      ]);
    };
  }
};
const _hoisted_1 = { class: "md:flex md:flex-shrink-0" };
const _hoisted_2 = { class: "flex w-full items-center justify-between px-4 py-2 md:flex-shrink-0 md:px-12" };
const _hoisted_3 = { class: "flex items-center justify-end gap-4" };
const _hoisted_4 = ["title"];
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  fill: "currentColor",
  class: "w-5 h-5"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "fill-rule": "evenodd",
    d: "M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z",
    "clip-rule": "evenodd"
  })
], -1);
const _hoisted_6 = {
  key: 3,
  class: "flex gap-4 uppercase"
};
const _sfc_main = {
  __name: "Navbar",
  setup(__props) {
    var _a;
    const user = (_a = J().props.auth) == null ? void 0 : _a.user;
    ref(false);
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        createBaseVNode("div", _hoisted_2, [
          createVNode(unref(ne), {
            class: "",
            href: _ctx.route("home"),
            title: "Blinest"
          }, {
            default: withCtx(() => [
              createVNode(_sfc_main$9, { class: "w-24 fill-inherit lg:w-32" })
            ]),
            _: 1
          }, 8, ["href"]),
          createVNode(_sfc_main$6, { class: "mt-1 hidden md:block" }),
          createBaseVNode("div", _hoisted_3, [
            unref(user) ? (openBlock(), createBlock(unref(ne), {
              key: 0,
              href: _ctx.route("rankings.index"),
              title: _ctx.__("Rankings")
            }, {
              default: withCtx(() => [
                createVNode(_sfc_main$a, {
                  name: "podium",
                  class: "h-5 w-5"
                })
              ]),
              _: 1
            }, 8, ["href", "title"])) : createCommentVNode("", true),
            createBaseVNode("a", {
              href: "https://discord.com/invite/uKyVgcxcFa",
              target: "_blank",
              title: _ctx.__("Join the Blinest community on Discord"),
              class: "umami--click--discord-button"
            }, [
              createVNode(SocialIcon, {
                name: "discord",
                class: "h-5 w-5"
              })
            ], 8, _hoisted_4),
            unref(user) ? (openBlock(), createBlock(_sfc_main$1, { key: 1 })) : createCommentVNode("", true),
            createVNode(LanguageSwitcher),
            createVNode(unref(ne), {
              href: _ctx.route("faq"),
              title: "FAQ"
            }, {
              default: withCtx(() => [
                _hoisted_5
              ]),
              _: 1
            }, 8, ["href"]),
            unref(user) ? (openBlock(), createBlock(_sfc_main$b, { key: 2 })) : createCommentVNode("", true),
            !unref(user) ? (openBlock(), createElementBlock("div", _hoisted_6, [
              createVNode(unref(ne), {
                href: _ctx.route("login"),
                title: _ctx.__("Login")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Login")), 1)
                ]),
                _: 1
              }, 8, ["href", "title"]),
              createVNode(unref(ne), {
                href: _ctx.route("register"),
                class: "hidden lg:block",
                title: _ctx.__("Register")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(_ctx.__("Register")), 1)
                ]),
                _: 1
              }, 8, ["href", "title"])
            ])) : createCommentVNode("", true)
          ])
        ])
      ]);
    };
  }
};
export {
  _sfc_main as _
};
