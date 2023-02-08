import { withCtx, createVNode, openBlock, createBlock, Fragment, renderList, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import "@inertiajs/vue3";
import { C as Card } from "./Card-ee13c838.mjs";
import { _ as _sfc_main$1 } from "./Pagination-6aa0e1ca.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "BannedUsers",
  __ssrInlineRender: true,
  props: {
    bannedUsers: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<table class="w-full whitespace-nowrap"${_scopeId}><thead${_scopeId}><tr class="text-left font-bold"${_scopeId}><th class="px-2 pb-4 pt-6"${_scopeId}>Utilisateur</th><th class="px-2 pb-4 pt-6"${_scopeId}>Bans</th></tr></thead><tbody${_scopeId}><!--[-->`);
            ssrRenderList(__props.bannedUsers.data, (user) => {
              _push2(`<tr${_scopeId}><td class="border-t px-2 py-4"${_scopeId}><div class="flex items-center gap-2"${_scopeId}><img${ssrRenderAttr("src", user.photo)} class="h-10 w-10 rounded-full"${_scopeId}><div class="flex flex-col"${_scopeId}>${ssrInterpolate(user.name)} <span class="text-xs text-neutral-500"${_scopeId}>ID : ${ssrInterpolate(user.id)}</span></div></div></td><td class="border-t px-2 py-4"${_scopeId}><ul class="flex flex-col"${_scopeId}><!--[-->`);
              ssrRenderList(user.bans, (ban) => {
                _push2(`<li class="flex flex-col border-b border-neutral-600 p-2"${_scopeId}><span class="text-xs text-neutral-500"${_scopeId}>Banni par : ${ssrInterpolate(ban.banned_by)}</span><span class="text-xs text-neutral-500"${_scopeId}>le : ${ssrInterpolate(ban.created_at)}</span><span class="text-xs text-neutral-500"${_scopeId}>Raison : ${ssrInterpolate(ban.comment)}</span><span class="text-xs text-neutral-500"${_scopeId}>Expire le : ${ssrInterpolate(ban.expired_at)}</span></li>`);
              });
              _push2(`<!--]--></ul></td></tr>`);
            });
            _push2(`<!--]--></tbody></table>`);
            _push2(ssrRenderComponent(_sfc_main$1, {
              links: __props.bannedUsers.links
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode("table", { class: "w-full whitespace-nowrap" }, [
                createVNode("thead", null, [
                  createVNode("tr", { class: "text-left font-bold" }, [
                    createVNode("th", { class: "px-2 pb-4 pt-6" }, "Utilisateur"),
                    createVNode("th", { class: "px-2 pb-4 pt-6" }, "Bans")
                  ])
                ]),
                createVNode("tbody", null, [
                  (openBlock(true), createBlock(Fragment, null, renderList(__props.bannedUsers.data, (user) => {
                    return openBlock(), createBlock("tr", {
                      key: user.id
                    }, [
                      createVNode("td", { class: "border-t px-2 py-4" }, [
                        createVNode("div", { class: "flex items-center gap-2" }, [
                          createVNode("img", {
                            src: user.photo,
                            class: "h-10 w-10 rounded-full"
                          }, null, 8, ["src"]),
                          createVNode("div", { class: "flex flex-col" }, [
                            createTextVNode(toDisplayString(user.name) + " ", 1),
                            createVNode("span", { class: "text-xs text-neutral-500" }, "ID : " + toDisplayString(user.id), 1)
                          ])
                        ])
                      ]),
                      createVNode("td", { class: "border-t px-2 py-4" }, [
                        createVNode("ul", { class: "flex flex-col" }, [
                          (openBlock(true), createBlock(Fragment, null, renderList(user.bans, (ban) => {
                            return openBlock(), createBlock("li", {
                              key: ban.id,
                              class: "flex flex-col border-b border-neutral-600 p-2"
                            }, [
                              createVNode("span", { class: "text-xs text-neutral-500" }, "Banni par : " + toDisplayString(ban.banned_by), 1),
                              createVNode("span", { class: "text-xs text-neutral-500" }, "le : " + toDisplayString(ban.created_at), 1),
                              createVNode("span", { class: "text-xs text-neutral-500" }, "Raison : " + toDisplayString(ban.comment), 1),
                              createVNode("span", { class: "text-xs text-neutral-500" }, "Expire le : " + toDisplayString(ban.expired_at), 1)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Moderation/partials/BannedUsers.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
