import { ref, onMounted, onUnmounted, withCtx, createTextVNode, toDisplayString, createVNode, openBlock, createBlock, Fragment, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { router } from "@inertiajs/vue3";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { _ as _sfc_main$1 } from "./Modal-e38f3366.mjs";
import _sfc_main$2 from "./EditOptionsForm-88410024.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./TextInput-cddc091b.mjs";
import "uuid";
import "./Icon-4a47e6e0.mjs";
import "./CheckboxInput-934baa4b.mjs";
const _sfc_main = {
  __name: "Controls",
  __ssrInlineRender: true,
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
      router.post(`/rounds/${props.round.id}/stop`, {
        preserveScroll: true,
        preserveState: false
      });
    };
    const startRound = () => {
      startingRound.value = true;
      router.post(`/rooms/${props.room.id}/start`, {
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(Card, {
        class: _ctx.$attrs.class
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex flex-col items-center gap-4 text-sm lg:flex-row lg:justify-between"${_scopeId}><div${_scopeId}><div class="mx-auto flex flex-wrap items-center gap-4"${_scopeId}><span class="uppercase text-neutral-500"${_scopeId}>Controls</span></div></div><div class="flex items-center gap-4"${_scopeId}><button type="button" class="btn-secondary btn-sm"${_scopeId}>Options</button>`);
            if (!__props.room.is_autostart) {
              _push2(`<!--[-->`);
              if (__props.room.is_playing) {
                _push2(ssrRenderComponent(LoadingButton, {
                  loading: endingRound.value,
                  type: "button",
                  class: "btn-danger btn-sm",
                  onClick: stopRound
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`${ssrInterpolate(_ctx.__("Stop round"))}`);
                    } else {
                      return [
                        createTextVNode(toDisplayString(_ctx.__("Stop round")), 1)
                      ];
                    }
                  }),
                  _: 1
                }, _parent2, _scopeId));
              } else {
                _push2(ssrRenderComponent(LoadingButton, {
                  type: "button",
                  loading: startingRound.value,
                  class: "btn-primary btn-sm",
                  onClick: startRound
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`${ssrInterpolate(_ctx.__("Start round"))}`);
                    } else {
                      return [
                        createTextVNode(toDisplayString(_ctx.__("Start round")), 1)
                      ];
                    }
                  }),
                  _: 1
                }, _parent2, _scopeId));
              }
              _push2(`<!--]-->`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "flex flex-col items-center gap-4 text-sm lg:flex-row lg:justify-between" }, [
                createVNode("div", null, [
                  createVNode("div", { class: "mx-auto flex flex-wrap items-center gap-4" }, [
                    createVNode("span", { class: "uppercase text-neutral-500" }, "Controls")
                  ])
                ]),
                createVNode("div", { class: "flex items-center gap-4" }, [
                  createVNode("button", {
                    type: "button",
                    class: "btn-secondary btn-sm",
                    onClick: ($event) => showingOptionsModal.value = true
                  }, "Options", 8, ["onClick"]),
                  !__props.room.is_autostart ? (openBlock(), createBlock(Fragment, { key: 0 }, [
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
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        show: showingOptionsModal.value,
        onClose: ($event) => showingOptionsModal.value = false
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(` Options de la room `);
                } else {
                  return [
                    createTextVNode(" Options de la room ")
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    room: __props.room,
                    modal: true,
                    onClose: ($event) => showingOptionsModal.value = false
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$2, {
                      room: __props.room,
                      modal: true,
                      onClose: ($event) => showingOptionsModal.value = false
                    }, null, 8, ["room", "onClose"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, null, {
                header: withCtx(() => [
                  createTextVNode(" Options de la room ")
                ]),
                default: withCtx(() => [
                  createVNode(_sfc_main$2, {
                    room: __props.room,
                    modal: true,
                    onClose: ($event) => showingOptionsModal.value = false
                  }, null, 8, ["room", "onClose"])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/Controls.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
