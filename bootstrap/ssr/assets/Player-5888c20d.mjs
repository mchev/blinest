import { ssrRenderComponent, ssrInterpolate, ssrRenderAttrs, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
import { ref, onMounted, mergeProps, withCtx, createVNode, openBlock, createBlock, createTextVNode, toDisplayString, useSSRContext, onUnmounted } from "vue";
import { _ as _sfc_main$2 } from "./Modal-61e7836d.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main$1 = {
  __name: "UserGestureModal",
  __ssrInlineRender: true,
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$2, mergeProps({
        show: show.value,
        onClose: ($event) => show.value = false
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h3 class="flex items-center pl-4 text-xl font-bold"${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"${_scopeId2}></path></svg> Autoriser la lecture </h3>`);
                } else {
                  return [
                    createVNode("h3", { class: "flex items-center pl-4 text-xl font-bold" }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "mr-2 h-6 w-6",
                        fill: "none",
                        viewBox: "0 0 24 24",
                        stroke: "currentColor",
                        "stroke-width": "2"
                      }, [
                        createVNode("path", {
                          "stroke-linecap": "round",
                          "stroke-linejoin": "round",
                          d: "M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"
                        })
                      ])),
                      createTextVNode(" Autoriser la lecture ")
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="p-4"${_scopeId2}>${ssrInterpolate(_ctx.__("The browser needs your permission to start playing audio files."))} <p class="mt-4 flex items-start rounded bg-neutral-200 p-2 text-sm text-neutral-400"${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"${_scopeId2}></path></svg><span${_scopeId2}>${ssrInterpolate(_ctx.__("This message will appear every time you go directly to a room without going through the homepage."))}</span></p><div class="mt-8 flex justify-end"${_scopeId2}><button class="btn-primary"${_scopeId2}>${ssrInterpolate(_ctx.__("Let's go!"))}</button></div></div>`);
                } else {
                  return [
                    createVNode("div", { class: "p-4" }, [
                      createTextVNode(toDisplayString(_ctx.__("The browser needs your permission to start playing audio files.")) + " ", 1),
                      createVNode("p", { class: "mt-4 flex items-start rounded bg-neutral-200 p-2 text-sm text-neutral-400" }, [
                        (openBlock(), createBlock("svg", {
                          xmlns: "http://www.w3.org/2000/svg",
                          class: "mr-2 h-10 w-10",
                          fill: "none",
                          viewBox: "0 0 24 24",
                          stroke: "currentColor",
                          "stroke-width": "2"
                        }, [
                          createVNode("path", {
                            "stroke-linecap": "round",
                            "stroke-linejoin": "round",
                            d: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                          })
                        ])),
                        createVNode("span", null, toDisplayString(_ctx.__("This message will appear every time you go directly to a room without going through the homepage.")), 1)
                      ]),
                      createVNode("div", { class: "mt-8 flex justify-end" }, [
                        createVNode("button", {
                          class: "btn-primary",
                          onClick: triggerUserGesture
                        }, toDisplayString(_ctx.__("Let's go!")), 1)
                      ])
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, null, {
                header: withCtx(() => [
                  createVNode("h3", { class: "flex items-center pl-4 text-xl font-bold" }, [
                    (openBlock(), createBlock("svg", {
                      xmlns: "http://www.w3.org/2000/svg",
                      class: "mr-2 h-6 w-6",
                      fill: "none",
                      viewBox: "0 0 24 24",
                      stroke: "currentColor",
                      "stroke-width": "2"
                    }, [
                      createVNode("path", {
                        "stroke-linecap": "round",
                        "stroke-linejoin": "round",
                        d: "M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"
                      })
                    ])),
                    createTextVNode(" Autoriser la lecture ")
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("div", { class: "p-4" }, [
                    createTextVNode(toDisplayString(_ctx.__("The browser needs your permission to start playing audio files.")) + " ", 1),
                    createVNode("p", { class: "mt-4 flex items-start rounded bg-neutral-200 p-2 text-sm text-neutral-400" }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "mr-2 h-10 w-10",
                        fill: "none",
                        viewBox: "0 0 24 24",
                        stroke: "currentColor",
                        "stroke-width": "2"
                      }, [
                        createVNode("path", {
                          "stroke-linecap": "round",
                          "stroke-linejoin": "round",
                          d: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                        })
                      ])),
                      createVNode("span", null, toDisplayString(_ctx.__("This message will appear every time you go directly to a room without going through the homepage.")), 1)
                    ]),
                    createVNode("div", { class: "mt-8 flex justify-end" }, [
                      createVNode("button", {
                        class: "btn-primary",
                        onClick: triggerUserGesture
                      }, toDisplayString(_ctx.__("Let's go!")), 1)
                    ])
                  ])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UserGestureModal.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Player",
  __ssrInlineRender: true,
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><div id="player" class="relative flex h-4 w-full items-center rounded-t-lg bg-purple-200">`);
      if (usersWithAllAnswers.value.length) {
        _push(`<ul${ssrRenderAttrs({ name: "list" })}>`);
        ssrRenderList(usersWithAllAnswers.value, (user) => {
          _push(`<li class="absolute -top-8 z-20 rounded bg-teal-600 p-1 text-xs text-white" style="${ssrRenderStyle("left:calc(" + 100 / props.room.track_duration * user.time + "% - 0.25rem)")}">${ssrInterpolate(user.name)} <div class="absolute left-1 top-full mt-1 h-full h-0 w-full w-0 translate-y-[-50%] border-t-[8px] border-l-[8px] border-r-[8px] border-t-transparent border-l-transparent border-r-transparent border-t-teal-600"></div></li>`);
        });
        _push(`</ul>`);
      } else {
        _push(`<!---->`);
      }
      if (error.value) {
        _push(`<div class="flex h-4 w-full animate-pulse items-center justify-center rounded-t-lg text-red-500">${ssrInterpolate(error.value)}</div>`);
      } else if (loading.value && !countdowning.value) {
        _push(`<div class="flex h-4 w-full max-w-full animate-pulse items-center justify-center rounded-t-lg bg-purple-500">${ssrInterpolate(_ctx.__("Loading"))}</div>`);
      } else if (countdowning.value && countdown.value != -1) {
        _push(`<div class="flex max-w-full flex-grow flex-col"><div class="relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200"><div class="flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear" style="${ssrRenderStyle("width:" + countdown.value / parseInt(props.room.pause_between_tracks) * 100 + "%")}"><span class="absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600">Prochain extrait dans ${ssrInterpolate(countdown.value)}</span></div></div></div>`);
      } else {
        _push(`<div class="w-full max-w-full"><div class="absolute top-0 left-0 z-10 h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-red-600 to-transparent transition-all duration-500 ease-linear" style="${ssrRenderStyle("width:" + percent.value + "%; max-width: 18%")}"></div><div class="shine absolute h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-purple-300 to-purple-400 transition-all duration-500 ease-linear" style="${ssrRenderStyle("width:" + percent.value + "%")}"></div></div>`);
      }
      _push(`</div>`);
      _push(ssrRenderComponent(_sfc_main$1, { onPlay: triggerUserGesture }, null, _parent));
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/Player.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
