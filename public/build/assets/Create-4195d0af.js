import { P, c as createElementBlock, a as createVNode, u as unref, w as withCtx, F as Fragment, o as openBlock, X, b as createBaseVNode, t as toDisplayString, d as createTextVNode, e as withModifiers } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import { _ as _sfc_main$2 } from "./TextInput-541fe8b5.js";
import { L as LoadingButton } from "./LoadingButton-61f4ce4f.js";
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
const _hoisted_1 = ["onSubmit"];
const _hoisted_2 = { class: "mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6" };
const _hoisted_3 = { class: "mx-auto mb-8 max-w-screen-sm lg:mb-16" };
const _hoisted_4 = { class: "mb-4 text-4xl font-extrabold tracking-tight" };
const _hoisted_5 = /* @__PURE__ */ createBaseVNode("p", { class: "font-light sm:text-xl" }, "Si tu connais déjà par coeur les playlists publiques ou que tu souhaites créer quelque chose d'unique c'est ici que ça se passe.", -1);
const _hoisted_6 = { class: "mt-16 flex justify-center" };
const _hoisted_7 = { class: "my-8 flex justify-center" };
const _sfc_main = {
  __name: "Create",
  setup(__props) {
    const form = P({
      name: ""
    });
    const store = () => {
      form.post(route("playlists.store"));
    };
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        createVNode(unref(X), { title: "Create Playlist" }),
        createVNode(_sfc_main$1, null, {
          default: withCtx(() => [
            createBaseVNode("form", {
              onSubmit: withModifiers(store, ["prevent"])
            }, [
              createBaseVNode("div", _hoisted_2, [
                createBaseVNode("div", _hoisted_3, [
                  createBaseVNode("h2", _hoisted_4, toDisplayString(_ctx.__("Playlists")), 1),
                  _hoisted_5,
                  createBaseVNode("div", _hoisted_6, [
                    createVNode(_sfc_main$2, {
                      modelValue: unref(form).name,
                      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => unref(form).name = $event),
                      error: unref(form).errors.name,
                      class: "text-xl",
                      placeholder: _ctx.__("Playlist name"),
                      required: ""
                    }, null, 8, ["modelValue", "error", "placeholder"])
                  ]),
                  createBaseVNode("div", _hoisted_7, [
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
            ], 40, _hoisted_1)
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
