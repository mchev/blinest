import { ref, watch, onMounted, onUnmounted, withCtx, unref, createVNode, toDisplayString, openBlock, createBlock, createCommentVNode, TransitionGroup, Fragment, renderList, createTextVNode, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { usePage } from "@inertiajs/vue3";
import { C as Card } from "./Card-ee13c838.mjs";
import { _ as _sfc_main$1 } from "./Icon-4a47e6e0.mjs";
import _sfc_main$2 from "./PodiumModal-6b4b587a.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Modal-61e7836d.mjs";
import "./Spinner-ec1c59c5.mjs";
const _sfc_main = {
  __name: "Ranking",
  __ssrInlineRender: true,
  props: {
    room: Object,
    users: Array,
    channel: String,
    data: Object
  },
  setup(__props) {
    const props = __props;
    const me = usePage().props.auth.user;
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(_attrs)}>`);
      _push(ssrRenderComponent(Card, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full items-center justify-between"${_scopeId}><h3 class="text-xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Ranking"))}</h3><div${_scopeId}><sup class="mr-2 text-xs"${_scopeId}>${ssrInterpolate(userList.value.length)}</sup>`);
            if (unref(me)) {
              _push2(`<button type="button"${ssrRenderAttr("title", _ctx.__("Show rankings for this room"))}${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$1, {
                name: "podium",
                class: "mr-2 h-8 w-8"
              }, null, _parent2, _scopeId));
              _push2(`</button>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full items-center justify-between" }, [
                createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Ranking")), 1),
                createVNode("div", null, [
                  createVNode("sup", { class: "mr-2 text-xs" }, toDisplayString(userList.value.length), 1),
                  unref(me) ? (openBlock(), createBlock("button", {
                    key: 0,
                    type: "button",
                    onClick: ($event) => showPodiumModal.value = true,
                    title: _ctx.__("Show rankings for this room")
                  }, [
                    createVNode(_sfc_main$1, {
                      name: "podium",
                      class: "mr-2 h-8 w-8"
                    })
                  ], 8, ["onClick", "title"])) : createCommentVNode("", true)
                ])
              ])
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96"${_scopeId}><ul${ssrRenderAttrs({ name: "flip-list" })}>`);
            ssrRenderList(userList.value, (user, index) => {
              _push2(`<li class="${ssrRenderClass([{ "bg-neutral-700": unref(me) && unref(me).id === user.id }, "flex items-center gap-4 rounded border-b border-neutral-600 px-2 py-4"])}"${_scopeId}><div class="flex items-center justify-center text-xl font-bold"${_scopeId}>${ssrInterpolate(index + 1)}</div><div class="flex items-center"${_scopeId}><img${ssrRenderAttr("src", user.photo)}${ssrRenderAttr("alt", user.name)} class="h-12 w-12 rounded-full shadow-lg"${_scopeId}></div><div class="flex flex-grow flex-col"${_scopeId}><div class="mb-2"${_scopeId}>${ssrInterpolate(user.name)} `);
              if (user.team) {
                _push2(`<sup class="text-[9px] uppercase"${_scopeId}>[${ssrInterpolate(user.team.name)}]</sup>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div><div class="flex flex-wrap items-center gap-1"${_scopeId}>`);
              if (user.score) {
                _push2(`<!--[-->`);
                ssrRenderList(user.score.answers, (userAnswer) => {
                  _push2(`<span class="${ssrRenderClass([{ "mr-2": userAnswer.order < 4 }, "relative flex items-center rounded bg-purple-500 px-1 text-[11px] font-bold uppercase text-white"])}"${_scopeId}>`);
                  if (userAnswer.speedBonus) {
                    _push2(`<span class="mr-1 text-white"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3"${_scopeId}><path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd"${_scopeId}></path></svg></span>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(` ${ssrInterpolate(_ctx.__(userAnswer.name))} `);
                  if (userAnswer.order < 4) {
                    _push2(`<span class="absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-purple-500 text-[11px] text-neutral-100"${_scopeId}>${ssrInterpolate(userAnswer.order)}</span>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`</span>`);
                });
                _push2(`<!--]-->`);
              } else {
                _push2(`<!---->`);
              }
              if (track.value) {
                _push2(`<!--[-->`);
                ssrRenderList(track.value.answers, (answer) => {
                  var _a;
                  _push2(`<span${_scopeId}>`);
                  if (!((_a = user == null ? void 0 : user.score) == null ? void 0 : _a.answers.find((x) => x.id === answer.id))) {
                    _push2(`<span class="relative flex rounded bg-neutral-300 px-1 text-[11px] font-bold uppercase text-neutral-500 text-white"${_scopeId}>${ssrInterpolate(_ctx.__(answer.name))}</span>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`</span>`);
                });
                _push2(`<!--]-->`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div></div><div${_scopeId}>${ssrInterpolate((user == null ? void 0 : user.score) ? user.score.total : 0)} <sup${_scopeId}>PTS</sup></div></li>`);
            });
            _push2(`</ul></div>`);
          } else {
            return [
              createVNode("div", { class: "h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96" }, [
                createVNode(TransitionGroup, {
                  name: "flip-list",
                  tag: "ul"
                }, {
                  default: withCtx(() => [
                    (openBlock(true), createBlock(Fragment, null, renderList(userList.value, (user, index) => {
                      return openBlock(), createBlock("li", {
                        key: user.id,
                        class: ["flex items-center gap-4 rounded border-b border-neutral-600 px-2 py-4", { "bg-neutral-700": unref(me) && unref(me).id === user.id }]
                      }, [
                        createVNode("div", { class: "flex items-center justify-center text-xl font-bold" }, toDisplayString(index + 1), 1),
                        createVNode("div", { class: "flex items-center" }, [
                          createVNode("img", {
                            src: user.photo,
                            alt: user.name,
                            class: "h-12 w-12 rounded-full shadow-lg"
                          }, null, 8, ["src", "alt"])
                        ]),
                        createVNode("div", { class: "flex flex-grow flex-col" }, [
                          createVNode("div", { class: "mb-2" }, [
                            createTextVNode(toDisplayString(user.name) + " ", 1),
                            user.team ? (openBlock(), createBlock("sup", {
                              key: 0,
                              class: "text-[9px] uppercase"
                            }, "[" + toDisplayString(user.team.name) + "]", 1)) : createCommentVNode("", true)
                          ]),
                          createVNode("div", { class: "flex flex-wrap items-center gap-1" }, [
                            user.score ? (openBlock(true), createBlock(Fragment, { key: 0 }, renderList(user.score.answers, (userAnswer) => {
                              return openBlock(), createBlock("span", {
                                class: ["relative flex items-center rounded bg-purple-500 px-1 text-[11px] font-bold uppercase text-white", { "mr-2": userAnswer.order < 4 }]
                              }, [
                                userAnswer.speedBonus ? (openBlock(), createBlock("span", {
                                  key: 0,
                                  class: "mr-1 text-white"
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
                                createTextVNode(" " + toDisplayString(_ctx.__(userAnswer.name)) + " ", 1),
                                userAnswer.order < 4 ? (openBlock(), createBlock("span", {
                                  key: 1,
                                  class: "absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-purple-500 text-[11px] text-neutral-100"
                                }, toDisplayString(userAnswer.order), 1)) : createCommentVNode("", true)
                              ], 2);
                            }), 256)) : createCommentVNode("", true),
                            track.value ? (openBlock(true), createBlock(Fragment, { key: 1 }, renderList(track.value.answers, (answer) => {
                              var _a;
                              return openBlock(), createBlock("span", null, [
                                !((_a = user == null ? void 0 : user.score) == null ? void 0 : _a.answers.find((x) => x.id === answer.id)) ? (openBlock(), createBlock("span", {
                                  key: 0,
                                  class: "relative flex rounded bg-neutral-300 px-1 text-[11px] font-bold uppercase text-neutral-500 text-white"
                                }, toDisplayString(_ctx.__(answer.name)), 1)) : createCommentVNode("", true)
                              ]);
                            }), 256)) : createCommentVNode("", true)
                          ])
                        ]),
                        createVNode("div", null, [
                          createTextVNode(toDisplayString((user == null ? void 0 : user.score) ? user.score.total : 0) + " ", 1),
                          createVNode("sup", null, "PTS")
                        ])
                      ], 2);
                    }), 128))
                  ]),
                  _: 1
                })
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      if (unref(me) && showPodiumModal.value) {
        _push(ssrRenderComponent(_sfc_main$2, {
          room: __props.room,
          show: showPodiumModal.value,
          onClose: ($event) => showPodiumModal.value = false
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/Ranking.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
