import { withCtx, unref, createTextVNode, toDisplayString, createVNode, openBlock, createBlock, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import { Link, router } from "@inertiajs/vue3";
import { C as Card } from "./Card-ee13c838.mjs";
import { _ as _sfc_main$1 } from "./Pagination-6aa0e1ca.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "TrashedMessages",
  __ssrInlineRender: true,
  props: {
    trashedMessages: Object
  },
  setup(__props) {
    const restoreMessage = (message) => {
      router.put(route("moderation.messages.restore", message), {
        preserveScroll: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="overflow-x-auto"${_scopeId}><table class="w-full whitespace-nowrap"${_scopeId}><thead${_scopeId}><tr class="text-left font-bold"${_scopeId}><th class="px-2 pb-4 pt-6"${_scopeId}>Auteur</th><th class="px-2 pb-4 pt-6"${_scopeId}>Date</th><th class="px-2 pb-4 pt-6"${_scopeId}>Message</th><th class="px-2 pb-4 pt-6"${_scopeId}>Signalements</th><th class="px-2 pb-4 pt-6"${_scopeId}>Actions</th></tr></thead><tbody${_scopeId}><!--[-->`);
            ssrRenderList(__props.trashedMessages.data, (message) => {
              _push2(`<tr${_scopeId}><td class="border-t px-2 py-4"${_scopeId}><div class="flex items-center gap-2"${_scopeId}><img${ssrRenderAttr("src", message.user.photo)} class="h-10 w-10 rounded-full"${_scopeId}><div class="flex flex-col"${_scopeId}>${ssrInterpolate(message.user.name)} <span class="text-xs text-neutral-500"${_scopeId}>ID : ${ssrInterpolate(message.user.id)}</span><span class="text-xs text-neutral-500"${_scopeId}>IP : ${ssrInterpolate(message.user.ip)}</span></div></div></td><td class="border-t px-2 py-4"${_scopeId}><div class="flex flex-col"${_scopeId}><span class="text-sm"${_scopeId}>${ssrInterpolate(message.time)} sur `);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("rooms.show", message.room.slug)
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(message.room.name)}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(message.room.name), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
              _push2(`</span><span class="text-xs text-neutral-500"${_scopeId}>Creation : ${ssrInterpolate(message.created_at)}</span><span class="text-xs text-neutral-500"${_scopeId}>Suppression : ${ssrInterpolate(message.deleted_at)}</span></div></td><td class="border-t px-2 py-4"${_scopeId}><span class="break-all text-sm italic"${_scopeId}>${ssrInterpolate(message.body)}</span></td><td class="border-t px-2 py-4"${_scopeId}><div class="flex flex-col"${_scopeId}>${ssrInterpolate(message.votes.total)} <!--[-->`);
              ssrRenderList(message.votes.voters, (user) => {
                _push2(`<ul class="flex gap-2 text-xs text-neutral-500"${_scopeId}><li${_scopeId}>${ssrInterpolate(user.name)}</li></ul>`);
              });
              _push2(`<!--]--></div></td><td class="border-t px-2 py-4"${_scopeId}><div class="flex gap-2"${_scopeId}><button class="btn-secondary btn-sm"${_scopeId}>Restaurer</button></div></td></tr>`);
            });
            _push2(`<!--]--></tbody></table></div>`);
            _push2(ssrRenderComponent(_sfc_main$1, {
              links: __props.trashedMessages.links
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode("div", { class: "overflow-x-auto" }, [
                createVNode("table", { class: "w-full whitespace-nowrap" }, [
                  createVNode("thead", null, [
                    createVNode("tr", { class: "text-left font-bold" }, [
                      createVNode("th", { class: "px-2 pb-4 pt-6" }, "Auteur"),
                      createVNode("th", { class: "px-2 pb-4 pt-6" }, "Date"),
                      createVNode("th", { class: "px-2 pb-4 pt-6" }, "Message"),
                      createVNode("th", { class: "px-2 pb-4 pt-6" }, "Signalements"),
                      createVNode("th", { class: "px-2 pb-4 pt-6" }, "Actions")
                    ])
                  ]),
                  createVNode("tbody", null, [
                    (openBlock(true), createBlock(Fragment, null, renderList(__props.trashedMessages.data, (message) => {
                      return openBlock(), createBlock("tr", {
                        key: message.id
                      }, [
                        createVNode("td", { class: "border-t px-2 py-4" }, [
                          createVNode("div", { class: "flex items-center gap-2" }, [
                            createVNode("img", {
                              src: message.user.photo,
                              class: "h-10 w-10 rounded-full"
                            }, null, 8, ["src"]),
                            createVNode("div", { class: "flex flex-col" }, [
                              createTextVNode(toDisplayString(message.user.name) + " ", 1),
                              createVNode("span", { class: "text-xs text-neutral-500" }, "ID : " + toDisplayString(message.user.id), 1),
                              createVNode("span", { class: "text-xs text-neutral-500" }, "IP : " + toDisplayString(message.user.ip), 1)
                            ])
                          ])
                        ]),
                        createVNode("td", { class: "border-t px-2 py-4" }, [
                          createVNode("div", { class: "flex flex-col" }, [
                            createVNode("span", { class: "text-sm" }, [
                              createTextVNode(toDisplayString(message.time) + " sur ", 1),
                              createVNode(unref(Link), {
                                href: _ctx.route("rooms.show", message.room.slug)
                              }, {
                                default: withCtx(() => [
                                  createTextVNode(toDisplayString(message.room.name), 1)
                                ]),
                                _: 2
                              }, 1032, ["href"])
                            ]),
                            createVNode("span", { class: "text-xs text-neutral-500" }, "Creation : " + toDisplayString(message.created_at), 1),
                            createVNode("span", { class: "text-xs text-neutral-500" }, "Suppression : " + toDisplayString(message.deleted_at), 1)
                          ])
                        ]),
                        createVNode("td", { class: "border-t px-2 py-4" }, [
                          createVNode("span", { class: "break-all text-sm italic" }, toDisplayString(message.body), 1)
                        ]),
                        createVNode("td", { class: "border-t px-2 py-4" }, [
                          createVNode("div", { class: "flex flex-col" }, [
                            createTextVNode(toDisplayString(message.votes.total) + " ", 1),
                            (openBlock(true), createBlock(Fragment, null, renderList(message.votes.voters, (user) => {
                              return openBlock(), createBlock("ul", {
                                class: "flex gap-2 text-xs text-neutral-500",
                                key: user.id
                              }, [
                                createVNode("li", null, toDisplayString(user.name), 1)
                              ]);
                            }), 128))
                          ])
                        ]),
                        createVNode("td", { class: "border-t px-2 py-4" }, [
                          createVNode("div", { class: "flex gap-2" }, [
                            createVNode("button", {
                              class: "btn-secondary btn-sm",
                              onClick: ($event) => restoreMessage(message)
                            }, "Restaurer", 8, ["onClick"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Moderation/partials/TrashedMessages.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
