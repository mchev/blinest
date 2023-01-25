import { computed, unref, withCtx, createVNode, toDisplayString, useSSRContext, ref, mergeProps, createTextVNode, openBlock, createBlock, createCommentVNode, withDirectives, vShow, onMounted, onUnmounted, Fragment, renderList, Transition } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderSlot, ssrRenderAttrs, ssrRenderClass, ssrRenderList } from "vue/server-renderer";
import { usePage, Head, useForm } from "@inertiajs/vue3";
import { F as FlashMessages } from "./LanguageSwitcher-18bdae21.mjs";
import "./Dropdown-6785e0d2.mjs";
import "./Icon-4a47e6e0.mjs";
import { _ as _sfc_main$6 } from "./Navbar-d136d2f8.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { S as Spinner } from "./Spinner-ec1c59c5.mjs";
import { _ as _sfc_main$7 } from "./Modal-e38f3366.mjs";
import { _ as _sfc_main$b } from "./TextInput-cddc091b.mjs";
import "./SelectInput-279cfc81.mjs";
import { _ as _sfc_main$8 } from "./BanForm-3c9f0f2c.mjs";
import { T as Tip } from "./Tip-9a139e5c.mjs";
import { _ as _sfc_main$9 } from "./TextareaInput-989d56d6.mjs";
import { _ as _sfc_main$a } from "./Share-188342a1.mjs";
import _sfc_main$c from "./LoginForm-cfb98086.mjs";
import _sfc_main$h from "./Controls-f59be8ad.mjs";
import _sfc_main$d from "./Player-3b43d535.mjs";
import _sfc_main$e from "./UserInput-258c9c02.mjs";
import _sfc_main$f from "./Answers-91e9850c.mjs";
import _sfc_main$g from "./Ranking-9c79d7e6.mjs";
import _sfc_main$i from "./FinishedRoundModal-5045bd0e.mjs";
import _sfc_main$j from "./SendSuggestionModal-c21dcb14.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "@popperjs/core";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
import "./LoadingButton-14ad237b.mjs";
import "./Socialite-680e42f3.mjs";
import "./EditOptionsForm-88410024.mjs";
import "./CheckboxInput-934baa4b.mjs";
import "./PodiumModal-951e3b18.mjs";
import "./Podium-31a549cb.mjs";
const _sfc_main$5 = {
  __name: "RoomLayout",
  __ssrInlineRender: true,
  setup(__props) {
    const room = computed(() => usePage().props.room);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<title${_scopeId}>${ssrInterpolate(unref(room).name)}</title><meta name="description"${ssrRenderAttr("content", unref(room).description)}${_scopeId}><meta property="og:url"${ssrRenderAttr("content", unref(room).url)}${_scopeId}><meta property="og:type" content="website"${_scopeId}><meta property="og:image"${ssrRenderAttr("content", unref(room).photo_src ? unref(room).photo_src : unref(room).photo)}${_scopeId}><meta property="og:title"${ssrRenderAttr("content", unref(room).name + " - Blinest.com")}${_scopeId}><meta property="og:description"${ssrRenderAttr("content", unref(room).description)}${_scopeId}><meta name="twitter:card" content="summary_large_image"${_scopeId}><meta property="twitter:domain" content="blinest.com"${_scopeId}><meta property="twitter:url"${ssrRenderAttr("content", unref(room).url)}${_scopeId}><meta name="twitter:description"${ssrRenderAttr("content", unref(room).description)}${_scopeId}><meta name="twitter:title"${ssrRenderAttr("content", unref(room).name + " - Blinest")}${_scopeId}><meta name="twitter:image"${ssrRenderAttr("content", unref(room).photo_src ? unref(room).photo_src : unref(room).photo)}${_scopeId}>`);
          } else {
            return [
              createVNode("title", null, toDisplayString(unref(room).name), 1),
              createVNode("meta", {
                name: "description",
                content: unref(room).description
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:url",
                content: unref(room).url
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:type",
                content: "website"
              }),
              createVNode("meta", {
                property: "og:image",
                content: unref(room).photo_src ? unref(room).photo_src : unref(room).photo
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:title",
                content: unref(room).name + " - Blinest.com"
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:description",
                content: unref(room).description
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:card",
                content: "summary_large_image"
              }),
              createVNode("meta", {
                property: "twitter:domain",
                content: "blinest.com"
              }),
              createVNode("meta", {
                property: "twitter:url",
                content: unref(room).url
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:description",
                content: unref(room).description
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:title",
                content: unref(room).name + " - Blinest"
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:image",
                content: unref(room).photo_src ? unref(room).photo_src : unref(room).photo
              }, null, 8, ["content"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<div class="text-neutral-200"><div id="dropdown"></div><div class="md:flex md:flex-col"><div class="md:flex md:h-screen md:flex-col">`);
      _push(ssrRenderComponent(_sfc_main$6, null, null, _parent));
      _push(`<div class="md:flex md:flex-grow md:overflow-hidden">`);
      if (_ctx.$slots.default) {
        _push(`<div class="md:flex-1">`);
        _push(ssrRenderComponent(FlashMessages, null, null, _parent));
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></div></div><!--]-->`);
    };
  }
};
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/RoomLayout.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const _sfc_main$4 = {
  __name: "Moderation",
  __ssrInlineRender: true,
  props: {
    message: Object,
    room: Object
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    const user = usePage().props.auth.user;
    const authorIsModerator = props.room.moderators.find((x) => x.id === props.message.user.id);
    const userIsPublicModerator = usePage().props.publicModerators.find((x) => x.id === user.id);
    const show = ref(true);
    const showingBanForm = ref(false);
    const deleteMessage = () => {
      axios.delete(`/moderation/messages/${props.message.id}`).then((response) => {
        close();
      });
    };
    const close = () => {
      emit("close");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$7, mergeProps({
        show: show.value,
        onClose: close
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex w-full items-center justify-between"${_scopeId2}><h2${_scopeId2}>${ssrInterpolate(_ctx.__("Moderation"))}</h2><span class="pr-2 text-sm italic"${_scopeId2}>${ssrInterpolate(__props.message.user.name)}</span></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex w-full items-center justify-between" }, [
                      createVNode("h2", null, toDisplayString(_ctx.__("Moderation")), 1),
                      createVNode("span", { class: "pr-2 text-sm italic" }, toDisplayString(__props.message.user.name), 1)
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<blockquote class="my-2 border-l px-4 py-2 font-mono text-neutral-400"${_scopeId2}><time class="mr-1 text-xs"${_scopeId2}>${ssrInterpolate(__props.message.time)}</time><br${_scopeId2}> ${ssrInterpolate(__props.message.body)}</blockquote><div class="my-4 flex items-center gap-4"${_scopeId2}><button class="btn-danger btn-sm"${_scopeId2}>${ssrInterpolate(_ctx.__("Delete message"))}</button>`);
                  if (!unref(authorIsModerator) && unref(userIsPublicModerator)) {
                    _push3(`<button class="btn-danger btn-sm"${_scopeId2}>${ssrInterpolate(_ctx.__("Ban"))} ${ssrInterpolate(__props.message.user.name)}</button>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`<button class="btn-secondary btn-sm"${_scopeId2}>${ssrInterpolate(_ctx.__("Cancel"))}</button></div>`);
                  _push3(ssrRenderComponent(_sfc_main$8, {
                    style: showingBanForm.value ? null : { display: "none" },
                    user: __props.message.user
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("blockquote", { class: "my-2 border-l px-4 py-2 font-mono text-neutral-400" }, [
                      createVNode("time", { class: "mr-1 text-xs" }, toDisplayString(__props.message.time), 1),
                      createVNode("br"),
                      createTextVNode(" " + toDisplayString(__props.message.body), 1)
                    ]),
                    createVNode("div", { class: "my-4 flex items-center gap-4" }, [
                      createVNode("button", {
                        class: "btn-danger btn-sm",
                        onClick: deleteMessage
                      }, toDisplayString(_ctx.__("Delete message")), 1),
                      !unref(authorIsModerator) && unref(userIsPublicModerator) ? (openBlock(), createBlock("button", {
                        key: 0,
                        class: "btn-danger btn-sm",
                        onClick: ($event) => showingBanForm.value = !showingBanForm.value
                      }, toDisplayString(_ctx.__("Ban")) + " " + toDisplayString(__props.message.user.name), 9, ["onClick"])) : createCommentVNode("", true),
                      createVNode("button", {
                        class: "btn-secondary btn-sm",
                        onClick: close
                      }, toDisplayString(_ctx.__("Cancel")), 1)
                    ]),
                    withDirectives(createVNode(_sfc_main$8, {
                      user: __props.message.user
                    }, null, 8, ["user"]), [
                      [vShow, showingBanForm.value]
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, null, {
                header: withCtx(() => [
                  createVNode("div", { class: "flex w-full items-center justify-between" }, [
                    createVNode("h2", null, toDisplayString(_ctx.__("Moderation")), 1),
                    createVNode("span", { class: "pr-2 text-sm italic" }, toDisplayString(__props.message.user.name), 1)
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("blockquote", { class: "my-2 border-l px-4 py-2 font-mono text-neutral-400" }, [
                    createVNode("time", { class: "mr-1 text-xs" }, toDisplayString(__props.message.time), 1),
                    createVNode("br"),
                    createTextVNode(" " + toDisplayString(__props.message.body), 1)
                  ]),
                  createVNode("div", { class: "my-4 flex items-center gap-4" }, [
                    createVNode("button", {
                      class: "btn-danger btn-sm",
                      onClick: deleteMessage
                    }, toDisplayString(_ctx.__("Delete message")), 1),
                    !unref(authorIsModerator) && unref(userIsPublicModerator) ? (openBlock(), createBlock("button", {
                      key: 0,
                      class: "btn-danger btn-sm",
                      onClick: ($event) => showingBanForm.value = !showingBanForm.value
                    }, toDisplayString(_ctx.__("Ban")) + " " + toDisplayString(__props.message.user.name), 9, ["onClick"])) : createCommentVNode("", true),
                    createVNode("button", {
                      class: "btn-secondary btn-sm",
                      onClick: close
                    }, toDisplayString(_ctx.__("Cancel")), 1)
                  ]),
                  withDirectives(createVNode(_sfc_main$8, {
                    user: __props.message.user
                  }, null, 8, ["user"]), [
                    [vShow, showingBanForm.value]
                  ])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Chat/Moderation.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const _sfc_main$3 = {
  __name: "Message",
  __ssrInlineRender: true,
  props: {
    room: Object,
    message: Object
  },
  setup(__props) {
    const props = __props;
    const moderate = ref(false);
    const reporting = ref(false);
    const user = usePage().props.auth.user;
    const isModerator = props.room.moderators.find((x) => x.id === user.id);
    const userIsPublicModerator = usePage().props.publicModerators.find((x) => x.id === user.id);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "group relative my-1 flex flex-wrap items-center text-sm hover:opacity-90" }, _attrs))}>`);
      if (!unref(usePage)().props.publicModerators.find((x) => x.id === __props.message.user.id)) {
        _push(`<div class="${ssrRenderClass([__props.message.reports < 0 ? "flex" : "hidden", "absolute top-0 right-0 items-center bg-neutral-800 py-1 px-2 group-hover:flex"])}">`);
        if (reporting.value) {
          _push(`<div><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 animate-spin"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path></svg></div>`);
        } else {
          _push(`<button class="flex items-center text-xs"><span class="mr-1 text-yellow-600">${ssrInterpolate(__props.message.reports)}</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-red-500"><title>${ssrInterpolate(_ctx.__("Report this message"))}</title><path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"></path></svg></button>`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<time class="mr-2 text-xs italic text-neutral-500">${ssrInterpolate(__props.message.time)}</time>`);
      if (unref(isModerator) || unref(userIsPublicModerator)) {
        _push(`<button class="${ssrRenderClass([__props.room.moderators.find((x) => x.id === __props.message.user.id) ? "text-purple-500" : "text-neutral-400", "mr-1 flex items-center"])}"><img${ssrRenderAttr("src", __props.message.user.photo)}${ssrRenderAttr("alt", __props.message.user.name)} class="mr-1 h-7 w-7 rounded-full"> ${ssrInterpolate(__props.message.user.name)} `);
        if (__props.message.user.team) {
          _push(`<sup class="mx-1 text-[9px] uppercase">[${ssrInterpolate(__props.message.user.team.name)}]</sup>`);
        } else {
          _push(`<!---->`);
        }
        _push(` : </button>`);
      } else {
        _push(`<span class="${ssrRenderClass([__props.room.moderators.find((x) => x.id === __props.message.user.id) ? "text-purple-500" : "text-neutral-400", "mr-1 flex items-center"])}"><img${ssrRenderAttr("src", __props.message.user.photo)}${ssrRenderAttr("alt", __props.message.user.name)} class="mr-1 h-7 w-7 rounded-full"> ${ssrInterpolate(__props.message.user.name)} `);
        if (__props.message.user.team) {
          _push(`<sup class="mx-1 text-[9px] uppercase">[${ssrInterpolate(__props.message.user.team.name)}]</sup>`);
        } else {
          _push(`<!---->`);
        }
        _push(` : </span>`);
      }
      _push(`<span class="">${ssrInterpolate(__props.message.body)}</span>`);
      if (moderate.value) {
        _push(ssrRenderComponent(_sfc_main$4, {
          message: __props.message,
          room: __props.room,
          onClose: ($event) => moderate.value = false
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Chat/Message.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = {
  __name: "AlertModeratorsModal",
  __ssrInlineRender: true,
  props: {
    room: Object
  },
  emits: ["close", "reported"],
  setup(__props, { emit }) {
    const props = __props;
    const show = ref(false);
    const form = useForm({
      message: ""
    });
    const alertModerators = () => {
      form.post(`/rooms/${props.room.id}/alert`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          emit("reported");
          emit("close");
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$7, mergeProps({
        show: show.value,
        onClose: ($event) => _ctx.$emit("close")
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<h3 class="flex items-center pl-4 text-xl font-bold"${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"${_scopeId2}></path></svg> ${ssrInterpolate(_ctx.__("Alerting moderators"))}</h3>`);
                } else {
                  return [
                    createVNode("h3", { class: "flex items-center pl-4 text-xl font-bold" }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "mr-2 h-5 w-5",
                        fill: "none",
                        viewBox: "0 0 24 24",
                        stroke: "currentColor",
                        "stroke-width": "2"
                      }, [
                        createVNode("path", {
                          "stroke-linecap": "round",
                          "stroke-linejoin": "round",
                          d: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                        })
                      ])),
                      createTextVNode(" " + toDisplayString(_ctx.__("Alerting moderators")), 1)
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="p-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(Tip, null, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Attention, cette fonctionnalité n&#39;est à utiliser qu&#39;en cas de problème de respect des règles sur le chat ou d&#39;une panne sur la partie.`);
                      } else {
                        return [
                          createTextVNode("Attention, cette fonctionnalité n'est à utiliser qu'en cas de problème de respect des règles sur le chat ou d'une panne sur la partie.")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$9, {
                    modelValue: unref(form).message,
                    "onUpdate:modelValue": ($event) => unref(form).message = $event,
                    error: unref(form).errors.message,
                    label: "Commentaire (facultatif)"
                  }, null, _parent3, _scopeId2));
                  _push3(`<div class="mt-8 flex justify-end"${_scopeId2}><button class="btn-secondary mr-2"${_scopeId2}>${ssrInterpolate(_ctx.__("Close"))}</button><button class="btn-danger"${_scopeId2}>${ssrInterpolate(_ctx.__("Alerting moderators"))}</button></div></div>`);
                } else {
                  return [
                    createVNode("div", { class: "p-4" }, [
                      createVNode(Tip, null, {
                        default: withCtx(() => [
                          createTextVNode("Attention, cette fonctionnalité n'est à utiliser qu'en cas de problème de respect des règles sur le chat ou d'une panne sur la partie.")
                        ]),
                        _: 1
                      }),
                      createVNode(_sfc_main$9, {
                        modelValue: unref(form).message,
                        "onUpdate:modelValue": ($event) => unref(form).message = $event,
                        error: unref(form).errors.message,
                        label: "Commentaire (facultatif)"
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                      createVNode("div", { class: "mt-8 flex justify-end" }, [
                        createVNode("button", {
                          class: "btn-secondary mr-2",
                          onClick: ($event) => _ctx.$emit("close")
                        }, toDisplayString(_ctx.__("Close")), 9, ["onClick"]),
                        createVNode("button", {
                          class: "btn-danger",
                          onClick: alertModerators
                        }, toDisplayString(_ctx.__("Alerting moderators")), 1)
                      ])
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(Card, null, {
                header: withCtx(() => [
                  createVNode("h3", { class: "flex items-center pl-4 text-xl font-bold" }, [
                    (openBlock(), createBlock("svg", {
                      xmlns: "http://www.w3.org/2000/svg",
                      class: "mr-2 h-5 w-5",
                      fill: "none",
                      viewBox: "0 0 24 24",
                      stroke: "currentColor",
                      "stroke-width": "2"
                    }, [
                      createVNode("path", {
                        "stroke-linecap": "round",
                        "stroke-linejoin": "round",
                        d: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                      })
                    ])),
                    createTextVNode(" " + toDisplayString(_ctx.__("Alerting moderators")), 1)
                  ])
                ]),
                default: withCtx(() => [
                  createVNode("div", { class: "p-4" }, [
                    createVNode(Tip, null, {
                      default: withCtx(() => [
                        createTextVNode("Attention, cette fonctionnalité n'est à utiliser qu'en cas de problème de respect des règles sur le chat ou d'une panne sur la partie.")
                      ]),
                      _: 1
                    }),
                    createVNode(_sfc_main$9, {
                      modelValue: unref(form).message,
                      "onUpdate:modelValue": ($event) => unref(form).message = $event,
                      error: unref(form).errors.message,
                      label: "Commentaire (facultatif)"
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                    createVNode("div", { class: "mt-8 flex justify-end" }, [
                      createVNode("button", {
                        class: "btn-secondary mr-2",
                        onClick: ($event) => _ctx.$emit("close")
                      }, toDisplayString(_ctx.__("Close")), 9, ["onClick"]),
                      createVNode("button", {
                        class: "btn-danger",
                        onClick: alertModerators
                      }, toDisplayString(_ctx.__("Alerting moderators")), 1)
                    ])
                  ])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Chat/AlertModeratorsModal.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = {
  __name: "Chat",
  __ssrInlineRender: true,
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const channel = `chat-room.${props.room.id}`;
    const body = ref("");
    const messages = ref(props.room.latest_messages);
    usePage().props.auth.user;
    const messenger = ref();
    const reported = ref(null);
    const alertingModerators = ref(null);
    onMounted(() => {
      Echo.private(channel).listen("NewMessage", (e) => {
        messages.value.unshift(...[e.message]);
        scrollToBottom();
      }).listen("MessageReported", (e) => {
        messages.value = messages.value.map((x) => {
          if (x.id === e.message.id)
            return e.message;
          return x;
        });
      }).listen("MessageDeleted", (e) => {
        messages.value = messages.value.filter((x) => {
          return x.id != e.message.id;
        });
      });
    });
    onUnmounted(() => {
      Echo.leave(channel);
    });
    const scrollToBottom = () => {
      let container = messenger.value;
      container.scrollTop = container.scrollHeight + 1e3;
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><div class="flex h-full flex-col"><div class="flex justify-center items-center gap-2 px-2 py-4">`);
      if (!reported.value) {
        _push(`<button class="btn-secondary btn-sm bg-neutral-700"><svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg> ${ssrInterpolate(_ctx.__("Alerting moderators"))}</button>`);
      } else {
        _push(`<div class="flex items-center text-green-500"><svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg> ${ssrInterpolate(_ctx.__("Alert sent"))}</div>`);
      }
      _push(ssrRenderComponent(_sfc_main$a, {
        url: __props.room.url,
        class: "w-5 h-5"
      }, null, _parent));
      _push(`</div><div class="flex flex-1 flex-col-reverse overflow-y-scroll p-2"><!--[-->`);
      ssrRenderList(messages.value, (message) => {
        _push(ssrRenderComponent(_sfc_main$3, {
          key: message.id,
          message,
          room: __props.room
        }, null, _parent));
      });
      _push(`<!--]--></div><div class="flex w-full p-2"><form class="flex w-full">`);
      _push(ssrRenderComponent(_sfc_main$b, {
        modelValue: body.value,
        "onUpdate:modelValue": ($event) => body.value = $event,
        autocomplete: "off",
        inputClass: "rounded-r-none border-0",
        class: "flex-grow"
      }, null, _parent));
      _push(`<button type="submit" class="rounded-r bg-teal-600 p-2 text-neutral-100"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><title>${ssrInterpolate(_ctx.__("Send"))}</title><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"></path></svg></button></form></div></div>`);
      _push(ssrRenderComponent(_sfc_main$2, {
        room: __props.room,
        show: alertingModerators.value,
        onReported: ($event) => reported.value = true,
        onClose: ($event) => alertingModerators.value = false
      }, null, _parent));
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Chat/Chat.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const user = usePage().props.auth.user;
    const channel = `rooms.${props.room.id}`;
    const round = ref(null);
    const joined = ref(false);
    const users = ref([]);
    const data = ref(null);
    const roundFinished = ref(false);
    const sendingSuggestion = ref(false);
    const showSidebar = ref(true);
    const currentTime = ref(0);
    const users_podium = ref([]);
    const teams_podium = ref([]);
    onMounted(() => {
      if (user) {
        Echo.join(channel).here((usersHere) => {
          users.value = usersHere;
          joining();
        }).joining((user2) => {
          users.value.push(user2);
        }).leaving((user2) => {
          users.value = users.value.filter((u) => u.id !== user2.id);
        }).error((error) => {
          console.error(error);
        });
      }
    });
    onUnmounted(() => {
      Echo.leave(channel);
    });
    const joining = () => {
      axios.get(`/rooms/${props.room.id}/joined`).then(() => {
        joined.value = true;
        listenRounds();
      });
    };
    const listenRounds = () => {
      Echo.channel(channel).listen("RoundStarted", (e) => {
        console.warn("Round started");
        round.value = e.round;
        roundFinished.value = false;
      }).listen("RoundFinished", (e) => {
        console.warn("Round finished");
        round.value = e.round;
        users_podium.value = e.users_podium;
        teams_podium.value = e.teams_podium;
        roundFinished.value = true;
      }).listen("TrackPlayed", (e) => {
        round.value = e.round;
        props.room.value = e.room;
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$5, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (!unref(user)) {
              _push2(ssrRenderComponent(_sfc_main$7, { show: true }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(ssrRenderComponent(_sfc_main$c, { isFromModal: true }, null, _parent3, _scopeId2));
                  } else {
                    return [
                      createVNode(_sfc_main$c, { isFromModal: true })
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            if (!joined.value && unref(user)) {
              _push2(`<div class="flex h-full w-full items-center justify-center"${_scopeId}>`);
              _push2(ssrRenderComponent(Spinner, null, null, _parent2, _scopeId));
              _push2(`<h2${_scopeId}>${ssrInterpolate(_ctx.__("Loading"))}...</h2></div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(``);
            if (joined.value || !unref(user)) {
              _push2(`<div class="h-full md:flex"${_scopeId}><div class="relative flex-1 overflow-y-auto p-4 md:px-12 md:py-8" scroll-region${_scopeId}><article class="mb-4 flex items-center"${_scopeId}><h2 class="mr-2 text-xl font-bold"${_scopeId}>${ssrInterpolate(__props.room.name)}</h2>`);
              _push2(ssrRenderComponent(_sfc_main$a, {
                url: __props.room.url,
                class: "w-5"
              }, null, _parent2, _scopeId));
              _push2(`</article>`);
              if (!__props.room.is_autostart && (!round.value || !round.value.is_playing) && !__props.room.is_playing) {
                _push2(ssrRenderComponent(Tip, { class: "bg-orange-400 text-orange-800" }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`<span class="font-bold"${_scopeId2}>${ssrInterpolate(_ctx.__("This room is in manual start mode."))}</span><br${_scopeId2}> ${ssrInterpolate(_ctx.__("The person responsible for the room (moderators) must be present to start the game."))}`);
                    } else {
                      return [
                        createVNode("span", { class: "font-bold" }, toDisplayString(_ctx.__("This room is in manual start mode.")), 1),
                        createVNode("br"),
                        createTextVNode(" " + toDisplayString(_ctx.__("The person responsible for the room (moderators) must be present to start the game.")), 1)
                      ];
                    }
                  }),
                  _: 1
                }, _parent2, _scopeId));
              } else {
                _push2(`<!---->`);
              }
              if (unref(user)) {
                _push2(`<div class="mb-4 md:mb-8"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$d, {
                  room: __props.room,
                  channel,
                  "onTrack:currentTime": ($event) => currentTime.value = $event
                }, null, _parent2, _scopeId));
                _push2(ssrRenderComponent(_sfc_main$e, {
                  channel,
                  currentTime: currentTime.value,
                  room: __props.room
                }, null, _parent2, _scopeId));
                _push2(`</div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<div class="grid md:grid-cols-2 md:gap-8"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$f, {
                class: "mb-4 md:mb-8",
                users: users.value,
                channel
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(_sfc_main$g, {
                class: "mb-4 md:mb-8",
                room: __props.room,
                users: users.value,
                channel,
                data: data.value
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
              if (unref(user)) {
                _push2(`<!--[-->`);
                if (__props.room.moderators.find((x) => unref(user).id === x.id)) {
                  _push2(ssrRenderComponent(_sfc_main$h, {
                    room: __props.room,
                    round: round.value,
                    channel,
                    class: "mb-4"
                  }, null, _parent2, _scopeId));
                } else {
                  _push2(`<!---->`);
                }
                _push2(`<!--]-->`);
              } else {
                _push2(`<!---->`);
              }
              _push2(ssrRenderComponent(Card, null, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="flex items-center flex-col lg:flex-row lg:justify-between gap-4 text-sm"${_scopeId2}><div${_scopeId2}><div class="mx-auto flex flex-wrap items-center gap-4"${_scopeId2}><span class="uppercase text-neutral-500"${_scopeId2}>Modos</span><!--[-->`);
                    ssrRenderList(__props.room.moderators, (moderator) => {
                      _push3(`<span class="${ssrRenderClass([{ "font-bold text-teal-500": users.value.find((x) => moderator.id === x.id) }, "flex items-center"])}"${_scopeId2}><img${ssrRenderAttr("src", moderator.photo)}${ssrRenderAttr("alt", moderator.name)}${ssrRenderAttr("title", moderator.name)} class="mr-1 h-8 w-8 rounded-full"${_scopeId2}> ${ssrInterpolate(moderator.name)}</span>`);
                    });
                    _push3(`<!--]--></div></div>`);
                    if (unref(user)) {
                      _push3(`<div class="flex items-center gap-4"${_scopeId2}><button class="btn-secondary bg-neutral-900 btn-sm"${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1 h-5 w-5"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"${_scopeId2}></path></svg> ${ssrInterpolate(_ctx.__("Send a suggestion"))}</button><span class="uppercase text-neutral-500"${_scopeId2}>${ssrInterpolate(__props.room.tracks_count)} audios</span></div>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    _push3(`</div>`);
                  } else {
                    return [
                      createVNode("div", { class: "flex items-center flex-col lg:flex-row lg:justify-between gap-4 text-sm" }, [
                        createVNode("div", null, [
                          createVNode("div", { class: "mx-auto flex flex-wrap items-center gap-4" }, [
                            createVNode("span", { class: "uppercase text-neutral-500" }, "Modos"),
                            (openBlock(true), createBlock(Fragment, null, renderList(__props.room.moderators, (moderator) => {
                              return openBlock(), createBlock("span", {
                                class: ["flex items-center", { "font-bold text-teal-500": users.value.find((x) => moderator.id === x.id) }]
                              }, [
                                createVNode("img", {
                                  src: moderator.photo,
                                  alt: moderator.name,
                                  title: moderator.name,
                                  class: "mr-1 h-8 w-8 rounded-full"
                                }, null, 8, ["src", "alt", "title"]),
                                createTextVNode(" " + toDisplayString(moderator.name), 1)
                              ], 2);
                            }), 256))
                          ])
                        ]),
                        unref(user) ? (openBlock(), createBlock("div", {
                          key: 0,
                          class: "flex items-center gap-4"
                        }, [
                          createVNode("button", {
                            class: "btn-secondary bg-neutral-900 btn-sm",
                            onClick: ($event) => sendingSuggestion.value = true
                          }, [
                            (openBlock(), createBlock("svg", {
                              xmlns: "http://www.w3.org/2000/svg",
                              fill: "none",
                              viewBox: "0 0 24 24",
                              "stroke-width": "1.5",
                              stroke: "currentColor",
                              class: "mr-1 h-5 w-5"
                            }, [
                              createVNode("path", {
                                "stroke-linecap": "round",
                                "stroke-linejoin": "round",
                                d: "M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"
                              })
                            ])),
                            createTextVNode(" " + toDisplayString(_ctx.__("Send a suggestion")), 1)
                          ], 8, ["onClick"]),
                          createVNode("span", { class: "uppercase text-neutral-500" }, toDisplayString(__props.room.tracks_count) + " audios", 1)
                        ])) : createCommentVNode("", true)
                      ])
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              if (unref(user) && __props.room.is_chat_active) {
                _push2(`<button class="absolute right-0 top-5 hidden rounded-l-lg bg-neutral-800 p-2 md:block"${ssrRenderAttr("title", _ctx.__("Hide/Show chatbox"))}${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"${_scopeId}></path></svg></button>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div>`);
              if (unref(user) && showSidebar.value && __props.room.is_chat_active) {
                _push2(`<div class="flex h-96 w-full flex-shrink-0 flex-col rounded-tl border-neutral-700 bg-neutral-800 transition-all duration-300 md:h-full md:w-1/5"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$1, { room: __props.room }, null, _parent2, _scopeId));
                _push2(`</div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
            if (round.value) {
              _push2(ssrRenderComponent(_sfc_main$i, {
                show: roundFinished.value,
                round: round.value,
                users_podium: users_podium.value,
                teams_podium: teams_podium.value,
                onClose: ($event) => roundFinished.value = false
              }, null, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            if (unref(user)) {
              _push2(ssrRenderComponent(_sfc_main$j, {
                show: sendingSuggestion.value,
                room: __props.room,
                onClose: ($event) => sendingSuggestion.value = false
              }, null, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              !unref(user) ? (openBlock(), createBlock(_sfc_main$7, {
                key: 0,
                show: true
              }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$c, { isFromModal: true })
                ]),
                _: 1
              })) : createCommentVNode("", true),
              !joined.value && unref(user) ? (openBlock(), createBlock("div", {
                key: 1,
                class: "flex h-full w-full items-center justify-center"
              }, [
                createVNode(Spinner),
                createVNode("h2", null, toDisplayString(_ctx.__("Loading")) + "...", 1)
              ])) : createCommentVNode("", true),
              createVNode(Transition, { name: "slide-right" }, {
                default: withCtx(() => [
                  joined.value || !unref(user) ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "h-full md:flex"
                  }, [
                    createVNode("div", {
                      class: "relative flex-1 overflow-y-auto p-4 md:px-12 md:py-8",
                      "scroll-region": ""
                    }, [
                      createVNode("article", { class: "mb-4 flex items-center" }, [
                        createVNode("h2", { class: "mr-2 text-xl font-bold" }, toDisplayString(__props.room.name), 1),
                        createVNode(_sfc_main$a, {
                          url: __props.room.url,
                          class: "w-5"
                        }, null, 8, ["url"])
                      ]),
                      !__props.room.is_autostart && (!round.value || !round.value.is_playing) && !__props.room.is_playing ? (openBlock(), createBlock(Tip, {
                        key: 0,
                        class: "bg-orange-400 text-orange-800"
                      }, {
                        default: withCtx(() => [
                          createVNode("span", { class: "font-bold" }, toDisplayString(_ctx.__("This room is in manual start mode.")), 1),
                          createVNode("br"),
                          createTextVNode(" " + toDisplayString(_ctx.__("The person responsible for the room (moderators) must be present to start the game.")), 1)
                        ]),
                        _: 1
                      })) : createCommentVNode("", true),
                      unref(user) ? (openBlock(), createBlock("div", {
                        key: 1,
                        class: "mb-4 md:mb-8"
                      }, [
                        createVNode(_sfc_main$d, {
                          room: __props.room,
                          channel,
                          "onTrack:currentTime": ($event) => currentTime.value = $event
                        }, null, 8, ["room", "onTrack:currentTime"]),
                        createVNode(_sfc_main$e, {
                          channel,
                          currentTime: currentTime.value,
                          room: __props.room
                        }, null, 8, ["currentTime", "room"])
                      ])) : createCommentVNode("", true),
                      createVNode("div", { class: "grid md:grid-cols-2 md:gap-8" }, [
                        createVNode(_sfc_main$f, {
                          class: "mb-4 md:mb-8",
                          users: users.value,
                          channel
                        }, null, 8, ["users"]),
                        createVNode(_sfc_main$g, {
                          class: "mb-4 md:mb-8",
                          room: __props.room,
                          users: users.value,
                          channel,
                          data: data.value
                        }, null, 8, ["room", "users", "data"])
                      ]),
                      unref(user) ? (openBlock(), createBlock(Fragment, { key: 2 }, [
                        __props.room.moderators.find((x) => unref(user).id === x.id) ? (openBlock(), createBlock(_sfc_main$h, {
                          key: 0,
                          room: __props.room,
                          round: round.value,
                          channel,
                          class: "mb-4"
                        }, null, 8, ["room", "round"])) : createCommentVNode("", true)
                      ], 64)) : createCommentVNode("", true),
                      createVNode(Card, null, {
                        default: withCtx(() => [
                          createVNode("div", { class: "flex items-center flex-col lg:flex-row lg:justify-between gap-4 text-sm" }, [
                            createVNode("div", null, [
                              createVNode("div", { class: "mx-auto flex flex-wrap items-center gap-4" }, [
                                createVNode("span", { class: "uppercase text-neutral-500" }, "Modos"),
                                (openBlock(true), createBlock(Fragment, null, renderList(__props.room.moderators, (moderator) => {
                                  return openBlock(), createBlock("span", {
                                    class: ["flex items-center", { "font-bold text-teal-500": users.value.find((x) => moderator.id === x.id) }]
                                  }, [
                                    createVNode("img", {
                                      src: moderator.photo,
                                      alt: moderator.name,
                                      title: moderator.name,
                                      class: "mr-1 h-8 w-8 rounded-full"
                                    }, null, 8, ["src", "alt", "title"]),
                                    createTextVNode(" " + toDisplayString(moderator.name), 1)
                                  ], 2);
                                }), 256))
                              ])
                            ]),
                            unref(user) ? (openBlock(), createBlock("div", {
                              key: 0,
                              class: "flex items-center gap-4"
                            }, [
                              createVNode("button", {
                                class: "btn-secondary bg-neutral-900 btn-sm",
                                onClick: ($event) => sendingSuggestion.value = true
                              }, [
                                (openBlock(), createBlock("svg", {
                                  xmlns: "http://www.w3.org/2000/svg",
                                  fill: "none",
                                  viewBox: "0 0 24 24",
                                  "stroke-width": "1.5",
                                  stroke: "currentColor",
                                  class: "mr-1 h-5 w-5"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    d: "M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"
                                  })
                                ])),
                                createTextVNode(" " + toDisplayString(_ctx.__("Send a suggestion")), 1)
                              ], 8, ["onClick"]),
                              createVNode("span", { class: "uppercase text-neutral-500" }, toDisplayString(__props.room.tracks_count) + " audios", 1)
                            ])) : createCommentVNode("", true)
                          ])
                        ]),
                        _: 1
                      }),
                      unref(user) && __props.room.is_chat_active ? (openBlock(), createBlock("button", {
                        key: 3,
                        class: "absolute right-0 top-5 hidden rounded-l-lg bg-neutral-800 p-2 md:block",
                        onClick: ($event) => showSidebar.value = !showSidebar.value,
                        title: _ctx.__("Hide/Show chatbox")
                      }, [
                        (openBlock(), createBlock("svg", {
                          xmlns: "http://www.w3.org/2000/svg",
                          class: "h-8 w-8",
                          fill: "none",
                          viewBox: "0 0 24 24",
                          stroke: "currentColor",
                          "stroke-width": "2"
                        }, [
                          createVNode("path", {
                            "stroke-linecap": "round",
                            "stroke-linejoin": "round",
                            d: "M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                          })
                        ]))
                      ], 8, ["onClick", "title"])) : createCommentVNode("", true)
                    ]),
                    unref(user) && showSidebar.value && __props.room.is_chat_active ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex h-96 w-full flex-shrink-0 flex-col rounded-tl border-neutral-700 bg-neutral-800 transition-all duration-300 md:h-full md:w-1/5"
                    }, [
                      createVNode(_sfc_main$1, { room: __props.room }, null, 8, ["room"])
                    ])) : createCommentVNode("", true)
                  ])) : createCommentVNode("", true)
                ]),
                _: 1
              }),
              round.value ? (openBlock(), createBlock(_sfc_main$i, {
                key: 2,
                show: roundFinished.value,
                round: round.value,
                users_podium: users_podium.value,
                teams_podium: teams_podium.value,
                onClose: ($event) => roundFinished.value = false
              }, null, 8, ["show", "round", "users_podium", "teams_podium", "onClose"])) : createCommentVNode("", true),
              unref(user) ? (openBlock(), createBlock(_sfc_main$j, {
                key: 3,
                show: sendingSuggestion.value,
                room: __props.room,
                onClose: ($event) => sendingSuggestion.value = false
              }, null, 8, ["show", "room", "onClose"])) : createCommentVNode("", true)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Rooms/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
