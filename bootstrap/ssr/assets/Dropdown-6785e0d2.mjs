import { ref, watch, nextTick, onMounted, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderSlot, ssrRenderTeleport } from "vue/server-renderer";
import { createPopper } from "@popperjs/core";
const _sfc_main = {
  __name: "Dropdown",
  __ssrInlineRender: true,
  props: {
    placement: {
      type: String,
      default: "bottom-end"
    },
    autoClose: {
      type: Boolean,
      default: true
    }
  },
  emits: ["closed"],
  setup(__props, { emit }) {
    const props = __props;
    const show = ref(false);
    const dropdown = ref(null);
    const popper = ref(null);
    const root = ref(null);
    watch(show, (show2) => {
      if (show2) {
        nextTick(() => {
          popper.value = createPopper(root.value, dropdown.value, {
            placement: props.placement,
            modifiers: [
              {
                name: "preventOverflow",
                options: {
                  altBoundary: true
                }
              }
            ]
          });
        });
      } else if (popper.value) {
        setTimeout(() => {
          popper.value.destroy();
          emit("closed");
        }, 100);
      }
    });
    onMounted(() => {
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          show.value = false;
        }
      });
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        ref_key: "root",
        ref: root
      }, _attrs))}><button type="button" class="flex h-full">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      if (show.value) {
        ssrRenderTeleport(_push, (_push2) => {
          _push2(`<div><div class="fixed top-0 right-0 left-0 bottom-0 z-[99998] bg-black opacity-10"></div><div class="absolute z-[99999] overflow-hidden rounded-lg bg-neutral-800 text-sm text-neutral-100 shadow-lg">`);
          ssrRenderSlot(_ctx.$slots, "dropdown", {}, null, _push2, _parent);
          _push2(`</div></div>`);
        }, "#dropdown", false, _parent);
      } else {
        _push(`<!---->`);
      }
      _push(`</button></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Dropdown.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
