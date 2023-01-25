import { ssrRenderComponent, ssrRenderAttr, ssrRenderClass, ssrInterpolate, ssrIncludeBooleanAttr, ssrRenderList } from "vue/server-renderer";
import { ref, onMounted, watch, mergeProps, withCtx, createVNode, openBlock, createBlock, withDirectives, vModelText, useSSRContext, onUnmounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$2 } from "./Dropdown-6785e0d2.mjs";
import "uuid";
import "./Icon-4a47e6e0.mjs";
import "@popperjs/core";
const _sfc_main$1 = {
  __name: "Volume",
  __ssrInlineRender: true,
  setup(__props) {
    const volume = ref(0.7);
    onMounted(() => {
      volume.value = localStorage.getItem("volume") ?? 0.7;
      dispatch();
    });
    watch(volume, (value) => {
      localStorage.setItem("volume", value);
      dispatch();
    });
    const dispatch = () => {
      window.dispatchEvent(
        new CustomEvent("volume-localstorage-changed", {
          detail: {
            volume: volume.value
          }
        })
      );
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$2, mergeProps({
        placement: "bottom-end",
        autoClose: false
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="group flex cursor-pointer select-none items-center h-full mr-1"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"${_scopeId}></path></svg></div>`);
          } else {
            return [
              createVNode("div", { class: "group flex cursor-pointer select-none items-center h-full mr-1" }, [
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
                    d: "M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"
                  })
                ]))
              ])
            ];
          }
        }),
        dropdown: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="p-2"${_scopeId}><input id="default-range" type="range" min="0" max="1" step="0.01"${ssrRenderAttr("value", volume.value)} class="h-2 w-full cursor-pointer appearance-none rounded-lg"${_scopeId}></div>`);
          } else {
            return [
              createVNode("div", { class: "p-2" }, [
                withDirectives(createVNode("input", {
                  id: "default-range",
                  type: "range",
                  min: "0",
                  max: "1",
                  step: "0.01",
                  "onUpdate:modelValue": ($event) => volume.value = $event,
                  class: "h-2 w-full cursor-pointer appearance-none rounded-lg"
                }, null, 8, ["onUpdate:modelValue"]), [
                  [vModelText, volume.value]
                ])
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Volume.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const __default__ = {
  inheritAttrs: false
};
const _sfc_main = /* @__PURE__ */ Object.assign(__default__, {
  __name: "UserInput",
  __ssrInlineRender: true,
  props: {
    room: Object,
    channel: String,
    currentTime: Number
  },
  setup(__props) {
    const props = __props;
    const input = ref(null);
    const track = ref(null);
    const round = ref(null);
    const text = ref("");
    const words = ref([]);
    const message = ref(null);
    const answers = ref([]);
    usePage().props.auth.user;
    const inputDisabled = ref(true);
    onMounted(() => {
      focus();
      Echo.channel(props.channel).listen("TrackPlayed", (e) => {
        props.room.value = e.room;
        round.value = e.round;
        track.value = e.track;
        answers.value = [];
        inputDisabled.value = false;
        text.value = "";
      }).listen("TrackEnded", (e) => {
        inputDisabled.value = true;
        text.value = "";
        words.value = [];
      });
    });
    onUnmounted(() => {
      Echo.leave(props.channel);
    });
    const focus = () => {
      input.value.focus();
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><form class="flex w-full items-center justify-center"><div class="relative flex w-full items-center">`);
      if (message.value) {
        _push(`<blockquote class="${ssrRenderClass([{ "bg-teal-600": message.value.type === "good", "bg-orange-600": message.value.type === "almost", "bg-red-600": message.value.type === "bad" }, "absolute bottom-full right-0 flex translate-y-[-80%] translate-x-[-50%] items-center rounded-lg bg-teal-600 py-1 px-2 text-neutral-100"])}">${ssrInterpolate(message.value.body)} <div class="${ssrRenderClass([{ "border-t-teal-600": message.value.type === "good", "border-t-orange-600": message.value.type === "almost", "border-t-red-600": message.value.type === "bad" }, "absolute left-5 top-full h-full h-0 w-full w-0 translate-y-[-50%] border-t-[10px] mt-1 border-l-[10px] border-r-[10px] border-t-transparent border-l-transparent border-r-transparent"])}"></div></blockquote>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<input${ssrRenderAttr("value", text.value)} type="text" class="h-14 w-full flex-grow rounded-none rounded-bl-md border-none p-2 text-2xl uppercase text-gray-600 focus:shadow-none focus:outline-none focus:ring-0 border-none" placeholder="Une idée?" autofocus${ssrIncludeBooleanAttr(inputDisabled.value) ? " readonly" : ""}>`);
      _push(ssrRenderComponent(_sfc_main$1, { class: "flex items-center justify-center p-2 h-14 text-neutral-700 bg-white -ml-1" }, null, _parent));
      _push(`<button type="submit" class="btn-send h-14"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="w-6 h-6"><title>${ssrInterpolate(_ctx.__("Send"))}</title><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg></button></div></form><ul class="mt-2 flex gap-2"><!--[-->`);
      ssrRenderList(answers.value, (answer) => {
        _push(`<li class="flex items-center rounded-lg bg-teal-600 py-1 px-2 text-neutral-100">`);
        if (answer.type.svg_icon) {
          _push(`<span class="mr-1">${answer.type.svg_icon}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(` ${ssrInterpolate(answer.value)}</li>`);
      });
      _push(`<!--]--></ul><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/UserInput.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
