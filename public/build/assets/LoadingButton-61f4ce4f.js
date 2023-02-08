import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.js";
import { o as openBlock, c as createElementBlock, g as createCommentVNode, r as renderSlot } from "./app-910e457d.js";
const _sfc_main = {
  props: {
    loading: Boolean
  }
};
const _hoisted_1 = ["disabled"];
const _hoisted_2 = {
  key: 0,
  class: "btn-spinner mr-2"
};
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("button", {
    disabled: $props.loading,
    class: "flex items-center"
  }, [
    $props.loading ? (openBlock(), createElementBlock("div", _hoisted_2)) : createCommentVNode("", true),
    renderSlot(_ctx.$slots, "default")
  ], 8, _hoisted_1);
}
const LoadingButton = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export {
  LoadingButton as L
};
