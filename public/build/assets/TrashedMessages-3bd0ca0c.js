import { h as createBlock, w as withCtx, o as openBlock, b as createBaseVNode, c as createElementBlock, v as renderList, d as createTextVNode, t as toDisplayString, a as createVNode, u as unref, n as ne, F as Fragment, s as Je } from "./app-910e457d.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$1 } from "./Pagination-cf5f2783.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _hoisted_1 = { class: "overflow-x-auto" };
const _hoisted_2 = { class: "w-full whitespace-nowrap" };
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("thead", null, [
  /* @__PURE__ */ createBaseVNode("tr", { class: "text-left font-bold" }, [
    /* @__PURE__ */ createBaseVNode("th", { class: "px-2 pb-4 pt-6" }, "Auteur"),
    /* @__PURE__ */ createBaseVNode("th", { class: "px-2 pb-4 pt-6" }, "Date"),
    /* @__PURE__ */ createBaseVNode("th", { class: "px-2 pb-4 pt-6" }, "Message"),
    /* @__PURE__ */ createBaseVNode("th", { class: "px-2 pb-4 pt-6" }, "Signalements"),
    /* @__PURE__ */ createBaseVNode("th", { class: "px-2 pb-4 pt-6" }, "Actions")
  ])
], -1);
const _hoisted_4 = { class: "border-t px-2 py-4" };
const _hoisted_5 = { class: "flex items-center gap-2" };
const _hoisted_6 = ["src"];
const _hoisted_7 = { class: "flex flex-col" };
const _hoisted_8 = { class: "text-xs text-neutral-500" };
const _hoisted_9 = { class: "text-xs text-neutral-500" };
const _hoisted_10 = { class: "border-t px-2 py-4" };
const _hoisted_11 = { class: "flex flex-col" };
const _hoisted_12 = { class: "text-sm" };
const _hoisted_13 = { class: "text-xs text-neutral-500" };
const _hoisted_14 = { class: "text-xs text-neutral-500" };
const _hoisted_15 = { class: "border-t px-2 py-4" };
const _hoisted_16 = { class: "break-all text-sm italic" };
const _hoisted_17 = { class: "border-t px-2 py-4" };
const _hoisted_18 = { class: "flex flex-col" };
const _hoisted_19 = { class: "border-t px-2 py-4" };
const _hoisted_20 = { class: "flex gap-2" };
const _hoisted_21 = ["onClick"];
const _sfc_main = {
  __name: "TrashedMessages",
  props: {
    trashedMessages: Object
  },
  setup(__props) {
    const restoreMessage = (message) => {
      Je.put(route("moderation.messages.restore", message), {
        preserveScroll: true
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        default: withCtx(() => [
          createBaseVNode("div", _hoisted_1, [
            createBaseVNode("table", _hoisted_2, [
              _hoisted_3,
              createBaseVNode("tbody", null, [
                (openBlock(true), createElementBlock(Fragment, null, renderList(__props.trashedMessages.data, (message) => {
                  return openBlock(), createElementBlock("tr", {
                    key: message.id
                  }, [
                    createBaseVNode("td", _hoisted_4, [
                      createBaseVNode("div", _hoisted_5, [
                        createBaseVNode("img", {
                          src: message.user.photo,
                          class: "h-10 w-10 rounded-full"
                        }, null, 8, _hoisted_6),
                        createBaseVNode("div", _hoisted_7, [
                          createTextVNode(toDisplayString(message.user.name) + " ", 1),
                          createBaseVNode("span", _hoisted_8, "ID : " + toDisplayString(message.user.id), 1),
                          createBaseVNode("span", _hoisted_9, "IP : " + toDisplayString(message.user.ip), 1)
                        ])
                      ])
                    ]),
                    createBaseVNode("td", _hoisted_10, [
                      createBaseVNode("div", _hoisted_11, [
                        createBaseVNode("span", _hoisted_12, [
                          createTextVNode(toDisplayString(message.time) + " sur ", 1),
                          createVNode(unref(ne), {
                            href: _ctx.route("rooms.show", message.room.slug)
                          }, {
                            default: withCtx(() => [
                              createTextVNode(toDisplayString(message.room.name), 1)
                            ]),
                            _: 2
                          }, 1032, ["href"])
                        ]),
                        createBaseVNode("span", _hoisted_13, "Creation : " + toDisplayString(message.created_at), 1),
                        createBaseVNode("span", _hoisted_14, "Suppression : " + toDisplayString(message.deleted_at), 1)
                      ])
                    ]),
                    createBaseVNode("td", _hoisted_15, [
                      createBaseVNode("span", _hoisted_16, toDisplayString(message.body), 1)
                    ]),
                    createBaseVNode("td", _hoisted_17, [
                      createBaseVNode("div", _hoisted_18, [
                        createTextVNode(toDisplayString(message.votes.total) + " ", 1),
                        (openBlock(true), createElementBlock(Fragment, null, renderList(message.votes.voters, (user) => {
                          return openBlock(), createElementBlock("ul", {
                            class: "flex gap-2 text-xs text-neutral-500",
                            key: user.id
                          }, [
                            createBaseVNode("li", null, toDisplayString(user.name), 1)
                          ]);
                        }), 128))
                      ])
                    ]),
                    createBaseVNode("td", _hoisted_19, [
                      createBaseVNode("div", _hoisted_20, [
                        createBaseVNode("button", {
                          class: "btn-secondary btn-sm",
                          onClick: ($event) => restoreMessage(message)
                        }, "Restaurer", 8, _hoisted_21)
                      ])
                    ])
                  ]);
                }), 128))
              ])
            ])
          ]),
          createVNode(_sfc_main$1, {
            links: __props.trashedMessages.links
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
