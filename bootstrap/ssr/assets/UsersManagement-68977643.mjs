import { ref, watch, withCtx, createVNode, openBlock, createBlock, Fragment, renderList, createCommentVNode, toDisplayString, createTextVNode, withDirectives, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrRenderStyle } from "vue/server-renderer";
import { useForm } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$1 } from "./Dropdown-6785e0d2.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { _ as _sfc_main$3 } from "./BanForm-3c9f0f2c.mjs";
import debounce from "lodash/debounce.js";
import "uuid";
import "./Icon-4a47e6e0.mjs";
import "@popperjs/core";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./SelectInput-279cfc81.mjs";
const _sfc_main = {
  __name: "UsersManagement",
  __ssrInlineRender: true,
  setup(__props) {
    const search = ref("");
    const searching = ref(false);
    const banningUser = ref(false);
    const users = ref(null);
    const selectedUser = ref(null);
    useForm({
      user_id: null
    });
    watch(
      search,
      debounce(() => {
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$1, {
              placement: "bottom-start",
              class: "mb-2 pb-2",
              onClosed: ($event) => search.value = ""
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    modelValue: search.value,
                    "onUpdate:modelValue": ($event) => search.value = $event,
                    "prepend-icon": "search",
                    "append-icon": "cheveron-down",
                    loading: searching.value,
                    placeholder: "Chercher un utilisateur"
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$2, {
                      modelValue: search.value,
                      "onUpdate:modelValue": ($event) => search.value = $event,
                      "prepend-icon": "search",
                      "append-icon": "cheveron-down",
                      loading: searching.value,
                      placeholder: "Chercher un utilisateur"
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "loading"])
                  ];
                }
              }),
              dropdown: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (users.value && users.value.data.length) {
                    _push3(`<ul class="max-w-50 max-h-80 overflow-y-auto"${_scopeId2}><!--[-->`);
                    ssrRenderList(users.value.data, (user) => {
                      _push3(`<li class="flex items-center p-2"${_scopeId2}>`);
                      if (user.photo) {
                        _push3(`<img class="-my-2 mr-2 block h-8 w-8 rounded-full"${ssrRenderAttr("src", user.photo)}${_scopeId2}>`);
                      } else {
                        _push3(`<!---->`);
                      }
                      _push3(`<span class="mr-4"${_scopeId2}>${ssrInterpolate(user.name)}</span><button class="ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase"${_scopeId2}>Sélectionner</button></li>`);
                    });
                    _push3(`<!--]--></ul>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    users.value && users.value.data.length ? (openBlock(), createBlock("ul", {
                      key: 0,
                      class: "max-w-50 max-h-80 overflow-y-auto"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(users.value.data, (user) => {
                        return openBlock(), createBlock("li", {
                          key: user.id,
                          class: "flex items-center p-2"
                        }, [
                          user.photo ? (openBlock(), createBlock("img", {
                            key: 0,
                            class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                            src: user.photo
                          }, null, 8, ["src"])) : createCommentVNode("", true),
                          createVNode("span", { class: "mr-4" }, toDisplayString(user.name), 1),
                          createVNode("button", {
                            class: "ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                            onClick: ($event) => selectUser(user)
                          }, "Sélectionner", 8, ["onClick"])
                        ]);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            if (selectedUser.value) {
              _push2(`<div${_scopeId}><div class="mb-4 flex w-full justify-between"${_scopeId}><div class="flex items-center gap-2"${_scopeId}><img${ssrRenderAttr("src", selectedUser.value.photo)} class="h-10 w-10 rounded-full"${ssrRenderAttr("alt", selectedUser.value.name)}${_scopeId}> ${ssrInterpolate(selectedUser.value.name)}</div><span class="text-sm text-neutral-500"${_scopeId}>Inscription le ${ssrInterpolate(selectedUser.value.created_at)}</span></div><div class="mb-4 flex w-full gap-4"${_scopeId}>`);
              if (selectedUser.value.latest_messages) {
                _push2(`<div class="rounded border border-neutral-600 p-2 md:w-1/2"${_scopeId}><h3 class="mb-2 uppercase"${_scopeId}>Derniers messages</h3><ul class="flex flex-col text-xs"${_scopeId}><!--[-->`);
                ssrRenderList(selectedUser.value.latest_messages, (message) => {
                  _push2(`<li class="mb-2 flex flex-col"${_scopeId}><span class="text-neutral-500"${_scopeId}>${ssrInterpolate(message.time)} sur ${ssrInterpolate(message.room.name)} : `);
                  if (message.deleted_at) {
                    _push2(`<span class="text-red-500"${_scopeId}>[Supprimé]</span>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(` [${ssrInterpolate(message.reports)} signalements]</span><span class="break-word"${_scopeId}>${ssrInterpolate(message.body)}</span></li>`);
                });
                _push2(`<!--]--></ul></div>`);
              } else {
                _push2(`<!---->`);
              }
              if (selectedUser.value.bans) {
                _push2(`<div class="rounded border border-neutral-600 p-2 md:w-1/2"${_scopeId}><h3 class="mb-2 uppercase"${_scopeId}>Historique des bans</h3>`);
                if (selectedUser.value.bans.length) {
                  _push2(`<ul class="my-2 flex flex-col"${_scopeId}><!--[-->`);
                  ssrRenderList(selectedUser.value.bans, (ban) => {
                    _push2(`<li class="flex flex-col mb-2 border border-neutral-600-b border border-neutral-600-neutral-600 p-2"${_scopeId}><span class="text-xs text-neutral-500"${_scopeId}>Banni par : ${ssrInterpolate(ban.banned_by)}</span><span class="text-xs text-neutral-500"${_scopeId}>le : ${ssrInterpolate(ban.created_at)}</span><span class="text-xs text-neutral-500"${_scopeId}>Raison : ${ssrInterpolate(ban.comment)}</span><span class="text-xs text-neutral-500"${_scopeId}>Expire le : ${ssrInterpolate(ban.expired_at)}</span></li>`);
                  });
                  _push2(`<!--]--></ul>`);
                } else {
                  _push2(`<div class="text-sm text-neutral-500"${_scopeId}> Aucun </div>`);
                }
                _push2(`</div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div><div class="flex items-center gap-2"${_scopeId}><button class="btn-secondary btn-sm"${_scopeId}>Fermer</button><button style="${ssrRenderStyle(!banningUser.value ? null : { display: "none" })}" class="btn-danger btn-sm"${_scopeId}>Bannir</button></div>`);
              _push2(ssrRenderComponent(_sfc_main$3, {
                style: banningUser.value ? null : { display: "none" },
                user: selectedUser.value,
                onUserBanned: userHasBeenBanned
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              createVNode(_sfc_main$1, {
                placement: "bottom-start",
                class: "mb-2 pb-2",
                onClosed: ($event) => search.value = ""
              }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$2, {
                    modelValue: search.value,
                    "onUpdate:modelValue": ($event) => search.value = $event,
                    "prepend-icon": "search",
                    "append-icon": "cheveron-down",
                    loading: searching.value,
                    placeholder: "Chercher un utilisateur"
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "loading"])
                ]),
                dropdown: withCtx(() => [
                  users.value && users.value.data.length ? (openBlock(), createBlock("ul", {
                    key: 0,
                    class: "max-w-50 max-h-80 overflow-y-auto"
                  }, [
                    (openBlock(true), createBlock(Fragment, null, renderList(users.value.data, (user) => {
                      return openBlock(), createBlock("li", {
                        key: user.id,
                        class: "flex items-center p-2"
                      }, [
                        user.photo ? (openBlock(), createBlock("img", {
                          key: 0,
                          class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                          src: user.photo
                        }, null, 8, ["src"])) : createCommentVNode("", true),
                        createVNode("span", { class: "mr-4" }, toDisplayString(user.name), 1),
                        createVNode("button", {
                          class: "ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                          onClick: ($event) => selectUser(user)
                        }, "Sélectionner", 8, ["onClick"])
                      ]);
                    }), 128))
                  ])) : createCommentVNode("", true)
                ]),
                _: 1
              }, 8, ["onClosed"]),
              selectedUser.value ? (openBlock(), createBlock("div", { key: 0 }, [
                createVNode("div", { class: "mb-4 flex w-full justify-between" }, [
                  createVNode("div", { class: "flex items-center gap-2" }, [
                    createVNode("img", {
                      src: selectedUser.value.photo,
                      class: "h-10 w-10 rounded-full",
                      alt: selectedUser.value.name
                    }, null, 8, ["src", "alt"]),
                    createTextVNode(" " + toDisplayString(selectedUser.value.name), 1)
                  ]),
                  createVNode("span", { class: "text-sm text-neutral-500" }, "Inscription le " + toDisplayString(selectedUser.value.created_at), 1)
                ]),
                createVNode("div", { class: "mb-4 flex w-full gap-4" }, [
                  selectedUser.value.latest_messages ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "rounded border border-neutral-600 p-2 md:w-1/2"
                  }, [
                    createVNode("h3", { class: "mb-2 uppercase" }, "Derniers messages"),
                    createVNode("ul", { class: "flex flex-col text-xs" }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(selectedUser.value.latest_messages, (message) => {
                        return openBlock(), createBlock("li", {
                          key: message.id,
                          class: "mb-2 flex flex-col"
                        }, [
                          createVNode("span", { class: "text-neutral-500" }, [
                            createTextVNode(toDisplayString(message.time) + " sur " + toDisplayString(message.room.name) + " : ", 1),
                            message.deleted_at ? (openBlock(), createBlock("span", {
                              key: 0,
                              class: "text-red-500"
                            }, "[Supprimé]")) : createCommentVNode("", true),
                            createTextVNode(" [" + toDisplayString(message.reports) + " signalements]", 1)
                          ]),
                          createVNode("span", { class: "break-word" }, toDisplayString(message.body), 1)
                        ]);
                      }), 128))
                    ])
                  ])) : createCommentVNode("", true),
                  selectedUser.value.bans ? (openBlock(), createBlock("div", {
                    key: 1,
                    class: "rounded border border-neutral-600 p-2 md:w-1/2"
                  }, [
                    createVNode("h3", { class: "mb-2 uppercase" }, "Historique des bans"),
                    selectedUser.value.bans.length ? (openBlock(), createBlock("ul", {
                      key: 0,
                      class: "my-2 flex flex-col"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(selectedUser.value.bans, (ban) => {
                        return openBlock(), createBlock("li", {
                          key: ban.id,
                          class: "flex flex-col mb-2 border border-neutral-600-b border border-neutral-600-neutral-600 p-2"
                        }, [
                          createVNode("span", { class: "text-xs text-neutral-500" }, "Banni par : " + toDisplayString(ban.banned_by), 1),
                          createVNode("span", { class: "text-xs text-neutral-500" }, "le : " + toDisplayString(ban.created_at), 1),
                          createVNode("span", { class: "text-xs text-neutral-500" }, "Raison : " + toDisplayString(ban.comment), 1),
                          createVNode("span", { class: "text-xs text-neutral-500" }, "Expire le : " + toDisplayString(ban.expired_at), 1)
                        ]);
                      }), 128))
                    ])) : (openBlock(), createBlock("div", {
                      key: 1,
                      class: "text-sm text-neutral-500"
                    }, " Aucun "))
                  ])) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "flex items-center gap-2" }, [
                  createVNode("button", {
                    class: "btn-secondary btn-sm",
                    onClick: ($event) => selectedUser.value = null
                  }, "Fermer", 8, ["onClick"]),
                  withDirectives(createVNode("button", {
                    class: "btn-danger btn-sm",
                    onClick: ($event) => banningUser.value = true
                  }, "Bannir", 8, ["onClick"]), [
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Moderation/partials/UsersManagement.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
