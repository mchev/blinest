import { h as createBlock, w as withCtx, o as openBlock, b as createBaseVNode, c as createElementBlock, v as renderList, d as createTextVNode, t as toDisplayString, F as Fragment } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "mb-2 flex flex-wrap gap-1" };
const _hoisted_2 = { class: "badge flex items-center gap-1" };
const _hoisted_3 = ["src"];
const _sfc_main = {
  __name: "Moderators",
  props: {
    moderators: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        default: withCtx(() => [
          createBaseVNode("ul", null, [
            (openBlock(true), createElementBlock(Fragment, null, renderList(__props.moderators, (room) => {
              return openBlock(), createElementBlock("li", {
                key: room.id,
                class: "mb-4 flex flex-col border-b border-neutral-600"
              }, [
                createTextVNode(toDisplayString(room.name) + " ", 1),
                createBaseVNode("ul", _hoisted_1, [
                  (openBlock(true), createElementBlock(Fragment, null, renderList(room.moderators, (moderator) => {
                    return openBlock(), createElementBlock("li", null, [
                      createBaseVNode("span", _hoisted_2, [
                        createBaseVNode("img", {
                          src: moderator.photo,
                          class: "h-5 w-5 rounded-full"
                        }, null, 8, _hoisted_3),
                        createTextVNode(toDisplayString(moderator.name), 1)
                      ])
                    ]);
                  }), 256))
                ])
              ]);
            }), 128))
          ])
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
