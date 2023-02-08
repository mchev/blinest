import { o as openBlock, c as createElementBlock, b as createBaseVNode, F as Fragment, v as renderList, f as normalizeClass, g as createCommentVNode, s as Je } from "./app-910e457d.js";
const _hoisted_1 = { key: 0 };
const _hoisted_2 = ["innerHTML"];
const _hoisted_3 = ["onClick", "innerHTML"];
const _sfc_main = {
  __name: "Pagination",
  props: {
    links: Array
  },
  setup(__props) {
    const visit = (url) => {
      Je.visit(url, { preserveState: true, preserveScroll: true }, { only: ["categories"] });
    };
    return (_ctx, _cache) => {
      return __props.links.length > 3 ? (openBlock(), createElementBlock("div", _hoisted_1, [
        createBaseVNode("div", {
          class: normalizeClass([_ctx.$attrs.class, "m-6 flex flex-wrap justify-end"])
        }, [
          (openBlock(true), createElementBlock(Fragment, null, renderList(__props.links, (link, key) => {
            return openBlock(), createElementBlock(Fragment, null, [
              link.url === null ? (openBlock(), createElementBlock("div", {
                key,
                class: "mb-1 mr-1 rounded bg-neutral-700 px-4 py-3 text-sm leading-4 text-neutral-300",
                innerHTML: link.label
              }, null, 8, _hoisted_2)) : (openBlock(), createElementBlock("button", {
                key: `link-${key}`,
                class: normalizeClass(["mb-1 mr-1 rounded px-4 py-3 text-sm leading-4 hover:bg-neutral-500", link.active ? "bg-neutral-500" : "bg-neutral-700"]),
                onClick: ($event) => visit(link.url),
                innerHTML: link.label
              }, null, 10, _hoisted_3))
            ], 64);
          }), 256))
        ], 2)
      ])) : createCommentVNode("", true);
    };
  }
};
export {
  _sfc_main as _
};
