import { k as ref, q as onMounted, l as watch, o as openBlock, h as createBlock, w as withCtx, b as createBaseVNode, z as withDirectives, V as vModelText, J, L as onUnmounted, c as createElementBlock, f as normalizeClass, d as createTextVNode, t as toDisplayString, g as createCommentVNode, a as createVNode, e as withModifiers, F as Fragment, v as renderList } from "./app-910e457d.js";
import { _ as _sfc_main$2 } from "./Dropdown-f0e2d937.js";
const _hoisted_1$1 = /* @__PURE__ */ createBaseVNode("div", { class: "group mr-1 flex h-full cursor-pointer select-none items-center" }, [
  /* @__PURE__ */ createBaseVNode("svg", {
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
      d: "M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"
    })
  ])
], -1);
const _hoisted_2$1 = { class: "p-2" };
const _sfc_main$1 = {
  __name: "Volume",
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$2, {
        placement: "bottom-end",
        autoClose: false
      }, {
        default: withCtx(() => [
          _hoisted_1$1
        ]),
        dropdown: withCtx(() => [
          createBaseVNode("div", _hoisted_2$1, [
            withDirectives(createBaseVNode("input", {
              id: "default-range",
              type: "range",
              min: "0",
              max: "1",
              step: "0.01",
              "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => volume.value = $event),
              class: "h-2 w-full cursor-pointer appearance-none rounded-lg"
            }, null, 512), [
              [vModelText, volume.value]
            ])
          ])
        ]),
        _: 1
      });
    };
  }
};
const _hoisted_1 = ["onSubmit"];
const _hoisted_2 = { class: "relative flex w-full items-center" };
const _hoisted_3 = ["readonly"];
const _hoisted_4 = {
  type: "submit",
  class: "btn-send h-14"
};
const _hoisted_5 = {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "4",
  stroke: "currentColor",
  class: "h-6 w-6"
};
const _hoisted_6 = /* @__PURE__ */ createBaseVNode("path", {
  "stroke-linecap": "round",
  "stroke-linejoin": "round",
  d: "M4.5 12.75l6 6 9-13.5"
}, null, -1);
const _hoisted_7 = { class: "mt-2 flex gap-2" };
const _hoisted_8 = ["innerHTML"];
const __default__ = {
  inheritAttrs: false
};
const _sfc_main = /* @__PURE__ */ Object.assign(__default__, {
  __name: "UserInput",
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
    J().props.auth.user;
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
    const check = () => {
      if (text.value.length >= 1 && track.value) {
        axios.post(`/rounds/${round.value.id}/tracks/${track.value.id}/check`, { text: text.value, words: words.value, currentTime: props.currentTime }).then((response) => {
          answers.value.push(...response.data.good_answers);
          words.value = response.data.words;
          showMessage(response.data.message);
          focus();
        });
      }
      text.value = "";
    };
    const showMessage = (data) => {
      message.value = data;
      setTimeout(() => {
        message.value = null;
      }, 1500);
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createBaseVNode("form", {
          class: "flex w-full items-center justify-center",
          onSubmit: withModifiers(check, ["prevent"])
        }, [
          createBaseVNode("div", _hoisted_2, [
            message.value ? (openBlock(), createElementBlock("blockquote", {
              key: 0,
              class: normalizeClass(["absolute bottom-full right-0 flex translate-y-[-80%] translate-x-[-50%] items-center rounded-lg bg-teal-600 py-1 px-2 text-neutral-100", { "bg-teal-600": message.value.type === "good", "bg-orange-600": message.value.type === "almost", "bg-red-600": message.value.type === "bad" }])
            }, [
              createTextVNode(toDisplayString(message.value.body) + " ", 1),
              createBaseVNode("div", {
                class: normalizeClass(["absolute left-5 top-full mt-1 h-full h-0 w-full w-0 translate-y-[-50%] border-t-[10px] border-l-[10px] border-r-[10px] border-t-transparent border-l-transparent border-r-transparent", { "border-t-teal-600": message.value.type === "good", "border-t-orange-600": message.value.type === "almost", "border-t-red-600": message.value.type === "bad" }])
              }, null, 2)
            ], 2)) : createCommentVNode("", true),
            withDirectives(createBaseVNode("input", {
              ref_key: "input",
              ref: input,
              "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => text.value = $event),
              type: "text",
              class: "h-14 w-full flex-grow rounded-none rounded-bl-md border-none border-none p-2 text-2xl uppercase text-gray-600 focus:shadow-none focus:outline-none focus:ring-0",
              placeholder: "Une idée?",
              autofocus: "",
              readonly: inputDisabled.value
            }, null, 8, _hoisted_3), [
              [vModelText, text.value]
            ]),
            createVNode(_sfc_main$1, { class: "-ml-1 flex h-14 items-center justify-center bg-white p-2 text-neutral-700" }),
            createBaseVNode("button", _hoisted_4, [
              (openBlock(), createElementBlock("svg", _hoisted_5, [
                createBaseVNode("title", null, toDisplayString(_ctx.__("Send")), 1),
                _hoisted_6
              ]))
            ])
          ])
        ], 40, _hoisted_1),
        createBaseVNode("ul", _hoisted_7, [
          (openBlock(true), createElementBlock(Fragment, null, renderList(answers.value, (answer) => {
            return openBlock(), createElementBlock("li", {
              key: answer.id,
              class: "flex items-center rounded-lg bg-teal-600 py-1 px-2 text-neutral-100"
            }, [
              answer.type.svg_icon ? (openBlock(), createElementBlock("span", {
                key: 0,
                class: "mr-1",
                innerHTML: answer.type.svg_icon
              }, null, 8, _hoisted_8)) : createCommentVNode("", true),
              createTextVNode(" " + toDisplayString(answer.value), 1)
            ]);
          }), 128))
        ])
      ], 64);
    };
  }
});
export {
  _sfc_main as default
};
