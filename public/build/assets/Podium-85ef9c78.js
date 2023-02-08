import { c as createElementBlock, b as createBaseVNode, g as createCommentVNode, o as openBlock } from "./app-910e457d.js";
const _hoisted_1 = {
  key: 0,
  class: "flex w-full justify-center gap-1 border-b border-neutral-600"
};
const _hoisted_2 = {
  key: 0,
  class: "flex flex-col justify-end"
};
const _hoisted_3 = ["src"];
const _hoisted_4 = /* @__PURE__ */ createBaseVNode("div", { class: "h-10 rounded-t bg-purple-500 p-4 opacity-25" }, [
  /* @__PURE__ */ createBaseVNode("span", { class: "text-xl font-bold" }, "5")
], -1);
const _hoisted_5 = {
  key: 1,
  class: "flex flex-col items-center justify-end"
};
const _hoisted_6 = ["src"];
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("div", { class: "h-32 rounded-t bg-purple-500 p-4 opacity-60" }, [
  /* @__PURE__ */ createBaseVNode("span", { class: "text-xl font-bold" }, "3")
], -1);
const _hoisted_8 = {
  key: 2,
  class: "flex flex-col items-center justify-end"
};
const _hoisted_9 = ["src"];
const _hoisted_10 = /* @__PURE__ */ createBaseVNode("div", { class: "h-40 rounded-t bg-purple-500 p-4" }, [
  /* @__PURE__ */ createBaseVNode("span", { class: "text-xl font-bold" }, "1")
], -1);
const _hoisted_11 = {
  key: 3,
  class: "flex flex-col items-center justify-end"
};
const _hoisted_12 = ["src"];
const _hoisted_13 = /* @__PURE__ */ createBaseVNode("div", { class: "h-36 rounded-t bg-purple-500 p-4 opacity-80" }, [
  /* @__PURE__ */ createBaseVNode("span", { class: "text-xl font-bold" }, "2")
], -1);
const _hoisted_14 = {
  key: 4,
  class: "flex flex-col items-center justify-end"
};
const _hoisted_15 = ["src"];
const _hoisted_16 = /* @__PURE__ */ createBaseVNode("div", { class: "h-20 rounded-t bg-purple-500 p-4 opacity-30" }, [
  /* @__PURE__ */ createBaseVNode("span", { class: "text-xl font-bold" }, "4")
], -1);
const _sfc_main = {
  __name: "Podium",
  props: {
    list: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return __props.list.length ? (openBlock(), createElementBlock("div", _hoisted_1, [
        __props.list[4] ? (openBlock(), createElementBlock("div", _hoisted_2, [
          createBaseVNode("img", {
            class: "mb-2 h-10 w-10 rounded-full",
            src: __props.list[4].user ? __props.list[4].user.photo : __props.list[4].team.photo
          }, null, 8, _hoisted_3),
          _hoisted_4
        ])) : createCommentVNode("", true),
        __props.list[2] ? (openBlock(), createElementBlock("div", _hoisted_5, [
          createBaseVNode("img", {
            class: "mb-2 h-10 w-10 rounded-full",
            src: __props.list[2].user ? __props.list[2].user.photo : __props.list[2].team.photo
          }, null, 8, _hoisted_6),
          _hoisted_7
        ])) : createCommentVNode("", true),
        __props.list[0] ? (openBlock(), createElementBlock("div", _hoisted_8, [
          createBaseVNode("img", {
            class: "mb-2 h-10 w-10 rounded-full",
            src: __props.list[0].user ? __props.list[0].user.photo : __props.list[0].team.photo
          }, null, 8, _hoisted_9),
          _hoisted_10
        ])) : createCommentVNode("", true),
        __props.list[1] ? (openBlock(), createElementBlock("div", _hoisted_11, [
          createBaseVNode("img", {
            class: "mb-2 h-10 w-10 rounded-full",
            src: __props.list[1].user ? __props.list[1].user.photo : __props.list[1].team.photo
          }, null, 8, _hoisted_12),
          _hoisted_13
        ])) : createCommentVNode("", true),
        __props.list[3] ? (openBlock(), createElementBlock("div", _hoisted_14, [
          createBaseVNode("img", {
            class: "mb-2 h-10 w-10 rounded-full",
            src: __props.list[3].user ? __props.list[3].user.photo : __props.list[3].team.photo
          }, null, 8, _hoisted_15),
          _hoisted_16
        ])) : createCommentVNode("", true)
      ])) : createCommentVNode("", true);
    };
  }
};
export {
  _sfc_main as default
};
