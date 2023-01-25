import { useSSRContext, mergeProps } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import { v4 } from "uuid";
const __default__ = {
  inheritAttrs: false
};
const _sfc_main = /* @__PURE__ */ Object.assign(__default__, {
  __name: "TextareaInput",
  __ssrInlineRender: true,
  props: {
    id: {
      type: String,
      default() {
        return `textarea-input-${v4()}`;
      }
    },
    error: String,
    label: String,
    modelValue: String
  },
  emits: ["update:modelValue"],
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      let _temp0;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: _ctx.$attrs.class
      }, _attrs))}>`);
      if (__props.label) {
        _push(`<label class="form-label"${ssrRenderAttr("for", __props.id)}>${ssrInterpolate(__props.label)}:</label>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<textarea${ssrRenderAttrs(_temp0 = mergeProps({
        id: __props.id,
        ref: "input"
      }, { ..._ctx.$attrs, class: null }, {
        class: ["form-textarea", { error: __props.error }],
        value: __props.modelValue
      }), "textarea")}>${ssrInterpolate("value" in _temp0 ? _temp0.value : "")}</textarea>`);
      if (__props.error) {
        _push(`<div class="form-error">${ssrInterpolate(__props.error)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/TextareaInput.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
