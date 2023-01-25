import { mergeProps, withCtx, unref, openBlock, createBlock, Fragment, renderList, toDisplayString, createVNode, withModifiers, createCommentVNode, useSSRContext, ref, onUnmounted, useAttrs, createTextVNode, withDirectives, vShow, watch, vModelCheckbox } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr, ssrRenderClass, ssrIncludeBooleanAttr, ssrRenderAttrs, ssrRenderSlot, ssrRenderStyle, ssrLooseContain } from "vue/server-renderer";
import { useForm, router, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$a } from "./Icon-4a47e6e0.mjs";
import { C as Card } from "./Card-eda4b3e2.mjs";
import { _ as _sfc_main$9 } from "./TextInput-cddc091b.mjs";
import { _ as _sfc_main$8 } from "./SelectInput-279cfc81.mjs";
import { _ as _sfc_main$c } from "./Pagination-d517bd16.mjs";
import { _ as _sfc_main$7 } from "./Modal-e38f3366.mjs";
import { S as Spinner } from "./Spinner-ec1c59c5.mjs";
import { _ as _sfc_main$b } from "./Dropdown-6785e0d2.mjs";
import pickBy from "lodash/pickBy.js";
import debounce from "lodash/debounce.js";
import throttle from "lodash/throttle.js";
import { T as Tip } from "./Tip-9a139e5c.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
const _sfc_main$6 = {
  __name: "TrackAnswerForm",
  __ssrInlineRender: true,
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
    const form = useForm({
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$7, mergeProps({
        show: __props.show,
        "max-width": __props.maxWidth,
        closeable: __props.closeable,
        onClose: close
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<form${_scopeId}><div class="px-6 py-4"${_scopeId}>`);
            if (__props.answer) {
              _push2(`<div class="text-lg"${_scopeId}>${ssrInterpolate(_ctx.__("Edit Answer"))}</div>`);
            } else {
              _push2(`<div class="text-lg"${_scopeId}>${ssrInterpolate(_ctx.__("Add Answer"))}</div>`);
            }
            _push2(`<div class="mt-4 flex flex-wrap"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$8, {
              modelValue: unref(form).answer_type_id,
              "onUpdate:modelValue": ($event) => unref(form).answer_type_id = $event,
              error: unref(form).errors.answer_type_id,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: _ctx.__("Type")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<!--[-->`);
                  ssrRenderList(__props.answer_types, (type) => {
                    _push3(`<option${ssrRenderAttr("value", type.id)}${_scopeId2}>${ssrInterpolate(_ctx.__(type.name))}</option>`);
                  });
                  _push3(`<!--]-->`);
                } else {
                  return [
                    (openBlock(true), createBlock(Fragment, null, renderList(__props.answer_types, (type) => {
                      return openBlock(), createBlock("option", {
                        value: type.id
                      }, toDisplayString(_ctx.__(type.name)), 9, ["value"]);
                    }), 256))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$9, {
              modelValue: unref(form).score,
              "onUpdate:modelValue": ($event) => unref(form).score = $event,
              type: "number",
              step: "0.5",
              min: "0",
              max: "99",
              error: unref(form).errors.score,
              class: "w-full pb-8 pr-6 lg:w-1/2",
              label: _ctx.__("Score")
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$9, {
              modelValue: unref(form).value,
              "onUpdate:modelValue": ($event) => unref(form).value = $event,
              error: unref(form).errors.value,
              class: "w-full pb-8 pr-6",
              label: _ctx.__("Answer")
            }, null, _parent2, _scopeId));
            _push2(`</div></div><div class="flex items-center justify-between px-2 py-4 text-right"${_scopeId}>`);
            if (__props.answer && __props.answer.id) {
              _push2(`<button type="button" class="${ssrRenderClass([{ "opacity-25": unref(form).processing }, "mx-2 text-red-400"])}"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>${ssrInterpolate(_ctx.__("Delete"))}</button>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="flex items-center"${_scopeId}><button class="btn-secondary mx-2 bg-gray-400"${_scopeId}>${ssrInterpolate(_ctx.__("Close"))}</button>`);
            if (__props.answer && __props.answer.id) {
              _push2(`<button type="submit" class="${ssrRenderClass([{ "opacity-25": unref(form).processing }, "btn-primary ml-2"])}"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>${ssrInterpolate(_ctx.__("Update"))}</button>`);
            } else {
              _push2(`<button type="submit" class="${ssrRenderClass([{ "opacity-25": unref(form).processing }, "btn-primary ml-2"])}"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>${ssrInterpolate(_ctx.__("Add"))}</button>`);
            }
            _push2(`</div></div></form>`);
          } else {
            return [
              createVNode("form", {
                onSubmit: withModifiers(submitForm, ["prevent"])
              }, [
                createVNode("div", { class: "px-6 py-4" }, [
                  __props.answer ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-lg"
                  }, toDisplayString(_ctx.__("Edit Answer")), 1)) : (openBlock(), createBlock("div", {
                    key: 1,
                    class: "text-lg"
                  }, toDisplayString(_ctx.__("Add Answer")), 1)),
                  createVNode("div", { class: "mt-4 flex flex-wrap" }, [
                    createVNode(_sfc_main$8, {
                      modelValue: unref(form).answer_type_id,
                      "onUpdate:modelValue": ($event) => unref(form).answer_type_id = $event,
                      error: unref(form).errors.answer_type_id,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: _ctx.__("Type")
                    }, {
                      default: withCtx(() => [
                        (openBlock(true), createBlock(Fragment, null, renderList(__props.answer_types, (type) => {
                          return openBlock(), createBlock("option", {
                            value: type.id
                          }, toDisplayString(_ctx.__(type.name)), 9, ["value"]);
                        }), 256))
                      ]),
                      _: 1
                    }, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                    createVNode(_sfc_main$9, {
                      modelValue: unref(form).score,
                      "onUpdate:modelValue": ($event) => unref(form).score = $event,
                      type: "number",
                      step: "0.5",
                      min: "0",
                      max: "99",
                      error: unref(form).errors.score,
                      class: "w-full pb-8 pr-6 lg:w-1/2",
                      label: _ctx.__("Score")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"]),
                    createVNode(_sfc_main$9, {
                      modelValue: unref(form).value,
                      "onUpdate:modelValue": ($event) => unref(form).value = $event,
                      error: unref(form).errors.value,
                      class: "w-full pb-8 pr-6",
                      label: _ctx.__("Answer")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "label"])
                  ])
                ]),
                createVNode("div", { class: "flex items-center justify-between px-2 py-4 text-right" }, [
                  __props.answer && __props.answer.id ? (openBlock(), createBlock("button", {
                    key: 0,
                    type: "button",
                    class: ["mx-2 text-red-400", { "opacity-25": unref(form).processing }],
                    disabled: unref(form).processing,
                    onClick: deleteAnswer
                  }, toDisplayString(_ctx.__("Delete")), 11, ["disabled"])) : createCommentVNode("", true),
                  createVNode("div", { class: "flex items-center" }, [
                    createVNode("button", {
                      class: "btn-secondary mx-2 bg-gray-400",
                      onClick: close
                    }, toDisplayString(_ctx.__("Close")), 1),
                    __props.answer && __props.answer.id ? (openBlock(), createBlock("button", {
                      key: 0,
                      type: "submit",
                      class: ["btn-primary ml-2", { "opacity-25": unref(form).processing }],
                      disabled: unref(form).processing
                    }, toDisplayString(_ctx.__("Update")), 11, ["disabled"])) : (openBlock(), createBlock("button", {
                      key: 1,
                      type: "submit",
                      class: ["btn-primary ml-2", { "opacity-25": unref(form).processing }],
                      disabled: unref(form).processing
                    }, toDisplayString(_ctx.__("Add")), 11, ["disabled"]))
                  ])
                ])
              ], 40, ["onSubmit"])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Playlists/TrackAnswerForm.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const _sfc_main$5 = {
  __name: "MiniPlayer",
  __ssrInlineRender: true,
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
    return (_ctx, _push, _parent, _attrs) => {
      if (!isPlaying.value) {
        _push(ssrRenderComponent(_sfc_main$a, mergeProps({
          name: "play",
          class: "mr-2 h-12 w-12 flex-shrink-0 cursor-pointer",
          onClick: play
        }, _attrs), null, _parent));
      } else if (loading.value) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "mr-2 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-neutral-800" }, _attrs))}>`);
        _push(ssrRenderComponent(Spinner, { class: "h-full w-full" }, null, _parent));
        _push(`</div>`);
      } else {
        _push(ssrRenderComponent(_sfc_main$a, mergeProps({
          name: "stop",
          class: "mr-2 h-12 w-12 flex-shrink-0 cursor-pointer",
          onClick: stop
        }, _attrs), null, _parent));
      }
    };
  }
};
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/MiniPlayer.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const __default__ = {
  inheritAttrs: false
};
const _sfc_main$4 = /* @__PURE__ */ Object.assign(__default__, {
  __name: "Sortable",
  __ssrInlineRender: true,
  props: {
    field: {
      type: String,
      default: null
    }
  },
  emits: ["update:modelValue"],
  setup(__props, { emit }) {
    useAttrs();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: [_ctx.$attrs.class, "cursor-pointer"]
      }, _attrs))}>`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      if (_ctx.$attrs.modelValue && __props.field == _ctx.$attrs.modelValue.field) {
        _push(`<div class="inline-block align-middle ml-1">`);
        if (_ctx.$attrs.modelValue.direction === "asc") {
          _push(ssrRenderComponent(_sfc_main$a, {
            name: "arrow-down",
            class: "block w-4 h-4 fill-gray-400"
          }, null, _parent));
        } else {
          _push(ssrRenderComponent(_sfc_main$a, {
            name: "arrow-up",
            class: "block w-4 h-4 fill-gray-400"
          }, null, _parent));
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Sortable.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const _sfc_main$3 = {
  __name: "ImportPlaylist",
  __ssrInlineRender: true,
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
    const checkForm = useForm({
      provider: providers.value[0],
      playlist_id: ""
    });
    const checkPlaylist = () => {
      console.log("Checking");
      checkForm.post(route("playlists.providers.find", props.playlist.id), {
        only: ["providerPlaylist"],
        onSuccess: (response) => {
          pp.value = response.props.providerPlaylist;
          if (pp.value) {
            if (pp.value.message) {
              checkForm.errors.provider_id = `[${pp.value.code}] ${pp.value.message}`;
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$7, mergeProps({
        show: show.value,
        onClose: ($event) => emit("close")
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="p-4"${_scopeId}>`);
            if (step.value === 1) {
              _push2(ssrRenderComponent(Card, { class: "shadow-none" }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<form class="flex flex-col gap-4"${_scopeId2}>`);
                    _push3(ssrRenderComponent(Tip, null, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`Pour importer une playlist, celle-ci doit être publique.<br${_scopeId3}>Les extraits ne pouvant pas être lus par le site ne seront pas importés.`);
                        } else {
                          return [
                            createTextVNode("Pour importer une playlist, celle-ci doit être publique."),
                            createVNode("br"),
                            createTextVNode("Les extraits ne pouvant pas être lus par le site ne seront pas importés.")
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                    _push3(ssrRenderComponent(_sfc_main$8, {
                      modelValue: unref(checkForm).provider,
                      "onUpdate:modelValue": ($event) => unref(checkForm).provider = $event,
                      error: unref(checkForm).provider.error,
                      label: "Origine de la playlist",
                      required: ""
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`<!--[-->`);
                          ssrRenderList(providers.value, (provider) => {
                            _push4(`<option${ssrRenderAttr("value", provider)}${_scopeId3}>${ssrInterpolate(provider)}</option>`);
                          });
                          _push4(`<!--]-->`);
                        } else {
                          return [
                            (openBlock(true), createBlock(Fragment, null, renderList(providers.value, (provider) => {
                              return openBlock(), createBlock("option", { value: provider }, toDisplayString(provider), 9, ["value"]);
                            }), 256))
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                    if (unref(checkForm).provider != "Blinest likes") {
                      _push3(`<div${_scopeId2}>`);
                      _push3(ssrRenderComponent(_sfc_main$9, {
                        modelValue: unref(checkForm).playlist_id,
                        "onUpdate:modelValue": ($event) => unref(checkForm).playlist_id = $event,
                        error: unref(checkForm).errors.provider_id,
                        type: "text",
                        label: "ID de la playlist",
                        required: ""
                      }, null, _parent3, _scopeId2));
                      _push3(`<small${_scopeId2}>Vous pouvez trouver l&#39;ID de la playlist dans la barre d&#39;adresse de votre navigateur</small><br${_scopeId2}><small style="${ssrRenderStyle(unref(checkForm).provider === "Spotify" ? null : { display: "none" })}"${_scopeId2}>Exemple d&#39;ID Spotify : https://open.spotify.com/playlist/<span class="font-bold underline"${_scopeId2}>37i9dQZF1DXcBWIGoYBM5M</span></small><small style="${ssrRenderStyle(unref(checkForm).provider === "Deezer" ? null : { display: "none" })}"${_scopeId2}>Exemple d&#39;ID Deezer : https://www.deezer.com/fr/playlist/<span class="font-bold underline"${_scopeId2}>53362031</span></small></div>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    _push3(ssrRenderComponent(LoadingButton, {
                      class: "btn-secondary btn-sm ml-auto",
                      loading: unref(checkForm).processing
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(_ctx.__("Next"))}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(_ctx.__("Next")), 1)
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                    _push3(`</form>`);
                  } else {
                    return [
                      createVNode("form", {
                        onSubmit: withModifiers(checkPlaylist, ["prevent"]),
                        class: "flex flex-col gap-4"
                      }, [
                        createVNode(Tip, null, {
                          default: withCtx(() => [
                            createTextVNode("Pour importer une playlist, celle-ci doit être publique."),
                            createVNode("br"),
                            createTextVNode("Les extraits ne pouvant pas être lus par le site ne seront pas importés.")
                          ]),
                          _: 1
                        }),
                        createVNode(_sfc_main$8, {
                          modelValue: unref(checkForm).provider,
                          "onUpdate:modelValue": ($event) => unref(checkForm).provider = $event,
                          error: unref(checkForm).provider.error,
                          label: "Origine de la playlist",
                          required: ""
                        }, {
                          default: withCtx(() => [
                            (openBlock(true), createBlock(Fragment, null, renderList(providers.value, (provider) => {
                              return openBlock(), createBlock("option", { value: provider }, toDisplayString(provider), 9, ["value"]);
                            }), 256))
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                        unref(checkForm).provider != "Blinest likes" ? (openBlock(), createBlock("div", { key: 0 }, [
                          createVNode(_sfc_main$9, {
                            modelValue: unref(checkForm).playlist_id,
                            "onUpdate:modelValue": ($event) => unref(checkForm).playlist_id = $event,
                            error: unref(checkForm).errors.provider_id,
                            type: "text",
                            label: "ID de la playlist",
                            required: ""
                          }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                          createVNode("small", null, "Vous pouvez trouver l'ID de la playlist dans la barre d'adresse de votre navigateur"),
                          createVNode("br"),
                          withDirectives(createVNode("small", null, [
                            createTextVNode("Exemple d'ID Spotify : https://open.spotify.com/playlist/"),
                            createVNode("span", { class: "font-bold underline" }, "37i9dQZF1DXcBWIGoYBM5M")
                          ], 512), [
                            [vShow, unref(checkForm).provider === "Spotify"]
                          ]),
                          withDirectives(createVNode("small", null, [
                            createTextVNode("Exemple d'ID Deezer : https://www.deezer.com/fr/playlist/"),
                            createVNode("span", { class: "font-bold underline" }, "53362031")
                          ], 512), [
                            [vShow, unref(checkForm).provider === "Deezer"]
                          ])
                        ])) : createCommentVNode("", true),
                        createVNode(LoadingButton, {
                          class: "btn-secondary btn-sm ml-auto",
                          loading: unref(checkForm).processing
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Next")), 1)
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
            if (step.value === 2) {
              _push2(ssrRenderComponent(Card, { class: "shadow-none" }, {
                header: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<h3 class="uppercase"${_scopeId2}>Confirmer l&#39;importation</h3>`);
                  } else {
                    return [
                      createVNode("h3", { class: "uppercase" }, "Confirmer l'importation")
                    ];
                  }
                }),
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="mb-4 flex items-center"${_scopeId2}><img${ssrRenderAttr("src", pp.value.image)} class="mr-2 h-20 w-20 rounded-full"${_scopeId2}><div class="flex flex-col"${_scopeId2}><h3 class="text-xl"${_scopeId2}>${ssrInterpolate(pp.value.name)}</h3><p class="italic"${_scopeId2}>${ssrInterpolate(pp.value.description)}</p></div></div><ul class="mb-4"${_scopeId2}><li${_scopeId2}><b${_scopeId2}>Origine :</b> ${ssrInterpolate(unref(checkForm).provider)}</li>`);
                    if (pp.value.id) {
                      _push3(`<li${_scopeId2}><b${_scopeId2}>Playlist ID :</b> ${ssrInterpolate(pp.value.id)}</li>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    _push3(`<li${_scopeId2}><b${_scopeId2}>Titres à importer :</b> ${ssrInterpolate(pp.value.tracks_count)}</li></ul>`);
                    _push3(ssrRenderComponent(Tip, null, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`Les titres en doublon ne seront pas importés.`);
                        } else {
                          return [
                            createTextVNode("Les titres en doublon ne seront pas importés.")
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                    _push3(`<div class="mt-4 flex items-center justify-end gap-2"${_scopeId2}><button class="btn-secondary btn-sm"${_scopeId2}>${ssrInterpolate(_ctx.__("Cancel"))}</button>`);
                    _push3(ssrRenderComponent(LoadingButton, {
                      class: "btn-primary btn-sm",
                      onClick: importPlaylist,
                      loading: unref(checkForm).processing
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(_ctx.__("Import"))}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(_ctx.__("Import")), 1)
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                    _push3(`</div>`);
                  } else {
                    return [
                      createVNode("div", { class: "mb-4 flex items-center" }, [
                        createVNode("img", {
                          src: pp.value.image,
                          class: "mr-2 h-20 w-20 rounded-full"
                        }, null, 8, ["src"]),
                        createVNode("div", { class: "flex flex-col" }, [
                          createVNode("h3", { class: "text-xl" }, toDisplayString(pp.value.name), 1),
                          createVNode("p", { class: "italic" }, toDisplayString(pp.value.description), 1)
                        ])
                      ]),
                      createVNode("ul", { class: "mb-4" }, [
                        createVNode("li", null, [
                          createVNode("b", null, "Origine :"),
                          createTextVNode(" " + toDisplayString(unref(checkForm).provider), 1)
                        ]),
                        pp.value.id ? (openBlock(), createBlock("li", { key: 0 }, [
                          createVNode("b", null, "Playlist ID :"),
                          createTextVNode(" " + toDisplayString(pp.value.id), 1)
                        ])) : createCommentVNode("", true),
                        createVNode("li", null, [
                          createVNode("b", null, "Titres à importer :"),
                          createTextVNode(" " + toDisplayString(pp.value.tracks_count), 1)
                        ])
                      ]),
                      createVNode(Tip, null, {
                        default: withCtx(() => [
                          createTextVNode("Les titres en doublon ne seront pas importés.")
                        ]),
                        _: 1
                      }),
                      createVNode("div", { class: "mt-4 flex items-center justify-end gap-2" }, [
                        createVNode("button", {
                          class: "btn-secondary btn-sm",
                          onClick: ($event) => step.value = 1
                        }, toDisplayString(_ctx.__("Cancel")), 9, ["onClick"]),
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
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "p-4" }, [
                step.value === 1 ? (openBlock(), createBlock(Card, {
                  key: 0,
                  class: "shadow-none"
                }, {
                  default: withCtx(() => [
                    createVNode("form", {
                      onSubmit: withModifiers(checkPlaylist, ["prevent"]),
                      class: "flex flex-col gap-4"
                    }, [
                      createVNode(Tip, null, {
                        default: withCtx(() => [
                          createTextVNode("Pour importer une playlist, celle-ci doit être publique."),
                          createVNode("br"),
                          createTextVNode("Les extraits ne pouvant pas être lus par le site ne seront pas importés.")
                        ]),
                        _: 1
                      }),
                      createVNode(_sfc_main$8, {
                        modelValue: unref(checkForm).provider,
                        "onUpdate:modelValue": ($event) => unref(checkForm).provider = $event,
                        error: unref(checkForm).provider.error,
                        label: "Origine de la playlist",
                        required: ""
                      }, {
                        default: withCtx(() => [
                          (openBlock(true), createBlock(Fragment, null, renderList(providers.value, (provider) => {
                            return openBlock(), createBlock("option", { value: provider }, toDisplayString(provider), 9, ["value"]);
                          }), 256))
                        ]),
                        _: 1
                      }, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                      unref(checkForm).provider != "Blinest likes" ? (openBlock(), createBlock("div", { key: 0 }, [
                        createVNode(_sfc_main$9, {
                          modelValue: unref(checkForm).playlist_id,
                          "onUpdate:modelValue": ($event) => unref(checkForm).playlist_id = $event,
                          error: unref(checkForm).errors.provider_id,
                          type: "text",
                          label: "ID de la playlist",
                          required: ""
                        }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                        createVNode("small", null, "Vous pouvez trouver l'ID de la playlist dans la barre d'adresse de votre navigateur"),
                        createVNode("br"),
                        withDirectives(createVNode("small", null, [
                          createTextVNode("Exemple d'ID Spotify : https://open.spotify.com/playlist/"),
                          createVNode("span", { class: "font-bold underline" }, "37i9dQZF1DXcBWIGoYBM5M")
                        ], 512), [
                          [vShow, unref(checkForm).provider === "Spotify"]
                        ]),
                        withDirectives(createVNode("small", null, [
                          createTextVNode("Exemple d'ID Deezer : https://www.deezer.com/fr/playlist/"),
                          createVNode("span", { class: "font-bold underline" }, "53362031")
                        ], 512), [
                          [vShow, unref(checkForm).provider === "Deezer"]
                        ])
                      ])) : createCommentVNode("", true),
                      createVNode(LoadingButton, {
                        class: "btn-secondary btn-sm ml-auto",
                        loading: unref(checkForm).processing
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Next")), 1)
                        ]),
                        _: 1
                      }, 8, ["loading"])
                    ], 40, ["onSubmit"])
                  ]),
                  _: 1
                })) : createCommentVNode("", true),
                step.value === 2 ? (openBlock(), createBlock(Card, {
                  key: 1,
                  class: "shadow-none"
                }, {
                  header: withCtx(() => [
                    createVNode("h3", { class: "uppercase" }, "Confirmer l'importation")
                  ]),
                  default: withCtx(() => [
                    createVNode("div", { class: "mb-4 flex items-center" }, [
                      createVNode("img", {
                        src: pp.value.image,
                        class: "mr-2 h-20 w-20 rounded-full"
                      }, null, 8, ["src"]),
                      createVNode("div", { class: "flex flex-col" }, [
                        createVNode("h3", { class: "text-xl" }, toDisplayString(pp.value.name), 1),
                        createVNode("p", { class: "italic" }, toDisplayString(pp.value.description), 1)
                      ])
                    ]),
                    createVNode("ul", { class: "mb-4" }, [
                      createVNode("li", null, [
                        createVNode("b", null, "Origine :"),
                        createTextVNode(" " + toDisplayString(unref(checkForm).provider), 1)
                      ]),
                      pp.value.id ? (openBlock(), createBlock("li", { key: 0 }, [
                        createVNode("b", null, "Playlist ID :"),
                        createTextVNode(" " + toDisplayString(pp.value.id), 1)
                      ])) : createCommentVNode("", true),
                      createVNode("li", null, [
                        createVNode("b", null, "Titres à importer :"),
                        createTextVNode(" " + toDisplayString(pp.value.tracks_count), 1)
                      ])
                    ]),
                    createVNode(Tip, null, {
                      default: withCtx(() => [
                        createTextVNode("Les titres en doublon ne seront pas importés.")
                      ]),
                      _: 1
                    }),
                    createVNode("div", { class: "mt-4 flex items-center justify-end gap-2" }, [
                      createVNode("button", {
                        class: "btn-secondary btn-sm",
                        onClick: ($event) => step.value = 1
                      }, toDisplayString(_ctx.__("Cancel")), 9, ["onClick"]),
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
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Playlists/ImportPlaylist.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = {
  __name: "TracksManager",
  __ssrInlineRender: true,
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
    const form = useForm({
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
      debounce(() => {
        fecthMusicProviders();
      }, 300)
    );
    watch(
      form,
      throttle(() => {
        router.get(route("playlists.edit", props.playlist), pickBy(form), {
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
      router.post(route("playlists.tracks.store", props.playlist.id), track, {
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
        router.delete(route("playlists.tracks.delete", [props.playlist.id, id]), {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => {
            fecthMusicProviders();
          }
        });
      }
    };
    const updateDificulty = (e, track) => {
      router.put(
        route("playlists.tracks.update", [props.playlist.id, track]),
        { dificulty: e.target.value },
        {
          preserveScroll: true
        }
      );
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(Card, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full items-center justify-between"${_scopeId}><h3 class="text-xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Tracks manager"))} (${ssrInterpolate(__props.tracks.total)})</h3>`);
            _push2(ssrRenderComponent(_sfc_main$9, {
              modelValue: unref(form).search,
              "onUpdate:modelValue": ($event) => unref(form).search = $event,
              "prepend-icon": "search",
              placeholder: _ctx.__("Search in playlist") + "..."
            }, null, _parent2, _scopeId));
            _push2(`<a${ssrRenderAttr("href", _ctx.route("playlists.export", __props.playlist))} target="_blank" class="btn-secondary rounded"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-4 w-4"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"${_scopeId}></path></svg> Exporter </a></div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full items-center justify-between" }, [
                createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Tracks manager")) + " (" + toDisplayString(__props.tracks.total) + ")", 1),
                createVNode(_sfc_main$9, {
                  modelValue: unref(form).search,
                  "onUpdate:modelValue": ($event) => unref(form).search = $event,
                  "prepend-icon": "search",
                  placeholder: _ctx.__("Search in playlist") + "..."
                }, null, 8, ["modelValue", "onUpdate:modelValue", "placeholder"]),
                createVNode("a", {
                  href: _ctx.route("playlists.export", __props.playlist),
                  target: "_blank",
                  class: "btn-secondary rounded"
                }, [
                  (openBlock(), createBlock("svg", {
                    xmlns: "http://www.w3.org/2000/svg",
                    fill: "none",
                    viewBox: "0 0 24 24",
                    "stroke-width": "1.5",
                    stroke: "currentColor",
                    class: "mr-2 h-4 w-4"
                  }, [
                    createVNode("path", {
                      "stroke-linecap": "round",
                      "stroke-linejoin": "round",
                      d: "M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"
                    })
                  ])),
                  createTextVNode(" Exporter ")
                ], 8, ["href"])
              ])
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div${_scopeId}><div class="mb-4 flex justify-between gap-2 p-2"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$b, {
              placement: "bottom-start",
              "auto-close": false
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$9, {
                    modelValue: search_online.value,
                    "onUpdate:modelValue": ($event) => search_online.value = $event,
                    "prepend-icon": "plus",
                    "append-icon": "cheveron-down",
                    loading: loading.value,
                    placeholder: _ctx.__("Search on Deezer, Spotify and Apple music...")
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$9, {
                      modelValue: search_online.value,
                      "onUpdate:modelValue": ($event) => search_online.value = $event,
                      "prepend-icon": "plus",
                      "append-icon": "cheveron-down",
                      loading: loading.value,
                      placeholder: _ctx.__("Search on Deezer, Spotify and Apple music...")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "loading", "placeholder"])
                  ];
                }
              }),
              dropdown: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (results.value.length) {
                    _push3(`<div class="flex gap-2 border-b-2 border-neutral-600 bg-neutral-900 p-2 text-sm"${_scopeId2}><!--[-->`);
                    ssrRenderList(providers.value, (provider) => {
                      _push3(`<label${_scopeId2}><input type="checkbox"${ssrIncludeBooleanAttr(Array.isArray(provider.selected) ? ssrLooseContain(provider.selected, null) : provider.selected) ? " checked" : ""}${_scopeId2}> ${ssrInterpolate(provider.name)}</label>`);
                    });
                    _push3(`<!--]--></div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  if (results.value.length) {
                    _push3(`<ul class="max-h-80 overflow-y-auto bg-neutral-900"${_scopeId2}><!--[-->`);
                    ssrRenderList(results.value.filter((x) => selectedProviders.value.includes(x.provider)), (result) => {
                      _push3(`<li class="relative border-b border-neutral-600 px-2 py-3"${_scopeId2}><div class="flex items-center gap-2"${_scopeId2}><div${_scopeId2}>`);
                      _push3(ssrRenderComponent(_sfc_main$a, {
                        name: result.provider,
                        title: result.provider,
                        class: "h-6 w-6 flex-shrink-0"
                      }, null, _parent3, _scopeId2));
                      _push3(`</div><div${_scopeId2}>`);
                      _push3(ssrRenderComponent(_sfc_main$5, {
                        key: `mini-player-results-${result.id}`,
                        track: result
                      }, null, _parent3, _scopeId2));
                      _push3(`</div><div class="mr-2 flex w-80 flex-grow flex-col"${_scopeId2}><span class="max-w-[16rem] truncate break-normal font-bold"${_scopeId2}>${ssrInterpolate(result.artist_name)}</span><span class="max-w-[16rem] truncate break-normal text-sm"${_scopeId2}>${ssrInterpolate(result.track_name)}</span></div>`);
                      if (!result.added) {
                        _push3(`<div class="ml-auto"${_scopeId2}><button${ssrIncludeBooleanAttr(loading.value) ? " disabled" : ""} class="btn-primary btn-sm" type="button"${_scopeId2}>${ssrInterpolate(_ctx.__("Add"))}</button></div>`);
                      } else {
                        _push3(`<div class="ml-auto"${_scopeId2}><button${ssrIncludeBooleanAttr(loading.value) ? " disabled" : ""} class="btn-danger btn-sm" type="button"${_scopeId2}>${ssrInterpolate(_ctx.__("Remove"))}</button></div>`);
                      }
                      _push3(`</div></li>`);
                    });
                    _push3(`<!--]--></ul>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    results.value.length ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex gap-2 border-b-2 border-neutral-600 bg-neutral-900 p-2 text-sm"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(providers.value, (provider) => {
                        return openBlock(), createBlock("label", null, [
                          withDirectives(createVNode("input", {
                            type: "checkbox",
                            "onUpdate:modelValue": ($event) => provider.selected = $event,
                            onClick: ($event) => check(provider)
                          }, null, 8, ["onUpdate:modelValue", "onClick"]), [
                            [vModelCheckbox, provider.selected]
                          ]),
                          createTextVNode(" " + toDisplayString(provider.name), 1)
                        ]);
                      }), 256))
                    ])) : createCommentVNode("", true),
                    results.value.length ? (openBlock(), createBlock("ul", {
                      key: 1,
                      class: "max-h-80 overflow-y-auto bg-neutral-900"
                    }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(results.value.filter((x) => selectedProviders.value.includes(x.provider)), (result) => {
                        return openBlock(), createBlock("li", {
                          class: "relative border-b border-neutral-600 px-2 py-3",
                          key: result.id
                        }, [
                          createVNode("div", { class: "flex items-center gap-2" }, [
                            createVNode("div", null, [
                              createVNode(_sfc_main$a, {
                                name: result.provider,
                                title: result.provider,
                                class: "h-6 w-6 flex-shrink-0"
                              }, null, 8, ["name", "title"])
                            ]),
                            createVNode("div", null, [
                              (openBlock(), createBlock(_sfc_main$5, {
                                key: `mini-player-results-${result.id}`,
                                track: result
                              }, null, 8, ["track"]))
                            ]),
                            createVNode("div", { class: "mr-2 flex w-80 flex-grow flex-col" }, [
                              createVNode("span", { class: "max-w-[16rem] truncate break-normal font-bold" }, toDisplayString(result.artist_name), 1),
                              createVNode("span", { class: "max-w-[16rem] truncate break-normal text-sm" }, toDisplayString(result.track_name), 1)
                            ]),
                            !result.added ? (openBlock(), createBlock("div", {
                              key: 0,
                              class: "ml-auto"
                            }, [
                              createVNode("button", {
                                disabled: loading.value,
                                class: "btn-primary btn-sm",
                                type: "button",
                                onClick: ($event) => addTrack(result)
                              }, toDisplayString(_ctx.__("Add")), 9, ["disabled", "onClick"])
                            ])) : (openBlock(), createBlock("div", {
                              key: 1,
                              class: "ml-auto"
                            }, [
                              createVNode("button", {
                                disabled: loading.value,
                                class: "btn-danger btn-sm",
                                type: "button",
                                onClick: ($event) => removeTrack(result)
                              }, toDisplayString(_ctx.__("Remove")), 9, ["disabled", "onClick"])
                            ]))
                          ])
                        ]);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<button class="btn-secondary btn-sm"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 mr-2"${_scopeId}><path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v11.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V3a.75.75 0 01.75-.75zm-9 13.5a.75.75 0 01.75.75v2.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V16.5a.75.75 0 011.5 0v2.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V16.5a.75.75 0 01.75-.75z" clip-rule="evenodd"${_scopeId}></path></svg> Importer une playlist </button><div class="flex items-center gap-2"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$8, {
              modelValue: unref(form).paginate,
              "onUpdate:modelValue": ($event) => unref(form).paginate = $event
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<option${ssrRenderAttr("value", 5)}${_scopeId2}>5</option><option${ssrRenderAttr("value", 10)}${_scopeId2}>10</option><option${ssrRenderAttr("value", 15)}${_scopeId2}>15</option><option${ssrRenderAttr("value", 20)}${_scopeId2}>20</option>`);
                } else {
                  return [
                    createVNode("option", { value: 5 }, "5"),
                    createVNode("option", { value: 10 }, "10"),
                    createVNode("option", { value: 15 }, "15"),
                    createVNode("option", { value: 20 }, "20")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></div>`);
            if (__props.tracks.data.length) {
              _push2(`<div class="mx-4 overflow-x-auto"${_scopeId}><table class="w-full whitespace-nowrap"${_scopeId}><tr class="text-left font-bold"${_scopeId}><th class="px-6 pb-4 pt-6" colspan="2"${_scopeId}></th><th class="px-6 pb-4 pt-6"${_scopeId}>${ssrInterpolate(_ctx.__("Answers"))}</th><th class="px-6 pb-4 pt-6"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$4, {
                field: "dificulty",
                modelValue: unref(form).sortable,
                "onUpdate:modelValue": ($event) => unref(form).sortable = $event
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Dificulty"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Dificulty")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</th><th class="px-6 pb-4 pt-6" colspan="2"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$4, {
                field: "votes",
                modelValue: unref(form).sortable,
                "onUpdate:modelValue": ($event) => unref(form).sortable = $event
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Votes"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Votes")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</th><th class="px-6 pb-4 pt-6" colspan="2"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$4, {
                field: "created_at",
                modelValue: unref(form).sortable,
                "onUpdate:modelValue": ($event) => unref(form).sortable = $event
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(_ctx.__("Created at"))}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(_ctx.__("Created at")), 1)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</th></tr><!--[-->`);
              ssrRenderList(__props.tracks.data, (track) => {
                _push2(`<tr${_scopeId}><td class="border-t"${_scopeId}><a target="_blank"${ssrRenderAttr("href", track.provider_url)} class="flex items-center justify-center px-2 py-4 focus:text-blinest-500"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$a, {
                  name: track.provider,
                  title: track.provider,
                  class: "mr-2 h-6 w-6 flex-shrink-0"
                }, null, _parent2, _scopeId));
                _push2(`</a></td><td class="border-t"${_scopeId}><div class="flex items-center justify-center px-2 py-4 focus:text-blinest-500"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$5, {
                  key: `mini-player-list-${track.id}`,
                  track
                }, null, _parent2, _scopeId));
                _push2(`</div></td><td class="border-t"${_scopeId}><div class="flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500"${_scopeId}><!--[-->`);
                ssrRenderList(track.answers, (answer) => {
                  _push2(`<div class="cursor-pointer whitespace-normal break-words"${_scopeId}><span class="font-bold"${_scopeId}>${ssrInterpolate(_ctx.__(answer.type.name))}:</span> ${ssrInterpolate(answer.value)} (${ssrInterpolate(answer.score)}pts) </div>`);
                });
                _push2(`<!--]--><button class="text-neutral-400"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$a, {
                  name: "plus",
                  class: "inline-block h-4 w-4 flex-shrink-0 fill-neutral-400"
                }, null, _parent2, _scopeId));
                _push2(`${ssrInterpolate(_ctx.__("Add an answer"))}</button></div></td><td class="border-t"${_scopeId}><div class="flex items-start px-6 py-4 text-center text-sm"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$8, {
                  modelValue: track.dificulty,
                  "onUpdate:modelValue": ($event) => track.dificulty = $event,
                  error: _ctx.$page.props.errors.dificulty,
                  onChange: ($event) => updateDificulty($event, track)
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`<option${ssrRenderAttr("value", 0)}${_scopeId2}>Facile</option><option${ssrRenderAttr("value", 1)}${_scopeId2}>Moyen</option><option${ssrRenderAttr("value", 2)}${_scopeId2}>Difficile</option><option${ssrRenderAttr("value", 3)}${_scopeId2}>Expert</option>`);
                    } else {
                      return [
                        createVNode("option", { value: 0 }, "Facile"),
                        createVNode("option", { value: 1 }, "Moyen"),
                        createVNode("option", { value: 2 }, "Difficile"),
                        createVNode("option", { value: 3 }, "Expert")
                      ];
                    }
                  }),
                  _: 2
                }, _parent2, _scopeId));
                _push2(`</div></td><td class="border-t"${_scopeId}><div class="flex items-start px-6 py-4 text-center text-sm"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$a, {
                  name: "thumb-up",
                  class: "mr-2 h-6 w-6 flex-shrink-0"
                }, null, _parent2, _scopeId));
                _push2(` ${ssrInterpolate(track.up_votes)}</div></td><td class="border-t"${_scopeId}><div class="flex items-start px-6 py-4 text-center text-sm focus:text-blinest-500"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$a, {
                  name: "thumb-down",
                  class: "mr-2 h-6 w-6 flex-shrink-0"
                }, null, _parent2, _scopeId));
                _push2(` ${ssrInterpolate(track.down_votes)}</div></td><td class="border-t"${_scopeId}><div class="flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500"${_scopeId}>${ssrInterpolate(track.created_at)}</div></td><td class="border-t"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$a, {
                  name: "delete",
                  class: "mr-2 h-6 w-6 flex-shrink-0 cursor-pointer fill-red-400",
                  onClick: ($event) => removeTrack(track)
                }, null, _parent2, _scopeId));
                _push2(`</td></tr>`);
              });
              _push2(`<!--]-->`);
              if (__props.tracks.length === 0) {
                _push2(`<tr${_scopeId}><td class="border-t px-6 py-4" colspan="4"${_scopeId}>${ssrInterpolate(_ctx.__("No tracks found"))}.</td></tr>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</table></div>`);
            } else {
              _push2(`<div class="p-2"${_scopeId}>${ssrInterpolate(_ctx.__("Aucun extrait"))}</div>`);
            }
            _push2(ssrRenderComponent(_sfc_main$c, {
              class: "p-8",
              links: __props.tracks.links
            }, null, _parent2, _scopeId));
            if (creatingAnswer.value || editingAnswer.value && selectedAnswer.value) {
              _push2(ssrRenderComponent(_sfc_main$6, {
                answer: selectedAnswer.value,
                answer_types: __props.answer_types,
                show: editingAnswer.value || creatingAnswer.value,
                "max-width": "md",
                onClose: closeModal
              }, null, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", null, [
                createVNode("div", { class: "mb-4 flex justify-between gap-2 p-2" }, [
                  createVNode(_sfc_main$b, {
                    placement: "bottom-start",
                    "auto-close": false
                  }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$9, {
                        modelValue: search_online.value,
                        "onUpdate:modelValue": ($event) => search_online.value = $event,
                        "prepend-icon": "plus",
                        "append-icon": "cheveron-down",
                        loading: loading.value,
                        placeholder: _ctx.__("Search on Deezer, Spotify and Apple music...")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "loading", "placeholder"])
                    ]),
                    dropdown: withCtx(() => [
                      results.value.length ? (openBlock(), createBlock("div", {
                        key: 0,
                        class: "flex gap-2 border-b-2 border-neutral-600 bg-neutral-900 p-2 text-sm"
                      }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(providers.value, (provider) => {
                          return openBlock(), createBlock("label", null, [
                            withDirectives(createVNode("input", {
                              type: "checkbox",
                              "onUpdate:modelValue": ($event) => provider.selected = $event,
                              onClick: ($event) => check(provider)
                            }, null, 8, ["onUpdate:modelValue", "onClick"]), [
                              [vModelCheckbox, provider.selected]
                            ]),
                            createTextVNode(" " + toDisplayString(provider.name), 1)
                          ]);
                        }), 256))
                      ])) : createCommentVNode("", true),
                      results.value.length ? (openBlock(), createBlock("ul", {
                        key: 1,
                        class: "max-h-80 overflow-y-auto bg-neutral-900"
                      }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(results.value.filter((x) => selectedProviders.value.includes(x.provider)), (result) => {
                          return openBlock(), createBlock("li", {
                            class: "relative border-b border-neutral-600 px-2 py-3",
                            key: result.id
                          }, [
                            createVNode("div", { class: "flex items-center gap-2" }, [
                              createVNode("div", null, [
                                createVNode(_sfc_main$a, {
                                  name: result.provider,
                                  title: result.provider,
                                  class: "h-6 w-6 flex-shrink-0"
                                }, null, 8, ["name", "title"])
                              ]),
                              createVNode("div", null, [
                                (openBlock(), createBlock(_sfc_main$5, {
                                  key: `mini-player-results-${result.id}`,
                                  track: result
                                }, null, 8, ["track"]))
                              ]),
                              createVNode("div", { class: "mr-2 flex w-80 flex-grow flex-col" }, [
                                createVNode("span", { class: "max-w-[16rem] truncate break-normal font-bold" }, toDisplayString(result.artist_name), 1),
                                createVNode("span", { class: "max-w-[16rem] truncate break-normal text-sm" }, toDisplayString(result.track_name), 1)
                              ]),
                              !result.added ? (openBlock(), createBlock("div", {
                                key: 0,
                                class: "ml-auto"
                              }, [
                                createVNode("button", {
                                  disabled: loading.value,
                                  class: "btn-primary btn-sm",
                                  type: "button",
                                  onClick: ($event) => addTrack(result)
                                }, toDisplayString(_ctx.__("Add")), 9, ["disabled", "onClick"])
                              ])) : (openBlock(), createBlock("div", {
                                key: 1,
                                class: "ml-auto"
                              }, [
                                createVNode("button", {
                                  disabled: loading.value,
                                  class: "btn-danger btn-sm",
                                  type: "button",
                                  onClick: ($event) => removeTrack(result)
                                }, toDisplayString(_ctx.__("Remove")), 9, ["disabled", "onClick"])
                              ]))
                            ])
                          ]);
                        }), 128))
                      ])) : createCommentVNode("", true)
                    ]),
                    _: 1
                  }),
                  createVNode("button", {
                    class: "btn-secondary btn-sm",
                    onClick: ($event) => importingPlaylist.value = true
                  }, [
                    (openBlock(), createBlock("svg", {
                      xmlns: "http://www.w3.org/2000/svg",
                      viewBox: "0 0 24 24",
                      fill: "currentColor",
                      class: "h-5 w-5 mr-2"
                    }, [
                      createVNode("path", {
                        "fill-rule": "evenodd",
                        d: "M12 2.25a.75.75 0 01.75.75v11.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V3a.75.75 0 01.75-.75zm-9 13.5a.75.75 0 01.75.75v2.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V16.5a.75.75 0 011.5 0v2.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V16.5a.75.75 0 01.75-.75z",
                        "clip-rule": "evenodd"
                      })
                    ])),
                    createTextVNode(" Importer une playlist ")
                  ], 8, ["onClick"]),
                  createVNode("div", { class: "flex items-center gap-2" }, [
                    createVNode(_sfc_main$8, {
                      modelValue: unref(form).paginate,
                      "onUpdate:modelValue": ($event) => unref(form).paginate = $event
                    }, {
                      default: withCtx(() => [
                        createVNode("option", { value: 5 }, "5"),
                        createVNode("option", { value: 10 }, "10"),
                        createVNode("option", { value: 15 }, "15"),
                        createVNode("option", { value: 20 }, "20")
                      ]),
                      _: 1
                    }, 8, ["modelValue", "onUpdate:modelValue"])
                  ])
                ]),
                __props.tracks.data.length ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "mx-4 overflow-x-auto"
                }, [
                  createVNode("table", { class: "w-full whitespace-nowrap" }, [
                    createVNode("tr", { class: "text-left font-bold" }, [
                      createVNode("th", {
                        class: "px-6 pb-4 pt-6",
                        colspan: "2"
                      }),
                      createVNode("th", { class: "px-6 pb-4 pt-6" }, toDisplayString(_ctx.__("Answers")), 1),
                      createVNode("th", { class: "px-6 pb-4 pt-6" }, [
                        createVNode(_sfc_main$4, {
                          field: "dificulty",
                          modelValue: unref(form).sortable,
                          "onUpdate:modelValue": ($event) => unref(form).sortable = $event
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Dificulty")), 1)
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue"])
                      ]),
                      createVNode("th", {
                        class: "px-6 pb-4 pt-6",
                        colspan: "2"
                      }, [
                        createVNode(_sfc_main$4, {
                          field: "votes",
                          modelValue: unref(form).sortable,
                          "onUpdate:modelValue": ($event) => unref(form).sortable = $event
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Votes")), 1)
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue"])
                      ]),
                      createVNode("th", {
                        class: "px-6 pb-4 pt-6",
                        colspan: "2"
                      }, [
                        createVNode(_sfc_main$4, {
                          field: "created_at",
                          modelValue: unref(form).sortable,
                          "onUpdate:modelValue": ($event) => unref(form).sortable = $event
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(_ctx.__("Created at")), 1)
                          ]),
                          _: 1
                        }, 8, ["modelValue", "onUpdate:modelValue"])
                      ])
                    ]),
                    (openBlock(true), createBlock(Fragment, null, renderList(__props.tracks.data, (track) => {
                      return openBlock(), createBlock("tr", {
                        key: track.id
                      }, [
                        createVNode("td", { class: "border-t" }, [
                          createVNode("a", {
                            target: "_blank",
                            href: track.provider_url,
                            class: "flex items-center justify-center px-2 py-4 focus:text-blinest-500"
                          }, [
                            createVNode(_sfc_main$a, {
                              name: track.provider,
                              title: track.provider,
                              class: "mr-2 h-6 w-6 flex-shrink-0"
                            }, null, 8, ["name", "title"])
                          ], 8, ["href"])
                        ]),
                        createVNode("td", { class: "border-t" }, [
                          createVNode("div", { class: "flex items-center justify-center px-2 py-4 focus:text-blinest-500" }, [
                            (openBlock(), createBlock(_sfc_main$5, {
                              key: `mini-player-list-${track.id}`,
                              track
                            }, null, 8, ["track"]))
                          ])
                        ]),
                        createVNode("td", { class: "border-t" }, [
                          createVNode("div", { class: "flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500" }, [
                            (openBlock(true), createBlock(Fragment, null, renderList(track.answers, (answer) => {
                              return openBlock(), createBlock("div", {
                                key: answer.id,
                                class: "cursor-pointer whitespace-normal break-words",
                                onClick: ($event) => editAnswer(track, answer)
                              }, [
                                createVNode("span", { class: "font-bold" }, toDisplayString(_ctx.__(answer.type.name)) + ":", 1),
                                createTextVNode(" " + toDisplayString(answer.value) + " (" + toDisplayString(answer.score) + "pts) ", 1)
                              ], 8, ["onClick"]);
                            }), 128)),
                            createVNode("button", {
                              class: "text-neutral-400",
                              onClick: ($event) => createAnswer(track)
                            }, [
                              createVNode(_sfc_main$a, {
                                name: "plus",
                                class: "inline-block h-4 w-4 flex-shrink-0 fill-neutral-400"
                              }),
                              createTextVNode(toDisplayString(_ctx.__("Add an answer")), 1)
                            ], 8, ["onClick"])
                          ])
                        ]),
                        createVNode("td", { class: "border-t" }, [
                          createVNode("div", { class: "flex items-start px-6 py-4 text-center text-sm" }, [
                            createVNode(_sfc_main$8, {
                              modelValue: track.dificulty,
                              "onUpdate:modelValue": ($event) => track.dificulty = $event,
                              error: _ctx.$page.props.errors.dificulty,
                              onChange: ($event) => updateDificulty($event, track)
                            }, {
                              default: withCtx(() => [
                                createVNode("option", { value: 0 }, "Facile"),
                                createVNode("option", { value: 1 }, "Moyen"),
                                createVNode("option", { value: 2 }, "Difficile"),
                                createVNode("option", { value: 3 }, "Expert")
                              ]),
                              _: 2
                            }, 1032, ["modelValue", "onUpdate:modelValue", "error", "onChange"])
                          ])
                        ]),
                        createVNode("td", { class: "border-t" }, [
                          createVNode("div", { class: "flex items-start px-6 py-4 text-center text-sm" }, [
                            createVNode(_sfc_main$a, {
                              name: "thumb-up",
                              class: "mr-2 h-6 w-6 flex-shrink-0"
                            }),
                            createTextVNode(" " + toDisplayString(track.up_votes), 1)
                          ])
                        ]),
                        createVNode("td", { class: "border-t" }, [
                          createVNode("div", { class: "flex items-start px-6 py-4 text-center text-sm focus:text-blinest-500" }, [
                            createVNode(_sfc_main$a, {
                              name: "thumb-down",
                              class: "mr-2 h-6 w-6 flex-shrink-0"
                            }),
                            createTextVNode(" " + toDisplayString(track.down_votes), 1)
                          ])
                        ]),
                        createVNode("td", { class: "border-t" }, [
                          createVNode("div", { class: "flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500" }, toDisplayString(track.created_at), 1)
                        ]),
                        createVNode("td", { class: "border-t" }, [
                          createVNode(_sfc_main$a, {
                            name: "delete",
                            class: "mr-2 h-6 w-6 flex-shrink-0 cursor-pointer fill-red-400",
                            onClick: ($event) => removeTrack(track)
                          }, null, 8, ["onClick"])
                        ])
                      ]);
                    }), 128)),
                    __props.tracks.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                      createVNode("td", {
                        class: "border-t px-6 py-4",
                        colspan: "4"
                      }, toDisplayString(_ctx.__("No tracks found")) + ".", 1)
                    ])) : createCommentVNode("", true)
                  ])
                ])) : (openBlock(), createBlock("div", {
                  key: 1,
                  class: "p-2"
                }, toDisplayString(_ctx.__("Aucun extrait")), 1)),
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
            ];
          }
        }),
        _: 1
      }, _parent));
      if (importingPlaylist.value) {
        _push(ssrRenderComponent(_sfc_main$3, {
          onClose: ($event) => importingPlaylist.value = false,
          playlist: __props.playlist
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Playlists/TracksManager.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = {
  __name: "ModeratorsManager",
  __ssrInlineRender: true,
  props: {
    playlist: Object
  },
  setup(__props) {
    const props = __props;
    const search = ref("");
    const searching = ref(false);
    const users = ref(null);
    const form = useForm({
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
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="text-xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Moderators"))}</h3>`);
          } else {
            return [
              createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Moderators")), 1)
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$b, {
              placement: "bottom-start",
              class: "mb-2 pb-2",
              onClosed: ($event) => search.value = ""
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$9, {
                    modelValue: search.value,
                    "onUpdate:modelValue": ($event) => search.value = $event,
                    "prepend-icon": "search",
                    "append-icon": "cheveron-down",
                    loading: searching.value,
                    placeholder: _ctx.__("Add a moderator")
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$9, {
                      modelValue: search.value,
                      "onUpdate:modelValue": ($event) => search.value = $event,
                      "prepend-icon": "search",
                      "append-icon": "cheveron-down",
                      loading: searching.value,
                      placeholder: _ctx.__("Add a moderator")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "loading", "placeholder"])
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
                      _push3(`<span class="mr-4"${_scopeId2}>${ssrInterpolate(user.name)}</span><button class="ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase"${ssrRenderAttr("title", _ctx.__("Add"))}${_scopeId2}>${ssrInterpolate(_ctx.__("Add"))}</button></li>`);
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
                            title: _ctx.__("Add"),
                            onClick: ($event) => attach(user)
                          }, toDisplayString(_ctx.__("Add")), 9, ["title", "onClick"])
                        ]);
                      }), 128))
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            if (__props.playlist.moderators.length) {
              _push2(`<ul${_scopeId}><!--[-->`);
              ssrRenderList(__props.playlist.moderators, (moderator) => {
                _push2(`<li class="flex items-center rounded p-3"${_scopeId}>`);
                if (moderator.photo) {
                  _push2(`<img class="-my-2 mr-2 block h-8 w-8 rounded-full"${ssrRenderAttr("src", moderator.photo)}${_scopeId}>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(` ${ssrInterpolate(moderator.name)} `);
                if (moderator.id != __props.playlist.user_id) {
                  _push2(`<button class="ml-auto text-red-500"${ssrRenderAttr("title", _ctx.__("Remove"))}${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"${_scopeId}></path></svg></button>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(`</li>`);
              });
              _push2(`<!--]--></ul>`);
            } else {
              _push2(`<p class="my-2 text-sm text-neutral-400"${_scopeId}>${ssrInterpolate(_ctx.__("No moderator"))}</p>`);
            }
          } else {
            return [
              createVNode(_sfc_main$b, {
                placement: "bottom-start",
                class: "mb-2 pb-2",
                onClosed: ($event) => search.value = ""
              }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$9, {
                    modelValue: search.value,
                    "onUpdate:modelValue": ($event) => search.value = $event,
                    "prepend-icon": "search",
                    "append-icon": "cheveron-down",
                    loading: searching.value,
                    placeholder: _ctx.__("Add a moderator")
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "loading", "placeholder"])
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
                          title: _ctx.__("Add"),
                          onClick: ($event) => attach(user)
                        }, toDisplayString(_ctx.__("Add")), 9, ["title", "onClick"])
                      ]);
                    }), 128))
                  ])) : createCommentVNode("", true)
                ]),
                _: 1
              }, 8, ["onClosed"]),
              __props.playlist.moderators.length ? (openBlock(), createBlock("ul", { key: 0 }, [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.playlist.moderators, (moderator) => {
                  return openBlock(), createBlock("li", {
                    key: moderator.id,
                    class: "flex items-center rounded p-3"
                  }, [
                    moderator.photo ? (openBlock(), createBlock("img", {
                      key: 0,
                      class: "-my-2 mr-2 block h-8 w-8 rounded-full",
                      src: moderator.photo
                    }, null, 8, ["src"])) : createCommentVNode("", true),
                    createTextVNode(" " + toDisplayString(moderator.name) + " ", 1),
                    moderator.id != __props.playlist.user_id ? (openBlock(), createBlock("button", {
                      key: 1,
                      class: "ml-auto text-red-500",
                      title: _ctx.__("Remove"),
                      onClick: ($event) => detach(moderator)
                    }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        class: "h-6 w-6",
                        fill: "none",
                        viewBox: "0 0 24 24",
                        stroke: "currentColor",
                        "stroke-width": "2"
                      }, [
                        createVNode("path", {
                          "stroke-linecap": "round",
                          "stroke-linejoin": "round",
                          d: "M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        })
                      ]))
                    ], 8, ["title", "onClick"])) : createCommentVNode("", true)
                  ]);
                }), 128))
              ])) : (openBlock(), createBlock("p", {
                key: 1,
                class: "my-2 text-sm text-neutral-400"
              }, toDisplayString(_ctx.__("No moderator")), 1))
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Playlists/ModeratorsManager.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "RoomsList",
  __ssrInlineRender: true,
  props: {
    playlist: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Card, _attrs, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="text-xl font-bold"${_scopeId}>${ssrInterpolate(_ctx.__("Rooms"))}</h3>`);
          } else {
            return [
              createVNode("h3", { class: "text-xl font-bold" }, toDisplayString(_ctx.__("Rooms")), 1)
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (__props.playlist.rooms.length) {
              _push2(`<ul${_scopeId}><!--[-->`);
              ssrRenderList(__props.playlist.rooms, (room) => {
                _push2(`<li class="flex items-center rounded p-3 hover:bg-neutral-200"${_scopeId}>`);
                _push2(ssrRenderComponent(unref(Link), {
                  href: _ctx.route("rooms.show", room.id)
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`${ssrInterpolate(room.name)}`);
                    } else {
                      return [
                        createTextVNode(toDisplayString(room.name), 1)
                      ];
                    }
                  }),
                  _: 2
                }, _parent2, _scopeId));
                _push2(`</li>`);
              });
              _push2(`<!--]--></ul>`);
            } else {
              _push2(`<p class="my-2 text-sm text-neutral-400"${_scopeId}>${ssrInterpolate(_ctx.__("Empty"))}</p>`);
            }
          } else {
            return [
              __props.playlist.rooms.length ? (openBlock(), createBlock("ul", { key: 0 }, [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.playlist.rooms, (room) => {
                  return openBlock(), createBlock("li", {
                    key: room.id,
                    class: "flex items-center rounded p-3 hover:bg-neutral-200"
                  }, [
                    createVNode(unref(Link), {
                      href: _ctx.route("rooms.show", room.id)
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(room.name), 1)
                      ]),
                      _: 2
                    }, 1032, ["href"])
                  ]);
                }), 128))
              ])) : (openBlock(), createBlock("p", {
                key: 1,
                class: "my-2 text-sm text-neutral-400"
              }, toDisplayString(_ctx.__("Empty")), 1))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Playlists/RoomsList.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main$1 as _,
  _sfc_main as a,
  _sfc_main$2 as b
};
