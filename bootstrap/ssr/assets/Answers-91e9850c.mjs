import { ref, onMounted, onUnmounted, withCtx, createVNode, openBlock, createBlock, toDisplayString, createTextVNode, createCommentVNode, unref, TransitionGroup, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttrs, ssrRenderList, ssrRenderAttr, ssrRenderClass } from "vue/server-renderer";
import { usePage } from "@inertiajs/vue3";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { _ as _sfc_main$1 } from "./Icon-4a47e6e0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "Answers",
  __ssrInlineRender: true,
  props: {
    users: Array,
    channel: String
  },
  setup(__props) {
    const props = __props;
    const user = usePage().props.auth.user;
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full items-center justify-between"${_scopeId}><h3 class="text-xl font-bold"${_scopeId}>Playlist</h3>`);
            if (round.value) {
              _push2(`<span class="text-xl font-bold text-neutral-500"${_scopeId}><span class="text-neutral-700"${_scopeId}>${ssrInterpolate(round.value.current)}</span> / ${ssrInterpolate(round.value.tracks.length)}</span>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full items-center justify-between" }, [
                createVNode("h3", { class: "text-xl font-bold" }, "Playlist"),
                round.value ? (openBlock(), createBlock("span", {
                  key: 0,
                  class: "text-xl font-bold text-neutral-500"
                }, [
                  createVNode("span", { class: "text-neutral-700" }, toDisplayString(round.value.current), 1),
                  createTextVNode(" / " + toDisplayString(round.value.tracks.length), 1)
                ])) : createCommentVNode("", true)
              ])
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<ul${ssrRenderAttrs({
              name: "flip-list",
              class: "h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96"
            })}>`);
            ssrRenderList(tracks.value, (track) => {
              _push2(`<li class="mb-2 flex rounded bg-neutral-900 opacity-70"${_scopeId}><div class="p-2"${_scopeId}><img${ssrRenderAttr("src", track.artwork_url)}${ssrRenderAttr("alt", track.album_name)} class="h-20 w-auto rounded"${_scopeId}></div><div class="flex-grow p-2"${_scopeId}><div class="flex h-full justify-between"${_scopeId}><ul${_scopeId}><!--[-->`);
              ssrRenderList(track.answers, (answer) => {
                var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j;
                _push2(`<li class="mb-2 flex items-start overflow-ellipsis text-sm"${_scopeId}>`);
                if (_ctx.userAnswer = userAnswers.value.filter((x) => x.track_id === track.id && x.answers.filter((a) => a.id === answer.id)[0])[0]) {
                  _push2(`<div class="flex items-center gap-2"${_scopeId}><div class="${ssrRenderClass([{ "mr-1": ((_b = (_a = _ctx.userAnswer.answers) == null ? void 0 : _a.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _b.order) < 4 }, "relative flex items-center gap-1 rounded bg-purple-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white"])}"${_scopeId}>`);
                  if ((_d = (_c = _ctx.userAnswer.answers) == null ? void 0 : _c.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _d.speedBonus) {
                    _push2(`<span class="text-white"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3"${_scopeId}><path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd"${_scopeId}></path></svg></span>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(` ${ssrInterpolate(_ctx.__((_f = (_e = _ctx.userAnswer.answers) == null ? void 0 : _e.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _f.name))} `);
                  if (((_h = (_g = _ctx.userAnswer.answers) == null ? void 0 : _g.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _h.order) < 4) {
                    _push2(`<span class="absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-purple-500 text-[11px] text-neutral-100"${_scopeId}>${ssrInterpolate((_j = (_i = _ctx.userAnswer.answers) == null ? void 0 : _i.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _j.order)}</span>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`</div> ${ssrInterpolate(answer.value)}</div>`);
                } else {
                  _push2(`<div class="flex items-center gap-2"${_scopeId}><span class="flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white"${_scopeId}>${ssrInterpolate(_ctx.__(answer.type.name))}</span> ${ssrInterpolate(answer.value)}</div>`);
                }
                _push2(`</li>`);
              });
              _push2(`<!--]--></ul><div class="hidden flex-col items-end lg:flex"${_scopeId}>`);
              if (track.track_url) {
                _push2(`<a class="flex items-center whitespace-nowrap text-xs opacity-50 hover:opacity-90"${ssrRenderAttr("href", track.track_url)} target="_blank"${ssrRenderAttr("title", _ctx.__("Listen on") + " " + track.provider)}${_scopeId}>${ssrInterpolate(_ctx.__("Listen on"))} `);
                _push2(ssrRenderComponent(_sfc_main$1, {
                  name: track.provider,
                  class: "ml-1 h-5 w-5"
                }, null, _parent2, _scopeId));
                _push2(`</a>`);
              } else {
                _push2(`<!---->`);
              }
              if (unref(user)) {
                _push2(`<div class="mt-4 flex items-center text-xs"${_scopeId}><button class="mr-4 flex items-center"${ssrRenderAttr("title", _ctx.__("Like"))}${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$1, {
                  name: "thumb-up",
                  class: "mr-1 h-5 w-5"
                }, null, _parent2, _scopeId));
                _push2(` ${ssrInterpolate(track.upvotes)}</button><button class="flex items-center"${ssrRenderAttr("title", _ctx.__("Don't like"))}${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$1, {
                  name: "thumb-down",
                  class: "mr-1 h-5 w-5"
                }, null, _parent2, _scopeId));
                _push2(` ${ssrInterpolate(track.downvotes)}</button></div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div></div></div></li>`);
            });
            _push2(`</ul>`);
          } else {
            return [
              createVNode(TransitionGroup, {
                name: "flip-list",
                tag: "ul",
                class: "h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96"
              }, {
                default: withCtx(() => [
                  (openBlock(true), createBlock(Fragment, null, renderList(tracks.value, (track) => {
                    return openBlock(), createBlock("li", {
                      key: track.id,
                      class: "mb-2 flex rounded bg-neutral-900 opacity-70"
                    }, [
                      createVNode("div", { class: "p-2" }, [
                        createVNode("img", {
                          src: track.artwork_url,
                          alt: track.album_name,
                          class: "h-20 w-auto rounded"
                        }, null, 8, ["src", "alt"])
                      ]),
                      createVNode("div", { class: "flex-grow p-2" }, [
                        createVNode("div", { class: "flex h-full justify-between" }, [
                          createVNode("ul", null, [
                            (openBlock(true), createBlock(Fragment, null, renderList(track.answers, (answer) => {
                              var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j;
                              return openBlock(), createBlock("li", {
                                key: answer.id,
                                class: "mb-2 flex items-start overflow-ellipsis text-sm"
                              }, [
                                (_ctx.userAnswer = userAnswers.value.filter((x) => x.track_id === track.id && x.answers.filter((a) => a.id === answer.id)[0])[0]) ? (openBlock(), createBlock("div", {
                                  key: 0,
                                  class: "flex items-center gap-2"
                                }, [
                                  createVNode("div", {
                                    class: ["relative flex items-center gap-1 rounded bg-purple-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white", { "mr-1": ((_b = (_a = _ctx.userAnswer.answers) == null ? void 0 : _a.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _b.order) < 4 }]
                                  }, [
                                    ((_d = (_c = _ctx.userAnswer.answers) == null ? void 0 : _c.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _d.speedBonus) ? (openBlock(), createBlock("span", {
                                      key: 0,
                                      class: "text-white"
                                    }, [
                                      (openBlock(), createBlock("svg", {
                                        xmlns: "http://www.w3.org/2000/svg",
                                        viewBox: "0 0 20 20",
                                        fill: "currentColor",
                                        class: "h-3 w-3"
                                      }, [
                                        createVNode("path", {
                                          "fill-rule": "evenodd",
                                          d: "M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z",
                                          "clip-rule": "evenodd"
                                        })
                                      ]))
                                    ])) : createCommentVNode("", true),
                                    createTextVNode(" " + toDisplayString(_ctx.__((_f = (_e = _ctx.userAnswer.answers) == null ? void 0 : _e.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _f.name)) + " ", 1),
                                    ((_h = (_g = _ctx.userAnswer.answers) == null ? void 0 : _g.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _h.order) < 4 ? (openBlock(), createBlock("span", {
                                      key: 1,
                                      class: "absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-purple-500 text-[11px] text-neutral-100"
                                    }, toDisplayString((_j = (_i = _ctx.userAnswer.answers) == null ? void 0 : _i.filter((a) => a.id === answer.id)[0]) == null ? void 0 : _j.order), 1)) : createCommentVNode("", true)
                                  ], 2),
                                  createTextVNode(" " + toDisplayString(answer.value), 1)
                                ])) : (openBlock(), createBlock("div", {
                                  key: 1,
                                  class: "flex items-center gap-2"
                                }, [
                                  createVNode("span", { class: "flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white" }, toDisplayString(_ctx.__(answer.type.name)), 1),
                                  createTextVNode(" " + toDisplayString(answer.value), 1)
                                ]))
                              ]);
                            }), 128))
                          ]),
                          createVNode("div", { class: "hidden flex-col items-end lg:flex" }, [
                            track.track_url ? (openBlock(), createBlock("a", {
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
                            ], 8, ["href", "title"])) : createCommentVNode("", true),
                            unref(user) ? (openBlock(), createBlock("div", {
                              key: 1,
                              class: "mt-4 flex items-center text-xs"
                            }, [
                              createVNode("button", {
                                onClick: ($event) => voteTrackUp(track),
                                class: "mr-4 flex items-center",
                                title: _ctx.__("Like")
                              }, [
                                createVNode(_sfc_main$1, {
                                  name: "thumb-up",
                                  class: "mr-1 h-5 w-5"
                                }),
                                createTextVNode(" " + toDisplayString(track.upvotes), 1)
                              ], 8, ["onClick", "title"]),
                              createVNode("button", {
                                onClick: ($event) => voteTrackDown(track),
                                class: "flex items-center",
                                title: _ctx.__("Don't like")
                              }, [
                                createVNode(_sfc_main$1, {
                                  name: "thumb-down",
                                  class: "mr-1 h-5 w-5"
                                }),
                                createTextVNode(" " + toDisplayString(track.downvotes), 1)
                              ], 8, ["onClick", "title"])
                            ])) : createCommentVNode("", true)
                          ])
                        ])
                      ])
                    ]);
                  }), 128))
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
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/Answers.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
