import { k as ref, q as onMounted, L as onUnmounted, c as createElementBlock, a as createVNode, w as withCtx, f as normalizeClass, F as Fragment, o as openBlock, b as createBaseVNode, h as createBlock, d as createTextVNode, t as toDisplayString, g as createCommentVNode, s as Je } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$1 } from "./Modal-f990bd3c.js";
import _sfc_main$2 from "./EditOptionsForm-80793cf6.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./TextInput-541fe8b5.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
import "./CheckboxInput-45662aca.js";
const _hoisted_1 = { class: "flex flex-col items-center gap-4 text-sm lg:flex-row lg:justify-between" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", null, [
  /* @__PURE__ */ createBaseVNode("div", { class: "mx-auto flex flex-wrap items-center gap-4" }, [
    /* @__PURE__ */ createBaseVNode("span", { class: "uppercase text-neutral-500" }, "Controls")
  ])
], -1);
const _hoisted_3 = { class: "flex items-center gap-4" };
const _sfc_main = {
  __name: "Controls",
  props: {
    channel: String,
    room: Object,
    round: [Object, Boolean]
  },
  setup(__props) {
    const props = __props;
    const startingRound = ref(false);
    const endingRound = ref(false);
    const showingOptionsModal = ref(false);
    const stopRound = () => {
      endingRound.value = true;
      Je.post(`/rounds/${props.round.id}/stop`, {
        preserveScroll: true,
        preserveState: false
      });
    };
    const startRound = () => {
      startingRound.value = true;
      Je.post(`/rooms/${props.room.id}/start`, {
        preserveScroll: true,
        preserveState: false
      });
    };
    onMounted(() => {
      Echo.channel(props.channel).listen("RoundStarted", () => {
        startingRound.value = false;
        props.room.is_playing = true;
      }).listen("RoundFinished", (e) => {
        endingRound.value = false;
        props.room.is_playing = false;
      });
    });
    onUnmounted(() => {
      Echo.leave(props.channel);
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(Card, {
          class: normalizeClass(_ctx.$attrs.class)
        }, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              _hoisted_2,
              createBaseVNode("div", _hoisted_3, [
                createBaseVNode("button", {
                  type: "button",
                  class: "btn-secondary btn-sm",
                  onClick: _cache[0] || (_cache[0] = ($event) => showingOptionsModal.value = true)
                }, "Options"),
                !__props.room.is_autostart ? (openBlock(), createElementBlock(Fragment, { key: 0 }, [
                  __props.room.is_playing ? (openBlock(), createBlock(LoadingButton, {
                    key: 0,
                    loading: endingRound.value,
                    type: "button",
                    class: "btn-danger btn-sm",
                    onClick: stopRound
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Stop round")), 1)
                    ]),
                    _: 1
                  }, 8, ["loading"])) : (openBlock(), createBlock(LoadingButton, {
                    key: 1,
                    type: "button",
                    loading: startingRound.value,
                    class: "btn-primary btn-sm",
                    onClick: startRound
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Start round")), 1)
                    ]),
                    _: 1
                  }, 8, ["loading"]))
                ], 64)) : createCommentVNode("", true)
              ])
            ])
          ]),
          _: 1
        }, 8, ["class"]),
        createVNode(_sfc_main$1, {
          show: showingOptionsModal.value,
          onClose: _cache[2] || (_cache[2] = ($event) => showingOptionsModal.value = false)
        }, {
          default: withCtx(() => [
            createVNode(Card, null, {
              header: withCtx(() => [
                createTextVNode(" Options de la room ")
              ]),
              default: withCtx(() => [
                createVNode(_sfc_main$2, {
                  room: __props.room,
                  modal: true,
                  onClose: _cache[1] || (_cache[1] = ($event) => showingOptionsModal.value = false)
                }, null, 8, ["room"])
              ]),
              _: 1
            })
          ]),
          _: 1
        }, 8, ["show"])
      ], 64);
    };
  }
};
export {
  _sfc_main as default
};
