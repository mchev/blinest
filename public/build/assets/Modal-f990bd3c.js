import { l as watch, q as onMounted, L as onUnmounted, Q as computed, o as openBlock, h as createBlock, a as createVNode, w as withCtx, z as withDirectives, N as vShow, b as createBaseVNode, R as Transition, m as mergeProps, u as unref, r as renderSlot, g as createCommentVNode, T as Teleport } from "./app-910e457d.js";
const _hoisted_1 = {
  class: "fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0",
  "scroll-region": ""
};
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { class: "absolute inset-0 bg-gray-500 opacity-75" }, null, -1);
const _hoisted_3 = [
  _hoisted_2
];
const _sfc_main = {
  __name: "Modal",
  props: {
    show: {
      type: [Boolean, Number],
      default: false
    },
    maxWidth: {
      type: String,
      default: "2xl"
    },
    closeable: {
      type: Boolean,
      default: true
    }
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    watch(
      () => props.show,
      () => {
        if (props.show) {
          document.body.style.overflow = "hidden";
        } else {
          document.body.style.overflow = null;
        }
      }
    );
    const close = () => {
      if (props.closeable) {
        emit("close");
      }
    };
    const closeOnEscape = (e) => {
      if (e.key === "Escape" && props.show) {
        close();
      }
    };
    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => {
      document.removeEventListener("keydown", closeOnEscape);
      document.body.style.overflow = null;
    });
    const maxWidthClass = computed(() => {
      return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
        "3xl": "sm:max-w-3xl",
        "4xl": "sm:max-w-4xl",
        "5xl": "sm:max-w-5xl"
      }[props.maxWidth];
    });
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Teleport, { to: "body" }, [
        createVNode(Transition, { "leave-active-class": "duration-200" }, {
          default: withCtx(() => [
            withDirectives(createBaseVNode("div", _hoisted_1, [
              createVNode(Transition, {
                "enter-active-class": "ease-out duration-300",
                "enter-from-class": "opacity-0",
                "enter-to-class": "opacity-100",
                "leave-active-class": "ease-in duration-200",
                "leave-from-class": "opacity-100",
                "leave-to-class": "opacity-0"
              }, {
                default: withCtx(() => [
                  withDirectives(createBaseVNode("div", {
                    class: "fixed inset-0 transform transition-all",
                    onClick: close
                  }, _hoisted_3, 512), [
                    [vShow, __props.show]
                  ])
                ]),
                _: 1
              }),
              createVNode(Transition, {
                "enter-active-class": "ease-out duration-300",
                "enter-from-class": "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95",
                "enter-to-class": "opacity-100 translate-y-0 sm:scale-100",
                "leave-active-class": "ease-in duration-200",
                "leave-from-class": "opacity-100 translate-y-0 sm:scale-100",
                "leave-to-class": "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              }, {
                default: withCtx(() => [
                  withDirectives(createBaseVNode("div", mergeProps({ ..._ctx.$attrs, class: unref(maxWidthClass) }, { class: "mb-6 transform overflow-hidden rounded-lg bg-neutral-800 text-neutral-300 shadow-xl transition-all sm:mx-auto sm:w-full" }), [
                    __props.show ? renderSlot(_ctx.$slots, "default", { key: 0 }) : createCommentVNode("", true)
                  ], 16), [
                    [vShow, __props.show]
                  ])
                ]),
                _: 3
              })
            ], 512), [
              [vShow, __props.show]
            ])
          ]),
          _: 3
        })
      ]);
    };
  }
};
export {
  _sfc_main as _
};