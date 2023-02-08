import { h as createBlock, w as withCtx, o as openBlock, b as createBaseVNode, c as createElementBlock, v as renderList, d as createTextVNode, t as toDisplayString, F as Fragment, a as createVNode } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$1 } from "./Pagination-cf5f2783.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "w-full whitespace-nowrap" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("thead", null, [
  /* @__PURE__ */ createBaseVNode("tr", { class: "text-left font-bold" }, [
    /* @__PURE__ */ createBaseVNode("th", { class: "px-2 pb-4 pt-6" }, "Utilisateur"),
    /* @__PURE__ */ createBaseVNode("th", { class: "px-2 pb-4 pt-6" }, "Bans")
  ])
], -1);
const _hoisted_3 = { class: "border-t px-2 py-4" };
const _hoisted_4 = { class: "flex items-center gap-2" };
const _hoisted_5 = ["src"];
const _hoisted_6 = { class: "flex flex-col" };
const _hoisted_7 = { class: "text-xs text-neutral-500" };
const _hoisted_8 = { class: "border-t px-2 py-4" };
const _hoisted_9 = { class: "flex flex-col" };
const _hoisted_10 = { class: "text-xs text-neutral-500" };
const _hoisted_11 = { class: "text-xs text-neutral-500" };
const _hoisted_12 = { class: "text-xs text-neutral-500" };
const _hoisted_13 = { class: "text-xs text-neutral-500" };
const _sfc_main = {
  __name: "BannedUsers",
  props: {
    bannedUsers: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        default: withCtx(() => [
          createBaseVNode("table", _hoisted_1, [
            _hoisted_2,
            createBaseVNode("tbody", null, [
              (openBlock(true), createElementBlock(Fragment, null, renderList(__props.bannedUsers.data, (user) => {
                return openBlock(), createElementBlock("tr", {
                  key: user.id
                }, [
                  createBaseVNode("td", _hoisted_3, [
                    createBaseVNode("div", _hoisted_4, [
                      createBaseVNode("img", {
                        src: user.photo,
                        class: "h-10 w-10 rounded-full"
                      }, null, 8, _hoisted_5),
                      createBaseVNode("div", _hoisted_6, [
                        createTextVNode(toDisplayString(user.name) + " ", 1),
                        createBaseVNode("span", _hoisted_7, "ID : " + toDisplayString(user.id), 1)
                      ])
                    ])
                  ]),
                  createBaseVNode("td", _hoisted_8, [
                    createBaseVNode("ul", _hoisted_9, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(user.bans, (ban) => {
                        return openBlock(), createElementBlock("li", {
                          key: ban.id,
                          class: "flex flex-col border-b border-neutral-600 p-2"
                        }, [
                          createBaseVNode("span", _hoisted_10, "Banni par : " + toDisplayString(ban.banned_by), 1),
                          createBaseVNode("span", _hoisted_11, "le : " + toDisplayString(ban.created_at), 1),
                          createBaseVNode("span", _hoisted_12, "Raison : " + toDisplayString(ban.comment), 1),
                          createBaseVNode("span", _hoisted_13, "Expire le : " + toDisplayString(ban.expired_at), 1)
                        ]);
                      }), 128))
                    ])
                  ])
                ]);
              }), 128))
            ])
          ]),
          createVNode(_sfc_main$1, {
            links: __props.bannedUsers.links
          }, null, 8, ["links"])
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
