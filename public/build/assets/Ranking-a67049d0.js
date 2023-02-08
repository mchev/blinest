import { J, k as ref, l as watch, q as onMounted, L as onUnmounted, c as createElementBlock, a as createVNode, w as withCtx, u as unref, h as createBlock, g as createCommentVNode, o as openBlock, b as createBaseVNode, t as toDisplayString, W as TransitionGroup, F as Fragment, v as renderList, f as normalizeClass, d as createTextVNode } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$2 } from "./Icon-86c99edc.js";
import _sfc_main$1 from "./PodiumModal-9d08649f.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Modal-f990bd3c.js";
import "./Spinner-bfddfb59.js";
const _hoisted_1 = { class: "flex w-full items-center justify-between" };
const _hoisted_2 = { class: "text-xl font-bold" };
const _hoisted_3 = { class: "mr-2 text-xs" };
const _hoisted_4 = ["title"];
const _hoisted_5 = { class: "h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96" };
const _hoisted_6 = { class: "flex items-center justify-center text-xl font-bold" };
const _hoisted_7 = { class: "flex items-center" };
const _hoisted_8 = ["src", "alt"];
const _hoisted_9 = { class: "flex flex-grow flex-col" };
const _hoisted_10 = { class: "mb-2" };
const _hoisted_11 = {
  key: 0,
  class: "text-[9px] uppercase"
};
const _hoisted_12 = { class: "flex flex-wrap items-center gap-1" };
const _hoisted_13 = {
  key: 0,
  class: "mr-1 text-white"
};
const _hoisted_14 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 20 20",
  fill: "currentColor",
  class: "h-3 w-3"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "fill-rule": "evenodd",
    d: "M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z",
    "clip-rule": "evenodd"
  })
], -1);
const _hoisted_15 = [
  _hoisted_14
];
const _hoisted_16 = {
  key: 1,
  class: "absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-purple-500 text-[11px] text-neutral-100"
};
const _hoisted_17 = {
  key: 0,
  class: "relative flex rounded bg-neutral-300 px-1 text-[11px] font-bold uppercase text-neutral-500 text-white"
};
const _hoisted_18 = /* @__PURE__ */ createBaseVNode("sup", null, "PTS", -1);
const _sfc_main = {
  __name: "Ranking",
  props: {
    room: Object,
    users: Array,
    channel: String,
    data: Object
  },
  setup(__props) {
    const props = __props;
    const me = J().props.auth.user;
    const scores = ref([]);
    const userList = ref(props == null ? void 0 : props.users);
    const track = ref(null);
    const showPodiumModal = ref(false);
    watch(
      () => props.users,
      (value) => {
        userList.value = value;
      }
    );
    onMounted(() => {
      Echo.channel(props.channel).listen("NewScore", (e) => {
        scores.value.push(e.score);
        let index = userList.value.findIndex((x) => x.id === e.score.user_id);
        userList.value[index].score.total = e.score.total;
        userList.value[index].score.answers.push(...e.score.answers);
        userList.value.sort((a, b) => b.score.total - a.score.total);
      }).listen("TrackPlayed", (e) => {
        track.value = e.track;
        userList.value.map((x) => {
          if (x.score)
            return x.score.answers = [];
        });
      }).listen("RoundStarted", () => {
        userList.value.forEach((x) => {
          x.score.total = 0;
        });
      });
    });
    onUnmounted(() => {
      Echo.leave(props.channel);
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", null, [
        createVNode(Card, null, {
          header: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              createBaseVNode("h3", _hoisted_2, toDisplayString(_ctx.__("Ranking")), 1),
              createBaseVNode("div", null, [
                createBaseVNode("sup", _hoisted_3, toDisplayString(userList.value.length), 1),
                unref(me) ? (openBlock(), createElementBlock("button", {
                  key: 0,
                  type: "button",
                  onClick: _cache[0] || (_cache[0] = ($event) => showPodiumModal.value = true),
                  title: _ctx.__("Show rankings for this room")
                }, [
                  createVNode(_sfc_main$2, {
                    name: "podium",
                    class: "mr-2 h-8 w-8"
                  })
                ], 8, _hoisted_4)) : createCommentVNode("", true)
              ])
            ])
          ]),
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_5, [
              createVNode(TransitionGroup, {
                name: "flip-list",
                tag: "ul"
              }, {
                default: withCtx(() => [
                  (openBlock(true), createElementBlock(Fragment, null, renderList(userList.value, (user, index) => {
                    return openBlock(), createElementBlock("li", {
                      key: user.id,
                      class: normalizeClass(["flex items-center gap-4 rounded border-b border-neutral-600 px-2 py-4", { "bg-neutral-700": unref(me) && unref(me).id === user.id }])
                    }, [
                      createBaseVNode("div", _hoisted_6, toDisplayString(index + 1), 1),
                      createBaseVNode("div", _hoisted_7, [
                        createBaseVNode("img", {
                          src: user.photo,
                          alt: user.name,
                          class: "h-12 w-12 rounded-full shadow-lg"
                        }, null, 8, _hoisted_8)
                      ]),
                      createBaseVNode("div", _hoisted_9, [
                        createBaseVNode("div", _hoisted_10, [
                          createTextVNode(toDisplayString(user.name) + " ", 1),
                          user.team ? (openBlock(), createElementBlock("sup", _hoisted_11, "[" + toDisplayString(user.team.name) + "]", 1)) : createCommentVNode("", true)
                        ]),
                        createBaseVNode("div", _hoisted_12, [
                          user.score ? (openBlock(true), createElementBlock(Fragment, { key: 0 }, renderList(user.score.answers, (userAnswer) => {
                            return openBlock(), createElementBlock("span", {
                              class: normalizeClass(["relative flex items-center rounded bg-purple-500 px-1 text-[11px] font-bold uppercase text-white", { "mr-2": userAnswer.order < 4 }])
                            }, [
                              userAnswer.speedBonus ? (openBlock(), createElementBlock("span", _hoisted_13, _hoisted_15)) : createCommentVNode("", true),
                              createTextVNode(" " + toDisplayString(_ctx.__(userAnswer.name)) + " ", 1),
                              userAnswer.order < 4 ? (openBlock(), createElementBlock("span", _hoisted_16, toDisplayString(userAnswer.order), 1)) : createCommentVNode("", true)
                            ], 2);
                          }), 256)) : createCommentVNode("", true),
                          track.value ? (openBlock(true), createElementBlock(Fragment, { key: 1 }, renderList(track.value.answers, (answer) => {
                            var _a;
                            return openBlock(), createElementBlock("span", null, [
                              !((_a = user == null ? void 0 : user.score) == null ? void 0 : _a.answers.find((x) => x.id === answer.id)) ? (openBlock(), createElementBlock("span", _hoisted_17, toDisplayString(_ctx.__(answer.name)), 1)) : createCommentVNode("", true)
                            ]);
                          }), 256)) : createCommentVNode("", true)
                        ])
                      ]),
                      createBaseVNode("div", null, [
                        createTextVNode(toDisplayString((user == null ? void 0 : user.score) ? user.score.total : 0) + " ", 1),
                        _hoisted_18
                      ])
                    ], 2);
                  }), 128))
                ]),
                _: 1
              })
            ])
          ]),
          _: 1
        }),
        unref(me) && showPodiumModal.value ? (openBlock(), createBlock(_sfc_main$1, {
          key: 0,
          room: __props.room,
          show: showPodiumModal.value,
          onClose: _cache[1] || (_cache[1] = ($event) => showPodiumModal.value = false)
        }, null, 8, ["room", "show"])) : createCommentVNode("", true)
      ]);
    };
  }
};
export {
  _sfc_main as default
};
