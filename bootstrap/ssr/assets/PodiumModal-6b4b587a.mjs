import { ref, onMounted, mergeProps, withCtx, createVNode, toDisplayString, createTextVNode, openBlock, createBlock, Fragment, renderList, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList } from "vue/server-renderer";
import "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./Modal-61e7836d.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import { S as Spinner } from "./Spinner-ec1c59c5.mjs";
import "./Icon-4a47e6e0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "PodiumModal",
  __ssrInlineRender: true,
  props: {
    room: Object,
    show: Boolean
  },
  setup(__props) {
    const props = __props;
    const loading = ref(true);
    const scores = ref(null);
    onMounted(() => {
      axios.get(`/rooms/${props.room.id}/scores`).then((response) => {
        loading.value = false;
        scores.value = response.data;
      });
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, mergeProps({
        show: __props.show,
        maxWidth: "5xl"
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="bg-neutral-800 text-sm"${_scopeId}><div class="flex items-center justify-between px-4 pt-2"${_scopeId}><h2 class="font-bold uppercase text-teal-500"${_scopeId}>${ssrInterpolate(__props.room.name)}</h2><button${ssrRenderAttr("title", _ctx.__("Close"))} class="hover:animate-[spin_.5s_ease-in-out] hover:text-white"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"${_scopeId}></path></svg></button></div>`);
            if (loading.value) {
              _push2(`<div class="flex w-full items-center justify-center p-12"${_scopeId}>`);
              _push2(ssrRenderComponent(Spinner, null, null, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              _push2(`<div class="grid grid-cols-1 xl:grid-cols-2"${_scopeId}>`);
              _push2(ssrRenderComponent(Card, { class: "m-4" }, {
                header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h3 class="font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("All-time"))}</h3><span class="rounded bg-teal-500 p-1 font-bold text-white"${_scopeId2}>${ssrInterpolate(scores.value.user.lifetime.score)}<sup class="ml-1"${_scopeId2}>PTS</sup></span></div>`);
                  } else {
                    return [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("All-time")), 1),
                        createVNode("span", { class: "rounded bg-teal-500 p-1 font-bold text-white" }, [
                          createTextVNode(toDisplayString(scores.value.user.lifetime.score), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])
                      ])
                    ];
                  }
                }),
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<table class="w-full"${_scopeId2}><thead${_scopeId2}><tr${_scopeId2}><th class="border-b-2 p-2 text-left"${_scopeId2}>#</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Name"))}</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Score"))}</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                    ssrRenderList(scores.value.lifetime, (score, index) => {
                      _push3(`<tr${_scopeId2}><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(index + 1)}</td><td class="truncate border-b p-2"${_scopeId2}>${ssrInterpolate(score.user.name)}</td><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(score.score)}<sup class="ml-1"${_scopeId2}>PTS</sup></td></tr>`);
                    });
                    _push3(`<!--]--></tbody></table>`);
                  } else {
                    return [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.lifetime, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.user.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.score), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(ssrRenderComponent(Card, { class: "m-4" }, {
                header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h3 class="font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Teams"))}</h3>`);
                    if (scores.value.user.team) {
                      _push3(`<span class="rounded bg-teal-500 p-1 font-bold text-white"${_scopeId2}>${ssrInterpolate(scores.value.user.team.total)}<sup class="ml-1"${_scopeId2}>PTS</sup></span>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    _push3(`</div>`);
                  } else {
                    return [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("Teams")), 1),
                        scores.value.user.team ? (openBlock(), createBlock("span", {
                          key: 0,
                          class: "rounded bg-teal-500 p-1 font-bold text-white"
                        }, [
                          createTextVNode(toDisplayString(scores.value.user.team.total), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])) : createCommentVNode("", true)
                      ])
                    ];
                  }
                }),
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<table class="w-full"${_scopeId2}><thead${_scopeId2}><tr${_scopeId2}><th class="border-b-2 p-2 text-left"${_scopeId2}>#</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Name"))}</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Score"))}</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                    ssrRenderList(scores.value.teams, (score, index) => {
                      _push3(`<tr${_scopeId2}><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(index + 1)}</td><td class="truncate border-b p-2"${_scopeId2}>${ssrInterpolate(score.team.name)}</td><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(score.score)}<sup class="ml-1"${_scopeId2}>PTS</sup></td></tr>`);
                    });
                    _push3(`<!--]--></tbody></table>`);
                  } else {
                    return [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.teams, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.team.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.score), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(ssrRenderComponent(Card, { class: "m-4" }, {
                header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h3 class="font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Last 7 days"))}</h3><span class="rounded bg-teal-500 p-1 font-bold text-white"${_scopeId2}>${ssrInterpolate(scores.value.user.week.total)}<sup class="ml-1"${_scopeId2}>PTS</sup></span></div>`);
                  } else {
                    return [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("Last 7 days")), 1),
                        createVNode("span", { class: "rounded bg-teal-500 p-1 font-bold text-white" }, [
                          createTextVNode(toDisplayString(scores.value.user.week.total), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])
                      ])
                    ];
                  }
                }),
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<table class="w-full"${_scopeId2}><thead${_scopeId2}><tr${_scopeId2}><th class="border-b-2 p-2 text-left"${_scopeId2}>#</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Name"))}</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Score"))}</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                    ssrRenderList(scores.value.week, (score, index) => {
                      _push3(`<tr${_scopeId2}><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(index + 1)}</td><td class="truncate border-b p-2"${_scopeId2}>${ssrInterpolate(score.user.name)}</td><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(score.total)}<sup class="ml-1"${_scopeId2}>PTS</sup></td></tr>`);
                    });
                    _push3(`<!--]--></tbody></table>`);
                  } else {
                    return [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.week, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.user.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.total), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(ssrRenderComponent(Card, { class: "m-4" }, {
                header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h3 class="font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("Last 30 days"))}</h3><span class="rounded bg-teal-500 p-1 font-bold text-white"${_scopeId2}>${ssrInterpolate(scores.value.user.month.total)}<sup class="ml-1"${_scopeId2}>PTS</sup></span></div>`);
                  } else {
                    return [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("Last 30 days")), 1),
                        createVNode("span", { class: "rounded bg-teal-500 p-1 font-bold text-white" }, [
                          createTextVNode(toDisplayString(scores.value.user.month.total), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])
                      ])
                    ];
                  }
                }),
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<table class="w-full"${_scopeId2}><thead${_scopeId2}><tr${_scopeId2}><th class="border-b-2 p-2 text-left"${_scopeId2}>#</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Name"))}</th><th class="border-b-2 p-2 text-left"${_scopeId2}>${ssrInterpolate(_ctx.__("Score"))}</th></tr></thead><tbody${_scopeId2}><!--[-->`);
                    ssrRenderList(scores.value.month, (score, index) => {
                      _push3(`<tr${_scopeId2}><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(index + 1)}</td><td class="truncate border-b p-2"${_scopeId2}>${ssrInterpolate(score.user.name)}</td><td class="border-b p-2"${_scopeId2}>${ssrInterpolate(score.total)}<sup class="ml-1"${_scopeId2}>PTS</sup></td></tr>`);
                    });
                    _push3(`<!--]--></tbody></table>`);
                  } else {
                    return [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.month, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.user.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.total), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</div>`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "bg-neutral-800 text-sm" }, [
                createVNode("div", { class: "flex items-center justify-between px-4 pt-2" }, [
                  createVNode("h2", { class: "font-bold uppercase text-teal-500" }, toDisplayString(__props.room.name), 1),
                  createVNode("button", {
                    onClick: ($event) => _ctx.$emit("close"),
                    title: _ctx.__("Close"),
                    class: "hover:animate-[spin_.5s_ease-in-out] hover:text-white"
                  }, [
                    (openBlock(), createBlock("svg", {
                      xmlns: "http://www.w3.org/2000/svg",
                      fill: "none",
                      viewBox: "0 0 24 24",
                      "stroke-width": "1.5",
                      stroke: "currentColor",
                      class: "h-6 w-6"
                    }, [
                      createVNode("path", {
                        "stroke-linecap": "round",
                        "stroke-linejoin": "round",
                        d: "M6 18L18 6M6 6l12 12"
                      })
                    ]))
                  ], 8, ["onClick", "title"])
                ]),
                loading.value ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "flex w-full items-center justify-center p-12"
                }, [
                  createVNode(Spinner)
                ])) : (openBlock(), createBlock("div", {
                  key: 1,
                  class: "grid grid-cols-1 xl:grid-cols-2"
                }, [
                  createVNode(Card, { class: "m-4" }, {
                    header: withCtx(() => [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("All-time")), 1),
                        createVNode("span", { class: "rounded bg-teal-500 p-1 font-bold text-white" }, [
                          createTextVNode(toDisplayString(scores.value.user.lifetime.score), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])
                      ])
                    ]),
                    default: withCtx(() => [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.lifetime, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.user.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.score), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ]),
                    _: 1
                  }),
                  createVNode(Card, { class: "m-4" }, {
                    header: withCtx(() => [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("Teams")), 1),
                        scores.value.user.team ? (openBlock(), createBlock("span", {
                          key: 0,
                          class: "rounded bg-teal-500 p-1 font-bold text-white"
                        }, [
                          createTextVNode(toDisplayString(scores.value.user.team.total), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])) : createCommentVNode("", true)
                      ])
                    ]),
                    default: withCtx(() => [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.teams, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.team.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.score), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ]),
                    _: 1
                  }),
                  createVNode(Card, { class: "m-4" }, {
                    header: withCtx(() => [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("Last 7 days")), 1),
                        createVNode("span", { class: "rounded bg-teal-500 p-1 font-bold text-white" }, [
                          createTextVNode(toDisplayString(scores.value.user.week.total), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])
                      ])
                    ]),
                    default: withCtx(() => [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.week, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.user.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.total), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ]),
                    _: 1
                  }),
                  createVNode(Card, { class: "m-4" }, {
                    header: withCtx(() => [
                      createVNode("div", { class: "flex w-full items-center justify-between" }, [
                        createVNode("h3", { class: "font-bold" }, toDisplayString(_ctx.__("Last 30 days")), 1),
                        createVNode("span", { class: "rounded bg-teal-500 p-1 font-bold text-white" }, [
                          createTextVNode(toDisplayString(scores.value.user.month.total), 1),
                          createVNode("sup", { class: "ml-1" }, "PTS")
                        ])
                      ])
                    ]),
                    default: withCtx(() => [
                      createVNode("table", { class: "w-full" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, "#"),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Name")), 1),
                            createVNode("th", { class: "border-b-2 p-2 text-left" }, toDisplayString(_ctx.__("Score")), 1)
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(scores.value.month, (score, index) => {
                            return openBlock(), createBlock("tr", null, [
                              createVNode("td", { class: "border-b p-2" }, toDisplayString(index + 1), 1),
                              createVNode("td", { class: "truncate border-b p-2" }, toDisplayString(score.user.name), 1),
                              createVNode("td", { class: "border-b p-2" }, [
                                createTextVNode(toDisplayString(score.total), 1),
                                createVNode("sup", { class: "ml-1" }, "PTS")
                              ])
                            ]);
                          }), 256))
                        ])
                      ])
                    ]),
                    _: 1
                  })
                ]))
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/PodiumModal.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
