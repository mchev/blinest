import { useSSRContext, ref, watch, mergeProps } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrInterpolate, ssrRenderSlot } from "vue/server-renderer";
import { v4 } from "uuid";
const __default__ = {
  inheritAttrs: false
};
const _sfc_main = /* @__PURE__ */ Object.assign(__default__, {
  __name: "SelectInput",
  __ssrInlineRender: true,
  props: {
    id: {
      type: String,
      default() {
        return `select-input-${v4()}`;
      }
    },
    error: String,
    label: String,
    modelValue: [String, Number, Boolean]
  },
  emits: ["update:modelValue"],
  setup(__props, { emit }) {
    const props = __props;
    const selected = ref(props.modelValue);
    const input = ref(null);
    watch(selected, (selected2) => {
      emit("update:modelValue", selected2);
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: _ctx.$attrs.class
      }, _attrs))}>`);
      if (__props.label) {
        _push(`<label class="form-label"${ssrRenderAttr("for", __props.id)}>${ssrInterpolate(__props.label)}:</label>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<select${ssrRenderAttrs(mergeProps({
        id: __props.id,
        ref_key: "input",
        ref: input
      }, { ..._ctx.$attrs, class: null }, {
        class: ["form-select", { error: __props.error }]
      }))}>`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</select>`);
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/SelectInput.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
