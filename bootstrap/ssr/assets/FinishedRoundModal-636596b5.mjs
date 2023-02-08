import { ref, watch, onMounted, mergeProps, withCtx, createVNode, toDisplayString, openBlock, createBlock, Fragment, renderList, createTextVNode, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderStyle, ssrRenderList } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./Modal-61e7836d.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import _sfc_main$2 from "./Podium-31a549cb.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "FinishedRoundModal",
  __ssrInlineRender: true,
  props: {
    round: Object,
    users_podium: Array,
    teams_podium: Array,
    show: Boolean
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    const countdown = ref(parseInt(props.round.room.pause_between_rounds));
    const users_results = ref(null);
    const teams_results = ref(null);
    watch(
      () => props.round,
      (value) => {
        countdown.value = parseInt(props.round.room.pause_between_rounds);
      }
    );
    watch(
      () => props.show,
      (value) => {
        if (value) {
          startCountdown();
        }
      }
    );
    watch(
      () => props.users_podium,
      (value) => {
        if (value) {
          users_results.value = value;
        }
      }
    );
    watch(
      () => props.teams_podium,
      (value) => {
        if (value) {
          teams_results.value = value;
        }
      }
    );
    onMounted(() => {
      startCountdown();
    });
    const startCountdown = () => {
      let interval = setInterval(() => {
        if (countdown.value === 0) {
          clearInterval(interval);
        } else {
          countdown.value--;
        }
      }, 1e3);
    };
    const close = () => {
      emit("close");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, mergeProps({
        show: __props.show,
        onClose: close
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h2${_scopeId2}>${ssrInterpolate(_ctx.__("Round finished"))}</h2>`);
                } else {
                  return [
                    createVNode("h2", null, toDisplayString(_ctx.__("Round finished")), 1)
                  ];
                }
              }),
              footer: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex w-full items-center gap-6"${_scopeId2}><div class="flex flex-grow flex-col"${_scopeId2}><div class="relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200"${_scopeId2}><div class="flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear" style="${ssrRenderStyle("width:" + countdown.value / parseInt(props.round.room.pause_between_rounds) * 100 + "%")}"${_scopeId2}><span class="absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600"${_scopeId2}>Prochaine partie dans ${ssrInterpolate(countdown.value)}</span></div></div></div><div class="ml-auto flex items-center"${_scopeId2}><button type="button" class="btn-secondary mr-2"${_scopeId2}>${ssrInterpolate(_ctx.__("Close"))}</button></div></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex w-full items-center gap-6" }, [
                      createVNode("div", { class: "flex flex-grow flex-col" }, [
                        createVNode("div", { class: "relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200" }, [
                          createVNode("div", {
                            class: "flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear",
                            style: "width:" + countdown.value / parseInt(props.round.room.pause_between_rounds) * 100 + "%"
                          }, [
                            createVNode("span", { class: "absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600" }, "Prochaine partie dans " + toDisplayString(countdown.value), 1)
                          ], 4)
                        ])
                      ]),
                      createVNode("div", { class: "ml-auto flex items-center" }, [
                        createVNode("button", {
                          type: "button",
                          class: "btn-secondary mr-2",
                          onClick: ($event) => _ctx.$emit("close")
                        }, toDisplayString(_ctx.__("Close")), 9, ["onClick"])
                      ])
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex justify-between"${_scopeId2}>`);
                  if (users_results.value && users_results.value.length) {
                    _push3(`<div class="w-full"${_scopeId2}><h3 class="mb-2 py-2 text-center text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Ranking"))}</h3>`);
                    _push3(ssrRenderComponent(_sfc_main$2, { list: users_results.value }, null, _parent3, _scopeId2));
                    _push3(`<ul class="max-h-48 overflow-auto"${_scopeId2}><!--[-->`);
                    ssrRenderList(users_results.value, (result, index) => {
                      _push3(`<li class="broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2"${_scopeId2}><span class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(index + 1)}</span><span class="flex-grow"${_scopeId2}>${ssrInterpolate(result.user.name)}</span><span${_scopeId2}>${ssrInterpolate(result.total)}<sup class="ml-1"${_scopeId2}>PTS</sup></span></li>`);
                    });
                    _push3(`<!--]--></ul></div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  if (teams_results.value && teams_results.value.length) {
                    _push3(`<div class="w-full"${_scopeId2}><h3 class="mb-2 py-2 text-center text-xl font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Teams"))}</h3>`);
                    _push3(ssrRenderComponent(_sfc_main$2, { list: teams_results.value }, null, _parent3, _scopeId2));
                    _push3(`<ul class="max-h-48 overflow-auto"${_scopeId2}><!--[-->`);
                    ssrRenderList(teams_results.value, (result, index) => {
                      _push3(`<li class="broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2"${_scopeId2}><span class="text-xl font-bold"${_scopeId2}>${ssrInterpolate(index + 1)}</span><span class="flex-grow"${_scopeId2}>${ssrInterpolate(result.team.name)}</span><span${_scopeId2}>${ssrInterpolate(result.total)}<sup class="ml-1"${_scopeId2}>PTS</sup></span></li>`);
                    });
                    _push3(`<!--]--></ul></div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`</div>`);
                  if (users_results.value && !users_results.value.length && teams_results.value && !teams_results.value.length) {
                    _push3(`<div${_scopeId2}>${ssrInterpolate(_ctx.__("No scores"))}</div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    createVNode("div", { class: "flex justify-between" }, [
                      users_results.value && users_results.value.length ? (openBlock(), createBlock("div", {
                        key: 0,
                        class: "w-full"
                      }, [
                        createVNode("h3", { class: "mb-2 py-2 text-center text-xl font-bold" }, toDisplayString(_ctx.__("Ranking")), 1),
                        createVNode(_sfc_main$2, { list: users_results.value }, null, 8, ["list"]),
                        createVNode("ul", { class: "max-h-48 overflow-auto" }, [
                          (openBlock(true), createBlock(Fragment, null, renderList(users_results.value, (result, index) => {
                            return openBlock(), createBlock("li", { class: "broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2" }, [
                              createVNode("span", { class: "text-xl font-bold" }, toDisplayString(index + 1), 1),
                              createVNode("span", { class: "flex-grow" }, toDisplayString(result.user.name), 1),
                              createVNode("span", null, [
                                createTextVNode(toDisplayString(result.total), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])) : createCommentVNode("", true),
                      teams_results.value && teams_results.value.length ? (openBlock(), createBlock("div", {
                        key: 1,
                        class: "w-full"
                      }, [
                        createVNode("h3", { class: "mb-2 py-2 text-center text-xl font-bold" }, toDisplayString(_ctx.__("Teams")), 1),
                        createVNode(_sfc_main$2, { list: teams_results.value }, null, 8, ["list"]),
                        createVNode("ul", { class: "max-h-48 overflow-auto" }, [
                          (openBlock(true), createBlock(Fragment, null, renderList(teams_results.value, (result, index) => {
                            return openBlock(), createBlock("li", { class: "broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2" }, [
                              createVNode("span", { class: "text-xl font-bold" }, toDisplayString(index + 1), 1),
                              createVNode("span", { class: "flex-grow" }, toDisplayString(result.team.name), 1),
                              createVNode("span", null, [
                                createTextVNode(toDisplayString(result.total), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])) : createCommentVNode("", true)
                    ]),
                    users_results.value && !users_results.value.length && teams_results.value && !teams_results.value.length ? (openBlock(), createBlock("div", { key: 0 }, toDisplayString(_ctx.__("No scores")), 1)) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, null, {
                header: withCtx(() => [
                  createVNode("h2", null, toDisplayString(_ctx.__("Round finished")), 1)
                ]),
                footer: withCtx(() => [
                  createVNode("div", { class: "flex w-full items-center gap-6" }, [
                    createVNode("div", { class: "flex flex-grow flex-col" }, [
                      createVNode("div", { class: "relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200" }, [
                        createVNode("div", {
                          class: "flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear",
                          style: "width:" + countdown.value / parseInt(props.round.room.pause_between_rounds) * 100 + "%"
                        }, [
                          createVNode("span", { class: "absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600" }, "Prochaine partie dans " + toDisplayString(countdown.value), 1)
                        ], 4)
                      ])
                    ]),
                    createVNode("div", { class: "ml-auto flex items-center" }, [
                      createVNode("button", {
                        type: "button",
                        class: "btn-secondary mr-2",
                        onClick: ($event) => _ctx.$emit("close")
                      }, toDisplayString(_ctx.__("Close")), 9, ["onClick"])
                    ])
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("div", { class: "flex justify-between" }, [
                    users_results.value && users_results.value.length ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "w-full"
                    }, [
                      createVNode("h3", { class: "mb-2 py-2 text-center text-xl font-bold" }, toDisplayString(_ctx.__("Ranking")), 1),
                      createVNode(_sfc_main$2, { list: users_results.value }, null, 8, ["list"]),
                      createVNode("ul", { class: "max-h-48 overflow-auto" }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(users_results.value, (result, index) => {
                          return openBlock(), createBlock("li", { class: "broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2" }, [
                            createVNode("span", { class: "text-xl font-bold" }, toDisplayString(index + 1), 1),
                            createVNode("span", { class: "flex-grow" }, toDisplayString(result.user.name), 1),
                            createVNode("span", null, [
                              createTextVNode(toDisplayString(result.total), 1),
                              createVNode("sup", { class: "ml-1" }, "PTS")
                            ])
                          ]);
                        }), 256))
                      ])
                    ])) : createCommentVNode("", true),
                    teams_results.value && teams_results.value.length ? (openBlock(), createBlock("div", {
                      key: 1,
                      class: "w-full"
                    }, [
                      createVNode("h3", { class: "mb-2 py-2 text-center text-xl font-bold" }, toDisplayString(_ctx.__("Teams")), 1),
                      createVNode(_sfc_main$2, { list: teams_results.value }, null, 8, ["list"]),
                      createVNode("ul", { class: "max-h-48 overflow-auto" }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(teams_results.value, (result, index) => {
                          return openBlock(), createBlock("li", { class: "broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2" }, [
                            createVNode("span", { class: "text-xl font-bold" }, toDisplayString(index + 1), 1),
                            createVNode("span", { class: "flex-grow" }, toDisplayString(result.team.name), 1),
                            createVNode("span", null, [
                              createTextVNode(toDisplayString(result.total), 1),
                              createVNode("sup", { class: "ml-1" }, "PTS")
                            ])
                          ]);
                        }), 256))
                      ])
                    ])) : createCommentVNode("", true)
                  ]),
                  users_results.value && !users_results.value.length && teams_results.value && !teams_results.value.length ? (openBlock(), createBlock("div", { key: 0 }, toDisplayString(_ctx.__("No scores")), 1)) : createCommentVNode("", true)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/FinishedRoundModal.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
