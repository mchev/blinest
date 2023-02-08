import { J, P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, h as createBlock, e as withModifiers, g as createCommentVNode, d as createTextVNode, t as toDisplayString, v as renderList, s as Je } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$3 } from "./FileInput-ca2b29b7.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { T as Tip } from "./Tip-da87eaf5.js";
import { _ as _sfc_main$4 } from "./Share-8d298486.js";
import "./LanguageSwitcher-da420d03.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./Dropdown-f0e2d937.js";
import "./Icon-86c99edc.js";
import "./Navbar-cadf2428.js";
import "./throttle-19ac5bdb.js";
import "./_defineProperty-55942902.js";
import "./isSymbol-b518dd01.js";
import "./debounce-89ee665e.js";
import "./SocialIcon-5ed77127.js";
import "./v4-e7604ebc.js";
import "./Modal-f990bd3c.js";
const _hoisted_1 = { class: "mx-auto mt-8 max-w-xl" };
const _hoisted_2 = ["onSubmit"];
const _hoisted_3 = /* @__PURE__ */ createBaseVNode("sup", null, "PTS", -1);
const _hoisted_4 = { class: "flex items-center justify-between text-xl" };
const _hoisted_5 = { class: "mb-6 flex items-center" };
const _hoisted_6 = ["src"];
const _hoisted_7 = { class: "font-bold" };
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("sup", { class: "ml-1" }, "PTS", -1);
const _hoisted_9 = { class: "mx-2 flex w-full items-center justify-between" };
const _hoisted_10 = { class: "flex items-center" };
const _hoisted_11 = { class: "px-4 text-xl font-bold" };
const _hoisted_12 = { class: "flex flex-grow items-center gap-2 pr-4" };
const _hoisted_13 = ["src"];
const _hoisted_14 = { class: "mr-4 flex items-center gap-2" };
const _hoisted_15 = { key: 0 };
const _hoisted_16 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  fill: "currentColor",
  class: "h-4 w-4 fill-yellow-500"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "fill-rule": "evenodd",
    d: "M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z",
    "clip-rule": "evenodd"
  })
], -1);
const _hoisted_17 = [
  _hoisted_16
];
const _hoisted_18 = ["onClick", "title"];
const _hoisted_19 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "h-4 w-4 group-hover:fill-yellow-500"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
  })
], -1);
const _hoisted_20 = [
  _hoisted_19
];
const _hoisted_21 = ["onClick"];
const _hoisted_22 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "h-5 w-5"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"
  })
], -1);
const _hoisted_23 = [
  _hoisted_22
];
const _hoisted_24 = /* @__PURE__ */ createBaseVNode("sup", null, "PTS", -1);
const _hoisted_25 = { class: "my-6 flex items-center gap-4" };
const _hoisted_26 = { key: 1 };
const _sfc_main = {
  __name: "Show",
  props: {
    team: Object,
    score: Number,
    members: Object
  },
  setup(__props) {
    const props = __props;
    const user = J().props.auth.user;
    const form = P({
      _method: "put",
      name: props.team.name,
      photo: null
    });
    const leave = () => {
      if (confirm("Are you sure?")) {
        Je.post(`/teams/${props.team.id}/leave`);
      }
    };
    const sendRequest = (team) => {
      Je.post(`/teams/${team.id}/request`);
    };
    const cancelRequest = (team) => {
      Je.post(`/teams/${team.id}/request/cancel`);
    };
    const switchOwner = (member) => {
      if (confirm("Veux-tu vraiment transferer la gestion de la team à ce membre ?")) {
        Je.post(`/teams/${props.team.id}/owner/${member.id}`, {
          preserveState: false
        });
      }
    };
    const removeMember = (member) => {
      if (confirm("Veux-tu vraiment retirer ce membre de la team ?")) {
        Je.post(`/teams/${props.team.id}/members/${member.id}/remove`, {
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
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), {
          title: __props.team.name
        }, null, 8, ["title"]),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("div", _hoisted_1, [
              unref(user).id === __props.team.user_id ? (openBlock(), createBlock(Card, {
                key: 0,
                class: "my-8"
              }, {
                default: withCtx(() => [
                  createBaseVNode("form", {
                    onSubmit: withModifiers(updateTeam, ["prevent"])
                  }, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                      label: _ctx.__("Name"),
                      class: "mb-4",
                      error: unref(form).errors.name
                    }, null, 8, ["modelValue", "label", "error"]),
                    unref(user).permissions.canUploadImage ? (openBlock(), createBlock(_sfc_main$3, {
                      key: 0,
                      modelValue: unref(form).photo,
                      "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).photo = $event),
                      label: _ctx.__("Image"),
                      class: "mb-4",
                      error: unref(form).errors.photo
                    }, null, 8, ["modelValue", "label", "error"])) : createCommentVNode("", true),
                    !unref(user).permissions.canUploadImage ? (openBlock(), createBlock(Tip, { key: 1 }, {
                      default: withCtx(() => [
                        createTextVNode(" Pour changer l'image de la team il faut minimum 3 mois d'ancienneté et un score total de 2000"),
                        _hoisted_3,
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
                  ], 40, _hoisted_2)
                ]),
                _: 1
              })) : createCommentVNode("", true),
              createBaseVNode("div", _hoisted_4, [
                createBaseVNode("div", _hoisted_5, [
                  __props.team.photo ? (openBlock(), createElementBlock("img", {
                    key: 0,
                    class: "-my-2 mr-2 block h-10 w-10 rounded-full",
                    src: __props.team.photo
                  }, null, 8, _hoisted_6)) : createCommentVNode("", true),
                  createBaseVNode("h1", _hoisted_7, "Team " + toDisplayString(__props.team.name), 1)
                ]),
                createBaseVNode("span", null, [
                  createTextVNode(toDisplayString(__props.score), 1),
                  _hoisted_8
                ])
              ]),
              createVNode(Card, null, {
                header: withCtx(() => [
                  createBaseVNode("div", _hoisted_9, [
                    createBaseVNode("div", _hoisted_10, toDisplayString(_ctx.__("Members")), 1),
                    createBaseVNode("span", null, toDisplayString(Object.entries(__props.members).length) + " / " + toDisplayString(__props.team.seats), 1)
                  ])
                ]),
                default: withCtx(() => [
                  createBaseVNode("ul", null, [
                    (openBlock(true), createElementBlock(Fragment, null, renderList(Object.values(__props.members).sort((a, b) => b.score - a.score), (member, index) => {
                      return openBlock(), createElementBlock("li", {
                        key: member.id,
                        class: "m-2 flex items-center rounded bg-neutral-900 p-4"
                      }, [
                        createBaseVNode("div", _hoisted_11, toDisplayString(Number(index) + 1), 1),
                        createBaseVNode("div", _hoisted_12, [
                          member.photo ? (openBlock(), createElementBlock("img", {
                            key: 0,
                            class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                            src: member.photo
                          }, null, 8, _hoisted_13)) : createCommentVNode("", true),
                          createTextVNode(" " + toDisplayString(member.name), 1)
                        ]),
                        createBaseVNode("div", _hoisted_14, [
                          member.id === __props.team.user_id ? (openBlock(), createElementBlock("span", _hoisted_15, _hoisted_17)) : createCommentVNode("", true),
                          unref(user).id === __props.team.user_id && member.id != __props.team.user_id ? (openBlock(), createElementBlock("button", {
                            key: 1,
                            onClick: ($event) => switchOwner(member),
                            class: "group",
                            title: _ctx.__("Switch owner"),
                            type: "button"
                          }, _hoisted_20, 8, _hoisted_18)) : createCommentVNode("", true),
                          unref(user).id === __props.team.user_id && member.id != __props.team.user_id ? (openBlock(), createElementBlock("button", {
                            key: 2,
                            class: "text-red-600",
                            onClick: ($event) => removeMember(member),
                            type: "button",
                            title: "Retirer le membre de la team"
                          }, _hoisted_23, 8, _hoisted_21)) : createCommentVNode("", true)
                        ]),
                        createBaseVNode("div", null, [
                          createTextVNode(toDisplayString(member.score), 1),
                          _hoisted_24
                        ])
                      ]);
                    }), 128))
                  ])
                ]),
                _: 1
              }),
              createBaseVNode("div", _hoisted_25, [
                Object.values(__props.members).find((x) => x.id === unref(user).id) ? (openBlock(), createElementBlock("button", {
                  key: 0,
                  type: "button",
                  class: "btn-danger",
                  onClick: leave
                }, toDisplayString(_ctx.__("Leave the team")), 1)) : (openBlock(), createElementBlock("div", _hoisted_26, [
                  unref(user).declined_requests.includes(__props.team.id) ? (openBlock(), createElementBlock("button", {
                    key: 0,
                    type: "button",
                    onClick: _cache[2] || (_cache[2] = ($event) => cancelRequest(__props.team)),
                    class: "btn-danger mx-auto my-6"
                  }, toDisplayString(_ctx.__("Declined request")), 1)) : unref(user).pending_requests.includes(__props.team.id) ? (openBlock(), createElementBlock("button", {
                    key: 1,
                    type: "button",
                    onClick: _cache[3] || (_cache[3] = ($event) => cancelRequest(__props.team)),
                    class: "btn-danger mx-auto my-6"
                  }, toDisplayString(_ctx.__("Cancel join request")), 1)) : (openBlock(), createElementBlock("button", {
                    key: 2,
                    type: "button",
                    onClick: _cache[4] || (_cache[4] = ($event) => sendRequest(__props.team)),
                    class: "btn-secondary mx-auto my-6"
                  }, toDisplayString(_ctx.__("Send a join request")), 1))
                ])),
                createVNode(_sfc_main$4, {
                  url: _ctx.route("teams.show", __props.team),
                  class: "w-6"
                }, null, 8, ["url"])
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
