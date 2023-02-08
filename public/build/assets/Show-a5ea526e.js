import { Q as computed, o as openBlock, c as createElementBlock, a as createVNode, w as withCtx, b as createBaseVNode, t as toDisplayString, u as unref, X, r as renderSlot, g as createCommentVNode, R as Transition, F as Fragment, J, k as ref, h as createBlock, d as createTextVNode, z as withDirectives, N as vShow, f as normalizeClass, P, q as onMounted, L as onUnmounted, v as renderList, e as withModifiers } from "./app-910e457d.js";
import { F as FlashMessages } from "./LanguageSwitcher-da420d03.js";
import "./isSymbol-b518dd01.js";
import "./_defineProperty-55942902.js";
import { _ as _sfc_main$6 } from "./Navbar-cadf2428.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { S as Spinner } from "./Spinner-bfddfb59.js";
import { _ as _sfc_main$8 } from "./Modal-f990bd3c.js";
import { _ as _sfc_main$7 } from "./BanForm-d81d44f1.js";
import { T as Tip } from "./Tip-da87eaf5.js";
import { _ as _sfc_main$9 } from "./TextareaInput-b614dddb.js";
import { _ as _sfc_main$b } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$a } from "./Share-8d298486.js";
import _sfc_main$c from "./LoginForm-e24c76f9.js";
import _sfc_main$h from "./Controls-f68d6ecf.js";
import _sfc_main$d from "./Player-6a1e8766.js";
import _sfc_main$e from "./UserInput-a04e7e45.js";
import _sfc_main$f from "./Answers-22bd0c78.js";
import _sfc_main$g from "./Ranking-a67049d0.js";
import _sfc_main$i from "./FinishedRoundModal-67517066.js";
import _sfc_main$j from "./SendSuggestionModal-10988e5b.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./throttle-19ac5bdb.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
import "./SelectInput-5ecccdd8.js";
import "./v4-e7604ebc.js";
import "./LoadingButton-61f4ce4f.js";
import "./Socialite-eb75d06f.js";
import "./EditOptionsForm-80793cf6.js";
import "./CheckboxInput-45662aca.js";
import "./PodiumModal-9d08649f.js";
import "./Podium-85ef9c78.js";
const _hoisted_1$5 = ["content"];
const _hoisted_2$5 = ["content"];
const _hoisted_3$5 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "og:type",
  content: "website"
}, null, -1);
const _hoisted_4$5 = ["content"];
const _hoisted_5$4 = ["content"];
const _hoisted_6$4 = ["content"];
const _hoisted_7$3 = /* @__PURE__ */ createBaseVNode("meta", {
  name: "twitter:card",
  content: "summary_large_image"
}, null, -1);
const _hoisted_8$3 = /* @__PURE__ */ createBaseVNode("meta", {
  property: "twitter:domain",
  content: "blinest.com"
}, null, -1);
const _hoisted_9$3 = ["content"];
const _hoisted_10$3 = ["content"];
const _hoisted_11$3 = ["content"];
const _hoisted_12$3 = ["content"];
const _hoisted_13$3 = { class: "text-neutral-200" };
const _hoisted_14$1 = /* @__PURE__ */ createBaseVNode("div", { id: "dropdown" }, null, -1);
const _hoisted_15$1 = { class: "md:flex md:flex-col" };
const _hoisted_16$1 = { class: "md:flex md:h-screen md:flex-col" };
const _hoisted_17$1 = { class: "md:flex md:flex-grow md:overflow-hidden" };
const _hoisted_18$1 = {
  key: 0,
  class: "md:flex-1"
};
const _sfc_main$5 = {
  __name: "RoomLayout",
  setup(__props) {
    const room = computed(() => J().props.room);
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), null, {
          default: withCtx(() => [
            createBaseVNode("title", null, toDisplayString(unref(room).name), 1),
            createBaseVNode("meta", {
              name: "description",
              content: unref(room).description
            }, null, 8, _hoisted_1$5),
            createBaseVNode("meta", {
              property: "og:url",
              content: unref(room).url
            }, null, 8, _hoisted_2$5),
            _hoisted_3$5,
            createBaseVNode("meta", {
              property: "og:image",
              content: unref(room).photo_src ? unref(room).photo_src : unref(room).photo
            }, null, 8, _hoisted_4$5),
            createBaseVNode("meta", {
              property: "og:title",
              content: unref(room).name + " - Blinest.com"
            }, null, 8, _hoisted_5$4),
            createBaseVNode("meta", {
              property: "og:description",
              content: unref(room).description
            }, null, 8, _hoisted_6$4),
            _hoisted_7$3,
            _hoisted_8$3,
            createBaseVNode("meta", {
              property: "twitter:url",
              content: unref(room).url
            }, null, 8, _hoisted_9$3),
            createBaseVNode("meta", {
              name: "twitter:description",
              content: unref(room).description
            }, null, 8, _hoisted_10$3),
            createBaseVNode("meta", {
              name: "twitter:title",
              content: unref(room).name + " - Blinest"
            }, null, 8, _hoisted_11$3),
            createBaseVNode("meta", {
              name: "twitter:image",
              content: unref(room).photo_src ? unref(room).photo_src : unref(room).photo
            }, null, 8, _hoisted_12$3)
          ]),
          _: 1
        }),
        createBaseVNode("div", _hoisted_13$3, [
          _hoisted_14$1,
          createBaseVNode("div", _hoisted_15$1, [
            createBaseVNode("div", _hoisted_16$1, [
              createVNode(_sfc_main$6),
              createBaseVNode("div", _hoisted_17$1, [
                createVNode(Transition, {
                  name: "slide-right",
                  appear: ""
                }, {
                  default: withCtx(() => [
                    _ctx.$slots.default ? (openBlock(), createElementBlock("div", _hoisted_18$1, [
                      createVNode(FlashMessages),
                      renderSlot(_ctx.$slots, "default")
                    ])) : createCommentVNode("", true)
                  ]),
                  _: 3
                })
              ])
            ])
          ])
        ])
      ], 64);
    };
  }
};
const _hoisted_1$4 = { class: "flex w-full items-center justify-between" };
const _hoisted_2$4 = { class: "pr-2 text-sm italic" };
const _hoisted_3$4 = { class: "my-2 border-l px-4 py-2 font-mono text-neutral-400" };
const _hoisted_4$4 = { class: "mr-1 text-xs" };
const _hoisted_5$3 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_6$3 = { class: "my-4 flex items-center gap-4" };
const _sfc_main$4 = {
  __name: "Moderation",
  props: {
    message: Object,
    room: Object
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    const user = J().props.auth.user;
    const authorIsModerator = props.room.moderators.find((x) => x.id === props.message.user.id);
    const userIsPublicModerator = J().props.publicModerators.find((x) => x.id === user.id);
    const show = ref(true);
    const showingBanForm = ref(false);
    const deleteMessage = () => {
      axios.delete(`/moderation/messages/${props.message.id}`).then((response) => {
        close();
      });
    };
    const close = () => {
      emit("close");
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$8, {
        show: show.value,
        onClose: close
      }, {
        default: withCtx(() => [
          createVNode(Card, null, {
            header: withCtx(() => [
              createBaseVNode("div", _hoisted_1$4, [
                createBaseVNode("h2", null, toDisplayString(_ctx.__("Moderation")), 1),
                createBaseVNode("span", _hoisted_2$4, toDisplayString(__props.message.user.name), 1)
              ])
            ]),
            default: withCtx(() => [
              createBaseVNode("blockquote", _hoisted_3$4, [
                createBaseVNode("time", _hoisted_4$4, toDisplayString(__props.message.time), 1),
                _hoisted_5$3,
                createTextVNode(" " + toDisplayString(__props.message.body), 1)
              ]),
              createBaseVNode("div", _hoisted_6$3, [
                createBaseVNode("button", {
                  class: "btn-danger btn-sm",
                  onClick: deleteMessage
                }, toDisplayString(_ctx.__("Delete message")), 1),
                !unref(authorIsModerator) && unref(userIsPublicModerator) ? (openBlock(), createElementBlock("button", {
                  key: 0,
                  class: "btn-danger btn-sm",
                  onClick: _cache[0] || (_cache[0] = ($event) => showingBanForm.value = !showingBanForm.value)
                }, toDisplayString(_ctx.__("Ban")) + " " + toDisplayString(__props.message.user.name), 1)) : createCommentVNode("", true),
                createBaseVNode("button", {
                  class: "btn-secondary btn-sm",
                  onClick: close
                }, toDisplayString(_ctx.__("Cancel")), 1)
              ]),
              withDirectives(createVNode(_sfc_main$7, {
                user: __props.message.user
              }, null, 8, ["user"]), [
                [vShow, showingBanForm.value]
              ])
            ]),
            _: 1
          })
        ]),
        _: 1
      }, 8, ["show"]);
    };
  }
};
const _hoisted_1$3 = { class: "group relative my-1 flex flex-wrap items-center text-sm hover:opacity-90" };
const _hoisted_2$3 = { key: 0 };
const _hoisted_3$3 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "h-4 w-4 animate-spin"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
  })
], -1);
const _hoisted_4$3 = [
  _hoisted_3$3
];
const _hoisted_5$2 = { class: "mr-1 text-yellow-600" };
const _hoisted_6$2 = {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  fill: "currentColor",
  class: "h-4 w-4 text-red-500"
};
const _hoisted_7$2 = /* @__PURE__ */ createBaseVNode("path", {
  "fill-rule": "evenodd",
  d: "M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z",
  "clip-rule": "evenodd"
}, null, -1);
const _hoisted_8$2 = { class: "mr-2 text-xs italic text-neutral-500" };
const _hoisted_9$2 = ["src", "alt"];
const _hoisted_10$2 = {
  key: 0,
  class: "mx-1 text-[9px] uppercase"
};
const _hoisted_11$2 = ["src", "alt"];
const _hoisted_12$2 = {
  key: 0,
  class: "mx-1 text-[9px] uppercase"
};
const _hoisted_13$2 = { class: "" };
const _sfc_main$3 = {
  __name: "Message",
  props: {
    room: Object,
    message: Object
  },
  setup(__props) {
    const props = __props;
    const moderate = ref(false);
    const reporting = ref(false);
    const user = J().props.auth.user;
    const isModerator = props.room.moderators.find((x) => x.id === user.id);
    const userIsPublicModerator = J().props.publicModerators.find((x) => x.id === user.id);
    const report = () => {
      reporting.value = true;
      axios.post(`/rooms/${props.room.id}/message/${props.message.id}/report`).then((response) => {
        reporting.value = false;
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1$3, [
        !unref(J)().props.publicModerators.find((x) => x.id === __props.message.user.id) ? (openBlock(), createElementBlock("div", {
          key: 0,
          class: normalizeClass(["absolute top-0 right-0 items-center bg-neutral-800 py-1 px-2 group-hover:flex", __props.message.reports < 0 ? "flex" : "hidden"])
        }, [
          reporting.value ? (openBlock(), createElementBlock("div", _hoisted_2$3, _hoisted_4$3)) : (openBlock(), createElementBlock("button", {
            key: 1,
            onClick: report,
            class: "flex items-center text-xs"
          }, [
            createBaseVNode("span", _hoisted_5$2, toDisplayString(__props.message.reports), 1),
            (openBlock(), createElementBlock("svg", _hoisted_6$2, [
              createBaseVNode("title", null, toDisplayString(_ctx.__("Report this message")), 1),
              _hoisted_7$2
            ]))
          ]))
        ], 2)) : createCommentVNode("", true),
        createBaseVNode("time", _hoisted_8$2, toDisplayString(__props.message.time), 1),
        unref(isModerator) || unref(userIsPublicModerator) ? (openBlock(), createElementBlock("button", {
          key: 1,
          onClick: _cache[0] || (_cache[0] = ($event) => moderate.value = true),
          class: normalizeClass(["mr-1 flex items-center", __props.room.moderators.find((x) => x.id === __props.message.user.id) ? "text-purple-500" : "text-neutral-400"])
        }, [
          createBaseVNode("img", {
            src: __props.message.user.photo,
            alt: __props.message.user.name,
            class: "mr-1 h-7 w-7 rounded-full"
          }, null, 8, _hoisted_9$2),
          createTextVNode(" " + toDisplayString(__props.message.user.name) + " ", 1),
          __props.message.user.team ? (openBlock(), createElementBlock("sup", _hoisted_10$2, "[" + toDisplayString(__props.message.user.team.name) + "]", 1)) : createCommentVNode("", true),
          createTextVNode(" : ")
        ], 2)) : (openBlock(), createElementBlock("span", {
          key: 2,
          class: normalizeClass(["mr-1 flex items-center", __props.room.moderators.find((x) => x.id === __props.message.user.id) ? "text-purple-500" : "text-neutral-400"])
        }, [
          createBaseVNode("img", {
            src: __props.message.user.photo,
            alt: __props.message.user.name,
            class: "mr-1 h-7 w-7 rounded-full"
          }, null, 8, _hoisted_11$2),
          createTextVNode(" " + toDisplayString(__props.message.user.name) + " ", 1),
          __props.message.user.team ? (openBlock(), createElementBlock("sup", _hoisted_12$2, "[" + toDisplayString(__props.message.user.team.name) + "]", 1)) : createCommentVNode("", true),
          createTextVNode(" : ")
        ], 2)),
        createBaseVNode("span", _hoisted_13$2, toDisplayString(__props.message.body), 1),
        moderate.value ? (openBlock(), createBlock(_sfc_main$4, {
          key: 3,
          message: __props.message,
          room: __props.room,
          onClose: _cache[1] || (_cache[1] = ($event) => moderate.value = false)
        }, null, 8, ["message", "room"])) : createCommentVNode("", true)
      ]);
    };
  }
};
const _hoisted_1$2 = { class: "flex items-center pl-4 text-xl font-bold" };
const _hoisted_2$2 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "mr-2 h-5 w-5",
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
const _hoisted_3$2 = { class: "p-4" };
const _hoisted_4$2 = { class: "mt-8 flex justify-end" };
const _sfc_main$2 = {
  __name: "AlertModeratorsModal",
  props: {
    room: Object
  },
  emits: ["close", "reported"],
  setup(__props, { emit }) {
    const props = __props;
    const show = ref(false);
    const form = P({
      message: ""
    });
    const alertModerators = () => {
      form.post(`/rooms/${props.room.id}/alert`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          emit("reported");
          emit("close");
        }
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$8, {
        show: show.value,
        onClose: _cache[2] || (_cache[2] = ($event) => _ctx.$emit("close"))
      }, {
        default: withCtx(() => [
          createVNode(Card, null, {
            header: withCtx(() => [
              createBaseVNode("h3", _hoisted_1$2, [
                _hoisted_2$2,
                createTextVNode(" " + toDisplayString(_ctx.__("Alerting moderators")), 1)
              ])
            ]),
            default: withCtx(() => [
              createBaseVNode("div", _hoisted_3$2, [
                createVNode(Tip, null, {
                  default: withCtx(() => [
                    createTextVNode("Attention, cette fonctionnalité n'est à utiliser qu'en cas de problème de respect des règles sur le chat ou d'une panne sur la partie.")
                  ]),
                  _: 1
                }),
                createVNode(_sfc_main$9, {
                  modelValue: unref(form).message,
                  "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).message = $event),
                  error: unref(form).errors.message,
                  label: "Commentaire (facultatif)"
                }, null, 8, ["modelValue", "error"]),
                createBaseVNode("div", _hoisted_4$2, [
                  createBaseVNode("button", {
                    class: "btn-secondary mr-2",
                    onClick: _cache[1] || (_cache[1] = ($event) => _ctx.$emit("close"))
                  }, toDisplayString(_ctx.__("Close")), 1),
                  createBaseVNode("button", {
                    class: "btn-danger",
                    onClick: alertModerators
                  }, toDisplayString(_ctx.__("Alerting moderators")), 1)
                ])
              ])
            ]),
            _: 1
          })
        ]),
        _: 1
      }, 8, ["show"]);
    };
  }
};
const _hoisted_1$1 = { class: "flex h-full flex-col" };
const _hoisted_2$1 = { class: "flex items-center justify-center gap-2 px-2 py-4" };
const _hoisted_3$1 = /* @__PURE__ */ createBaseVNode("svg", {
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
const _hoisted_4$1 = {
  key: 1,
  class: "flex items-center text-green-500"
};
const _hoisted_5$1 = /* @__PURE__ */ createBaseVNode("svg", {
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
    d: "M5 13l4 4L19 7"
  })
], -1);
const _hoisted_6$1 = ["href"];
const _hoisted_7$1 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "w-6 h-6"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"
  })
], -1);
const _hoisted_8$1 = [
  _hoisted_7$1
];
const _hoisted_9$1 = { class: "flex w-full p-2" };
const _hoisted_10$1 = ["onSubmit"];
const _hoisted_11$1 = {
  type: "submit",
  class: "rounded-r bg-teal-600 p-2 text-neutral-100"
};
const _hoisted_12$1 = {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "h-6 w-6"
};
const _hoisted_13$1 = /* @__PURE__ */ createBaseVNode("path", {
  "stroke-linecap": "round",
  "stroke-linejoin": "round",
  d: "M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"
}, null, -1);
const _sfc_main$1 = {
  __name: "Chat",
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const channel = `chat-room.${props.room.id}`;
    const body = ref("");
    const messages = ref(props.room.latest_messages);
    J().props.auth.user;
    const messenger = ref();
    const reported = ref(null);
    const alertingModerators = ref(null);
    onMounted(() => {
      Echo.private(channel).listen("NewMessage", (e) => {
        messages.value.unshift(...[e.message]);
        scrollToBottom();
      }).listen("MessageReported", (e) => {
        messages.value = messages.value.map((x) => {
          if (x.id === e.message.id)
            return e.message;
          return x;
        });
      }).listen("MessageDeleted", (e) => {
        messages.value = messages.value.filter((x) => {
          return x.id != e.message.id;
        });
      });
    });
    onUnmounted(() => {
      Echo.leave(channel);
    });
    const sendMessage = () => {
      axios.post(`/rooms/${props.room.id}/message`, {
        body: body.value
      }).then((response) => {
        body.value = "";
      });
    };
    const scrollToBottom = () => {
      let container = messenger.value;
      container.scrollTop = container.scrollHeight + 1e3;
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createBaseVNode("div", _hoisted_1$1, [
          createBaseVNode("div", _hoisted_2$1, [
            !reported.value ? (openBlock(), createElementBlock("button", {
              key: 0,
              class: "btn-secondary btn-sm bg-neutral-700",
              onClick: _cache[0] || (_cache[0] = ($event) => alertingModerators.value = true)
            }, [
              _hoisted_3$1,
              createTextVNode(" " + toDisplayString(_ctx.__("Alerting moderators")), 1)
            ])) : (openBlock(), createElementBlock("div", _hoisted_4$1, [
              _hoisted_5$1,
              createTextVNode(" " + toDisplayString(_ctx.__("Alert sent")), 1)
            ])),
            createBaseVNode("a", {
              href: _ctx.route("faq"),
              title: "FAQ",
              target: "_blank"
            }, _hoisted_8$1, 8, _hoisted_6$1),
            createVNode(_sfc_main$a, {
              url: __props.room.url,
              class: "h-5 w-5"
            }, null, 8, ["url"])
          ]),
          createBaseVNode("div", {
            ref_key: "messenger",
            ref: messenger,
            class: "flex flex-1 flex-col-reverse overflow-y-scroll p-2"
          }, [
            (openBlock(true), createElementBlock(Fragment, null, renderList(messages.value, (message) => {
              return openBlock(), createBlock(_sfc_main$3, {
                key: message.id,
                message,
                room: __props.room
              }, null, 8, ["message", "room"]);
            }), 128))
          ], 512),
          createBaseVNode("div", _hoisted_9$1, [
            createBaseVNode("form", {
              onSubmit: withModifiers(sendMessage, ["prevent"]),
              class: "flex w-full"
            }, [
              createVNode(_sfc_main$b, {
                modelValue: body.value,
                "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => body.value = $event),
                autocomplete: "off",
                inputClass: "rounded-r-none border-0",
                class: "flex-grow"
              }, null, 8, ["modelValue"]),
              createBaseVNode("button", _hoisted_11$1, [
                (openBlock(), createElementBlock("svg", _hoisted_12$1, [
                  createBaseVNode("title", null, toDisplayString(_ctx.__("Send")), 1),
                  _hoisted_13$1
                ]))
              ])
            ], 40, _hoisted_10$1)
          ])
        ]),
        createVNode(_sfc_main$2, {
          room: __props.room,
          show: alertingModerators.value,
          onReported: _cache[2] || (_cache[2] = ($event) => reported.value = true),
          onClose: _cache[3] || (_cache[3] = ($event) => alertingModerators.value = false)
        }, null, 8, ["room", "show"])
      ], 64);
    };
  }
};
const _hoisted_1 = {
  key: 1,
  class: "flex h-full w-full items-center justify-center"
};
const _hoisted_2 = {
  key: 0,
  class: "h-full md:flex"
};
const _hoisted_3 = {
  class: "relative flex-1 overflow-y-auto p-4 md:px-12 md:py-8",
  "scroll-region": ""
};
const _hoisted_4 = { class: "mb-4 flex items-center" };
const _hoisted_5 = { class: "mr-2 text-xl font-bold" };
const _hoisted_6 = { class: "font-bold" };
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_8 = {
  key: 1,
  class: "mb-4 md:mb-8"
};
const _hoisted_9 = { class: "grid md:grid-cols-2 md:gap-8" };
const _hoisted_10 = { class: "flex flex-col items-center gap-4 text-sm lg:flex-row lg:justify-between" };
const _hoisted_11 = { class: "mx-auto flex flex-wrap items-center gap-4" };
const _hoisted_12 = /* @__PURE__ */ createBaseVNode("span", { class: "uppercase text-neutral-500" }, "Modos", -1);
const _hoisted_13 = ["src", "alt", "title"];
const _hoisted_14 = {
  key: 0,
  class: "flex items-center gap-4"
};
const _hoisted_15 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "mr-1 h-5 w-5"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"
  })
], -1);
const _hoisted_16 = { class: "uppercase text-neutral-500" };
const _hoisted_17 = ["title"];
const _hoisted_18 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "h-8 w-8",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
  })
], -1);
const _hoisted_19 = [
  _hoisted_18
];
const _hoisted_20 = {
  key: 0,
  class: "flex h-96 w-full flex-shrink-0 flex-col rounded-tl border-neutral-700 bg-neutral-800 transition-all duration-300 md:h-full md:w-1/5"
};
const _sfc_main = {
  __name: "Show",
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const user = J().props.auth.user;
    const channel = `rooms.${props.room.id}`;
    const round = ref(null);
    const joined = ref(false);
    const users = ref([]);
    const data = ref(null);
    const roundFinished = ref(false);
    const sendingSuggestion = ref(false);
    const showSidebar = ref(true);
    const currentTime = ref(0);
    const users_podium = ref([]);
    const teams_podium = ref([]);
    onMounted(() => {
      if (user) {
        Echo.join(channel).here((usersHere) => {
          users.value = usersHere;
          joining();
        }).joining((user2) => {
          users.value.push(user2);
        }).leaving((user2) => {
          users.value = users.value.filter((u) => u.id !== user2.id);
        }).error((error) => {
          console.error(error);
        });
      }
    });
    onUnmounted(() => {
      Echo.leave(channel);
    });
    const joining = () => {
      axios.get(`/rooms/${props.room.id}/joined`).then(() => {
        joined.value = true;
        listenRounds();
      });
    };
    const listenRounds = () => {
      Echo.channel(channel).listen("RoundStarted", (e) => {
        console.warn("Round started");
        round.value = e.round;
        roundFinished.value = false;
      }).listen("RoundFinished", (e) => {
        console.warn("Round finished");
        round.value = e.round;
        users_podium.value = e.users_podium;
        teams_podium.value = e.teams_podium;
        roundFinished.value = true;
      }).listen("TrackPlayed", (e) => {
        round.value = e.round;
        props.room.value = e.room;
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$5, null, {
        default: withCtx(() => [
          !unref(user) ? (openBlock(), createBlock(_sfc_main$8, {
            key: 0,
            show: true,
            maxWidth: "3xl"
          }, {
            default: withCtx(() => [
              createVNode(_sfc_main$c, { isFromModal: true })
            ]),
            _: 1
          })) : createCommentVNode("", true),
          !joined.value && unref(user) ? (openBlock(), createElementBlock("div", _hoisted_1, [
            createVNode(Spinner),
            createBaseVNode("h2", null, toDisplayString(_ctx.__("Loading")) + "...", 1)
          ])) : createCommentVNode("", true),
          createVNode(Transition, { name: "slide-right" }, {
            default: withCtx(() => [
              joined.value || !unref(user) ? (openBlock(), createElementBlock("div", _hoisted_2, [
                createBaseVNode("div", _hoisted_3, [
                  createBaseVNode("article", _hoisted_4, [
                    createBaseVNode("h2", _hoisted_5, toDisplayString(__props.room.name), 1),
                    createVNode(_sfc_main$a, {
                      url: __props.room.url,
                      class: "w-5"
                    }, null, 8, ["url"])
                  ]),
                  !__props.room.is_autostart && (!round.value || !round.value.is_playing) && !__props.room.is_playing ? (openBlock(), createBlock(Tip, {
                    key: 0,
                    class: "bg-orange-400 text-orange-800"
                  }, {
                    default: withCtx(() => [
                      createBaseVNode("span", _hoisted_6, toDisplayString(_ctx.__("This room is in manual start mode.")), 1),
                      _hoisted_7,
                      createTextVNode(" " + toDisplayString(_ctx.__("The person responsible for the room (moderators) must be present to start the game.")), 1)
                    ]),
                    _: 1
                  })) : createCommentVNode("", true),
                  unref(user) ? (openBlock(), createElementBlock("div", _hoisted_8, [
                    createVNode(_sfc_main$d, {
                      room: __props.room,
                      channel,
                      "onTrack:currentTime": _cache[0] || (_cache[0] = ($event) => currentTime.value = $event)
                    }, null, 8, ["room"]),
                    createVNode(_sfc_main$e, {
                      channel,
                      currentTime: currentTime.value,
                      room: __props.room
                    }, null, 8, ["currentTime", "room"])
                  ])) : createCommentVNode("", true),
                  createBaseVNode("div", _hoisted_9, [
                    createVNode(_sfc_main$f, {
                      class: "mb-4 md:mb-8",
                      users: users.value,
                      channel
                    }, null, 8, ["users"]),
                    createVNode(_sfc_main$g, {
                      class: "mb-4 md:mb-8",
                      room: __props.room,
                      users: users.value,
                      channel,
                      data: data.value
                    }, null, 8, ["room", "users", "data"])
                  ]),
                  unref(user) ? (openBlock(), createElementBlock(Fragment, { key: 2 }, [
                    __props.room.moderators.find((x) => unref(user).id === x.id) ? (openBlock(), createBlock(_sfc_main$h, {
                      key: 0,
                      room: __props.room,
                      round: round.value,
                      channel,
                      class: "mb-4"
                    }, null, 8, ["room", "round"])) : createCommentVNode("", true)
                  ], 64)) : createCommentVNode("", true),
                  createVNode(Card, null, {
                    default: withCtx(() => [
                      createBaseVNode("div", _hoisted_10, [
                        createBaseVNode("div", null, [
                          createBaseVNode("div", _hoisted_11, [
                            _hoisted_12,
                            (openBlock(true), createElementBlock(Fragment, null, renderList(__props.room.moderators, (moderator) => {
                              return openBlock(), createElementBlock("span", {
                                class: normalizeClass(["flex items-center", { "font-bold text-teal-500": users.value.find((x) => moderator.id === x.id) }])
                              }, [
                                createBaseVNode("img", {
                                  src: moderator.photo,
                                  alt: moderator.name,
                                  title: moderator.name,
                                  class: "mr-1 h-8 w-8 rounded-full"
                                }, null, 8, _hoisted_13),
                                createTextVNode(" " + toDisplayString(moderator.name), 1)
                              ], 2);
                            }), 256))
                          ])
                        ]),
                        unref(user) ? (openBlock(), createElementBlock("div", _hoisted_14, [
                          createBaseVNode("button", {
                            class: "btn-secondary btn-sm bg-neutral-900",
                            onClick: _cache[1] || (_cache[1] = ($event) => sendingSuggestion.value = true)
                          }, [
                            _hoisted_15,
                            createTextVNode(" " + toDisplayString(_ctx.__("Send a suggestion")), 1)
                          ]),
                          createBaseVNode("span", _hoisted_16, toDisplayString(__props.room.tracks_count) + " audios", 1)
                        ])) : createCommentVNode("", true)
                      ])
                    ]),
                    _: 1
                  }),
                  unref(user) && __props.room.is_chat_active ? (openBlock(), createElementBlock("button", {
                    key: 3,
                    class: "absolute right-0 top-5 hidden rounded-l-lg bg-neutral-800 p-2 md:block",
                    onClick: _cache[2] || (_cache[2] = ($event) => showSidebar.value = !showSidebar.value),
                    title: _ctx.__("Hide/Show chatbox")
                  }, _hoisted_19, 8, _hoisted_17)) : createCommentVNode("", true)
                ]),
                unref(user) && showSidebar.value && __props.room.is_chat_active ? (openBlock(), createElementBlock("div", _hoisted_20, [
                  createVNode(_sfc_main$1, { room: __props.room }, null, 8, ["room"])
                ])) : createCommentVNode("", true)
              ])) : createCommentVNode("", true)
            ]),
            _: 1
          }),
          round.value ? (openBlock(), createBlock(_sfc_main$i, {
            key: 2,
            show: roundFinished.value,
            round: round.value,
            users_podium: users_podium.value,
            teams_podium: teams_podium.value,
            onClose: _cache[3] || (_cache[3] = ($event) => roundFinished.value = false)
          }, null, 8, ["show", "round", "users_podium", "teams_podium"])) : createCommentVNode("", true),
          unref(user) ? (openBlock(), createBlock(_sfc_main$j, {
            key: 3,
            show: sendingSuggestion.value,
            room: __props.room,
            onClose: _cache[4] || (_cache[4] = ($event) => sendingSuggestion.value = false)
          }, null, 8, ["show", "room"])) : createCommentVNode("", true)
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
