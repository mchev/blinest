import { ssrRenderAttrs, ssrInterpolate, ssrRenderClass, ssrRenderAttr } from "vue/server-renderer";
import { useSSRContext, ref, watch } from "vue";
const _sfc_main = {
  __name: "FileInput",
  __ssrInlineRender: true,
  props: {
    modelValue: File,
    label: String,
    accept: String,
    error: String
  },
  emits: ["update:modelValue"],
  setup(__props, { emit }) {
    const props = __props;
    const file = ref(null);
    watch(props.modelValue, (value) => {
      if (!value) {
        file.value = "";
      }
    });
    const filesize = (size) => {
      var i = Math.floor(Math.log(size) / Math.log(1024));
      return (size / Math.pow(1024, i)).toFixed(2) * 1 + " " + ["B", "kB", "MB", "GB", "TB"][i];
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(_attrs)}>`);
      if (__props.label) {
        _push(`<label class="form-label">${ssrInterpolate(__props.label)}:</label>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass([{ error: __props.error }, "form-input p-0"])}"><input type="file"${ssrRenderAttr("accept", __props.accept)} class="hidden">`);
      if (!__props.modelValue) {
        _push(`<div class="px-2"><button type="button" class="rounded-sm bg-neutral-500 px-4 py-1 text-xs font-medium text-white hover:bg-neutral-700">${ssrInterpolate(_ctx.__("Browse"))}</button></div>`);
      } else {
        _push(`<div class="flex items-center justify-between p-2"><div class="flex-1 pr-1">${ssrInterpolate(__props.modelValue.name)} <span class="text-xs text-neutral-500">(${ssrInterpolate(filesize(__props.modelValue.size))})</span></div><button type="button" class="rounded-sm bg-neutral-500 px-4 py-1 text-xs font-medium text-white hover:bg-neutral-700">${ssrInterpolate(_ctx.__("Remove"))}</button></div>`);
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/FileInput.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
