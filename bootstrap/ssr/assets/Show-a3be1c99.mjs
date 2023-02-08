import { unref, withCtx, createTextVNode, createVNode, toDisplayString, withModifiers, openBlock, createBlock, createCommentVNode, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList } from "vue/server-renderer";
import { usePage, useForm, Head, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-58e562cc.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$3 } from "./FileInput-ae863f9e.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import { C as Card } from "./Card-ee13c838.mjs";
import { T as Tip } from "./Tip-027e2e8e.mjs";
import { _ as _sfc_main$4 } from "./Share-6d7e5e1b.mjs";
import "./LanguageSwitcher-e37332a0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-4b87aa4b.mjs";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
import "./Modal-61e7836d.mjs";
const _sfc_main = {
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    team: Object,
    score: Number,
    members: Object
  },
  setup(__props) {
    const props = __props;
    const user = usePage().props.auth.user;
    const form = useForm({
      _method: "put",
      name: props.team.name,
      photo: null
    });
    const leave = () => {
      if (confirm("Are you sure?")) {
        router.post(`/teams/${props.team.id}/leave`);
      }
    };
    const sendRequest = (team) => {
      router.post(`/teams/${team.id}/request`);
    };
    const cancelRequest = (team) => {
      router.post(`/teams/${team.id}/request/cancel`);
    };
    const switchOwner = (member) => {
      if (confirm("Veux-tu vraiment transferer la gestion de la team à ce membre ?")) {
        router.post(`/teams/${props.team.id}/owner/${member.id}`, {
          preserveState: false
        });
      }
    };
    const removeMember = (member) => {
      if (confirm("Veux-tu vraiment retirer ce membre de la team ?")) {
        router.post(`/teams/${props.team.id}/members/${member.id}/remove`, {
          onSuccess: () => {
            console.log("Member removed");
            memberList.value = memberList.value.filter((e) => e.id !== member.id);
          }
        });
      }
    };
    const updateTeam = () => {
      form.post(route("teams.update", props.team.id), {
        preserveScroll: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: __props.team.name
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="mx-auto mt-8 max-w-xl"${_scopeId}>`);
            if (unref(user).id === __props.team.user_id) {
              _push2(ssrRenderComponent(Card, { class: "my-8" }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<form${_scopeId2}>`);
                    _push3(ssrRenderComponent(_sfc_main$2, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": ($event) => unref(form).name = $event,
                      label: _ctx.__("Name"),
                      class: "mb-4",
                      error: unref(form).errors.name
                    }, null, _parent3, _scopeId2));
                    if (unref(user).permissions.canUploadImage) {
                      _push3(ssrRenderComponent(_sfc_main$3, {
                        modelValue: unref(form).photo,
                        "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                        label: _ctx.__("Image"),
                        class: "mb-4",
                        error: unref(form).errors.photo
                      }, null, _parent3, _scopeId2));
                    } else {
                      _push3(`<!---->`);
                    }
                    if (!unref(user).permissions.canUploadImage) {
                      _push3(ssrRenderComponent(Tip, null, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(` Pour changer l&#39;image de la team il faut minimum 3 mois d&#39;ancienneté et un score total de 2000<sup${_scopeId3}>PTS</sup>. `);
                          } else {
                            return [
                              createTextVNode(" Pour changer l'image de la team il faut minimum 3 mois d'ancienneté et un score total de 2000"),
                              createVNode("sup", null, "PTS"),
                              createTextVNode(". ")
                            ];
                          }
                        }),
                        _: 1
                      }, _parent3, _scopeId2));
                    } else {
                      _push3(`<!---->`);
                    }
                    _push3(ssrRenderComponent(LoadingButton, {
                      type: "submit",
                      onClick: updateTeam,
                      loading: unref(form).processing,
                      class: "btn-primary mb-4 ml-auto"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(_ctx.__("Update"))}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                    _push3(`</form>`);
                  } else {
                    return [
                      createVNode("form", {
                        onSubmit: withModifiers(updateTeam, ["prevent"])
                      }, [
                        createVNode(_sfc_main$2, {
                          modelValue: unref(form).name,
                          "onUpdate:modelValue": ($event) => unref(form).name = $event,
                          label: _ctx.__("Name"),
                          class: "mb-4",
                          error: unref(form).errors.name
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                        unref(user).permissions.canUploadImage ? (openBlock(), createBlock(_sfc_main$3, {
                          key: 0,
                          modelValue: unref(form).photo,
                          "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                          label: _ctx.__("Image"),
                          class: "mb-4",
                          error: unref(form).errors.photo
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"])) : createCommentVNode("", true),
                        !unref(user).permissions.canUploadImage ? (openBlock(), createBlock(Tip, { key: 1 }, {
                          default: withCtx(() => [
                            createTextVNode(" Pour changer l'image de la team il faut minimum 3 mois d'ancienneté et un score total de 2000"),
                            createVNode("sup", null, "PTS"),
                            createTextVNode(". ")
                          ]),
                          _: 1
                        })) : createCommentVNode("", true),
                        createVNode(LoadingButton, {
                          type: "submit",
                          onClick: updateTeam,
                          loading: unref(form).processing,
                          class: "btn-primary mb-4 ml-auto"
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                          ]),
                          _: 1
                        }, 8, ["loading"])
                      ], 40, ["onSubmit"])
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="flex items-center justify-between text-xl"${_scopeId}><div class="mb-6 flex items-center"${_scopeId}>`);
            if (__props.team.photo) {
              _push2(`<img class="-my-2 mr-2 block h-10 w-10 rounded-full"${ssrRenderAttr("src", __props.team.photo)}${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<h1 class="font-bold"${_scopeId}>Team ${ssrInterpolate(__props.team.name)}</h1></div><span${_scopeId}>${ssrInterpolate(__props.score)}<sup class="ml-1"${_scopeId}>PTS</sup></span></div>`);
            _push2(ssrRenderComponent(Card, null, {
              header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="mx-2 flex w-full items-center justify-between"${_scopeId2}><div class="flex items-center"${_scopeId2}>${ssrInterpolate(_ctx.__("Members"))}</div><span${_scopeId2}>${ssrInterpolate(Object.entries(__props.members).length)} / ${ssrInterpolate(__props.team.seats)}</span></div>`);
                } else {
                  return [
                    createVNode("div", { class: "mx-2 flex w-full items-center justify-between" }, [
                      createVNode("div", { class: "flex items-center" }, toDisplayString(_ctx.__("Members")), 1),
                      createVNode("span", null, toDisplayString(Object.entries(__props.members).length) + " / " + toDisplayString(__props.team.seats), 1)
                    ])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<ul${_scopeId2}><!--[-->`);
                  ssrRenderList(Object.values(__props.members).sort((a, b) => b.score - a.score), (member, index) => {
                    _push3(`<li class="m-2 flex items-center rounded bg-neutral-900 p-4"${_scopeId2}><div class="px-4 text-xl font-bold"${_scopeId2}>${ssrInterpolate(Number(index) + 1)}</div><div class="flex flex-grow items-center gap-2 pr-4"${_scopeId2}>`);
                    if (member.photo) {
                      _push3(`<img class="-my-2 mr-2 block h-8 w-8 rounded-full"${ssrRenderAttr("src", member.photo)}${_scopeId2}>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    _push3(` ${ssrInterpolate(member.name)}</div><div class="mr-4 flex items-center gap-2"${_scopeId2}>`);
                    if (member.id === __props.team.user_id) {
                      _push3(`<span${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 fill-yellow-500"${_scopeId2}><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"${_scopeId2}></path></svg></span>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    if (unref(user).id === __props.team.user_id && member.id != __props.team.user_id) {
                      _push3(`<button class="group"${ssrRenderAttr("title", _ctx.__("Switch owner"))} type="button"${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 group-hover:fill-yellow-500"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"${_scopeId2}></path></svg></button>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    if (unref(user).id === __props.team.user_id && member.id != __props.team.user_id) {
                      _push3(`<button class="text-red-600" type="button" title="Retirer le membre de la team"${_scopeId2}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"${_scopeId2}><path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"${_scopeId2}></path></svg></button>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    _push3(`</div><div${_scopeId2}>${ssrInterpolate(member.score)}<sup${_scopeId2}>PTS</sup></div></li>`);
                  });
                  _push3(`<!--]--></ul>`);
                } else {
                  return [
                    createVNode("ul", null, [
                      (openBlock(true), createBlock(Fragment, null, renderList(Object.values(__props.members).sort((a, b) => b.score - a.score), (member, index) => {
                        return openBlock(), createBlock("li", {
                          key: member.id,
                          class: "m-2 flex items-center rounded bg-neutral-900 p-4"
                        }, [
                          createVNode("div", { class: "px-4 text-xl font-bold" }, toDisplayString(Number(index) + 1), 1),
                          createVNode("div", { class: "flex flex-grow items-center gap-2 pr-4" }, [
                            member.photo ? (openBlock(), createBlock("img", {
                              key: 0,
                              class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                              src: member.photo
                            }, null, 8, ["src"])) : createCommentVNode("", true),
                            createTextVNode(" " + toDisplayString(member.name), 1)
                          ]),
                          createVNode("div", { class: "mr-4 flex items-center gap-2" }, [
                            member.id === __props.team.user_id ? (openBlock(), createBlock("span", { key: 0 }, [
                              (openBlock(), createBlock("svg", {
                                xmlns: "http://www.w3.org/2000/svg",
                                viewBox: "0 0 24 24",
                                fill: "currentColor",
                                class: "h-4 w-4 fill-yellow-500"
                              }, [
                                createVNode("path", {
                                  "fill-rule": "evenodd",
                                  d: "M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z",
                                  "clip-rule": "evenodd"
                                })
                              ]))
                            ])) : createCommentVNode("", true),
                            unref(user).id === __props.team.user_id && member.id != __props.team.user_id ? (openBlock(), createBlock("button", {
                              key: 1,
                              onClick: ($event) => switchOwner(member),
                              class: "group",
                              title: _ctx.__("Switch owner"),
                              type: "button"
                            }, [
                              (openBlock(), createBlock("svg", {
                                xmlns: "http://www.w3.org/2000/svg",
                                fill: "none",
                                viewBox: "0 0 24 24",
                                "stroke-width": "1.5",
                                stroke: "currentColor",
                                class: "h-4 w-4 group-hover:fill-yellow-500"
                              }, [
                                createVNode("path", {
                                  "stroke-linecap": "round",
                                  "stroke-linejoin": "round",
                                  d: "M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                })
                              ]))
                            ], 8, ["onClick", "title"])) : createCommentVNode("", true),
                            unref(user).id === __props.team.user_id && member.id != __props.team.user_id ? (openBlock(), createBlock("button", {
                              key: 2,
                              class: "text-red-600",
                              onClick: ($event) => removeMember(member),
                              type: "button",
                              title: "Retirer le membre de la team"
                            }, [
                              (openBlock(), createBlock("svg", {
                                xmlns: "http://www.w3.org/2000/svg",
                                fill: "none",
                                viewBox: "0 0 24 24",
                                "stroke-width": "1.5",
                                stroke: "currentColor",
                                class: "h-5 w-5"
                              }, [
                                createVNode("path", {
                                  "stroke-linecap": "round",
                                  "stroke-linejoin": "round",
                                  d: "M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"
                                })
                              ]))
                            ], 8, ["onClick"])) : createCommentVNode("", true)
                          ]),
                          createVNode("div", null, [
                            createTextVNode(toDisplayString(member.score), 1),
                            createVNode("sup", null, "PTS")
                          ])
                        ]);
                      }), 128))
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<div class="my-6 flex items-center gap-4"${_scopeId}>`);
            if (Object.values(__props.members).find((x) => x.id === unref(user).id)) {
              _push2(`<button type="button" class="btn-danger"${_scopeId}>${ssrInterpolate(_ctx.__("Leave the team"))}</button>`);
            } else {
              _push2(`<div${_scopeId}>`);
              if (unref(user).declined_requests.includes(__props.team.id)) {
                _push2(`<button type="button" class="btn-danger mx-auto my-6"${_scopeId}>${ssrInterpolate(_ctx.__("Declined request"))}</button>`);
              } else if (unref(user).pending_requests.includes(__props.team.id)) {
                _push2(`<button type="button" class="btn-danger mx-auto my-6"${_scopeId}>${ssrInterpolate(_ctx.__("Cancel join request"))}</button>`);
              } else {
                _push2(`<button type="button" class="btn-secondary mx-auto my-6"${_scopeId}>${ssrInterpolate(_ctx.__("Send a join request"))}</button>`);
              }
              _push2(`</div>`);
            }
            _push2(ssrRenderComponent(_sfc_main$4, {
              url: _ctx.route("teams.show", __props.team),
              class: "w-6"
            }, null, _parent2, _scopeId));
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "mx-auto mt-8 max-w-xl" }, [
                unref(user).id === __props.team.user_id ? (openBlock(), createBlock(Card, {
                  key: 0,
                  class: "my-8"
                }, {
                  default: withCtx(() => [
                    createVNode("form", {
                      onSubmit: withModifiers(updateTeam, ["prevent"])
                    }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).name,
                        "onUpdate:modelValue": ($event) => unref(form).name = $event,
                        label: _ctx.__("Name"),
                        class: "mb-4",
                        error: unref(form).errors.name
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                      unref(user).permissions.canUploadImage ? (openBlock(), createBlock(_sfc_main$3, {
                        key: 0,
                        modelValue: unref(form).photo,
                        "onUpdate:modelValue": ($event) => unref(form).photo = $event,
                        label: _ctx.__("Image"),
                        class: "mb-4",
                        error: unref(form).errors.photo
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"])) : createCommentVNode("", true),
                      !unref(user).permissions.canUploadImage ? (openBlock(), createBlock(Tip, { key: 1 }, {
                        default: withCtx(() => [
                          createTextVNode(" Pour changer l'image de la team il faut minimum 3 mois d'ancienneté et un score total de 2000"),
                          createVNode("sup", null, "PTS"),
                          createTextVNode(". ")
                        ]),
                        _: 1
                      })) : createCommentVNode("", true),
                      createVNode(LoadingButton, {
                        type: "submit",
                        onClick: updateTeam,
                        loading: unref(form).processing,
                        class: "btn-primary mb-4 ml-auto"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Update")), 1)
                        ]),
                        _: 1
                      }, 8, ["loading"])
                    ], 40, ["onSubmit"])
                  ]),
                  _: 1
                })) : createCommentVNode("", true),
                createVNode("div", { class: "flex items-center justify-between text-xl" }, [
                  createVNode("div", { class: "mb-6 flex items-center" }, [
                    __props.team.photo ? (openBlock(), createBlock("img", {
                      key: 0,
                      class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                      src: __props.team.photo
                    }, null, 8, ["src"])) : createCommentVNode("", true),
                    createVNode("h1", { class: "font-bold" }, "Team " + toDisplayString(__props.team.name), 1)
                  ]),
                  createVNode("span", null, [
                    createTextVNode(toDisplayString(__props.score), 1),
                    createVNode("sup", { class: "ml-1" }, "PTS")
                  ])
                ]),
                createVNode(Card, null, {
                  header: withCtx(() => [
                    createVNode("div", { class: "mx-2 flex w-full items-center justify-between" }, [
                      createVNode("div", { class: "flex items-center" }, toDisplayString(_ctx.__("Members")), 1),
                      createVNode("span", null, toDisplayString(Object.entries(__props.members).length) + " / " + toDisplayString(__props.team.seats), 1)
                    ])
                  ]),
                  default: withCtx(() => [
                    createVNode("ul", null, [
                      (openBlock(true), createBlock(Fragment, null, renderList(Object.values(__props.members).sort((a, b) => b.score - a.score), (member, index) => {
                        return openBlock(), createBlock("li", {
                          key: member.id,
                          class: "m-2 flex items-center rounded bg-neutral-900 p-4"
                        }, [
                          createVNode("div", { class: "px-4 text-xl font-bold" }, toDisplayString(Number(index) + 1), 1),
                          createVNode("div", { class: "flex flex-grow items-center gap-2 pr-4" }, [
                            member.photo ? (openBlock(), createBlock("img", {
                              key: 0,
                              class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                              src: member.photo
                            }, null, 8, ["src"])) : createCommentVNode("", true),
                            createTextVNode(" " + toDisplayString(member.name), 1)
                          ]),
                          createVNode("div", { class: "mr-4 flex items-center gap-2" }, [
                            member.id === __props.team.user_id ? (openBlock(), createBlock("span", { key: 0 }, [
                              (openBlock(), createBlock("svg", {
                                xmlns: "http://www.w3.org/2000/svg",
                                viewBox: "0 0 24 24",
                                fill: "currentColor",
                                class: "h-4 w-4 fill-yellow-500"
                              }, [
                                createVNode("path", {
                                  "fill-rule": "evenodd",
                                  d: "M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z",
                                  "clip-rule": "evenodd"
                                })
                              ]))
                            ])) : createCommentVNode("", true),
                            unref(user).id === __props.team.user_id && member.id != __props.team.user_id ? (openBlock(), createBlock("button", {
                              key: 1,
                              onClick: ($event) => switchOwner(member),
                              class: "group",
                              title: _ctx.__("Switch owner"),
                              type: "button"
                            }, [
                              (openBlock(), createBlock("svg", {
                                xmlns: "http://www.w3.org/2000/svg",
                                fill: "none",
                                viewBox: "0 0 24 24",
                                "stroke-width": "1.5",
                                stroke: "currentColor",
                                class: "h-4 w-4 group-hover:fill-yellow-500"
                              }, [
                                createVNode("path", {
                                  "stroke-linecap": "round",
                                  "stroke-linejoin": "round",
                                  d: "M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                })
                              ]))
                            ], 8, ["onClick", "title"])) : createCommentVNode("", true),
                            unref(user).id === __props.team.user_id && member.id != __props.team.user_id ? (openBlock(), createBlock("button", {
                              key: 2,
                              class: "text-red-600",
                              onClick: ($event) => removeMember(member),
                              type: "button",
                              title: "Retirer le membre de la team"
                            }, [
                              (openBlock(), createBlock("svg", {
                                xmlns: "http://www.w3.org/2000/svg",
                                fill: "none",
                                viewBox: "0 0 24 24",
                                "stroke-width": "1.5",
                                stroke: "currentColor",
                                class: "h-5 w-5"
                              }, [
                                createVNode("path", {
                                  "stroke-linecap": "round",
                                  "stroke-linejoin": "round",
                                  d: "M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"
                                })
                              ]))
                            ], 8, ["onClick"])) : createCommentVNode("", true)
                          ]),
                          createVNode("div", null, [
                            createTextVNode(toDisplayString(member.score), 1),
                            createVNode("sup", null, "PTS")
                          ])
                        ]);
                      }), 128))
                    ])
                  ]),
                  _: 1
                }),
                createVNode("div", { class: "my-6 flex items-center gap-4" }, [
                  Object.values(__props.members).find((x) => x.id === unref(user).id) ? (openBlock(), createBlock("button", {
                    key: 0,
                    type: "button",
                    class: "btn-danger",
                    onClick: leave
                  }, toDisplayString(_ctx.__("Leave the team")), 1)) : (openBlock(), createBlock("div", { key: 1 }, [
                    unref(user).declined_requests.includes(__props.team.id) ? (openBlock(), createBlock("button", {
                      key: 0,
                      type: "button",
                      onClick: ($event) => cancelRequest(__props.team),
                      class: "btn-danger mx-auto my-6"
                    }, toDisplayString(_ctx.__("Declined request")), 9, ["onClick"])) : unref(user).pending_requests.includes(__props.team.id) ? (openBlock(), createBlock("button", {
                      key: 1,
                      type: "button",
                      onClick: ($event) => cancelRequest(__props.team),
                      class: "btn-danger mx-auto my-6"
                    }, toDisplayString(_ctx.__("Cancel join request")), 9, ["onClick"])) : (openBlock(), createBlock("button", {
                      key: 2,
                      type: "button",
                      onClick: ($event) => sendRequest(__props.team),
                      class: "btn-secondary mx-auto my-6"
                    }, toDisplayString(_ctx.__("Send a join request")), 9, ["onClick"]))
                  ])),
                  createVNode(_sfc_main$4, {
                    url: _ctx.route("teams.show", __props.team),
                    class: "w-6"
                  }, null, 8, ["url"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Teams/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
