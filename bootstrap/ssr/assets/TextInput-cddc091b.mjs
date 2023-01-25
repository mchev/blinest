import { useSSRContext, mergeProps } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrInterpolate, ssrRenderComponent } from "vue/server-renderer";
import { v4 } from "uuid";
import { _ as _sfc_main$1 } from "./Icon-4a47e6e0.mjs";
const _sfc_main = {
  __name: "TextInput",
  __ssrInlineRender: true,
  props: {
    id: {
      type: String,
      default() {
        return `text-input-${v4()}`;
      }
    },
    type: {
      type: String,
      default: "text"
    },
    loading: Boolean,
    error: String,
    label: String,
    modelValue: String | Number,
    appendIcon: String,
    prependIcon: String
  },
  emits: ["update:modelValue"],
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: _ctx.$attrs.class
      }, _attrs))}>`);
      if (__props.label) {
        _push(`<label class="form-label"${ssrRenderAttr("for", __props.id)}>${ssrInterpolate(__props.label)}:</label>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="relative">`);
      if (__props.prependIcon) {
        _push(ssrRenderComponent(_sfc_main$1, {
          name: __props.prependIcon,
          class: "pointer-events-none absolute top-1/2 left-3 z-10 mr-2 h-5 w-5 flex-shrink-0 -translate-y-1/2 transform fill-gray-500"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<input${ssrRenderAttrs(mergeProps({
        id: __props.id,
        ref: "input"
      }, { ..._ctx.$attrs, class: _ctx.$attrs.inputClass }, {
        class: ["form-input px-2", { error: __props.error, "pl-10": __props.prependIcon, "pr-10": __props.appendIcon }],
        type: __props.type,
        value: __props.modelValue
      }))}>`);
      if (__props.appendIcon) {
        _push(ssrRenderComponent(_sfc_main$1, {
          name: __props.appendIcon,
          class: "pointer-events-none absolute top-1/2 right-3 z-10 ml-2 h-5 w-5 flex-shrink-0 -translate-y-1/2 transform fill-gray-500"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (__props.loading) {
        _push(`<svg class="pointer-events-none absolute top-1/2 right-3 z-10 -mt-2 ml-2 h-5 w-5 flex-shrink-0 animate-spin text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (__props.error) {
        _push(`<div class="form-error">${ssrInterpolate(__props.error)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/TextInput.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
