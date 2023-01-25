import { mergeProps, withCtx, createVNode, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { C as Card } from "./Card-eda4b3e2.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "Stats",
  __ssrInlineRender: true,
  props: {
    stats: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "mb-4 flex w-full flex-wrap justify-between gap-4 text-center text-xl" }, _attrs))}>`);
      _push(ssrRenderComponent(Card, { class: "flex-grow" }, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="uppercase text-lg mx-auto"${_scopeId}>Utilisateurs</h3>`);
          } else {
            return [
              createVNode("h3", { class: "uppercase text-lg mx-auto" }, "Utilisateurs")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` ${ssrInterpolate(__props.stats.users_count)}`);
          } else {
            return [
              createTextVNode(" " + toDisplayString(__props.stats.users_count), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(Card, { class: "flex-grow" }, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="uppercase text-lg mx-auto"${_scopeId}>Playlists publiques</h3>`);
          } else {
            return [
              createVNode("h3", { class: "uppercase text-lg mx-auto" }, "Playlists publiques")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` ${ssrInterpolate(__props.stats.public_playlists_count)} / ${ssrInterpolate(__props.stats.playlists_count)}`);
          } else {
            return [
              createTextVNode(" " + toDisplayString(__props.stats.public_playlists_count) + " / " + toDisplayString(__props.stats.playlists_count), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(Card, { class: "flex-grow" }, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="uppercase text-lg mx-auto"${_scopeId}>Rooms publiques</h3>`);
          } else {
            return [
              createVNode("h3", { class: "uppercase text-lg mx-auto" }, "Rooms publiques")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` ${ssrInterpolate(__props.stats.public_rooms_count)} / ${ssrInterpolate(__props.stats.rooms_count)}`);
          } else {
            return [
              createTextVNode(" " + toDisplayString(__props.stats.public_rooms_count) + " / " + toDisplayString(__props.stats.rooms_count), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(Card, { class: "flex-grow" }, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="uppercase text-lg mx-auto"${_scopeId}>Utilisateurs bannis</h3>`);
          } else {
            return [
              createVNode("h3", { class: "uppercase text-lg mx-auto" }, "Utilisateurs bannis")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` ${ssrInterpolate(__props.stats.banned_users_count)}`);
          } else {
            return [
              createTextVNode(" " + toDisplayString(__props.stats.banned_users_count), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Moderation/partials/Stats.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
