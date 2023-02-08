import { P, o as openBlock, h as createBlock, w as withCtx, b as createBaseVNode, e as withModifiers, c as createElementBlock, t as toDisplayString, a as createVNode, u as unref, F as Fragment, v as renderList, f as normalizeClass, g as createCommentVNode, k as ref, L as onUnmounted, M as useAttrs, r as renderSlot, d as createTextVNode, z as withDirectives, N as vShow, n as ne, l as watch, s as Je, O as vModelCheckbox } from "./app-910e457d.js";
import { _ as _sfc_main$a } from "./Icon-86c99edc.js";
import { C as Card } from "./Card-7ef4ce68.js";
import { _ as _sfc_main$8 } from "./TextInput-541fe8b5.js";
import { _ as _sfc_main$7 } from "./SelectInput-5ecccdd8.js";
import { _ as _sfc_main$c } from "./Pagination-cf5f2783.js";
import { _ as _sfc_main$9 } from "./Modal-f990bd3c.js";
import { S as Spinner } from "./Spinner-bfddfb59.js";
import { _ as _sfc_main$b } from "./Dropdown-f0e2d937.js";
import { t as throttle, p as pickBy_1 } from "./throttle-19ac5bdb.js";
import { d as debounce_1 } from "./debounce-89ee665e.js";
import { T as Tip } from "./Tip-da87eaf5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
const _hoisted_1$6 = ["onSubmit"];
const _hoisted_2$4 = { class: "px-6 py-4" };
const _hoisted_3$4 = {
  key: 0,
  class: "text-lg"
};
const _hoisted_4$3 = {
  key: 1,
  class: "text-lg"
};
const _hoisted_5$3 = { class: "mt-4 flex flex-wrap" };
const _hoisted_6$3 = ["value"];
const _hoisted_7$3 = { class: "flex items-center justify-between px-2 py-4 text-right" };
const _hoisted_8$3 = ["disabled"];
const _hoisted_9$3 = { class: "flex items-center" };
const _hoisted_10$3 = ["disabled"];
const _hoisted_11$3 = ["disabled"];
const _sfc_main$6 = {
  __name: "TrackAnswerForm",
  props: {
    answer: {
      type: Object,
      default() {
        return {
          answer_type_id: 1,
          value: "",
          score: "0.5"
        };
      }
    },
    show: [Boolean, Number],
    maxWidth: {
      type: String,
      default: "2xl"
    },
    closeable: {
      type: Boolean,
      default: true
    },
    answer_types: Object
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    const form = P({
      answer_type_id: props.answer ? props.answer.answer_type_id : 1,
      value: props.answer ? props.answer.value : "",
      score: props.answer ? props.answer.score : 0.5
    });
    const close = () => {
      emit("close");
    };
    const submitForm = () => {
      props.answer ? updateAnswer() : storeAnswer();
    };
    const updateAnswer = () => {
      form.put(route("tracks.answers.update", [props.show, props.answer.id]), {
        preserveScroll: true,
        onSuccess: () => {
          close();
        }
      });
    };
    const storeAnswer = () => {
      form.post(route("tracks.answers.store", props.show), {
        preserveScroll: true,
        onSuccess: () => {
          close();
        }
      });
    };
    const deleteAnswer = () => {
      form.delete(route("tracks.answers.delete", [props.show, props.answer.id]), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
          close();
        }
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$9, {
        show: __props.show,
        "max-width": __props.maxWidth,
        closeable: __props.closeable,
        onClose: close
      }, {
        default: withCtx(() => [
          createBaseVNode("form", {
            onSubmit: withModifiers(submitForm, ["prevent"])
          }, [
            createBaseVNode("div", _hoisted_2$4, [
              __props.answer ? (openBlock(), createElementBlock("div", _hoisted_3$4, toDisplayString(_ctx.__("Edit Answer")), 1)) : (openBlock(), createElementBlock("div", _hoisted_4$3, toDisplayString(_ctx.__("Add Answer")), 1)),
              createBaseVNode("div", _hoisted_5$3, [
                createVNode(_sfc_main$7, {
                  modelValue: unref(form).answer_type_id,
                  "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).answer_type_id = $event),
                  error: unref(form).errors.answer_type_id,
                  class: "w-full pb-8 pr-6 lg:w-1/2",
                  label: _ctx.__("Type")
                }, {
                  default: withCtx(() => [
                    (openBlock(true), createElementBlock(Fragment, null, renderList(__props.answer_types, (type) => {
                      return openBlock(), createElementBlock("option", {
                        value: type.id
                      }, toDisplayString(_ctx.__(type.name)), 9, _hoisted_6$3);
                    }), 256))
                  ]),
                  _: 1
                }, 8, ["modelValue", "error", "label"]),
                createVNode(_sfc_main$8, {
                  modelValue: unref(form).score,
                  "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(form).score = $event),
                  type: "number",
                  step: "0.5",
                  min: "0",
                  max: "99",
                  error: unref(form).errors.score,
                  class: "w-full pb-8 pr-6 lg:w-1/2",
                  label: _ctx.__("Score")
                }, null, 8, ["modelValue", "error", "label"]),
                createVNode(_sfc_main$8, {
                  modelValue: unref(form).value,
                  "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => unref(form).value = $event),
                  error: unref(form).errors.value,
                  class: "w-full pb-8 pr-6",
                  label: _ctx.__("Answer")
                }, null, 8, ["modelValue", "error", "label"])
              ])
            ]),
            createBaseVNode("div", _hoisted_7$3, [
              __props.answer && __props.answer.id ? (openBlock(), createElementBlock("button", {
                key: 0,
                type: "button",
                class: normalizeClass(["mx-2 text-red-400", { "opacity-25": unref(form).processing }]),
                disabled: unref(form).processing,
                onClick: deleteAnswer
              }, toDisplayString(_ctx.__("Delete")), 11, _hoisted_8$3)) : createCommentVNode("", true),
              createBaseVNode("div", _hoisted_9$3, [
                createBaseVNode("button", {
                  class: "btn-secondary mx-2 bg-gray-400",
                  onClick: close
                }, toDisplayString(_ctx.__("Close")), 1),
                __props.answer && __props.answer.id ? (openBlock(), createElementBlock("button", {
                  key: 0,
                  type: "submit",
                  class: normalizeClass(["btn-primary ml-2", { "opacity-25": unref(form).processing }]),
                  disabled: unref(form).processing
                }, toDisplayString(_ctx.__("Update")), 11, _hoisted_10$3)) : (openBlock(), createElementBlock("button", {
                  key: 1,
                  type: "submit",
                  class: normalizeClass(["btn-primary ml-2", { "opacity-25": unref(form).processing }]),
                  disabled: unref(form).processing
                }, toDisplayString(_ctx.__("Add")), 11, _hoisted_11$3))
              ])
            ])
          ], 40, _hoisted_1$6)
        ]),
        _: 1
      }, 8, ["show", "max-width", "closeable"]);
    };
  }
};
const _hoisted_1$5 = {
  key: 1,
  class: "mr-2 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-neutral-800"
};
const _sfc_main$5 = {
  __name: "MiniPlayer",
  props: {
    track: Object
  },
  setup(__props) {
    const props = __props;
    const audio = new Audio();
    const loading = ref(false);
    const isPlaying = ref(false);
    onUnmounted(() => {
      stop();
    });
    const play = () => {
      loading.value = true;
      isPlaying.value = true;
      audio.src = props.track.preview_url;
      audio.addEventListener("canplaythrough", () => {
        loading.value = false;
        audio.play();
      });
    };
    const stop = () => {
      isPlaying.value = false;
      audio.pause();
    };
    return (_ctx, _cache) => {
      return !isPlaying.value ? (openBlock(), createBlock(_sfc_main$a, {
        key: 0,
        name: "play",
        class: "mr-2 h-12 w-12 flex-shrink-0 cursor-pointer",
        onClick: play
      })) : loading.value ? (openBlock(), createElementBlock("div", _hoisted_1$5, [
        createVNode(Spinner, { class: "h-full w-full" })
      ])) : (openBlock(), createBlock(_sfc_main$a, {
        key: 2,
        name: "stop",
        class: "mr-2 h-12 w-12 flex-shrink-0 cursor-pointer",
        onClick: stop
      }));
    };
  }
};
const _hoisted_1$4 = {
  key: 0,
  class: "ml-1 inline-block align-middle"
};
const __default__ = {
  inheritAttrs: false
};
const _sfc_main$4 = /* @__PURE__ */ Object.assign(__default__, {
  __name: "Sortable",
  props: {
    field: {
      type: String,
      default: null
    }
  },
  emits: ["update:modelValue"],
  setup(__props, { emit }) {
    const props = __props;
    const attrs = useAttrs();
    const select = () => {
      emit("update:modelValue", {
        field: props.field,
        direction: attrs.modelValue && attrs.modelValue.direction === "asc" ? "desc" : "asc"
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        class: normalizeClass([_ctx.$attrs.class, "cursor-pointer"]),
        onClick: select
      }, [
        renderSlot(_ctx.$slots, "default"),
        _ctx.$attrs.modelValue && __props.field == _ctx.$attrs.modelValue.field ? (openBlock(), createElementBlock("div", _hoisted_1$4, [
          _ctx.$attrs.modelValue.direction === "asc" ? (openBlock(), createBlock(_sfc_main$a, {
            key: 0,
            name: "arrow-down",
            class: "block h-4 w-4 fill-gray-400"
          })) : (openBlock(), createBlock(_sfc_main$a, {
            key: 1,
            name: "arrow-up",
            class: "block h-4 w-4 fill-gray-400"
          }))
        ])) : createCommentVNode("", true)
      ], 2);
    };
  }
});
const _hoisted_1$3 = { class: "p-4" };
const _hoisted_2$3 = ["onSubmit"];
const _hoisted_3$3 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_4$2 = ["value"];
const _hoisted_5$2 = { key: 0 };
const _hoisted_6$2 = /* @__PURE__ */ createBaseVNode("small", null, "Vous pouvez trouver l'ID de la playlist dans la barre d'adresse de votre navigateur", -1);
const _hoisted_7$2 = /* @__PURE__ */ createBaseVNode("br", null, null, -1);
const _hoisted_8$2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-bold underline" }, "37i9dQZF1DXcBWIGoYBM5M", -1);
const _hoisted_9$2 = /* @__PURE__ */ createBaseVNode("span", { class: "font-bold underline" }, "53362031", -1);
const _hoisted_10$2 = { class: "mt-4 flex items-center justify-end gap-2" };
const _hoisted_11$2 = /* @__PURE__ */ createBaseVNode("h3", { class: "uppercase" }, "Confirmer l'importation", -1);
const _hoisted_12$1 = { class: "mb-4 flex items-center" };
const _hoisted_13$1 = ["src"];
const _hoisted_14$1 = { class: "flex flex-col" };
const _hoisted_15$1 = { class: "text-xl" };
const _hoisted_16$1 = { class: "italic" };
const _hoisted_17$1 = { class: "mb-4" };
const _hoisted_18$1 = /* @__PURE__ */ createBaseVNode("b", null, "Origine :", -1);
const _hoisted_19$1 = { key: 0 };
const _hoisted_20$1 = /* @__PURE__ */ createBaseVNode("b", null, "Playlist ID :", -1);
const _hoisted_21$1 = /* @__PURE__ */ createBaseVNode("b", null, "Titres à importer :", -1);
const _hoisted_22$1 = { class: "mt-4 flex items-center justify-end gap-2" };
const _sfc_main$3 = {
  __name: "ImportPlaylist",
  props: {
    playlist: Object,
    providerPlaylist: {
      type: Object,
      default: null
    }
  },
  emits: ["close"],
  setup(__props, { emit }) {
    const props = __props;
    const show = ref(true);
    const providers = ref(["Spotify", "Deezer", "Blinest likes"]);
    const step = ref(1);
    const pp = ref(null);
    const checkForm = P({
      provider: providers.value[0],
      playlist_id: ""
    });
    const checkPlaylist = () => {
      console.log("Checking");
      checkForm.post(route("playlists.providers.find", props.playlist.id), {
        only: ["providerPlaylist", "errors"],
        onSuccess: (response) => {
          pp.value = response.props.providerPlaylist;
          if (pp.value) {
            if (pp.value.message) {
              checkForm.errors.playlist_id = `[${pp.value.code}] ${pp.value.message}`;
            } else {
              step.value = 2;
            }
          }
        }
      });
    };
    const importPlaylist = () => {
      checkForm.transform((data) => ({
        ...data,
        tracks_count: pp.value.tracks_count
      })).post(route("playlists.providers.import", props.playlist.id), {
        onSuccess: () => {
          emit("close");
        }
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$9, {
        show: show.value,
        onClose: _cache[2] || (_cache[2] = ($event) => emit("close"))
      }, {
        default: withCtx(() => [
          createBaseVNode("div", _hoisted_1$3, [
            step.value === 1 ? (openBlock(), createBlock(Card, {
              key: 0,
              class: "shadow-none"
            }, {
              default: withCtx(() => [
                createBaseVNode("form", {
                  onSubmit: withModifiers(checkPlaylist, ["prevent"]),
                  class: "flex flex-col gap-4"
                }, [
                  createVNode(Tip, null, {
                    default: withCtx(() => [
                      createTextVNode("Pour importer une playlist, celle-ci doit être publique."),
                      _hoisted_3$3,
                      createTextVNode("Les extraits ne pouvant pas être lus par le site ne seront pas importés.")
                    ]),
                    _: 1
                  }),
                  createVNode(_sfc_main$7, {
                    modelValue: unref(checkForm).provider,
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(checkForm).provider = $event),
                    error: unref(checkForm).provider.error,
                    label: "Origine de la playlist",
                    required: ""
                  }, {
                    default: withCtx(() => [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(providers.value, (provider) => {
                        return openBlock(), createElementBlock("option", { value: provider }, toDisplayString(provider), 9, _hoisted_4$2);
                      }), 256))
                    ]),
                    _: 1
                  }, 8, ["modelValue", "error"]),
                  unref(checkForm).provider != "Blinest likes" ? (openBlock(), createElementBlock("div", _hoisted_5$2, [
                    createVNode(_sfc_main$8, {
                      modelValue: unref(checkForm).playlist_id,
                      "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => unref(checkForm).playlist_id = $event),
                      error: unref(checkForm).errors.playlist_id,
                      type: "text",
                      label: "ID de la playlist",
                      required: ""
                    }, null, 8, ["modelValue", "error"]),
                    _hoisted_6$2,
                    _hoisted_7$2,
                    withDirectives(createBaseVNode("small", null, [
                      createTextVNode("Exemple d'ID Spotify : https://open.spotify.com/playlist/"),
                      _hoisted_8$2
                    ], 512), [
                      [vShow, unref(checkForm).provider === "Spotify"]
                    ]),
                    withDirectives(createBaseVNode("small", null, [
                      createTextVNode("Exemple d'ID Deezer : https://www.deezer.com/fr/playlist/"),
                      _hoisted_9$2
                    ], 512), [
                      [vShow, unref(checkForm).provider === "Deezer"]
                    ])
                  ])) : createCommentVNode("", true),
                  createBaseVNode("div", _hoisted_10$2, [
                    createVNode(unref(ne), {
                      class: "btn-secondary btn-sm",
                      href: _ctx.route("playlists.edit", __props.playlist.id)
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Cancel")), 1)
                      ]),
                      _: 1
                    }, 8, ["href"]),
                    createVNode(LoadingButton, {
                      class: "btn-secondary btn-sm",
                      loading: unref(checkForm).processing
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(_ctx.__("Next")), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ])
                ], 40, _hoisted_2$3)
              ]),
              _: 1
            })) : createCommentVNode("", true),
            step.value === 2 ? (openBlock(), createBlock(Card, {
              key: 1,
              class: "shadow-none"
            }, {
              header: withCtx(() => [
                _hoisted_11$2
              ]),
              default: withCtx(() => [
                createBaseVNode("div", _hoisted_12$1, [
                  createBaseVNode("img", {
                    src: pp.value.image,
                    class: "mr-2 h-20 w-20 rounded-full"
                  }, null, 8, _hoisted_13$1),
                  createBaseVNode("div", _hoisted_14$1, [
                    createBaseVNode("h3", _hoisted_15$1, toDisplayString(pp.value.name), 1),
                    createBaseVNode("p", _hoisted_16$1, toDisplayString(pp.value.description), 1)
                  ])
                ]),
                createBaseVNode("ul", _hoisted_17$1, [
                  createBaseVNode("li", null, [
                    _hoisted_18$1,
                    createTextVNode(" " + toDisplayString(unref(checkForm).provider), 1)
                  ]),
                  pp.value.id ? (openBlock(), createElementBlock("li", _hoisted_19$1, [
                    _hoisted_20$1,
                    createTextVNode(" " + toDisplayString(pp.value.id), 1)
                  ])) : createCommentVNode("", true),
                  createBaseVNode("li", null, [
                    _hoisted_21$1,
                    createTextVNode(" " + toDisplayString(pp.value.tracks_count), 1)
                  ])
                ]),
                createVNode(Tip, null, {
                  default: withCtx(() => [
                    createTextVNode("Les titres en doublon ne seront pas importés.")
                  ]),
                  _: 1
                }),
                createBaseVNode("div", _hoisted_22$1, [
                  createVNode(unref(ne), {
                    class: "btn-secondary btn-sm",
                    href: _ctx.route("playlists.edit", __props.playlist.id)
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Cancel")), 1)
                    ]),
                    _: 1
                  }, 8, ["href"]),
                  createVNode(LoadingButton, {
                    class: "btn-primary btn-sm",
                    onClick: importPlaylist,
                    loading: unref(checkForm).processing
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(_ctx.__("Import")), 1)
                    ]),
                    _: 1
                  }, 8, ["loading"])
                ])
              ]),
              _: 1
            })) : createCommentVNode("", true)
          ])
        ]),
        _: 1
      }, 8, ["show"]);
    };
  }
};
const _hoisted_1$2 = { class: "flex w-full items-center justify-between" };
const _hoisted_2$2 = { class: "text-xl font-bold" };
const _hoisted_3$2 = ["href"];
const _hoisted_4$1 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor",
  class: "mr-2 h-4 w-4"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"
  })
], -1);
const _hoisted_5$1 = { class: "mb-4 flex justify-between gap-2 p-2" };
const _hoisted_6$1 = {
  key: 0,
  class: "flex gap-2 border-b-2 border-neutral-600 bg-neutral-900 p-2 text-sm"
};
const _hoisted_7$1 = ["onUpdate:modelValue", "onClick"];
const _hoisted_8$1 = {
  key: 1,
  class: "max-h-80 overflow-y-auto bg-neutral-900"
};
const _hoisted_9$1 = { class: "flex items-center gap-2" };
const _hoisted_10$1 = { class: "mr-2 flex w-80 flex-grow flex-col" };
const _hoisted_11$1 = { class: "max-w-[16rem] truncate break-normal font-bold" };
const _hoisted_12 = { class: "max-w-[16rem] truncate break-normal text-sm" };
const _hoisted_13 = {
  key: 0,
  class: "ml-auto"
};
const _hoisted_14 = ["disabled", "onClick"];
const _hoisted_15 = {
  key: 1,
  class: "ml-auto"
};
const _hoisted_16 = ["disabled", "onClick"];
const _hoisted_17 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  fill: "currentColor",
  class: "mr-2 h-5 w-5"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "fill-rule": "evenodd",
    d: "M12 2.25a.75.75 0 01.75.75v11.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V3a.75.75 0 01.75-.75zm-9 13.5a.75.75 0 01.75.75v2.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V16.5a.75.75 0 011.5 0v2.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V16.5a.75.75 0 01.75-.75z",
    "clip-rule": "evenodd"
  })
], -1);
const _hoisted_18 = { class: "flex items-center gap-2" };
const _hoisted_19 = /* @__PURE__ */ createBaseVNode("option", { value: 5 }, "5", -1);
const _hoisted_20 = /* @__PURE__ */ createBaseVNode("option", { value: 10 }, "10", -1);
const _hoisted_21 = /* @__PURE__ */ createBaseVNode("option", { value: 15 }, "15", -1);
const _hoisted_22 = /* @__PURE__ */ createBaseVNode("option", { value: 20 }, "20", -1);
const _hoisted_23 = {
  key: 0,
  class: "mx-4 overflow-x-auto"
};
const _hoisted_24 = { class: "w-full whitespace-nowrap" };
const _hoisted_25 = { class: "text-left font-bold" };
const _hoisted_26 = /* @__PURE__ */ createBaseVNode("th", {
  class: "px-6 pb-4 pt-6",
  colspan: "2"
}, null, -1);
const _hoisted_27 = { class: "px-6 pb-4 pt-6" };
const _hoisted_28 = { class: "px-6 pb-4 pt-6" };
const _hoisted_29 = {
  class: "px-6 pb-4 pt-6",
  colspan: "2"
};
const _hoisted_30 = {
  class: "px-6 pb-4 pt-6",
  colspan: "2"
};
const _hoisted_31 = { class: "border-t" };
const _hoisted_32 = ["href"];
const _hoisted_33 = { class: "border-t" };
const _hoisted_34 = { class: "flex items-center justify-center px-2 py-4 focus:text-blinest-500" };
const _hoisted_35 = { class: "border-t" };
const _hoisted_36 = { class: "flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500" };
const _hoisted_37 = ["onClick"];
const _hoisted_38 = { class: "font-bold" };
const _hoisted_39 = ["onClick"];
const _hoisted_40 = { class: "border-t" };
const _hoisted_41 = { class: "flex items-start px-6 py-4 text-center text-sm" };
const _hoisted_42 = /* @__PURE__ */ createBaseVNode("option", { value: 0 }, "Facile", -1);
const _hoisted_43 = /* @__PURE__ */ createBaseVNode("option", { value: 1 }, "Moyen", -1);
const _hoisted_44 = /* @__PURE__ */ createBaseVNode("option", { value: 2 }, "Difficile", -1);
const _hoisted_45 = /* @__PURE__ */ createBaseVNode("option", { value: 3 }, "Expert", -1);
const _hoisted_46 = { class: "border-t" };
const _hoisted_47 = { class: "flex items-start px-6 py-4 text-center text-sm" };
const _hoisted_48 = { class: "border-t" };
const _hoisted_49 = { class: "flex items-start px-6 py-4 text-center text-sm focus:text-blinest-500" };
const _hoisted_50 = { class: "border-t" };
const _hoisted_51 = { class: "flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500" };
const _hoisted_52 = { class: "border-t" };
const _hoisted_53 = { key: 0 };
const _hoisted_54 = {
  class: "border-t px-6 py-4",
  colspan: "4"
};
const _hoisted_55 = {
  key: 1,
  class: "p-2"
};
const _sfc_main$2 = {
  __name: "TracksManager",
  props: {
    playlist: Object,
    answer_types: Object,
    tracks: Object,
    filters: {
      type: Object,
      default: {
        search: "",
        paginate: 5,
        sortable: null
      }
    }
  },
  setup(__props) {
    const props = __props;
    const form = P({
      search: props.filters.search,
      paginate: props.filters.paginate ?? 5,
      sortable: props.filters.sortable
    });
    const selectedAnswer = ref(null);
    const creatingAnswer = ref(false);
    const editingAnswer = ref(false);
    const search_online = ref("");
    const providers = ref([
      { id: 1, provider: "deezer", name: "Deezer", selected: true },
      { id: 2, provider: "spotify", name: "Spotify", selected: true },
      { id: 3, provider: "itunes", name: "Apple music", selected: true }
    ]);
    const selectedProviders = ref(["deezer", "spotify", "itunes"]);
    ref("");
    const importingPlaylist = ref(false);
    const loading = ref(false);
    const results = ref([]);
    watch(
      search_online,
      debounce_1(() => {
        fecthMusicProviders();
      }, 300)
    );
    watch(
      form,
      throttle(() => {
        Je.get(route("playlists.edit", props.playlist), pickBy_1(form), {
          preserveScroll: true,
          preserveState: true
        });
      }, 150),
      { deep: true }
    );
    const check = (provider) => {
      if (selectedProviders.value.includes(provider.provider)) {
        selectedProviders.value = selectedProviders.value.filter((x) => x != provider.provider);
      } else {
        selectedProviders.value.push(provider.provider);
      }
    };
    const fecthMusicProviders = () => {
      if (search_online.value.length > 1) {
        loading.value = true;
        axios.get(route("tracks.search", props.playlist.id) + "?term=" + search_online.value).then((response) => {
          results.value = response.data.tracks;
          loading.value = false;
        });
      } else {
        results.value = [];
      }
    };
    const createAnswer = (track) => {
      creatingAnswer.value = track.id;
    };
    const editAnswer = (track, answer = null) => {
      selectedAnswer.value = answer;
      editingAnswer.value = track.id;
    };
    const closeModal = () => {
      selectedAnswer.value = null;
      creatingAnswer.value = false;
      editingAnswer.value = false;
    };
    const addTrack = (track) => {
      Je.post(route("playlists.tracks.store", props.playlist.id), track, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          fecthMusicProviders();
        }
      });
    };
    const removeTrack = (track) => {
      let id = track.added ? track.added.id : track.id;
      if (confirm("Voulez-vous vraiment supprimer cet extrait?")) {
        Je.delete(route("playlists.tracks.delete", [props.playlist.id, id]), {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => {
            fecthMusicProviders();
          }
        });
      }
    };
    const updateDificulty = (e, track) => {
      Je.put(
        route("playlists.tracks.update", [props.playlist.id, track]),
        { dificulty: e.target.value },
        {
          preserveScroll: true
        }
      );
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(Card, null, {
          header: withCtx(() => [
            createBaseVNode("div", _hoisted_1$2, [
              createBaseVNode("h3", _hoisted_2$2, toDisplayString(_ctx.__("Tracks manager")) + " (" + toDisplayString(__props.tracks.total) + ")", 1),
              createVNode(_sfc_main$8, {
                modelValue: unref(form).search,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).search = $event),
                "prepend-icon": "search",
                placeholder: _ctx.__("Search in playlist") + "..."
              }, null, 8, ["modelValue", "placeholder"]),
              createBaseVNode("a", {
                href: _ctx.route("playlists.export", __props.playlist),
                target: "_blank",
                class: "btn-secondary rounded"
              }, [
                _hoisted_4$1,
                createTextVNode(" Exporter ")
              ], 8, _hoisted_3$2)
            ])
          ]),
          default: withCtx(() => [
            createBaseVNode("div", null, [
              createBaseVNode("div", _hoisted_5$1, [
                createVNode(_sfc_main$b, {
                  placement: "bottom-start",
                  "auto-close": false
                }, {
                  default: withCtx(() => [
                    createVNode(_sfc_main$8, {
                      modelValue: search_online.value,
                      "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => search_online.value = $event),
                      "prepend-icon": "plus",
                      "append-icon": "cheveron-down",
                      loading: loading.value,
                      placeholder: _ctx.__("Search on Deezer, Spotify and Apple music...")
                    }, null, 8, ["modelValue", "loading", "placeholder"])
                  ]),
                  dropdown: withCtx(() => [
                    results.value.length ? (openBlock(), createElementBlock("div", _hoisted_6$1, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(providers.value, (provider) => {
                        return openBlock(), createElementBlock("label", null, [
                          withDirectives(createBaseVNode("input", {
                            type: "checkbox",
                            "onUpdate:modelValue": ($event) => provider.selected = $event,
                            onClick: ($event) => check(provider)
                          }, null, 8, _hoisted_7$1), [
                            [vModelCheckbox, provider.selected]
                          ]),
                          createTextVNode(" " + toDisplayString(provider.name), 1)
                        ]);
                      }), 256))
                    ])) : createCommentVNode("", true),
                    results.value.length ? (openBlock(), createElementBlock("ul", _hoisted_8$1, [
                      (openBlock(true), createElementBlock(Fragment, null, renderList(results.value.filter((x) => selectedProviders.value.includes(x.provider)), (result) => {
                        return openBlock(), createElementBlock("li", {
                          class: "relative border-b border-neutral-600 px-2 py-3",
                          key: result.id
                        }, [
                          createBaseVNode("div", _hoisted_9$1, [
                            createBaseVNode("div", null, [
                              createVNode(_sfc_main$a, {
                                name: result.provider,
                                title: result.provider,
                                class: "h-6 w-6 flex-shrink-0"
                              }, null, 8, ["name", "title"])
                            ]),
                            createBaseVNode("div", null, [
                              (openBlock(), createBlock(_sfc_main$5, {
                                key: `mini-player-results-${result.id}`,
                                track: result
                              }, null, 8, ["track"]))
                            ]),
                            createBaseVNode("div", _hoisted_10$1, [
                              createBaseVNode("span", _hoisted_11$1, toDisplayString(result.artist_name), 1),
                              createBaseVNode("span", _hoisted_12, toDisplayString(result.track_name), 1)
                            ]),
                            !result.added ? (openBlock(), createElementBlock("div", _hoisted_13, [
                              createBaseVNode("button", {
                                disabled: loading.value,
                                class: "btn-primary btn-sm",
                                type: "button",
                                onClick: ($event) => addTrack(result)
                              }, toDisplayString(_ctx.__("Add")), 9, _hoisted_14)
                            ])) : (openBlock(), createElementBlock("div", _hoisted_15, [
                              createBaseVNode("button", {
                                disabled: loading.value,
                                class: "btn-danger btn-sm",
                                type: "button",
                                onClick: ($event) => removeTrack(result)
                              }, toDisplayString(_ctx.__("Remove")), 9, _hoisted_16)
                            ]))
                          ])
                        ]);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ]),
                  _: 1
                }),
                createBaseVNode("button", {
                  class: "btn-secondary btn-sm",
                  onClick: _cache[2] || (_cache[2] = ($event) => importingPlaylist.value = true)
                }, [
                  _hoisted_17,
                  createTextVNode(" Importer une playlist ")
                ]),
                createBaseVNode("div", _hoisted_18, [
                  createVNode(_sfc_main$7, {
                    modelValue: unref(form).paginate,
                    "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => unref(form).paginate = $event)
                  }, {
                    default: withCtx(() => [
                      _hoisted_19,
                      _hoisted_20,
                      _hoisted_21,
                      _hoisted_22
                    ]),
                    _: 1
                  }, 8, ["modelValue"])
                ])
              ]),
              __props.tracks.data.length ? (openBlock(), createElementBlock("div", _hoisted_23, [
                createBaseVNode("table", _hoisted_24, [
                  createBaseVNode("tr", _hoisted_25, [
                    _hoisted_26,
                    createBaseVNode("th", _hoisted_27, toDisplayString(_ctx.__("Answers")), 1),
                    createBaseVNode("th", _hoisted_28, [
                      createVNode(_sfc_main$4, {
                        field: "dificulty",
                        modelValue: unref(form).sortable,
                        "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => unref(form).sortable = $event)
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Dificulty")), 1)
                        ]),
                        _: 1
                      }, 8, ["modelValue"])
                    ]),
                    createBaseVNode("th", _hoisted_29, [
                      createVNode(_sfc_main$4, {
                        field: "votes",
                        modelValue: unref(form).sortable,
                        "onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => unref(form).sortable = $event)
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Votes")), 1)
                        ]),
                        _: 1
                      }, 8, ["modelValue"])
                    ]),
                    createBaseVNode("th", _hoisted_30, [
                      createVNode(_sfc_main$4, {
                        field: "created_at",
                        modelValue: unref(form).sortable,
                        "onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => unref(form).sortable = $event)
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Created at")), 1)
                        ]),
                        _: 1
                      }, 8, ["modelValue"])
                    ])
                  ]),
                  (openBlock(true), createElementBlock(Fragment, null, renderList(__props.tracks.data, (track) => {
                    return openBlock(), createElementBlock("tr", {
                      key: track.id
                    }, [
                      createBaseVNode("td", _hoisted_31, [
                        createBaseVNode("a", {
                          target: "_blank",
                          href: track.provider_url,
                          class: "flex items-center justify-center px-2 py-4 focus:text-blinest-500"
                        }, [
                          createVNode(_sfc_main$a, {
                            name: track.provider,
                            title: track.provider,
                            class: "mr-2 h-6 w-6 flex-shrink-0"
                          }, null, 8, ["name", "title"])
                        ], 8, _hoisted_32)
                      ]),
                      createBaseVNode("td", _hoisted_33, [
                        createBaseVNode("div", _hoisted_34, [
                          (openBlock(), createBlock(_sfc_main$5, {
                            key: `mini-player-list-${track.id}`,
                            track
                          }, null, 8, ["track"]))
                        ])
                      ]),
                      createBaseVNode("td", _hoisted_35, [
                        createBaseVNode("div", _hoisted_36, [
                          (openBlock(true), createElementBlock(Fragment, null, renderList(track.answers, (answer) => {
                            return openBlock(), createElementBlock("div", {
                              key: answer.id,
                              class: "cursor-pointer whitespace-normal break-words",
                              onClick: ($event) => editAnswer(track, answer)
                            }, [
                              createBaseVNode("span", _hoisted_38, toDisplayString(_ctx.__(answer.type.name)) + ":", 1),
                              createTextVNode(" " + toDisplayString(answer.value) + " (" + toDisplayString(answer.score) + "pts) ", 1)
                            ], 8, _hoisted_37);
                          }), 128)),
                          createBaseVNode("button", {
                            class: "text-neutral-400",
                            onClick: ($event) => createAnswer(track)
                          }, [
                            createVNode(_sfc_main$a, {
                              name: "plus",
                              class: "inline-block h-4 w-4 flex-shrink-0 fill-neutral-400"
                            }),
                            createTextVNode(toDisplayString(_ctx.__("Add an answer")), 1)
                          ], 8, _hoisted_39)
                        ])
                      ]),
                      createBaseVNode("td", _hoisted_40, [
                        createBaseVNode("div", _hoisted_41, [
                          createVNode(_sfc_main$7, {
                            modelValue: track.dificulty,
                            "onUpdate:modelValue": ($event) => track.dificulty = $event,
                            error: _ctx.$page.props.errors.dificulty,
                            onChange: ($event) => updateDificulty($event, track)
                          }, {
                            default: withCtx(() => [
                              _hoisted_42,
                              _hoisted_43,
                              _hoisted_44,
                              _hoisted_45
                            ]),
                            _: 2
                          }, 1032, ["modelValue", "onUpdate:modelValue", "error", "onChange"])
                        ])
                      ]),
                      createBaseVNode("td", _hoisted_46, [
                        createBaseVNode("div", _hoisted_47, [
                          createVNode(_sfc_main$a, {
                            name: "thumb-up",
                            class: "mr-2 h-6 w-6 flex-shrink-0"
                          }),
                          createTextVNode(" " + toDisplayString(track.up_votes), 1)
                        ])
                      ]),
                      createBaseVNode("td", _hoisted_48, [
                        createBaseVNode("div", _hoisted_49, [
                          createVNode(_sfc_main$a, {
                            name: "thumb-down",
                            class: "mr-2 h-6 w-6 flex-shrink-0"
                          }),
                          createTextVNode(" " + toDisplayString(track.down_votes), 1)
                        ])
                      ]),
                      createBaseVNode("td", _hoisted_50, [
                        createBaseVNode("div", _hoisted_51, toDisplayString(track.created_at), 1)
                      ]),
                      createBaseVNode("td", _hoisted_52, [
                        createVNode(_sfc_main$a, {
                          name: "delete",
                          class: "mr-2 h-6 w-6 flex-shrink-0 cursor-pointer fill-red-400",
                          onClick: ($event) => removeTrack(track)
                        }, null, 8, ["onClick"])
                      ])
                    ]);
                  }), 128)),
                  __props.tracks.length === 0 ? (openBlock(), createElementBlock("tr", _hoisted_53, [
                    createBaseVNode("td", _hoisted_54, toDisplayString(_ctx.__("No tracks found")) + ".", 1)
                  ])) : createCommentVNode("", true)
                ])
              ])) : (openBlock(), createElementBlock("div", _hoisted_55, toDisplayString(_ctx.__("Aucun extrait")), 1)),
              createVNode(_sfc_main$c, {
                class: "p-8",
                links: __props.tracks.links
              }, null, 8, ["links"]),
              creatingAnswer.value || editingAnswer.value && selectedAnswer.value ? (openBlock(), createBlock(_sfc_main$6, {
                key: 2,
                answer: selectedAnswer.value,
                answer_types: __props.answer_types,
                show: editingAnswer.value || creatingAnswer.value,
                "max-width": "md",
                onClose: closeModal
              }, null, 8, ["answer", "answer_types", "show"])) : createCommentVNode("", true)
            ])
          ]),
          _: 1
        }),
        importingPlaylist.value ? (openBlock(), createBlock(_sfc_main$3, {
          key: 0,
          onClose: _cache[7] || (_cache[7] = ($event) => importingPlaylist.value = false),
          playlist: __props.playlist
        }, null, 8, ["playlist"])) : createCommentVNode("", true)
      ], 64);
    };
  }
};
const _hoisted_1$1 = { class: "text-xl font-bold" };
const _hoisted_2$1 = {
  key: 0,
  class: "max-w-50 max-h-80 overflow-y-auto"
};
const _hoisted_3$1 = ["src"];
const _hoisted_4 = { class: "mr-4" };
const _hoisted_5 = ["title", "onClick"];
const _hoisted_6 = { key: 0 };
const _hoisted_7 = ["src"];
const _hoisted_8 = ["title", "onClick"];
const _hoisted_9 = /* @__PURE__ */ createBaseVNode("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  class: "h-6 w-6",
  fill: "none",
  viewBox: "0 0 24 24",
  stroke: "currentColor",
  "stroke-width": "2"
}, [
  /* @__PURE__ */ createBaseVNode("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
  })
], -1);
const _hoisted_10 = [
  _hoisted_9
];
const _hoisted_11 = {
  key: 1,
  class: "my-2 text-sm text-neutral-400"
};
const _sfc_main$1 = {
  __name: "ModeratorsManager",
  props: {
    playlist: Object
  },
  setup(__props) {
    const props = __props;
    const search = ref("");
    const searching = ref(false);
    const users = ref(null);
    const form = P({
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
    const attach = (user) => {
      form.transform((data) => ({
        user_id: user.id
      })).post(`/playlists/${props.playlist.id}/moderators/attach`, {
        preserveScroll: true
      });
    };
    const detach = (user) => {
      form.transform((data) => ({
        user_id: user.id
      })).delete(`/playlists/${props.playlist.id}/moderators/detach`, {
        preserveScroll: true
      });
    };
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        header: withCtx(() => [
          createBaseVNode("h3", _hoisted_1$1, toDisplayString(_ctx.__("Moderators")), 1)
        ]),
        default: withCtx(() => [
          createVNode(_sfc_main$b, {
            placement: "bottom-start",
            class: "mb-2 pb-2",
            onClosed: _cache[1] || (_cache[1] = ($event) => search.value = "")
          }, {
            default: withCtx(() => [
              createVNode(_sfc_main$8, {
                modelValue: search.value,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => search.value = $event),
                "prepend-icon": "search",
                "append-icon": "cheveron-down",
                loading: searching.value,
                placeholder: _ctx.__("Add a moderator")
              }, null, 8, ["modelValue", "loading", "placeholder"])
            ]),
            dropdown: withCtx(() => [
              users.value && users.value.data.length ? (openBlock(), createElementBlock("ul", _hoisted_2$1, [
                (openBlock(true), createElementBlock(Fragment, null, renderList(users.value.data, (user) => {
                  return openBlock(), createElementBlock("li", {
                    key: user.id,
                    class: "flex items-center p-2"
                  }, [
                    user.photo ? (openBlock(), createElementBlock("img", {
                      key: 0,
                      class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                      src: user.photo
                    }, null, 8, _hoisted_3$1)) : createCommentVNode("", true),
                    createBaseVNode("span", _hoisted_4, toDisplayString(user.name), 1),
                    createBaseVNode("button", {
                      class: "ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase",
                      title: _ctx.__("Add"),
                      onClick: ($event) => attach(user)
                    }, toDisplayString(_ctx.__("Add")), 9, _hoisted_5)
                  ]);
                }), 128))
              ])) : createCommentVNode("", true)
            ]),
            _: 1
          }),
          __props.playlist.moderators.length ? (openBlock(), createElementBlock("ul", _hoisted_6, [
            (openBlock(true), createElementBlock(Fragment, null, renderList(__props.playlist.moderators, (moderator) => {
              return openBlock(), createElementBlock("li", {
                key: moderator.id,
                class: "flex items-center rounded p-3"
              }, [
                moderator.photo ? (openBlock(), createElementBlock("img", {
                  key: 0,
                  class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                  src: moderator.photo
                }, null, 8, _hoisted_7)) : createCommentVNode("", true),
                createTextVNode(" " + toDisplayString(moderator.name) + " ", 1),
                moderator.id != __props.playlist.user_id ? (openBlock(), createElementBlock("button", {
                  key: 1,
                  class: "ml-auto text-red-500",
                  title: _ctx.__("Remove"),
                  onClick: ($event) => detach(moderator)
                }, _hoisted_10, 8, _hoisted_8)) : createCommentVNode("", true)
              ]);
            }), 128))
          ])) : (openBlock(), createElementBlock("p", _hoisted_11, toDisplayString(_ctx.__("No moderator")), 1))
        ]),
        _: 1
      });
    };
  }
};
const _hoisted_1 = { class: "text-xl font-bold" };
const _hoisted_2 = { key: 0 };
const _hoisted_3 = {
  key: 1,
  class: "my-2 text-sm text-neutral-400"
};
const _sfc_main = {
  __name: "RoomsList",
  props: {
    playlist: Object
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createBlock(Card, null, {
        header: withCtx(() => [
          createBaseVNode("h3", _hoisted_1, toDisplayString(_ctx.__("Rooms")), 1)
        ]),
        default: withCtx(() => [
          __props.playlist.rooms.length ? (openBlock(), createElementBlock("ul", _hoisted_2, [
            (openBlock(true), createElementBlock(Fragment, null, renderList(__props.playlist.rooms, (room) => {
              return openBlock(), createElementBlock("li", {
                key: room.id,
                class: "flex items-center rounded p-3 hover:bg-neutral-200"
              }, [
                createVNode(unref(ne), {
                  href: _ctx.route("rooms.show", room.slug)
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(room.name), 1)
                  ]),
                  _: 2
                }, 1032, ["href"])
              ]);
            }), 128))
          ])) : (openBlock(), createElementBlock("p", _hoisted_3, toDisplayString(_ctx.__("Empty")), 1))
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main$1 as _,
  _sfc_main as a,
  _sfc_main$2 as b
};
