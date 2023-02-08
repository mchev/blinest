import { k as ref, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, f as normalizeClass, t as toDisplayString, z as withDirectives, N as vShow } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import _sfc_main$2 from "./Stats-5714a50c.js";
import _sfc_main$5 from "./UsersManagement-927ddfc8.js";
import _sfc_main$6 from "./Moderators-1ac91483.js";
import _sfc_main$3 from "./TrashedMessages-3bd0ca0c.js";
import _sfc_main$4 from "./BannedUsers-174bc609.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./TextInput-541fe8b5.js";
import "./v4-e7604ebc.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
import "./Card-7ef4ce68.js";
import "./BanForm-d81d44f1.js";
import "./SelectInput-5ecccdd8.js";
import "./Pagination-cf5f2783.js";
const _hoisted_1 = { class: "flex-grow" };
const _hoisted_2 = { class: "flex flex-wrap border-b border-neutral-700 text-center text-sm font-medium text-neutral-400" };
const _hoisted_3 = { class: "mr-2" };
const _hoisted_4 = { class: "mr-2" };
const _hoisted_5 = { class: "mr-2" };
const _hoisted_6 = { class: "mr-2" };
const _sfc_main = {
  __name: "Index",
  props: {
    stats: Object,
    moderators: Object,
    trashedMessages: Object,
    bannedUsers: Object
  },
  setup(__props) {
    const tab = ref("trashedMessages");
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: _ctx.__("Moderation")
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              createVNode(_sfc_main$2, { stats: __props.stats }, null, 8, ["stats"]),
              createBaseVNode("div", null, [
                createBaseVNode("ul", _hoisted_2, [
                  createBaseVNode("li", _hoisted_3, [
                    createBaseVNode("button", {
                      type: "button",
                      onClick: _cache[0] || (_cache[0] = ($event) => tab.value = "trashedMessages"),
                      class: normalizeClass(["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "trashedMessages" }])
                    }, "Messages supprimés (" + toDisplayString(__props.trashedMessages.total) + ")", 3)
                  ]),
                  createBaseVNode("li", _hoisted_4, [
                    createBaseVNode("button", {
                      type: "button",
                      onClick: _cache[1] || (_cache[1] = ($event) => tab.value = "bannedUsers"),
                      class: normalizeClass(["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "bannedUsers" }])
                    }, "Utilisateurs bannis (" + toDisplayString(__props.bannedUsers.total) + ")", 3)
                  ]),
                  createBaseVNode("li", _hoisted_5, [
                    createBaseVNode("button", {
                      type: "button",
                      onClick: _cache[2] || (_cache[2] = ($event) => tab.value = "userManagement"),
                      class: normalizeClass(["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "userManagement" }])
                    }, "Gestion des utilisateurs", 2)
                  ]),
                  createBaseVNode("li", _hoisted_6, [
                    createBaseVNode("button", {
                      type: "button",
                      onClick: _cache[3] || (_cache[3] = ($event) => tab.value = "moderators"),
                      class: normalizeClass(["inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300", { "active bg-neutral-800 text-neutral-300": tab.value == "moderators" }])
                    }, "Modérateurs", 2)
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
          ]),
          _: 1
        })
      ], 64);
    };
  }
};
export {
  _sfc_main as default
};
