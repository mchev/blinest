import { ref, unref, withCtx, createVNode, toDisplayString, withDirectives, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderClass, ssrInterpolate } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import "./Card-ee13c838.mjs";
import _sfc_main$2 from "./Stats-95bd8567.mjs";
import _sfc_main$5 from "./UsersManagement-de649025.mjs";
import _sfc_main$6 from "./Moderators-8a37c7e7.mjs";
import _sfc_main$3 from "./TrashedMessages-f21ed647.mjs";
import _sfc_main$4 from "./BannedUsers-9f53a1ae.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "./TextInput-cddc091b.mjs";
import "uuid";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "./BanForm-3c9f0f2c.mjs";
import "./SelectInput-279cfc81.mjs";
import "lodash/debounce.js";
import "./Pagination-6aa0e1ca.mjs";
const _sfc_main = {
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    stats: Object,
    moderators: Object,
    trashedMessages: Object,
    bannedUsers: Object
  },
  setup(__props) {
    const tab = ref("trashedMessages");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: _ctx.__("Moderation")
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex-grow"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, { stats: __props.stats }, null, _parent2, _scopeId));
            _push2(`<div${_scopeId}><ul class="flex flex-wrap border-b border-neutral-700 text-center text-sm font-medium text-neutral-400"${_scopeId}><li class="mr-2"${_scopeId}><button type="button" class="${ssrRenderClass([{ "active bg-neutral-800 text-neutral-300": tab.value == "trashedMessages" }, "inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300"])}"${_scopeId}>Messages supprimés (${ssrInterpolate(__props.trashedMessages.total)})</button></li><li class="mr-2"${_scopeId}><button type="button" class="${ssrRenderClass([{ "active bg-neutral-800 text-neutral-300": tab.value == "bannedUsers" }, "inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300"])}"${_scopeId}>Utilisateurs bannis (${ssrInterpolate(__props.bannedUsers.total)})</button></li><li class="mr-2"${_scopeId}><button type="button" class="${ssrRenderClass([{ "active bg-neutral-800 text-neutral-300": tab.value == "userManagement" }, "inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300"])}"${_scopeId}>Gestion des utilisateurs</button></li><li class="mr-2"${_scopeId}><button type="button" class="${ssrRenderClass([{ "active bg-neutral-800 text-neutral-300": tab.value == "moderators" }, "inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300"])}"${_scopeId}>Modérateurs</button></li></ul>`);
            _push2(ssrRenderComponent(_sfc_main$3, {
              style: tab.value === "trashedMessages" ? null : { display: "none" },
              class: "rounded-t-none",
              trashedMessages: __props.trashedMessages
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$4, {
              style: tab.value === "bannedUsers" ? null : { display: "none" },
              class: "rounded-t-none",
              bannedUsers: __props.bannedUsers
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$5, {
              style: tab.value === "userManagement" ? null : { display: "none" },
              class: "rounded-t-none"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$6, {
              style: tab.value === "moderators" ? null : { display: "none" },
              class: "rounded-t-none",
              moderators: __props.moderators
            }, null, _parent2, _scopeId));
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "flex-grow" }, [
                createVNode(_sfc_main$2, { stats: __props.stats }, null, 8, ["stats"]),
                createVNode("div", null, [
                  createVNode("ul", { class: "flex flex-wrap border-b border-neutral-700 text-center text-sm font-medium text-neutral-400" }, [
                    createVNode("li", { class: "mr-2" }, [
                      createVNode("button", {
                        type: "button",
                        onClick: ($event) => tab.value = "trashedMessages",
                        class: ["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "trashedMessages" }]
                      }, "Messages supprimés (" + toDisplayString(__props.trashedMessages.total) + ")", 11, ["onClick"])
                    ]),
                    createVNode("li", { class: "mr-2" }, [
                      createVNode("button", {
                        type: "button",
                        onClick: ($event) => tab.value = "bannedUsers",
                        class: ["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "bannedUsers" }]
                      }, "Utilisateurs bannis (" + toDisplayString(__props.bannedUsers.total) + ")", 11, ["onClick"])
                    ]),
                    createVNode("li", { class: "mr-2" }, [
                      createVNode("button", {
                        type: "button",
                        onClick: ($event) => tab.value = "userManagement",
                        class: ["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "userManagement" }]
                      }, "Gestion des utilisateurs", 10, ["onClick"])
                    ]),
                    createVNode("li", { class: "mr-2" }, [
                      createVNode("button", {
                        type: "button",
                        onClick: ($event) => tab.value = "moderators",
                        class: ["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "moderators" }]
                      }, "Modérateurs", 10, ["onClick"])
                    ])
                  ]),
                  withDirectives(createVNode(_sfc_main$3, {
                    class: "rounded-t-none",
                    trashedMessages: __props.trashedMessages
                  }, null, 8, ["trashedMessages"]), [
                    [vShow, tab.value === "trashedMessages"]
                  ]),
                  withDirectives(createVNode(_sfc_main$4, {
                    class: "rounded-t-none",
                    bannedUsers: __props.bannedUsers
                  }, null, 8, ["bannedUsers"]), [
                    [vShow, tab.value === "bannedUsers"]
                  ]),
                  withDirectives(createVNode(_sfc_main$5, { class: "rounded-t-none" }, null, 512), [
                    [vShow, tab.value === "userManagement"]
                  ]),
                  withDirectives(createVNode(_sfc_main$6, {
                    class: "rounded-t-none",
                    moderators: __props.moderators
                  }, null, 8, ["moderators"]), [
                    [vShow, tab.value === "moderators"]
                  ])
                ])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Moderation/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
