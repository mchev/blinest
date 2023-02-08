import { k as ref, q as onMounted, h as createBlock, w as withCtx, o as openBlock, b as createBaseVNode, t as toDisplayString, c as createElementBlock, a as createVNode, d as createTextVNode, F as Fragment, v as renderList, g as createCommentVNode } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./Modal-f990bd3c.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { S as Spinner } from "./Spinner-bfddfb59.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "bg-neutral-800 text-sm" };
const _hoisted_2 = { class: "flex items-center justify-between px-4 pt-2" };
const _hoisted_3 = { class: "font-bold uppercase text-teal-500" };
const _hoisted_4 = ["title"];
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "h-6 w-6"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M6 18L18 6M6 6l12 12"
  })
], -1);
const _hoisted_6 = [
  _hoisted_5
];
const _hoisted_7 = {
  key: 0,
  class: "flex w-full items-center justify-center p-12"
};
const _hoisted_8 = {
  key: 1,
  class: "grid grid-cols-1 xl:grid-cols-2"
};
const _hoisted_9 = { class: "flex w-full items-center justify-between" };
const _hoisted_10 = { class: "font-bold" };
const _hoisted_11 = { class: "rounded bg-teal-500 p-1 font-bold text-white" };
const _hoisted_12 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_13 = { class: "w-full" };
const _hoisted_14 = /* @__PURE__ */ createBaseVNode("th", { class: "border-b-2 p-2 text-left" }, "#", -1);
const _hoisted_15 = { class: "border-b-2 p-2 text-left" };
const _hoisted_16 = { class: "border-b-2 p-2 text-left" };
const _hoisted_17 = { class: "border-b p-2" };
const _hoisted_18 = { class: "truncate border-b p-2" };
const _hoisted_19 = { class: "border-b p-2" };
const _hoisted_20 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_21 = { class: "flex w-full items-center justify-between" };
const _hoisted_22 = { class: "font-bold" };
const _hoisted_23 = {
  key: 0,
  class: "rounded bg-teal-500 p-1 font-bold text-white"
};
const _hoisted_24 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_25 = { class: "w-full" };
const _hoisted_26 = /* @__PURE__ */ createBaseVNode("th", { class: "border-b-2 p-2 text-left" }, "#", -1);
const _hoisted_27 = { class: "border-b-2 p-2 text-left" };
const _hoisted_28 = { class: "border-b-2 p-2 text-left" };
const _hoisted_29 = { class: "border-b p-2" };
const _hoisted_30 = { class: "truncate border-b p-2" };
const _hoisted_31 = { class: "border-b p-2" };
const _hoisted_32 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_33 = { class: "flex w-full items-center justify-between" };
const _hoisted_34 = { class: "font-bold" };
const _hoisted_35 = { class: "rounded bg-teal-500 p-1 font-bold text-white" };
const _hoisted_36 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_37 = { class: "w-full" };
const _hoisted_38 = /* @__PURE__ */ createBaseVNode("th", { class: "border-b-2 p-2 text-left" }, "#", -1);
const _hoisted_39 = { class: "border-b-2 p-2 text-left" };
const _hoisted_40 = { class: "border-b-2 p-2 text-left" };
const _hoisted_41 = { class: "border-b p-2" };
const _hoisted_42 = { class: "truncate border-b p-2" };
const _hoisted_43 = { class: "border-b p-2" };
const _hoisted_44 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_45 = { class: "flex w-full items-center justify-between" };
const _hoisted_46 = { class: "font-bold" };
const _hoisted_47 = { class: "rounded bg-teal-500 p-1 font-bold text-white" };
const _hoisted_48 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_49 = { class: "w-full" };
const _hoisted_50 = /* @__PURE__ */ createBaseVNode("th", { class: "border-b-2 p-2 text-left" }, "#", -1);
const _hoisted_51 = { class: "border-b-2 p-2 text-left" };
const _hoisted_52 = { class: "border-b-2 p-2 text-left" };
const _hoisted_53 = { class: "border-b p-2" };
const _hoisted_54 = { class: "truncate border-b p-2" };
const _hoisted_55 = { class: "border-b p-2" };
const _hoisted_56 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _sfc_main = {
  __name: "PodiumModal",
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, {
        show: __props.show,
        maxWidth: "5xl"
      }, {
        default: withCtx(() => [
          createBaseVNode("div", _hoisted_1, [
            createBaseVNode("div", _hoisted_2, [
              createBaseVNode("h2", _hoisted_3, toDisplayString(__props.room.name), 1),
              createBaseVNode("button", {
                onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("close")),
                title: _ctx.__("Close"),
                class: "hover:animate-[spin_.5s_ease-in-out] hover:text-white"
              }, _hoisted_6, 8, _hoisted_4)
            ]),
            loading.value ? (openBlock(), createElementBlock("div", _hoisted_7, [
              createVNode(Spinner)
            ])) : (openBlock(), createElementBlock("div", _hoisted_8, [
              createVNode(Card, { class: "m-4" }, {
                header: withCtx(() => [
                  createBaseVNode("div", _hoisted_9, [
                    createBaseVNode("h3", _hoisted_10, toDisplayString(_ctx.__("All-time")), 1),
                    createBaseVNode("span", _hoisted_11, [
                      createTextVNode(toDisplayString(scores.value.user.lifetime.score), 1),
                      _hoisted_12
                    ])
                  ])
                ]),
                default: withCtx(() => [
                  createBaseVNode("table", _hoisted_13, [
                    createBaseVNode("thead", null, [
                      createBaseVNode("tr", null, [
                        _hoisted_14,
                        createBaseVNode("th", _hoisted_15, toDisplayString(_ctx.__("Name")), 1),
                        createBaseVNode("th", _hoisted_16, toDisplayString(_ctx.__("Score")), 1)
                      ])
                    ]),
                    createBaseVNode("tbody", null, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(scores.value.lifetime, (score, index) => {
                        return openBlock(), createElementBlock("tr", null, [
                          createBaseVNode("td", _hoisted_17, toDisplayString(index + 1), 1),
                          createBaseVNode("td", _hoisted_18, toDisplayString(score.user.name), 1),
                          createBaseVNode("td", _hoisted_19, [
                            createTextVNode(toDisplayString(score.score), 1),
                            _hoisted_20
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
                  createBaseVNode("div", _hoisted_21, [
                    createBaseVNode("h3", _hoisted_22, toDisplayString(_ctx.__("Teams")), 1),
                    scores.value.user.team ? (openBlock(), createElementBlock("span", _hoisted_23, [
                      createTextVNode(toDisplayString(scores.value.user.team.total), 1),
                      _hoisted_24
                    ])) : createCommentVNode("", true)
                  ])
                ]),
                default: withCtx(() => [
                  createBaseVNode("table", _hoisted_25, [
                    createBaseVNode("thead", null, [
                      createBaseVNode("tr", null, [
                        _hoisted_26,
                        createBaseVNode("th", _hoisted_27, toDisplayString(_ctx.__("Name")), 1),
                        createBaseVNode("th", _hoisted_28, toDisplayString(_ctx.__("Score")), 1)
                      ])
                    ]),
                    createBaseVNode("tbody", null, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(scores.value.teams, (score, index) => {
                        return openBlock(), createElementBlock("tr", null, [
                          createBaseVNode("td", _hoisted_29, toDisplayString(index + 1), 1),
                          createBaseVNode("td", _hoisted_30, toDisplayString(score.team.name), 1),
                          createBaseVNode("td", _hoisted_31, [
                            createTextVNode(toDisplayString(score.score), 1),
                            _hoisted_32
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
                  createBaseVNode("div", _hoisted_33, [
                    createBaseVNode("h3", _hoisted_34, toDisplayString(_ctx.__("Last 7 days")), 1),
                    createBaseVNode("span", _hoisted_35, [
                      createTextVNode(toDisplayString(scores.value.user.week.total), 1),
                      _hoisted_36
                    ])
                  ])
                ]),
                default: withCtx(() => [
                  createBaseVNode("table", _hoisted_37, [
                    createBaseVNode("thead", null, [
                      createBaseVNode("tr", null, [
                        _hoisted_38,
                        createBaseVNode("th", _hoisted_39, toDisplayString(_ctx.__("Name")), 1),
                        createBaseVNode("th", _hoisted_40, toDisplayString(_ctx.__("Score")), 1)
                      ])
                    ]),
                    createBaseVNode("tbody", null, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(scores.value.week, (score, index) => {
                        return openBlock(), createElementBlock("tr", null, [
                          createBaseVNode("td", _hoisted_41, toDisplayString(index + 1), 1),
                          createBaseVNode("td", _hoisted_42, toDisplayString(score.user.name), 1),
                          createBaseVNode("td", _hoisted_43, [
                            createTextVNode(toDisplayString(score.total), 1),
                            _hoisted_44
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
                  createBaseVNode("div", _hoisted_45, [
                    createBaseVNode("h3", _hoisted_46, toDisplayString(_ctx.__("Last 30 days")), 1),
                    createBaseVNode("span", _hoisted_47, [
                      createTextVNode(toDisplayString(scores.value.user.month.total), 1),
                      _hoisted_48
                    ])
                  ])
                ]),
                default: withCtx(() => [
                  createBaseVNode("table", _hoisted_49, [
                    createBaseVNode("thead", null, [
                      createBaseVNode("tr", null, [
                        _hoisted_50,
                        createBaseVNode("th", _hoisted_51, toDisplayString(_ctx.__("Name")), 1),
                        createBaseVNode("th", _hoisted_52, toDisplayString(_ctx.__("Score")), 1)
                      ])
                    ]),
                    createBaseVNode("tbody", null, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(scores.value.month, (score, index) => {
                        return openBlock(), createElementBlock("tr", null, [
                          createBaseVNode("td", _hoisted_53, toDisplayString(index + 1), 1),
                          createBaseVNode("td", _hoisted_54, toDisplayString(score.user.name), 1),
                          createBaseVNode("td", _hoisted_55, [
                            createTextVNode(toDisplayString(score.total), 1),
                            _hoisted_56
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
        ]),
        _: 1
      }, 8, ["show"]);
    };
  }
};
export {
  _sfc_main as default
};
