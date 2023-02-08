import { createSlots, withCtx, createVNode, openBlock, createBlock, toDisplayString, unref, createTextVNode, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
import { usePage, useForm } from "@inertiajs/vue3";
import { C as Card } from "./Card-ee13c838.mjs";
import { _ as _sfc_main$1 } from "./Icon-4a47e6e0.mjs";
import "./_plugin-vue_export-helper-cc2b3d55.mjs";
const _sfc_main = {
  __name: "FAQ",
  __ssrInlineRender: true,
  props: {
    faq: Object
  },
  setup(__props) {
    const props = __props;
    const user = usePage().props.auth.user;
    const form = useForm({
      id: props.faq.id
    });
    const voteUp = () => {
      form.post(`/faq/${props.faq.id}/vote/up`);
    };
    const voteDown = () => {
      form.post(`/faq/${props.faq.id}/vote/down`);
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<li${ssrRenderAttrs(_attrs)}>`);
      _push(ssrRenderComponent(Card, {
        itemscope: "",
        itemprop: "mainEntity",
        itemtype: "https://schema.org/Question"
      }, createSlots({
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex items-center gap-2 font-bold"${_scopeId}><div class="w-10"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"${_scopeId}></path></svg></div><h2 itemprop="name"${_scopeId}>${ssrInterpolate(__props.faq.question)}</h2></div>`);
          } else {
            return [
              createVNode("div", { class: "flex items-center gap-2 font-bold" }, [
                createVNode("div", { class: "w-10" }, [
                  (openBlock(), createBlock("svg", {
                    xmlns: "http://www.w3.org/2000/svg",
                    fill: "none",
                    viewBox: "0 0 24 24",
                    "stroke-width": "1.5",
                    stroke: "currentColor",
                    class: "w-8 h-8"
                  }, [
                    createVNode("path", {
                      "stroke-linecap": "round",
                      "stroke-linejoin": "round",
                      d: "M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"
                    })
                  ]))
                ]),
                createVNode("h2", { itemprop: "name" }, toDisplayString(__props.faq.question), 1)
              ])
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"${_scopeId}><div itemprop="text" class="prose whitespace-pre-wrap prose-invert"${_scopeId}>${__props.faq.answer}</div></div>`);
          } else {
            return [
              createVNode("div", {
                itemscope: "",
                itemprop: "acceptedAnswer",
                itemtype: "https://schema.org/Answer"
              }, [
                createVNode("div", {
                  itemprop: "text",
                  class: "prose whitespace-pre-wrap prose-invert",
                  innerHTML: __props.faq.answer
                }, null, 8, ["innerHTML"])
              ])
            ];
          }
        }),
        _: 2
      }, [
        unref(user) ? {
          name: "footer",
          fn: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="ml-auto flex gap-4"${_scopeId}><button class="flex items-center"${ssrRenderAttr("title", _ctx.__("Like"))}${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$1, {
                name: "thumb-up",
                class: "mr-1 h-5 w-5"
              }, null, _parent2, _scopeId));
              _push2(` ${ssrInterpolate(__props.faq.upvotes)}</button><button class="flex items-center"${ssrRenderAttr("title", _ctx.__("Don't like"))}${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$1, {
                name: "thumb-down",
                class: "mr-1 h-5 w-5"
              }, null, _parent2, _scopeId));
              _push2(` ${ssrInterpolate(__props.faq.downvotes)}</button></div>`);
            } else {
              return [
                createVNode("div", { class: "ml-auto flex gap-4" }, [
                  createVNode("button", {
                    onClick: ($event) => voteUp(),
                    class: "flex items-center",
                    title: _ctx.__("Like")
                  }, [
                    createVNode(_sfc_main$1, {
                      name: "thumb-up",
                      class: "mr-1 h-5 w-5"
                    }),
                    createTextVNode(" " + toDisplayString(__props.faq.upvotes), 1)
                  ], 8, ["onClick", "title"]),
                  createVNode("button", {
                    onClick: ($event) => voteDown(),
                    class: "flex items-center",
                    title: _ctx.__("Don't like")
                  }, [
                    createVNode(_sfc_main$1, {
                      name: "thumb-down",
                      class: "mr-1 h-5 w-5"
                    }),
                    createTextVNode(" " + toDisplayString(__props.faq.downvotes), 1)
                  ], 8, ["onClick", "title"])
                ])
              ];
            }
          }),
          key: "0"
        } : void 0
      ]), _parent));
      _push(`</li>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/FAQ/partials/FAQ.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
