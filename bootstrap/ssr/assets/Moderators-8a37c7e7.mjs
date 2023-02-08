import { withCtx, createVNode, openBlock, createBlock, Fragment, renderList, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
import "@inertiajs/vue3";
import { C as Card } from "./Card-ee13c838.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "Moderators",
  __ssrInlineRender: true,
  props: {
    moderators: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<ul${_scopeId}><!--[-->`);
            ssrRenderList(__props.moderators, (room) => {
              _push2(`<li class="mb-4 flex flex-col border-b border-neutral-600"${_scopeId}>${ssrInterpolate(room.name)} <ul class="mb-2 flex flex-wrap gap-1"${_scopeId}><!--[-->`);
              ssrRenderList(room.moderators, (moderator) => {
                _push2(`<li${_scopeId}><span class="badge flex items-center gap-1"${_scopeId}><img${ssrRenderAttr("src", moderator.photo)} class="h-5 w-5 rounded-full"${_scopeId}>${ssrInterpolate(moderator.name)}</span></li>`);
              });
              _push2(`<!--]--></ul></li>`);
            });
            _push2(`<!--]--></ul>`);
          } else {
            return [
              createVNode("ul", null, [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.moderators, (room) => {
                  return openBlock(), createBlock("li", {
                    key: room.id,
                    class: "mb-4 flex flex-col border-b border-neutral-600"
                  }, [
                    createTextVNode(toDisplayString(room.name) + " ", 1),
                    createVNode("ul", { class: "mb-2 flex flex-wrap gap-1" }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                        return openBlock(), createBlock("li", null, [
                          createVNode("span", { class: "badge flex items-center gap-1" }, [
                            createVNode("img", {
                              src: moderator.photo,
                              class: "h-5 w-5 rounded-full"
                            }, null, 8, ["src"]),
                            createTextVNode(toDisplayString(moderator.name), 1)
                          ])
                        ]);
                      }), 256))
                    ])
                  ]);
                }), 128))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Moderation/partials/Moderators.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
