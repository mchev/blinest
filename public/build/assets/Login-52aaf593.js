import { h as createBlock, w as withCtx, o as openBlock, a as createVNode, u as unref, X } from "./app-910e457d.js";
import { _ as _sfc_main$1 } from "./AppLayout-a30bc7e3.js";
import _sfc_main$2 from "./LoginForm-e24c76f9.js";
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
import "./LoadingButton-61f4ce4f.js";
import "./Socialite-eb75d06f.js";
const _sfc_main = {
  __name: "Login",
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1, null, {
        default: withCtx(() => [
          createVNode(unref(X), {
            title: _ctx.__("Login")
          }, null, 8, ["title"]),
          createVNode(_sfc_main$2)
        ]),
        _: 1
      });
    };
  }
};
export {
  _sfc_main as default
};
