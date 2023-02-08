import { unref, withCtx, createVNode, openBlock, createBlock, Fragment, renderList, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
import "@inertiajs/vue3";
import _sfc_main$1 from "./Room-848f4a78.mjs";
import _sfc_main$2 from "./Top-32c0f170.mjs";
import { Navigation, Lazy, A11y } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";
const swiper_min = "";
const navigation_min = "";
const Rooms_vue_vue_type_style_index_0_lang = "";
const _sfc_main = {
  __name: "Rooms",
  __ssrInlineRender: true,
  props: {
    id: String | Number,
    rooms: Object,
    is_top_5: Boolean
  },
  setup(__props) {
    const props = __props;
    const modules = [Navigation, Lazy, A11y];
    const maxSlides = props.rooms && props.rooms.length < 6 ? props.rooms.length : 6;
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.rooms && !__props.is_top_5) {
        _push(`<div${ssrRenderAttrs(_attrs)}>`);
        _push(ssrRenderComponent(unref(Swiper), {
          modules,
          "slides-per-view": 1,
          "space-between": 10,
          breakpoints: {
            640: {
              slidesPerView: 2,
              spaceBetween: 10
            },
            768: {
              slidesPerView: 4,
              spaceBetween: 20
            },
            1024: {
              slidesPerView: unref(maxSlides),
              spaceBetween: 30
            }
          },
          navigation: ""
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<!--[-->`);
              ssrRenderList(__props.rooms, (room) => {
                _push2(ssrRenderComponent(unref(SwiperSlide), {
                  key: room.id
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(ssrRenderComponent(_sfc_main$1, { room }, null, _parent3, _scopeId2));
                    } else {
                      return [
                        createVNode(_sfc_main$1, { room }, null, 8, ["room"])
                      ];
                    }
                  }),
                  _: 2
                }, _parent2, _scopeId));
              });
              _push2(`<!--]-->`);
            } else {
              return [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.rooms, (room) => {
                  return openBlock(), createBlock(unref(SwiperSlide), {
                    key: room.id
                  }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$1, { room }, null, 8, ["room"])
                    ]),
                    _: 2
                  }, 1024);
                }), 128))
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
      } else {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "relative grid grid-cols-1 gap-8 md:grid-cols-5" }, _attrs))}><!--[-->`);
        ssrRenderList(__props.rooms, (room, index) => {
          _push(ssrRenderComponent(_sfc_main$2, {
            room,
            key: room.id,
            index
          }, null, _parent));
        });
        _push(`<!--]--></div>`);
      }
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Home/partials/Rooms.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
