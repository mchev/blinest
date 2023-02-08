import { _ as _sfc_main$1 } from "./Modal-f990bd3c.js";
import { C as Card } from "./Card-7ef4ce68.js";
import _sfc_main$2 from "./Podium-85ef9c78.js";
import { k as ref, l as watch, q as onMounted, h as createBlock, w as withCtx, o as openBlock, a as createVNode, b as createBaseVNode, t as toDisplayString, x as normalizeStyle, c as createElementBlock, F as Fragment, v as renderList, d as createTextVNode, g as createCommentVNode } from "./app-910e457d.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "flex justify-between" };
const _hoisted_2 = {
  key: 0,
  class: "w-full"
};
const _hoisted_3 = { class: "mb-2 py-2 text-center text-xl font-bold" };
const _hoisted_4 = { class: "max-h-48 overflow-auto" };
const _hoisted_5 = { class: "broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2" };
const _hoisted_6 = { class: "text-xl font-bold" };
const _hoisted_7 = { class: "flex-grow" };
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_9 = {
  key: 1,
  class: "w-full"
};
const _hoisted_10 = { class: "mb-2 py-2 text-center text-xl font-bold" };
const _hoisted_11 = { class: "max-h-48 overflow-auto" };
const _hoisted_12 = { class: "broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2" };
const _hoisted_13 = { class: "text-xl font-bold" };
const _hoisted_14 = { class: "flex-grow" };
const _hoisted_15 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_16 = { key: 0 };
const _hoisted_17 = { class: "flex w-full items-center gap-6" };
const _hoisted_18 = { class: "flex flex-grow flex-col" };
const _hoisted_19 = { class: "relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200" };
const _hoisted_20 = { class: "absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600" };
const _hoisted_21 = { class: "ml-auto flex items-center" };
const _sfc_main = {
  __name: "FinishedRoundModal",
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, {
        show: __props.show,
        onClose: close
      }, {
        default: withCtx(() => [
          createVNode(Card, null, {
            header: withCtx(() => [
              createBaseVNode("h2", null, toDisplayString(_ctx.__("Round finished")), 1)
            ]),
            footer: withCtx(() => [
              createBaseVNode("div", _hoisted_17, [
                createBaseVNode("div", _hoisted_18, [
                  createBaseVNode("div", _hoisted_19, [
                    createBaseVNode("div", {
                      class: "flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear",
                      style: normalizeStyle("width:" + countdown.value / parseInt(props.round.room.pause_between_rounds) * 100 + "%")
                    }, [
                      createBaseVNode("span", _hoisted_20, "Prochaine partie dans " + toDisplayString(countdown.value), 1)
                    ], 4)
                  ])
                ]),
                createBaseVNode("div", _hoisted_21, [
                  createBaseVNode("button", {
                    type: "button",
                    class: "btn-secondary mr-2",
                    onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("close"))
                  }, toDisplayString(_ctx.__("Close")), 1)
                ])
              ])
            ]),
            default: withCtx(() => [
              createBaseVNode("div", _hoisted_1, [
                users_results.value && users_results.value.length ? (openBlock(), createElementBlock("div", _hoisted_2, [
                  createBaseVNode("h3", _hoisted_3, toDisplayString(_ctx.__("Ranking")), 1),
                  createVNode(_sfc_main$2, { list: users_results.value }, null, 8, ["list"]),
                  createBaseVNode("ul", _hoisted_4, [
                    (openBlock(true), createElementBlock(Fragment, null, renderList(users_results.value, (result, index) => {
                      return openBlock(), createElementBlock("li", _hoisted_5, [
                        createBaseVNode("span", _hoisted_6, toDisplayString(index + 1), 1),
                        createBaseVNode("span", _hoisted_7, toDisplayString(result.user.name), 1),
                        createBaseVNode("span", null, [
                          createTextVNode(toDisplayString(result.total), 1),
                          _hoisted_8
                        ])
                      ]);
                    }), 256))
                  ])
                ])) : createCommentVNode("", true),
                teams_results.value && teams_results.value.length ? (openBlock(), createElementBlock("div", _hoisted_9, [
                  createBaseVNode("h3", _hoisted_10, toDisplayString(_ctx.__("Teams")), 1),
                  createVNode(_sfc_main$2, { list: teams_results.value }, null, 8, ["list"]),
                  createBaseVNode("ul", _hoisted_11, [
                    (openBlock(true), createElementBlock(Fragment, null, renderList(teams_results.value, (result, index) => {
                      return openBlock(), createElementBlock("li", _hoisted_12, [
                        createBaseVNode("span", _hoisted_13, toDisplayString(index + 1), 1),
                        createBaseVNode("span", _hoisted_14, toDisplayString(result.team.name), 1),
                        createBaseVNode("span", null, [
                          createTextVNode(toDisplayString(result.total), 1),
                          _hoisted_15
                        ])
                      ]);
                    }), 256))
                  ])
                ])) : createCommentVNode("", true)
              ]),
              users_results.value && !users_results.value.length && teams_results.value && !teams_results.value.length ? (openBlock(), createElementBlock("div", _hoisted_16, toDisplayString(_ctx.__("No scores")), 1)) : createCommentVNode("", true)
            ]),
            _: 1
          })
        ]),
        _: 1
      }, 8, ["show"]);
    };
  }
};
export {
  _sfc_main as default
};
