import { unref, withCtx, createTextVNode, toDisplayString, createVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AppLayout-ffba4027.mjs";
import { _ as _sfc_main$2 } from "./TextInput-cddc091b.mjs";
import { L as LoadingButton } from "./LoadingButton-14ad237b.mjs";
import "./Card-eda4b3e2.mjs";
import "./LanguageSwitcher-18bdae21.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
import "./Icon-4a47e6e0.mjs";
import "./Dropdown-6785e0d2.mjs";
import "@popperjs/core";
import "./Navbar-d136d2f8.mjs";
import "lodash/throttle.js";
import "lodash/pickBy.js";
import "./SocialIcon-bb2fc3a0.mjs";
import "uuid";
const _sfc_main = {
  __name: "Create",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      name: ""
    });
    const store = () => {
      form.post(route("playlists.store"));
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Create Playlist" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<form${_scopeId}><div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6"${_scopeId}><div class="mx-auto mb-8 max-w-screen-sm lg:mb-16"${_scopeId}><h2 class="mb-4 text-4xl font-extrabold tracking-tight"${_scopeId}>${ssrInterpolate(_ctx.__("Playlists"))}</h2><p class="font-light sm:text-xl"${_scopeId}>Si tu connais déjà par coeur les playlists publiques ou que tu souhaites créer quelque chose d&#39;unique c&#39;est ici que ça se passe.</p><div class="mt-16 flex justify-center"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              modelValue: unref(form).name,
              "onUpdate:modelValue": ($event) => unref(form).name = $event,
              error: unref(form).errors.name,
              class: "text-xl",
              placeholder: _ctx.__("Playlist name"),
              required: ""
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="my-8 flex justify-center"${_scopeId}>`);
            _push2(ssrRenderComponent(LoadingButton, {
              loading: unref(form).processing,
              class: "btn-primary btn-lg",
              type: "submit"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(_ctx.__("Create the playlist"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(_ctx.__("Create the playlist")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></div></div></form>`);
          } else {
            return [
              createVNode("form", {
                onSubmit: withModifiers(store, ["prevent"])
              }, [
                createVNode("div", { class: "mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6" }, [
                  createVNode("div", { class: "mx-auto mb-8 max-w-screen-sm lg:mb-16" }, [
                    createVNode("h2", { class: "mb-4 text-4xl font-extrabold tracking-tight" }, toDisplayString(_ctx.__("Playlists")), 1),
                    createVNode("p", { class: "font-light sm:text-xl" }, "Si tu connais déjà par coeur les playlists publiques ou que tu souhaites créer quelque chose d'unique c'est ici que ça se passe."),
                    createVNode("div", { class: "mt-16 flex justify-center" }, [
                      createVNode(_sfc_main$2, {
                        modelValue: unref(form).name,
                        "onUpdate:modelValue": ($event) => unref(form).name = $event,
                        error: unref(form).errors.name,
                        class: "text-xl",
                        placeholder: _ctx.__("Playlist name"),
                        required: ""
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "placeholder"])
                    ]),
                    createVNode("div", { class: "my-8 flex justify-center" }, [
                      createVNode(LoadingButton, {
                        loading: unref(form).processing,
                        class: "btn-primary btn-lg",
                        type: "submit"
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(_ctx.__("Create the playlist")), 1)
                        ]),
                        _: 1
                      }, 8, ["loading"])
                    ])
                  ])
                ])
              ], 40, ["onSubmit"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Playlists/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
