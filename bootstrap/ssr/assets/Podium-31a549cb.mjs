import { mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderAttr } from "vue/server-renderer";
const _sfc_main = {
  __name: "Podium",
  __ssrInlineRender: true,
  props: {
    list: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.list.length) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex w-full justify-center gap-1 border-b border-neutral-600" }, _attrs))}>`);
        if (__props.list[4]) {
          _push(`<div class="flex flex-col justify-end"><img class="mb-2 h-10 w-10 rounded-full"${ssrRenderAttr("src", __props.list[4].user ? __props.list[4].user.photo : __props.list[4].team.photo)}><div class="h-10 rounded-t bg-purple-500 p-4 opacity-25"><span class="text-xl font-bold">5</span></div></div>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.list[2]) {
          _push(`<div class="flex flex-col items-center justify-end"><img class="mb-2 h-10 w-10 rounded-full"${ssrRenderAttr("src", __props.list[2].user ? __props.list[2].user.photo : __props.list[2].team.photo)}><div class="h-32 rounded-t bg-purple-500 p-4 opacity-60"><span class="text-xl font-bold">3</span></div></div>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.list[0]) {
          _push(`<div class="flex flex-col items-center justify-end"><img class="mb-2 h-10 w-10 rounded-full"${ssrRenderAttr("src", __props.list[0].user ? __props.list[0].user.photo : __props.list[0].team.photo)}><div class="h-40 rounded-t bg-purple-500 p-4"><span class="text-xl font-bold">1</span></div></div>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.list[1]) {
          _push(`<div class="flex flex-col items-center justify-end"><img class="mb-2 h-10 w-10 rounded-full"${ssrRenderAttr("src", __props.list[1].user ? __props.list[1].user.photo : __props.list[1].team.photo)}><div class="h-36 rounded-t bg-purple-500 p-4 opacity-80"><span class="text-xl font-bold">2</span></div></div>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.list[3]) {
          _push(`<div class="flex flex-col items-center justify-end"><img class="mb-2 h-10 w-10 rounded-full"${ssrRenderAttr("src", __props.list[3].user ? __props.list[3].user.photo : __props.list[3].team.photo)}><div class="h-20 rounded-t bg-purple-500 p-4 opacity-30"><span class="text-xl font-bold">4</span></div></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/partials/Podium.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
