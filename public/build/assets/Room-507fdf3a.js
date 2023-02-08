import { k as ref, q as onMounted, L as onUnmounted, h as createBlock, w as withCtx, x as normalizeStyle, u as unref, o as openBlock, b as createBaseVNode, c as createElementBlock, t as toDisplayString, g as createCommentVNode, a as createVNode, R as Transition, n as ne } from "./app-910e457d.js";
const _hoisted_1 = { class: "relative h-full w-full" };
const _hoisted_2 = /* @__PURE__ */ createBaseVNode("div", { class: "swiper-lazy-preloader" }, null, -1);
const _hoisted_3 = {
  key: 0,
  class: "ribbon truncate text-xs"
};
const _hoisted_4 = { class: "absolute top-0 left-0 w-auto rounded-br-md rounded-tl-md bg-neutral-800 p-3 text-sm text-white ease-in-out hover:scale-110" };
const _hoisted_5 = { class: "flex items-center" };
const _hoisted_6 = {
  key: 0,
  class: "mr-1 font-bold text-orange-400"
};
const _hoisted_7 = ["title"];
const _hoisted_8 = /* @__PURE__ */ createBaseVNode("path", {
  "fill-rule": "evenodd",
  d: "M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z",
  "clip-rule": "evenodd"
}, null, -1);
const _hoisted_9 = {
  key: 1,
  class: "mr-1 font-bold text-orange-400"
};
const _hoisted_10 = ["title"];
const _hoisted_11 = /* @__PURE__ */ createBaseVNode("path", {
  "fill-rule": "evenodd",
  d: "M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z",
  "clip-rule": "evenodd"
}, null, -1);
const _hoisted_12 = {
  key: 0,
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  fill: "currentColor",
  class: "mr-1 h-4 w-4 animate-pulse"
};
const _hoisted_13 = /* @__PURE__ */ createBaseVNode("path", {
  "fill-rule": "evenodd",
  d: "M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z",
  "clip-rule": "evenodd"
}, null, -1);
const _hoisted_14 = [
  _hoisted_13
];
const _hoisted_15 = { class: "font-semibold" };
const _hoisted_16 = { class: "absolute bottom-0 flex w-full items-center justify-between gap-2 bg-neutral-900 py-2 text-sm uppercase text-gray-100" };
const _hoisted_17 = { class: "truncate font-semibold" };
const _hoisted_18 = { class: "whitespace-nowrap" };
const _sfc_main = {
  __name: "Room",
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
    return (_ctx, _cache) => {
      return openBlock(), createBlock(unref(ne), {
        href: `/rooms/${__props.room.slug}`,
        class: "swiper-lazy relative flex h-52 w-full flex-col items-center justify-center rounded-md bg-neutral-800 bg-cover bg-center transition duration-100 ease-in-out hover:z-10 hover:scale-110",
        style: normalizeStyle(`background-image: url(${__props.room.photo_src ? __props.room.photo_src : __props.room.photo});`)
      }, {
        default: withCtx(() => [
          createBaseVNode("article", _hoisted_1, [
            _hoisted_2,
            !__props.room.is_public && __props.room.owner ? (openBlock(), createElementBlock("div", _hoisted_3, "@" + toDisplayString(__props.room.owner.name), 1)) : createCommentVNode("", true),
            createBaseVNode("div", _hoisted_4, [
              createBaseVNode("div", _hoisted_5, [
                __props.room.password ? (openBlock(), createElementBlock("span", _hoisted_6, [
                  (openBlock(), createElementBlock("svg", {
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 24 24",
                    fill: "currentColor",
                    class: "h-4 w-4",
                    title: _ctx.__("Password protected")
                  }, [
                    createBaseVNode("title", null, toDisplayString(_ctx.__("Password protected")), 1),
                    _hoisted_8
                  ], 8, _hoisted_7))
                ])) : createCommentVNode("", true),
                !__props.room.is_autostart ? (openBlock(), createElementBlock("span", _hoisted_9, [
                  (openBlock(), createElementBlock("svg", {
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 24 24",
                    fill: "currentColor",
                    class: "h-5 w-5",
                    title: _ctx.__("This room is in manual start mode.")
                  }, [
                    createBaseVNode("title", null, toDisplayString(_ctx.__("This room is in manual start mode.")), 1),
                    _hoisted_11
                  ], 8, _hoisted_10))
                ])) : createCommentVNode("", true),
                createVNode(Transition, { name: "slide-fade" }, {
                  default: withCtx(() => [
                    playing.value ? (openBlock(), createElementBlock("svg", _hoisted_12, _hoisted_14)) : createCommentVNode("", true)
                  ]),
                  _: 1
                }),
                createBaseVNode("span", _hoisted_15, toDisplayString(userCounter.value), 1)
              ])
            ]),
            createBaseVNode("div", _hoisted_16, [
              createBaseVNode("span", _hoisted_17, toDisplayString(__props.room.name), 1),
              createBaseVNode("div", _hoisted_18, toDisplayString(round.value ? round.value.current : __props.room.current_track_index) + " / " + toDisplayString(__props.room.tracks_by_round), 1)
            ])
          ])
        ]),
        _: 1
      }, 8, ["href", "style"]);
    };
  }
};
export {
  _sfc_main as default
};
