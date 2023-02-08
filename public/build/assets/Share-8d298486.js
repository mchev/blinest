import { _ as _sfc_main$2 } from "./Modal-f990bd3c.js";
import { k as ref, o as openBlock, c as createElementBlock, t as toDisplayString, f as normalizeClass, g as createCommentVNode, b as createBaseVNode, a as createVNode, w as withCtx, F as Fragment } from "./app-910e457d.js";
const _hoisted_1$1 = { class: "relative inline-block" };
const _hoisted_2$1 = ["textContent"];
const _hoisted_3$1 = { class: "flex" };
const _hoisted_4$1 = ["textContent"];
const _hoisted_5$1 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "h-4 w-4",
  viewBox: "0 0 20 20",
  fill: "currentColor"
}, [
  /* @__PURE__ */ createBaseVNode("path", { d: "M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" }),
  /* @__PURE__ */ createBaseVNode("path", { d: "M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" })
], -1);
const _hoisted_6$1 = [
  _hoisted_5$1
];
const _sfc_main$1 = {
  __name: "Clip",
  props: {
    text: String
  },
  setup(__props) {
    const props = __props;
    const message = ref({});
    const copy = () => {
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(props.text);
        message.value = {
          content: "Copié",
          status: true
        };
      } else {
        message.value = {
          content: "Copie impossible",
          status: false
        };
      }
      setTimeout(() => {
        message.value = {};
      }, 1e3);
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("span", _hoisted_1$1, [
        message.value.content ? (openBlock(), createElementBlock("div", {
          key: 0,
          textContent: toDisplayString(message.value.content),
          class: normalizeClass(["absolute right-0 bottom-full mb-1 rounded-lg p-1 text-xs text-white shadow", message.value.status ? "bg-green-500" : "bg-red-500"])
        }, null, 10, _hoisted_2$1)) : createCommentVNode("", true),
        createBaseVNode("div", _hoisted_3$1, [
          createBaseVNode("div", {
            type: "text",
            id: "website-admin",
            class: "min-w-0 rounded-none rounded-l-lg border border-gray-300 border-gray-300 bg-gray-50 px-1.5 py-1 font-mono text-xs text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
            textContent: toDisplayString(__props.text)
          }, null, 8, _hoisted_4$1),
          createBaseVNode("span", {
            onClick: copy,
            class: "inline-flex cursor-pointer items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-200 px-2 text-sm text-gray-900 opacity-80 hover:opacity-100 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400"
          }, _hoisted_6$1)
        ])
      ]);
    };
  }
};
const _hoisted_1 = /* @__PURE__ */ createBaseVNode("div", { id: "fb-root" }, null, -1);
const _hoisted_2 = ["title"];
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "h-full w-full"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"
  })
], -1);
const _hoisted_4 = [
  _hoisted_3
];
const _hoisted_5 = { class: "p-6" };
const _hoisted_6 = { class: "my-4 text-xl" };
const _hoisted_7 = /* @__PURE__ */ createBaseVNode("p", { class: "mb-1 text-sm" }, "Copier le lien :", -1);
const _hoisted_8 = ["href"];
const _hoisted_9 = ["href"];
const _sfc_main = {
  __name: "Share",
  props: {
    url: String
  },
  setup(__props) {
    const show = ref(false);
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        _hoisted_1,
        createBaseVNode("button", {
          onClick: _cache[0] || (_cache[0] = ($event) => show.value = true),
          class: normalizeClass(_ctx.$attrs.class),
          title: _ctx.__("Share")
        }, _hoisted_4, 10, _hoisted_2),
        createVNode(_sfc_main$2, {
          show: show.value,
          onClose: _cache[2] || (_cache[2] = ($event) => show.value = false)
        }, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_5, [
              createBaseVNode("h2", _hoisted_6, toDisplayString(_ctx.__("Share this page")), 1),
              _hoisted_7,
              createVNode(_sfc_main$1, {
                class: "mb-4",
                text: __props.url
              }, null, 8, ["text"]),
              createBaseVNode("a", {
                class: "btn-primary my-2",
                target: "_blank",
                href: `https://www.facebook.com/sharer/sharer.php?u=${__props.url}`
              }, toDisplayString(_ctx.__("Share on Facebook")), 9, _hoisted_8),
              createBaseVNode("a", {
                class: "btn-primary my-2",
                target: "_blank",
                href: `https://twitter.com/intent/tweet?&url=${__props.url}`
              }, toDisplayString(_ctx.__("Share on Twitter")), 9, _hoisted_9),
              createBaseVNode("button", {
                class: "btn-secondary mt-4 ml-auto",
                onClick: _cache[1] || (_cache[1] = ($event) => show.value = false)
              }, toDisplayString(_ctx.__("Close")), 1)
            ])
          ]),
          _: 1
        }, 8, ["show"])
      ], 64);
    };
  }
};
export {
  _sfc_main as _
};
