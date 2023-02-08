import { k as ref, P, l as watch, h as createBlock, w as withCtx, o as openBlock, a as createVNode, c as createElementBlock, F as Fragment, v as renderList, g as createCommentVNode, b as createBaseVNode, t as toDisplayString, d as createTextVNode, z as withDirectives, N as vShow } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$2 } from "./Dropdown-f0e2d937.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$3 } from "./BanForm-d81d44f1.js";
import { d as debounce_1 } from "./debounce-89ee665e.js";
import "./Icon-86c99edc.js";
import "./v4-e7604ebc.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./SelectInput-5ecccdd8.js";
import "./isSymbol-b518dd01.js";
const _hoisted_1 = {
  key: 0,
  class: "max-w-50 max-h-80 overflow-y-auto"
};
const _hoisted_2 = ["src"];
const _hoisted_3 = { class: "mr-4" };
const _hoisted_4 = ["onClick"];
const _hoisted_5 = { key: 0 };
const _hoisted_6 = { class: "mb-4 flex w-full justify-between" };
const _hoisted_7 = { class: "flex items-center gap-2" };
const _hoisted_8 = ["src", "alt"];
const _hoisted_9 = { class: "text-sm text-neutral-500" };
const _hoisted_10 = { class: "mb-4 flex w-full gap-4" };
const _hoisted_11 = {
  key: 0,
  class: "rounded border border-neutral-600 p-2 md:w-1/2"
};
const _hoisted_12 = /* @__PURE__ */ createBaseVNode("h3", { class: "mb-2 uppercase" }, "Derniers messages", -1);
const _hoisted_13 = { class: "flex flex-col text-xs" };
const _hoisted_14 = { class: "text-neutral-500" };
const _hoisted_15 = {
  key: 0,
  class: "text-red-500"
};
const _hoisted_16 = { class: "break-word" };
const _hoisted_17 = {
  key: 1,
  class: "rounded border border-neutral-600 p-2 md:w-1/2"
};
const _hoisted_18 = /* @__PURE__ */ createBaseVNode("h3", { class: "mb-2 uppercase" }, "Historique des bans", -1);
const _hoisted_19 = {
  key: 0,
  class: "my-2 flex flex-col"
};
const _hoisted_20 = { class: "text-xs text-neutral-500" };
const _hoisted_21 = { class: "text-xs text-neutral-500" };
const _hoisted_22 = { class: "text-xs text-neutral-500" };
const _hoisted_23 = { class: "text-xs text-neutral-500" };
const _hoisted_24 = {
  key: 1,
  class: "text-sm text-neutral-500"
};
const _hoisted_25 = { class: "flex items-center gap-2" };
const _sfc_main = {
  __name: "UsersManagement",
  setup(__props) {
    const search = ref("");
    const searching = ref(false);
    const banningUser = ref(false);
    const users = ref(null);
    const selectedUser = ref(null);
    P({
      user_id: null
    });
    watch(
      search,
      debounce_1(() => {
        searching.value = true;
        axios.get("/api/users", { params: { search: search.value } }).then((response) => {
          users.value = response.data.users;
          searching.value = false;
        });
      }, 300)
    );
    const selectUser = (user) => {
      selectedUser.value = user;
      axios.get(route("moderation.users.informations", user)).then((response) => {
        selectedUser.value = response.data;
      });
    };
    const userHasBeenBanned = () => {
      banningUser.value = false;
      selectUser(selectedUser.value);
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        default: withCtx(() => [
          createVNode(_sfc_main$2, {
            placement: "bottom-start",
            class: "mb-2 pb-2",
            onClosed: _cache[1] || (_cache[1] = ($event) => search.value = "")
          }, {
            default: withCtx(() => [
              createVNode(_sfc_main$1, {
                modelValue: search.value,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => search.value = $event),
                "prepend-icon": "search",
                "append-icon": "cheveron-down",
                loading: searching.value,
                placeholder: "Chercher un utilisateur"
              }, null, 8, ["modelValue", "loading"])
            ]),
            dropdown: withCtx(() => [
              users.value && users.value.data.length ? (openBlock(), createElementBlock("ul", _hoisted_1, [
                (openBlock(true), createElementBlock(Fragment, null, renderList(users.value.data, (user) => {
                  return openBlock(), createElementBlock("li", {
                    key: user.id,
                    class: "flex items-center p-2"
                  }, [
                    user.photo ? (openBlock(), createElementBlock("img", {
                      key: 0,
                      class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                      src: user.photo
                    }, null, 8, _hoisted_2)) : createCommentVNode("", true),
                    createBaseVNode("span", _hoisted_3, toDisplayString(user.name), 1),
                    createBaseVNode("button", {
                      class: "ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                      onClick: ($event) => selectUser(user)
                    }, "Sélectionner", 8, _hoisted_4)
                  ]);
                }), 128))
              ])) : createCommentVNode("", true)
            ]),
            _: 1
          }),
          selectedUser.value ? (openBlock(), createElementBlock("div", _hoisted_5, [
            createBaseVNode("div", _hoisted_6, [
              createBaseVNode("div", _hoisted_7, [
                createBaseVNode("img", {
                  src: selectedUser.value.photo,
                  class: "h-10 w-10 rounded-full",
                  alt: selectedUser.value.name
                }, null, 8, _hoisted_8),
                createTextVNode(" " + toDisplayString(selectedUser.value.name), 1)
              ]),
              createBaseVNode("span", _hoisted_9, "Inscription le " + toDisplayString(selectedUser.value.created_at), 1)
            ]),
            createBaseVNode("div", _hoisted_10, [
              selectedUser.value.latest_messages ? (openBlock(), createElementBlock("div", _hoisted_11, [
                _hoisted_12,
                createBaseVNode("ul", _hoisted_13, [
                  (openBlock(true), createElementBlock(Fragment, null, renderList(selectedUser.value.latest_messages, (message) => {
                    return openBlock(), createElementBlock("li", {
                      key: message.id,
                      class: "mb-2 flex flex-col"
                    }, [
                      createBaseVNode("span", _hoisted_14, [
                        createTextVNode(toDisplayString(message.time) + " sur " + toDisplayString(message.room.name) + " : ", 1),
                        message.deleted_at ? (openBlock(), createElementBlock("span", _hoisted_15, "[Supprimé]")) : createCommentVNode("", true),
                        createTextVNode(" [" + toDisplayString(message.reports) + " signalements]", 1)
                      ]),
                      createBaseVNode("span", _hoisted_16, toDisplayString(message.body), 1)
                    ]);
                  }), 128))
                ])
              ])) : createCommentVNode("", true),
              selectedUser.value.bans ? (openBlock(), createElementBlock("div", _hoisted_17, [
                _hoisted_18,
                selectedUser.value.bans.length ? (openBlock(), createElementBlock("ul", _hoisted_19, [
                  (openBlock(true), createElementBlock(Fragment, null, renderList(selectedUser.value.bans, (ban) => {
                    return openBlock(), createElementBlock("li", {
                      key: ban.id,
                      class: "border-neutral-600-b border-neutral-600-neutral-600 mb-2 flex flex-col border border p-2"
                    }, [
                      createBaseVNode("span", _hoisted_20, "Banni par : " + toDisplayString(ban.banned_by), 1),
                      createBaseVNode("span", _hoisted_21, "le : " + toDisplayString(ban.created_at), 1),
                      createBaseVNode("span", _hoisted_22, "Raison : " + toDisplayString(ban.comment), 1),
                      createBaseVNode("span", _hoisted_23, "Expire le : " + toDisplayString(ban.expired_at), 1)
                    ]);
                  }), 128))
                ])) : (openBlock(), createElementBlock("div", _hoisted_24, "Aucun"))
              ])) : createCommentVNode("", true)
            ]),
            createBaseVNode("div", _hoisted_25, [
              createBaseVNode("button", {
                class: "btn-secondary btn-sm",
                onClick: _cache[2] || (_cache[2] = ($event) => selectedUser.value = null)
              }, "Fermer"),
              withDirectives(createBaseVNode("button", {
                class: "btn-danger btn-sm",
                onClick: _cache[3] || (_cache[3] = ($event) => banningUser.value = true)
              }, "Bannir", 512), [
                [vShow, !banningUser.value]
              ])
            ]),
            withDirectives(createVNode(_sfc_main$3, {
              user: selectedUser.value,
              onUserBanned: userHasBeenBanned
            }, null, 8, ["user"]), [
              [vShow, banningUser.value]
            ])
          ])) : createCommentVNode("", true)
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
