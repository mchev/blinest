import { J, k as ref, q as onMounted, L as onUnmounted, h as createBlock, w as withCtx, o as openBlock, b as createBaseVNode, c as createElementBlock, t as toDisplayString, d as createTextVNode, g as createCommentVNode, a as createVNode, v as renderList, F as Fragment, f as normalizeClass, u as unref, W as TransitionGroup } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$1 } from "./Icon-86c99edc.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "flex w-full items-center justify-between" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("h3", { class: "text-xl font-bold" }, "Playlist", -1);
const _hoisted_3 = {
  key: 0,
  class: "text-xl font-bold text-neutral-500"
};
const _hoisted_4 = { class: "text-neutral-700" };
const _hoisted_5 = { class: "p-2" };
const _hoisted_6 = ["src", "alt"];
const _hoisted_7 = { class: "flex-grow p-2" };
const _hoisted_8 = { class: "flex h-full justify-between" };
const _hoisted_9 = {
  key: 0,
  class: "flex items-center gap-2"
};
const _hoisted_10 = {
  key: 0,
  class: "text-white"
};
const _hoisted_11 = /* @__PURE__ */ createBaseVNode("svg", {
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
const _hoisted_12 = [
  _hoisted_11
];
const _hoisted_13 = {
  key: 1,
  class: "absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-purple-500 text-[11px] text-neutral-100"
};
const _hoisted_14 = {
  key: 1,
  class: "flex items-center gap-2"
};
const _hoisted_15 = { class: "flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white" };
const _hoisted_16 = { class: "hidden flex-col items-end lg:flex" };
const _hoisted_17 = ["href", "title"];
const _hoisted_18 = {
  key: 1,
  class: "mt-4 flex items-center text-xs"
};
const _hoisted_19 = ["onClick", "title"];
const _hoisted_20 = ["onClick", "title"];
const _sfc_main = {
  __name: "Answers",
  props: {
    users: Array,
    channel: String
  },
  setup(__props) {
    const props = __props;
    const user = J().props.auth.user;
    const userAnswers = ref([]);
    const round = ref(null);
    const tracks = ref([]);
    onMounted(() => {
      Echo.channel(props.channel).listen("RoundStarted", (e) => {
        round.value = e.round;
        tracks.value = [];
        userAnswers.value = [];
      }).listen("TrackPlayed", (e) => {
        round.value = e.round;
      }).listen("TrackEnded", (e) => {
        tracks.value.unshift(e.track);
        round.value = e.round;
      }).listen("TrackVoted", (e) => {
        let index = tracks.value.findIndex((x) => x.id === e.track.id);
        tracks.value[index].downvotes = e.track.downvotes;
        tracks.value[index].upvotes = e.track.upvotes;
      }).listen("NewScore", (e) => {
        if (e.score.user_id === user.id) {
          console.log(e.score);
          userAnswers.value.push(e.score);
        }
      });
    });
    onUnmounted(() => {
      Echo.leave(props.channel);
    });
    const voteTrackDown = (track) => {
      axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/downvote`);
    };
    const voteTrackUp = (track) => {
      axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/upvote`);
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        header: withCtx(() => [
          createBaseVNode("div", _hoisted_1, [
            _hoisted_2,
            round.value ? (openBlock(), createElementBlock("span", _hoisted_3, [
              createBaseVNode("span", _hoisted_4, toDisplayString(round.value.current), 1),
              createTextVNode(" / " + toDisplayString(round.value.tracks.length), 1)
            ])) : createCommentVNode("", true)
          ])
        ]),
        default: withCtx(() => [
          createVNode(TransitionGroup, {
            name: "flip-list",
            tag: "ul",
            class: "h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96"
          }, {
            default: withCtx(() => [
              (openBlock(true), createElementBlock(Fragment, null, renderList(tracks.value, (track) => {
                return openBlock(), createElementBlock("li", {
                  key: track.id,
                  class: "mb-2 flex rounded bg-neutral-900 opacity-70"
                }, [
                  createBaseVNode("div", _hoisted_5, [
                    createBaseVNode("img", {
                      src: track.artwork_url,
                      alt: track.album_name,
                      class: "h-20 w-auto rounded"
                    }, null, 8, _hoisted_6)
                  ]),
                  createBaseVNode("div", _hoisted_7, [
                    createBaseVNode("div", _hoisted_8, [
                      createBaseVNode("ul", null, [
                        (openBlock(true), createElementBlock(Fragment, null, renderList(track.answers, (answer) => {
                          var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j;
                          return openBlock(), createElementBlock("li", {
                            key: answer.id,
                            class: "mb-2 flex items-start overflow-ellipsis text-sm"
                          }, [
                            (_ctx.userAnswer = userAnswers.value.filter((x) => x.track_id === track.id && x.answers.filter((a) => a.id === answer.id)[0])[0]) ? (openBlock(), createElementBlock("div", _hoisted_9, [
                              createBaseVNode("div", {
                                class: normalizeClass(["relative flex items-center gap-1 rounded bg-purple-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white", { "mr-1": ((_b = (_a = _ctx.userAnswer.answers) == null ? void 0 : _a.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _b.order) < 4 }])
                              }, [
                                ((_d = (_c = _ctx.userAnswer.answers) == null ? void 0 : _c.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _d.speedBonus) ? (openBlock(), createElementBlock("span", _hoisted_10, _hoisted_12)) : createCommentVNode("", true),
                                createTextVNode(" " + toDisplayString(_ctx.__((_f = (_e = _ctx.userAnswer.answers) == null ? void 0 : _e.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _f.name)) + " ", 1),
                                ((_h = (_g = _ctx.userAnswer.answers) == null ? void 0 : _g.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _h.order) < 4 ? (openBlock(), createElementBlock("span", _hoisted_13, toDisplayString((_j = (_i = _ctx.userAnswer.answers) == null ? void 0 : _i.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _j.order), 1)) : createCommentVNode("", true)
                              ], 2),
                              createTextVNode(" " + toDisplayString(answer.value), 1)
                            ])) : (openBlock(), createElementBlock("div", _hoisted_14, [
                              createBaseVNode("span", _hoisted_15, toDisplayString(_ctx.__(answer.type.name)), 1),
                              createTextVNode(" " + toDisplayString(answer.value), 1)
                            ]))
                          ]);
                        }), 128))
                      ]),
                      createBaseVNode("div", _hoisted_16, [
                        track.track_url ? (openBlock(), createElementBlock("a", {
                          key: 0,
                          class: "flex items-center whitespace-nowrap text-xs opacity-50 hover:opacity-90",
                          href: track.track_url,
                          target: "_blank",
                          title: _ctx.__("Listen on") + " " + track.provider
                        }, [
                          createTextVNode(toDisplayString(_ctx.__("Listen on")) + " ", 1),
                          createVNode(_sfc_main$1, {
                            name: track.provider,
                            class: "ml-1 h-5 w-5"
                          }, null, 8, ["name"])
                        ], 8, _hoisted_17)) : createCommentVNode("", true),
                        unref(user) ? (openBlock(), createElementBlock("div", _hoisted_18, [
                          createBaseVNode("button", {
                            onClick: ($event) => voteTrackUp(track),
                            class: "mr-4 flex items-center",
                            title: _ctx.__("Like")
                          }, [
                            createVNode(_sfc_main$1, {
                              name: "thumb-up",
                              class: "mr-1 h-5 w-5"
                            }),
                            createTextVNode(" " + toDisplayString(track.upvotes), 1)
                          ], 8, _hoisted_19),
                          createBaseVNode("button", {
                            onClick: ($event) => voteTrackDown(track),
                            class: "flex items-center",
                            title: _ctx.__("Don't like")
                          }, [
                            createVNode(_sfc_main$1, {
                              name: "thumb-down",
                              class: "mr-1 h-5 w-5"
                            }),
                            createTextVNode(" " + toDisplayString(track.downvotes), 1)
                          ], 8, _hoisted_20)
                        ])) : createCommentVNode("", true)
                      ])
                    ])
                  ])
                ]);
              }), 128))
            ]),
            _: 1
          })
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
