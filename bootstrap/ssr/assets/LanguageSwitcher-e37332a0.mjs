import { mergeProps, useSSRContext, withCtx, unref, createVNode, createTextVNode, toDisplayString, openBlock, createBlock, createCommentVNode, resolveComponent } from "vue";
import { ssrRenderAttrs, ssrRenderStyle, ssrInterpolate, ssrRenderComponent, ssrRenderAttr } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-cc2b3d55.mjs";
import { usePage, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$5 } from "./Icon-4a47e6e0.mjs";
import { _ as _sfc_main$4 } from "./Dropdown-6785e0d2.mjs";
const _sfc_main$3 = {
  __name: "Logo",
  __ssrInlineRender: true,
  props: {
    name: String
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.name === "xmas") {
        _push(`<svg${ssrRenderAttrs(mergeProps({
          xmlns: "http://www.w3.org/2000/svg",
          viewBox: "0 0 379.17 88.58"
        }, _attrs))}><path d="M75.8,50.9c-2.37-2.71-5.82-4.46-10.42-5.41a15.57,15.57,0,0,0,7.78-5.41,14.51,14.51,0,0,0,2.91-9.2,14.24,14.24,0,0,0-5.82-12c-3.79-2.84-9.2-4.26-16.16-4.26H27.43v4H53.88c5.55,0,9.88,1.08,12.86,3.31a11,11,0,0,1,4.53,9.4,11.41,11.41,0,0,1-4.53,9.61c-3,2.23-7.31,3.18-12.86,3.18H27.43v4H55.91C62,48.06,66.8,49.14,70,51.24s4.87,5.48,4.87,9.94S73.23,69,70,71.19s-7.84,3.18-14.07,3.18H27.43v4H55.91c7.78,0,13.6-1.48,17.59-4.39s6-7,6-12.52A16.14,16.14,0,0,0,75.8,50.9Z" fill="currentColor"></path><path d="M103.46,10.79V78.5H108V10.79Z" fill="currentColor"></path><path d="M136.27,30.88V78.5h4.53V30.88Zm-.4-12.65a3.4,3.4,0,0,0,2.63,1,3.55,3.55,0,0,0,2.64-1.08,3.44,3.44,0,0,0,1.08-2.64A3.43,3.43,0,0,0,141.14,13a3.58,3.58,0,0,0-2.64-1.08A3.47,3.47,0,0,0,135.87,13a3.56,3.56,0,0,0-1.09,2.64A3.38,3.38,0,0,0,135.87,18.23Z" fill="currentColor"></path><path d="M206.42,35.68c-3.45-3.45-8.19-5.21-14.14-5.21a23.36,23.36,0,0,0-11.57,2.84,17.87,17.87,0,0,0-7.3,7.85V30.88H169V78.5h4.53V53.13c0-5.68,1.56-10.14,4.87-13.53s7.58-5,13.26-5c4.87,0,8.59,1.49,11.3,4.19s4.12,7,4.12,12.25V78.5h4.53V50.63C211.62,44.2,209.87,39.13,206.42,35.68Z" fill="currentColor"></path><path d="M267.7,73.09a22.76,22.76,0,0,1-8.59,1.62,20.24,20.24,0,0,1-12.51-3.85,18.44,18.44,0,0,1-7-10.42l40.25-7.85a24.4,24.4,0,0,0-3.32-11.29,21.64,21.64,0,0,0-19.07-10.83,23.62,23.62,0,0,0-12,3.11,22.15,22.15,0,0,0-8.32,8.59,25.36,25.36,0,0,0-2.91,12.52A24.56,24.56,0,0,0,237.4,67.2a21.82,21.82,0,0,0,8.79,8.59A25.23,25.23,0,0,0,259,78.9,26.86,26.86,0,0,0,269.19,77,19.45,19.45,0,0,0,277,71.19l-2.64-3A16.54,16.54,0,0,1,267.7,73.09ZM247.88,37a19.2,19.2,0,0,1,9.67-2.57,18.33,18.33,0,0,1,8.59,2.1,18.85,18.85,0,0,1,6.23,5.55,20.18,20.18,0,0,1,3,7.84l-36.32,7.11a15.91,15.91,0,0,1-.21-2.84A21.34,21.34,0,0,1,241.25,44,16.87,16.87,0,0,1,247.88,37Z" fill="currentColor"></path><path d="M304.09,77.15a32.67,32.67,0,0,0,11,1.75c5.95,0,10.62-1.21,13.87-3.58a11,11,0,0,0,4.93-9.47,10.32,10.32,0,0,0-2.36-7,15,15,0,0,0-5.82-3.65A72.13,72.13,0,0,0,316.74,53a75.1,75.1,0,0,1-7.85-1.83,12.08,12.08,0,0,1-4.66-2.64,6.45,6.45,0,0,1-1.9-5,7.62,7.62,0,0,1,3.38-6.5c2.17-1.62,5.55-2.43,10-2.43a26.72,26.72,0,0,1,7.64,1.08,25.75,25.75,0,0,1,6.7,3.18l2.09-3.65a26.64,26.64,0,0,0-7.3-3.38,34.74,34.74,0,0,0-9.07-1.29c-5.81,0-10.28,1.29-13.39,3.72a11.36,11.36,0,0,0-4.67,9.34,10.32,10.32,0,0,0,2.44,7.3,13.05,13.05,0,0,0,5.95,3.86,52,52,0,0,0,9.13,2.16c3.18.61,5.75,1.22,7.58,1.76a11.88,11.88,0,0,1,4.53,2.57A6.08,6.08,0,0,1,329.25,66a7.58,7.58,0,0,1-3.45,6.57c-2.3,1.62-5.81,2.36-10.62,2.36a29,29,0,0,1-9.67-1.62A22.46,22.46,0,0,1,298,69.16l-2.09,3.66A23.17,23.17,0,0,0,304.09,77.15Z" fill="currentColor"></path><path d="M379.17,75.45l-1.89-3.17a10.51,10.51,0,0,1-7.37,2.63c-2.91,0-5.21-.74-6.7-2.36s-2.16-4-2.16-7.11V34.8H376.2V30.88H361.05V20.46h-4.54V30.88h-8.79V34.8h8.79V65.92c0,4.12,1.09,7.3,3.39,9.6s5.41,3.38,9.6,3.38a18,18,0,0,0,5.41-.81A12.3,12.3,0,0,0,379.17,75.45Z" fill="currentColor"></path><path d="M23.27,61.42c0-2.24,58.46-53,44.44-51.68C46.34-4.1,29.3.94,27.54.71,13.29,6.53,3.74,20.9,3.44,22.37c-6,7.15-2.49,26.82-1.7,29.69C15.24,83.29,10,72.88,12.32,78.32l8-.85,3-10.4a16.27,16.27,0,0,0,0-5.65Z" style="${ssrRenderStyle({ "fill": "#b7322f" })}"></path><path d="M28.83,59.25c31.83-22.06,25.58-27,36.2-36.54,5.83-4.44,3.86-5.85,5.15-8.52C71.36,11.28,71.56,9,70,8,68.89,5.8,67.4,4.86,65.38,5.76c-3.89.63-3.83,2-5.12,3.12-3.56,2.8-4.56,4.52-4.29,5.7a35.45,35.45,0,0,0-5.47,6.14c-1.32,6.91-2.62,6.4-3.89,7.66-5.58,2.14-5.92,3.51-5.87,4.19-3.79,4.77-2,2.59-3.84,3.91-7.5,4.52-5.47,6-6.68,8.45-3.49.49-6.37,4.53-7.47,6.35-.23,13.36-.16,12,6.08,8Z" style="${ssrRenderStyle({ "fill": "#fff", "stroke": "#010101", "stroke-miterlimit": "2.587160110473633", "stroke-width": "0.13997182738800262px" })}"></path><path d="M12.3,78c-2.34.86-2.24,1.92-2.54,2.94a7.34,7.34,0,0,1,.89,2.88l.16,2c.62.87.86,2.15,2.32,2.07,1,.39,2.11,1.12,2.95.11a3.81,3.81,0,0,1,2.27.43c1.35.19,2-.35,1.93-1.62a8.93,8.93,0,0,0,3.39-2.16c.77-1,.92-1.82.21-2.57a1.72,1.72,0,0,0-.82-2.32c-.42-2.82-1.17-2.3-1.83-2.7a3,3,0,0,0-2.55-.84c-1.75-1-2-.49-2.65-.32-.51-.05-1-.23-1.52.88-1.45,0-2.06.48-2.21,1.21Z" style="${ssrRenderStyle({ "fill": "#fff", "stroke": "#010101", "stroke-miterlimit": "2.587160110473633", "stroke-width": "0.13997182738800262px" })}"></path></svg>`);
      } else {
        _push(`<svg${ssrRenderAttrs(mergeProps({
          xmlns: "http://www.w3.org/2000/svg",
          viewBox: "0 0 520 100.7",
          fill: "currentColor"
        }, _attrs))}><path fill="currentColor" d="M71.5,59.3c-3.5-4-8.6-6.6-15.4-8a23,23,0,0,0,11.5-8,21.47,21.47,0,0,0,4.3-13.6c0-7.7-3-13.6-8.6-17.8S49.7,5.6,39.4,5.6H0v5.9H39.1c8.2,0,14.6,1.6,19,4.9s6.7,7.8,6.7,13.9-2.3,10.9-6.7,14.2-10.8,4.7-19,4.7H0v5.9H42.1c9,0,16.1,1.6,20.8,4.7s7.2,8.1,7.2,14.7-2.4,11.6-7.2,14.8S51.3,94,42.1,94H0v5.9H42.1c11.5,0,20.1-2.2,26-6.5S76.9,83,76.9,74.9a23.84,23.84,0,0,0-5.4-15.6Z"></path><path fill="currentColor" d="M112.4,0V100.1h6.7V0Z"></path><path fill="currentColor" d="M160.9,29.7v70.4h6.7V29.7ZM160.3,11a5.05,5.05,0,0,0,3.9,1.5,5.28,5.28,0,0,0,3.9-1.6A5.13,5.13,0,0,0,169.7,7a5.07,5.07,0,0,0-1.6-3.8,5.28,5.28,0,0,0-3.9-1.6,5.13,5.13,0,0,0-3.9,1.6,5.28,5.28,0,0,0-1.6,3.9A5,5,0,0,0,160.3,11Z"></path><path fill="currentColor" d="M264.6,36.8c-5.1-5.1-12.1-7.7-20.9-7.7a34.37,34.37,0,0,0-17.1,4.2,26.29,26.29,0,0,0-10.8,11.6V29.7h-6.5v70.4H216V62.6c0-8.4,2.3-15,7.2-20,4.7-4.9,11.2-7.4,19.6-7.4,7.2,0,12.7,2.2,16.7,6.2,4,4.2,6.1,10.3,6.1,18.1v40.6h6.7V58.9c0-9.5-2.6-17-7.7-22.1Z"></path><path fill="currentColor" d="M355.2,92.1a33.38,33.38,0,0,1-12.7,2.4c-7.3,0-13.4-1.9-18.5-5.7a27.25,27.25,0,0,1-10.3-15.4l59.5-11.6a36.16,36.16,0,0,0-4.9-16.7,32,32,0,0,0-28.2-16,34.83,34.83,0,0,0-17.7,4.6,32.65,32.65,0,0,0-12.3,12.7c-3,5.4-4.3,11.6-4.3,18.5a36.29,36.29,0,0,0,4.6,18.5,32.43,32.43,0,0,0,13,12.7,37.54,37.54,0,0,0,18.9,4.6,39.76,39.76,0,0,0,15.1-2.8,28.63,28.63,0,0,0,11.5-8.6L365,84.8a24.32,24.32,0,0,1-9.8,7.3ZM325.9,38.8A28.24,28.24,0,0,1,340.2,35a27.1,27.1,0,0,1,12.7,3.1,27.93,27.93,0,0,1,9.2,8.2,29.9,29.9,0,0,1,4.5,11.6L312.9,68.4a23.36,23.36,0,0,1-.3-4.2,31.43,31.43,0,0,1,3.5-15.1,24.91,24.91,0,0,1,9.8-10.3Z"></path><path fill="currentColor" d="M409,98.1a48.27,48.27,0,0,0,16.2,2.6c8.8,0,15.7-1.8,20.5-5.3a16.26,16.26,0,0,0,7.3-14c0-4.3-1.2-7.8-3.5-10.4a22.06,22.06,0,0,0-8.6-5.4,107.08,107.08,0,0,0-13.2-3.2,109.33,109.33,0,0,1-11.6-2.7,18,18,0,0,1-6.9-3.9c-1.9-1.8-2.8-4.2-2.8-7.4a11.22,11.22,0,0,1,5-9.6c3.2-2.4,8.2-3.6,14.8-3.6a39.56,39.56,0,0,1,11.3,1.6,37.6,37.6,0,0,1,9.9,4.7l3.1-5.4a40.22,40.22,0,0,0-10.8-5,51.61,51.61,0,0,0-13.4-1.9c-8.6,0-15.2,1.9-19.8,5.5a16.78,16.78,0,0,0-6.9,13.8q0,6.9,3.6,10.8A19.24,19.24,0,0,0,412,65a77.11,77.11,0,0,0,13.5,3.2c4.7.9,8.5,1.8,11.2,2.6a17.54,17.54,0,0,1,6.7,3.8,9,9,0,0,1,2.8,7,11.23,11.23,0,0,1-5.1,9.7c-3.4,2.4-8.6,3.5-15.7,3.5a42.93,42.93,0,0,1-14.3-2.4A33.44,33.44,0,0,1,400,86.3l-3.1,5.4c2.9,2.6,7,4.7,12.1,6.4Z"></path><path fill="currentColor" d="M520,95.6l-2.8-4.7a15.54,15.54,0,0,1-10.9,3.9c-4.3,0-7.7-1.1-9.9-3.5s-3.2-5.9-3.2-10.5V35.5h22.4V29.7H493.2V14.3h-6.7V29.7h-13v5.8h13v46c0,6.1,1.6,10.8,5,14.2,3.2,3.4,8,5,14.2,5a27.21,27.21,0,0,0,8-1.2,18.56,18.56,0,0,0,6.3-3.9Z"></path></svg>`);
      }
    };
  }
};
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Logo.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = {
  data() {
    return {
      show: true
    };
  },
  watch: {
    "$page.props.flash": {
      handler() {
        this.show = true;
      },
      deep: true
    }
  }
};
function _sfc_ssrRender$1(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex justify-center" }, _attrs))}>`);
  if (_ctx.$page.props.flash.success && $data.show) {
    _push(`<div class="mb-8 flex max-w-3xl items-center justify-between rounded bg-green-500"><div class="flex items-center"><svg class="ml-4 mr-2 h-4 w-4 flex-shrink-0 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="0 11 2 9 7 14 18 3 20 5 7 18"></polygon></svg><div class="py-4 text-sm font-medium text-white">${ssrInterpolate(_ctx.$page.props.flash.success)}</div></div><button type="button" class="group mr-2 p-2"><svg class="block h-2 w-2 fill-green-800 group-hover:fill-white" xmlns="http://www.w3.org/2000/svg" width="235.908" height="235.908" viewBox="278.046 126.846 235.908 235.908"><path d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"></path></svg></button></div>`);
  } else {
    _push(`<!---->`);
  }
  if ((_ctx.$page.props.flash.error || Object.keys(_ctx.$page.props.errors).length > 0) && $data.show) {
    _push(`<div class="mb-8 flex max-w-3xl items-center justify-between rounded bg-red-500"><div class="flex items-center"><svg class="ml-4 mr-2 h-4 w-4 flex-shrink-0 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"></path></svg>`);
    if (_ctx.$page.props.flash.error) {
      _push(`<div class="py-4 text-sm font-medium text-white">${ssrInterpolate(_ctx.$page.props.flash.error)}</div>`);
    } else {
      _push(`<div class="py-4 text-sm font-medium text-white">`);
      if (_ctx.$page.props.errors.login) {
        _push(`<span>${ssrInterpolate(_ctx.__(_ctx.$page.props.errors.login))}</span>`);
      } else if (Object.keys(_ctx.$page.props.errors).length === 1) {
        _push(`<span>${ssrInterpolate(_ctx.__("There is one form error."))}</span>`);
      } else {
        _push(`<span>${ssrInterpolate(_ctx.__("There are"))} ${ssrInterpolate(Object.keys(_ctx.$page.props.errors).length)} ${ssrInterpolate(_ctx.__("form errors"))}.</span>`);
      }
      _push(`</div>`);
    }
    _push(`</div><button type="button" class="group mr-2 p-2"><svg class="block h-2 w-2 fill-red-800 group-hover:fill-white" xmlns="http://www.w3.org/2000/svg" width="235.908" height="235.908" viewBox="278.046 126.846 235.908 235.908"><path d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"></path></svg></button></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div>`);
}
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/FlashMessages.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const FlashMessages = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["ssrRender", _sfc_ssrRender$1]]);
const _sfc_main$1 = {
  __name: "UserDropdown",
  __ssrInlineRender: true,
  setup(__props) {
    const user = usePage().props.auth.user;
    const isUrl = (...urls) => {
      let currentUrl = usePage().url.substr(1);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length;
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$4, mergeProps({ placement: "bottom-end" }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="group flex cursor-pointer select-none items-center"${_scopeId}><div class="mr-1 whitespace-nowrap"${_scopeId}><img${ssrRenderAttr("src", unref(user).photo)} class="h-10 w-10 rounded-full"${ssrRenderAttr("alt", unref(user).name)}${_scopeId}></div></div>`);
          } else {
            return [
              createVNode("div", { class: "group flex cursor-pointer select-none items-center" }, [
                createVNode("div", { class: "mr-1 whitespace-nowrap" }, [
                  createVNode("img", {
                    src: unref(user).photo,
                    class: "h-10 w-10 rounded-full",
                    alt: unref(user).name
                  }, null, 8, ["src", "alt"])
                ])
              ])
            ];
          }
        }),
        dropdown: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<ul${_scopeId}><li${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("me"),
              class: ["m-4 flex pl-2", isUrl("me") ? "font-bold" : "font-normal"]
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("My account"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("My account")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</li><li${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("teams.index"),
              class: ["m-4 flex pl-2", isUrl("teams") ? "font-bold" : "font-normal"]
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Teams"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Teams")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</li><li${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("rooms.index"),
              class: ["m-4 flex pl-2", isUrl("rooms") ? "font-bold" : "font-normal"]
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Rooms"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</li><li${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("playlists"),
              class: ["m-4 flex pl-2", isUrl("playlists") ? "font-bold" : "font-normal"]
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Playlists"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Playlists")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</li>`);
            if (unref(user).admin) {
              _push2(`<li${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("admin.dashboard"),
                class: ["m-4 flex pl-2", isUrl("admin") ? "font-bold" : "font-normal"]
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Administration"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Administration")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</li>`);
            } else {
              _push2(`<!---->`);
            }
            if (unref(user).is_public_moderator) {
              _push2(`<li${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("moderation.index"),
                class: ["m-4 flex pl-2", isUrl("moderation") ? "font-bold" : "font-normal"]
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Modération"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Modération")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</li>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<li${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: "/logout",
              method: "post",
              as: "button",
              class: "m-4 flex pl-2 text-red-500"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Logout"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Logout")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</li></ul>`);
          } else {
            return [
              createVNode("ul", null, [
                createVNode("li", null, [
                  createVNode(unref(Link), {
                    href: _ctx.route("me"),
                    class: ["m-4 flex pl-2", isUrl("me") ? "font-bold" : "font-normal"]
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("My account")), 1)
                    ]),
                    _: 1
                  }, 8, ["href", "class"])
                ]),
                createVNode("li", null, [
                  createVNode(unref(Link), {
                    href: _ctx.route("teams.index"),
                    class: ["m-4 flex pl-2", isUrl("teams") ? "font-bold" : "font-normal"]
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Teams")), 1)
                    ]),
                    _: 1
                  }, 8, ["href", "class"])
                ]),
                createVNode("li", null, [
                  createVNode(unref(Link), {
                    href: _ctx.route("rooms.index"),
                    class: ["m-4 flex pl-2", isUrl("rooms") ? "font-bold" : "font-normal"]
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Rooms")), 1)
                    ]),
                    _: 1
                  }, 8, ["href", "class"])
                ]),
                createVNode("li", null, [
                  createVNode(unref(Link), {
                    href: _ctx.route("playlists"),
                    class: ["m-4 flex pl-2", isUrl("playlists") ? "font-bold" : "font-normal"]
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Playlists")), 1)
                    ]),
                    _: 1
                  }, 8, ["href", "class"])
                ]),
                unref(user).admin ? (openBlock(), createBlock("li", { key: 0 }, [
                  createVNode(unref(Link), {
                    href: _ctx.route("admin.dashboard"),
                    class: ["m-4 flex pl-2", isUrl("admin") ? "font-bold" : "font-normal"]
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Administration")), 1)
                    ]),
                    _: 1
                  }, 8, ["href", "class"])
                ])) : createCommentVNode("", true),
                unref(user).is_public_moderator ? (openBlock(), createBlock("li", { key: 1 }, [
                  createVNode(unref(Link), {
                    href: _ctx.route("moderation.index"),
                    class: ["m-4 flex pl-2", isUrl("moderation") ? "font-bold" : "font-normal"]
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Modération")), 1)
                    ]),
                    _: 1
                  }, 8, ["href", "class"])
                ])) : createCommentVNode("", true),
                createVNode("li", null, [
                  createVNode(unref(Link), {
                    href: "/logout",
                    method: "post",
                    as: "button",
                    class: "m-4 flex pl-2 text-red-500"
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Logout")), 1)
                    ]),
                    _: 1
                  })
                ])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UserDropdown.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  components: {
    Link,
    Icon: _sfc_main$5,
    Dropdown: _sfc_main$4
  },
  computed: {
    selectable_locale() {
      if (this.$page.props.locale == "fr") {
        return "en";
      }
      return "fr";
    }
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_dropdown = resolveComponent("dropdown");
  const _component_Link = resolveComponent("Link");
  _push(ssrRenderComponent(_component_dropdown, mergeProps({ placement: "bottom-end" }, _attrs), {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<div class="group flex cursor-pointer select-none items-center"${_scopeId}><div class="whitespace-nowrap" title="Langue"${_scopeId}><span class="uppercase"${_scopeId}>${ssrInterpolate(_ctx.$page.props.locale)}</span></div></div>`);
      } else {
        return [
          createVNode("div", { class: "group flex cursor-pointer select-none items-center" }, [
            createVNode("div", {
              class: "whitespace-nowrap",
              title: "Langue"
            }, [
              createVNode("span", { class: "uppercase" }, toDisplayString(_ctx.$page.props.locale), 1)
            ])
          ])
        ];
      }
    }),
    dropdown: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Link, {
          href: _ctx.route("language", [$options.selectable_locale]),
          class: "p-3 uppercase leading-8"
        }, {
          default: withCtx((_2, _push3, _parent3, _scopeId2) => {
            if (_push3) {
              _push3(`${ssrInterpolate($options.selectable_locale)}`);
            } else {
              return [
                createTextVNode(toDisplayString($options.selectable_locale), 1)
              ];
            }
          }),
          _: 1
        }, _parent2, _scopeId));
      } else {
        return [
          createVNode(_component_Link, {
            href: _ctx.route("language", [$options.selectable_locale]),
            class: "p-3 uppercase leading-8"
          }, {
            default: withCtx(() => [
              createTextVNode(toDisplayString($options.selectable_locale), 1)
            ]),
            _: 1
          }, 8, ["href"])
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/LanguageSwitcher.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const LanguageSwitcher = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
export {
  FlashMessages as F,
  LanguageSwitcher as L,
  _sfc_main$3 as _,
  _sfc_main$1 as a
};
