import { k as ref, q as onMounted, o as openBlock, h as createBlock, w as withCtx, a as createVNode, b as createBaseVNode, d as createTextVNode, t as toDisplayString, L as onUnmounted, c as createElementBlock, W as TransitionGroup, g as createCommentVNode, x as normalizeStyle, F as Fragment, v as renderList } from "./app-910e457d.js";
import { _ as _sfc_main$2 } from "./Modal-f990bd3c.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1$1 = /* @__PURE__ */ createBaseVNode("h3", { class: "flex items-center pl-4 text-xl font-bold" }, [
  /* @__PURE__ */ createBaseVNode("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    class: "mr-2 h-6 w-6",
    fill: "none",
    viewBox: "0 0 24 24",
    stroke: "currentColor",
    "stroke-width": "2"
  }, [
    /* @__PURE__ */ createBaseVNode("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      d: "M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"
    })
  ]),
  /* @__PURE__ */ createTextVNode(" Autoriser la lecture ")
], -1);
const _hoisted_2$1 = { class: "p-4" };
const _hoisted_3$1 = { class: "mt-4 flex items-start rounded bg-neutral-200 p-2 text-sm text-neutral-400" };
const _hoisted_4$1 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "mr-2 h-10 w-10",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
  })
], -1);
const _hoisted_5$1 = { class: "mt-8 flex justify-end" };
const _sfc_main$1 = {
  __name: "UserGestureModal",
  emits: ["play"],
  setup(__props, { emit }) {
    const show = ref(false);
    const triggerUserGesture = () => {
      emit("play");
      show.value = false;
    };
    onMounted(() => {
      var autoPlayAllowed = true;
      var promise = document.createElement("audio").play();
      if (promise instanceof Promise) {
        promise.catch(function(error) {
          if (error.name == "NotAllowedError") {
            autoPlayAllowed = false;
          } else {
            throw error;
          }
        }).then(function() {
          if (!autoPlayAllowed) {
            show.value = true;
          }
        });
      }
    });
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$2, {
        show: show.value,
        onClose: _cache[0] || (_cache[0] = ($event) => show.value = false)
      }, {
        default: withCtx(() => [
          createVNode(Card, null, {
            header: withCtx(() => [
              _hoisted_1$1
            ]),
            default: withCtx(() => [
              createBaseVNode("div", _hoisted_2$1, [
                createTextVNode(toDisplayString(_ctx.__("The browser needs your permission to start playing audio files.")) + " ", 1),
                createBaseVNode("p", _hoisted_3$1, [
                  _hoisted_4$1,
                  createBaseVNode("span", null, toDisplayString(_ctx.__("This message will appear every time you go directly to a room without going through the homepage.")), 1)
                ]),
                createBaseVNode("div", _hoisted_5$1, [
                  createBaseVNode("button", {
                    class: "btn-primary",
                    onClick: triggerUserGesture
                  }, toDisplayString(_ctx.__("Let's go!")), 1)
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
const _hoisted_1 = {
  id: "player",
  class: "relative flex h-4 w-full items-center rounded-t-lg bg-purple-200"
};
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { class: "absolute left-1 top-full mt-1 h-full h-0 w-full w-0 translate-y-[-50%] border-t-[8px] border-l-[8px] border-r-[8px] border-t-transparent border-l-transparent border-r-transparent border-t-teal-600" }, null, -1);
const _hoisted_3 = {
  key: 1,
  class: "flex h-4 w-full animate-pulse items-center justify-center rounded-t-lg text-red-500"
};
const _hoisted_4 = {
  key: 2,
  class: "flex h-4 w-full max-w-full animate-pulse items-center justify-center rounded-t-lg bg-purple-500"
};
const _hoisted_5 = {
  key: 3,
  class: "flex max-w-full flex-grow flex-col"
};
const _hoisted_6 = { class: "relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200" };
const _hoisted_7 = { class: "absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600" };
const _hoisted_8 = {
  key: 4,
  class: "w-full max-w-full"
};
const _sfc_main = {
  __name: "Player",
  props: {
    room: Object,
    channel: String
  },
  emits: ["track:played", "track:ended", "track:paused", "track:stopped", "track:currentTime"],
  setup(__props, { emit }) {
    const props = __props;
    const audio = new Audio();
    const track = ref(null);
    const loading = ref(true);
    const isPlaying = ref(false);
    const error = ref(null);
    const percent = ref(0);
    const usersWithAllAnswers = ref([]);
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    const countdown = ref(0);
    const countdowning = ref(false);
    const triggerUserGesture = () => {
      console.log("User Gesture");
      audio.play();
    };
    onMounted(() => {
      audio.muted = true;
      window.addEventListener("volume-localstorage-changed", (event) => {
        audio.volume = event.detail.volume;
      });
      Echo.channel(props.channel).listen("TrackPlayed", (e) => {
        console.log("Track played");
        track.value = e.track;
        play();
      }).listen("TrackEnded", (e) => {
        console.log("Track ended");
        usersWithAllAnswers.value = [];
        stop();
        startCountdown();
      }).listen("TrackPaused", () => {
        pause();
      }).listen("TrackResumed", () => {
        resume();
      }).listen("UserHasFoundAllTheAnswers", (e) => {
        usersWithAllAnswers.value.push(e.user);
      });
    });
    onUnmounted(() => {
      stop();
      Echo.leave(props.channel);
    });
    const play = () => {
      if (isPlaying.value) {
        stop();
      }
      loading.value = true;
      error.value = null;
      isPlaying.value = true;
      audio.src = track.value.preview_url;
      audio.crossOrigin = "anonymous";
      audio.load();
      audio.muted = false;
      audio.addEventListener("error", () => {
        error.value = audio.error.message;
        isPlaying.value = false;
      });
      audio.addEventListener("canplaythrough", () => {
        loading.value = false;
        if (isIOS) {
          console.log("iOS Player");
          audio.pause();
          audio.currentTime = 0;
          audio.volume = localStorage.getItem("volume");
          audio.play();
        } else {
          audio.play();
        }
      });
      audio.addEventListener("timeupdate", () => {
        emit("track:currentTime", audio.currentTime);
        percent.value = parseInt(100 / props.room.track_duration * (audio.currentTime + 0.25));
      });
      audio.addEventListener("ended", () => {
        isPlaying.value = false;
        loading.value = true;
        emit("track:ended", props.track);
      });
    };
    const pause = () => {
      audio.pause();
      emit("track:paused", props.track);
    };
    const resume = () => {
      audio.play();
    };
    const stop = () => {
      audio.pause();
      emit("track:stopped", props.track);
    };
    const startCountdown = () => {
      countdown.value = parseInt(props.room.pause_between_tracks);
      countdowning.value = true;
      let interval = setInterval(() => {
        if (countdown.value === -1) {
          clearInterval(interval);
          countdowning.value = false;
        } else {
          countdown.value--;
        }
      }, 1e3);
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createBaseVNode("div", _hoisted_1, [
          usersWithAllAnswers.value.length ? (openBlock(), createBlock(TransitionGroup, {
            key: 0,
            name: "list",
            tag: "ul"
          }, {
            default: withCtx(() => [
              (openBlock(true), createElementBlock(Fragment, null, renderList(usersWithAllAnswers.value, (user) => {
                return openBlock(), createElementBlock("li", {
                  key: user.id,
                  class: "absolute -top-8 z-20 rounded bg-teal-600 p-1 text-xs text-white",
                  style: normalizeStyle("left:calc(" + 100 / props.room.track_duration * user.time + "% - 0.25rem)")
                }, [
                  createTextVNode(toDisplayString(user.name) + " ", 1),
                  _hoisted_2
                ], 4);
              }), 128))
            ]),
            _: 1
          })) : createCommentVNode("", true),
          error.value ? (openBlock(), createElementBlock("div", _hoisted_3, toDisplayString(error.value), 1)) : loading.value && !countdowning.value ? (openBlock(), createElementBlock("div", _hoisted_4, toDisplayString(_ctx.__("Loading")), 1)) : countdowning.value && countdown.value != -1 ? (openBlock(), createElementBlock("div", _hoisted_5, [
            createBaseVNode("div", _hoisted_6, [
              createBaseVNode("div", {
                class: "flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear",
                style: normalizeStyle("width:" + countdown.value / parseInt(props.room.pause_between_tracks) * 100 + "%")
              }, [
                createBaseVNode("span", _hoisted_7, "Prochain extrait dans " + toDisplayString(countdown.value), 1)
              ], 4)
            ])
          ])) : (openBlock(), createElementBlock("div", _hoisted_8, [
            createBaseVNode("div", {
              class: "absolute top-0 left-0 z-10 h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-red-600 to-transparent transition-all duration-500 ease-linear",
              style: normalizeStyle("width:" + percent.value + "%; max-width: 18%")
            }, null, 4),
            createBaseVNode("div", {
              class: "shine absolute h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-purple-300 to-purple-400 transition-all duration-500 ease-linear",
              style: normalizeStyle("width:" + percent.value + "%")
            }, null, 4)
          ]))
        ]),
        createVNode(_sfc_main$1, { onPlay: triggerUserGesture })
      ], 64);
    };
  }
};
export {
  _sfc_main as default
};
