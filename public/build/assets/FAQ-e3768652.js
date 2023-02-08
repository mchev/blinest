import { J, P, c as createElementBlock, a as createVNode, Y as createSlots, u as unref, w as withCtx, o as openBlock, b as createBaseVNode, d as createTextVNode, t as toDisplayString } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$1 } from "./Icon-86c99edc.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "flex items-center gap-2 font-bold" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { class: "w-10" }, [
  /* @__PURE__ */ createBaseVNode("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    fill: "none",
    viewBox: "0 0 24 24",
    "stroke-width": "1.5",
    stroke: "currentColor",
    class: "w-8 h-8"
  }, [
    /* @__PURE__ */ createBaseVNode("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      d: "M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"
    })
  ])
], -1);
const _hoisted_3 = { itemprop: "name" };
const _hoisted_4 = {
  itemscope: "",
  itemprop: "acceptedAnswer",
  itemtype: "https://schema.org/Answer"
};
const _hoisted_5 = ["innerHTML"];
const _hoisted_6 = { class: "ml-auto flex gap-4" };
const _hoisted_7 = ["title"];
const _hoisted_8 = ["title"];
const _sfc_main = {
  __name: "FAQ",
  props: {
    faq: Object
  },
  setup(__props) {
    const props = __props;
    const user = J().props.auth.user;
    const form = P({
      id: props.faq.id
    });
    const voteUp = () => {
      form.post(`/faq/${props.faq.id}/vote/up`);
    };
    const voteDown = () => {
      form.post(`/faq/${props.faq.id}/vote/down`);
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("li", null, [
        createVNode(Card, {
          itemscope: "",
          itemprop: "mainEntity",
          itemtype: "https://schema.org/Question"
        }, createSlots({
          header: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              _hoisted_2,
              createBaseVNode("h2", _hoisted_3, toDisplayString(__props.faq.question), 1)
            ])
          ]),
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_4, [
              createBaseVNode("div", {
                itemprop: "text",
                class: "prose whitespace-pre-wrap prose-invert",
                innerHTML: __props.faq.answer
              }, null, 8, _hoisted_5)
            ])
          ]),
          _: 2
        }, [
          unref(user) ? {
            name: "footer",
            fn: withCtx(() => [
              createBaseVNode("div", _hoisted_6, [
                createBaseVNode("button", {
                  onClick: _cache[0] || (_cache[0] = ($event) => voteUp()),
                  class: "flex items-center",
                  title: _ctx.__("Like")
                }, [
                  createVNode(_sfc_main$1, {
                    name: "thumb-up",
                    class: "mr-1 h-5 w-5"
                  }),
                  createTextVNode(" " + toDisplayString(__props.faq.upvotes), 1)
                ], 8, _hoisted_7),
                createBaseVNode("button", {
                  onClick: _cache[1] || (_cache[1] = ($event) => voteDown()),
                  class: "flex items-center",
                  title: _ctx.__("Don't like")
                }, [
                  createVNode(_sfc_main$1, {
                    name: "thumb-down",
                    class: "mr-1 h-5 w-5"
                  }),
                  createTextVNode(" " + toDisplayString(__props.faq.downvotes), 1)
                ], 8, _hoisted_8)
              ])
            ]),
            key: "0"
          } : void 0
        ]), 1024)
      ]);
    };
  }
};
export {
  _sfc_main as default
};
