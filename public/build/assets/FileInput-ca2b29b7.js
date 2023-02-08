import { k as ref, l as watch, o as openBlock, c as createElementBlock, t as toDisplayString, g as createCommentVNode, b as createBaseVNode, d as createTextVNode, f as normalizeClass } from "./app-910e457d.js";
const _hoisted_1 = {
  key: 0,
  class: "form-label"
};
const _hoisted_2 = ["accept"];
const _hoisted_3 = {
  key: 0,
  class: "px-2"
};
const _hoisted_4 = {
  key: 1,
  class: "flex items-center justify-between p-2"
};
const _hoisted_5 = { class: "flex-1 pr-1" };
const _hoisted_6 = { class: "text-xs text-neutral-500" };
const _hoisted_7 = {
  key: 1,
  class: "form-error"
};
const _sfc_main = {
  __name: "FileInput",
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
    const browse = () => {
      file.value.click();
    };
    const change = (e) => {
      emit("update:modelValue", e.target.files[0]);
    };
    const remove = () => {
      emit("update:modelValue", null);
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", null, [
        __props.label ? (openBlock(), createElementBlock("label", _hoisted_1, toDisplayString(__props.label) + ":", 1)) : createCommentVNode("", true),
        createBaseVNode("div", {
          class: normalizeClass(["form-input p-0", { error: __props.error }])
        }, [
          createBaseVNode("input", {
            ref_key: "file",
            ref: file,
            type: "file",
            accept: __props.accept,
            class: "hidden",
            onChange: change
          }, null, 40, _hoisted_2),
          !__props.modelValue ? (openBlock(), createElementBlock("div", _hoisted_3, [
            createBaseVNode("button", {
              type: "button",
              class: "rounded-sm bg-neutral-500 px-4 py-1 text-xs font-medium text-white hover:bg-neutral-700",
              onClick: browse
            }, toDisplayString(_ctx.__("Browse")), 1)
          ])) : (openBlock(), createElementBlock("div", _hoisted_4, [
            createBaseVNode("div", _hoisted_5, [
              createTextVNode(toDisplayString(__props.modelValue.name) + " ", 1),
              createBaseVNode("span", _hoisted_6, "(" + toDisplayString(filesize(__props.modelValue.size)) + ")", 1)
            ]),
            createBaseVNode("button", {
              type: "button",
              class: "rounded-sm bg-neutral-500 px-4 py-1 text-xs font-medium text-white hover:bg-neutral-700",
              onClick: remove
            }, toDisplayString(_ctx.__("Remove")), 1)
          ]))
        ], 2),
        __props.error ? (openBlock(), createElementBlock("div", _hoisted_7, toDisplayString(__props.error), 1)) : createCommentVNode("", true)
      ]);
    };
  }
};
export {
  _sfc_main as _
};
