import { ref, onMounted, onUnmounted, unref, mergeProps, withCtx, createVNode, openBlock, createBlock, toDisplayString, createCommentVNode, Transition, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
import { Link } from "@inertiajs/vue3";
const _sfc_main = {
  __name: "Room",
  __ssrInlineRender: true,
  props: {
    room: Object
  },
  setup(__props) {
    const props = __props;
    const channel = `rooms.${props.room.id}`;
    const track = ref(null);
    const round = ref(null);
    const playing = ref(props.room.is_playing);
    const userCounter = ref(props.room.users_count);
    onMounted(() => {
      Echo.channel(channel).listen("RoundStarted", (e) => {
        userCounter.value = e.round.room.users_count;
        playing.value = true;
        round.value = e.round;
      }).listen("TrackPlayed", (e) => {
        props.room.value = e.room;
        userCounter.value = e.room.users_count;
        round.value = e.round;
        track.value = e.track;
      }).listen("RoundFinished", (e) => {
        userCounter.value = e.round.room.users_count;
        playing.value = false;
        round.value = e.round;
        round.value.current = 0;
      });
    });
    onUnmounted(() => {
      Echo.leave(channel);
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Link), mergeProps({
        href: `/rooms/${__props.room.slug}`,
        class: "swiper-lazy relative flex h-52 w-full flex-col items-center justify-center rounded-md bg-neutral-800 bg-cover bg-center transition duration-100 ease-in-out hover:z-10 hover:scale-110",
        style: `background-image: url(${__props.room.photo_src ? __props.room.photo_src : __props.room.photo});`
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<article class="relative h-full w-full"${_scopeId}><div class="swiper-lazy-preloader"${_scopeId}></div>`);
            if (!__props.room.is_public && __props.room.owner) {
              _push2(`<div class="ribbon truncate text-xs"${_scopeId}>@${ssrInterpolate(__props.room.owner.name)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="absolute top-0 left-0 w-auto rounded-br-md rounded-tl-md bg-neutral-800 p-3 text-sm text-white ease-in-out hover:scale-110"${_scopeId}><div class="flex items-center"${_scopeId}>`);
            if (__props.room.password) {
              _push2(`<span class="mr-1 font-bold text-orange-400"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"${ssrRenderAttr("title", _ctx.__("Password protected"))}${_scopeId}><title${_scopeId}>${ssrInterpolate(_ctx.__("Password protected"))}</title><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd"${_scopeId}></path></svg></span>`);
            } else {
              _push2(`<!---->`);
            }
            if (!__props.room.is_autostart) {
              _push2(`<span class="mr-1 font-bold text-orange-400"${_scopeId}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"${ssrRenderAttr("title", _ctx.__("This room is in manual start mode."))}${_scopeId}><title${_scopeId}>${ssrInterpolate(_ctx.__("This room is in manual start mode."))}</title><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"${_scopeId}></path></svg></span>`);
            } else {
              _push2(`<!---->`);
            }
            if (playing.value) {
              _push2(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-4 w-4 animate-pulse"${_scopeId}><path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd"${_scopeId}></path></svg>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<span class="font-semibold"${_scopeId}>${ssrInterpolate(userCounter.value)}</span></div></div><div class="absolute bottom-0 flex w-full items-center justify-between gap-2 bg-neutral-900 py-2 text-sm uppercase text-gray-100"${_scopeId}><span class="truncate font-semibold"${_scopeId}>${ssrInterpolate(__props.room.name)}</span><div class="whitespace-nowrap"${_scopeId}>${ssrInterpolate(round.value ? round.value.current : __props.room.current_track_index)} / ${ssrInterpolate(__props.room.tracks_by_round)}</div></div></article>`);
          } else {
            return [
              createVNode("article", { class: "relative h-full w-full" }, [
                createVNode("div", { class: "swiper-lazy-preloader" }),
                !__props.room.is_public && __props.room.owner ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "ribbon truncate text-xs"
                }, "@" + toDisplayString(__props.room.owner.name), 1)) : createCommentVNode("", true),
                createVNode("div", { class: "absolute top-0 left-0 w-auto rounded-br-md rounded-tl-md bg-neutral-800 p-3 text-sm text-white ease-in-out hover:scale-110" }, [
                  createVNode("div", { class: "flex items-center" }, [
                    __props.room.password ? (openBlock(), createBlock("span", {
                      key: 0,
                      class: "mr-1 font-bold text-orange-400"
                    }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        viewBox: "0 0 24 24",
                        fill: "currentColor",
                        class: "h-4 w-4",
                        title: _ctx.__("Password protected")
                      }, [
                        createVNode("title", null, toDisplayString(_ctx.__("Password protected")), 1),
                        createVNode("path", {
                          "fill-rule": "evenodd",
                          d: "M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z",
                          "clip-rule": "evenodd"
                        })
                      ], 8, ["title"]))
                    ])) : createCommentVNode("", true),
                    !__props.room.is_autostart ? (openBlock(), createBlock("span", {
                      key: 1,
                      class: "mr-1 font-bold text-orange-400"
                    }, [
                      (openBlock(), createBlock("svg", {
                        xmlns: "http://www.w3.org/2000/svg",
                        viewBox: "0 0 24 24",
                        fill: "currentColor",
                        class: "h-5 w-5",
                        title: _ctx.__("This room is in manual start mode.")
                      }, [
                        createVNode("title", null, toDisplayString(_ctx.__("This room is in manual start mode.")), 1),
                        createVNode("path", {
                          "fill-rule": "evenodd",
                          d: "M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z",
                          "clip-rule": "evenodd"
                        })
                      ], 8, ["title"]))
                    ])) : createCommentVNode("", true),
                    createVNode(Transition, { name: "slide-fade" }, {
                      default: withCtx(() => [
                        playing.value ? (openBlock(), createBlock("svg", {
                          key: 0,
                          xmlns: "http://www.w3.org/2000/svg",
                          viewBox: "0 0 24 24",
                          fill: "currentColor",
                          class: "mr-1 h-4 w-4 animate-pulse"
                        }, [
                          createVNode("path", {
                            "fill-rule": "evenodd",
                            d: "M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z",
                            "clip-rule": "evenodd"
                          })
                        ])) : createCommentVNode("", true)
                      ]),
                      _: 1
                    }),
                    createVNode("span", { class: "font-semibold" }, toDisplayString(userCounter.value), 1)
                  ])
                ]),
                createVNode("div", { class: "absolute bottom-0 flex w-full items-center justify-between gap-2 bg-neutral-900 py-2 text-sm uppercase text-gray-100" }, [
                  createVNode("span", { class: "truncate font-semibold" }, toDisplayString(__props.room.name), 1),
                  createVNode("div", { class: "whitespace-nowrap" }, toDisplayString(round.value ? round.value.current : __props.room.current_track_index) + " / " + toDisplayString(__props.room.tracks_by_round), 1)
                ])
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Home/partials/Room.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
