
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

const moment = require('moment')
require('moment/locale/fr')
Vue.use(require('vue-moment'), {
    moment
})

window.moment = require('moment');


Vue.directive('click-outside', {
  bind: function (el, binding, vnode) {
    el.clickOutsideEvent = function (event) {
      // here I check that click was outside the el and his childrens
      if (!(el == event.target || el.contains(event.target))) {
        // and if it did, call method provided in attribute value
        vnode.context[binding.expression](event);
      }
    };
    document.body.addEventListener('click', el.clickOutsideEvent)
  },
  unbind: function (el) {
    document.body.removeEventListener('click', el.clickOutsideEvent)
  },
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

Vue.component('add-track', require('./components/AddTrack.vue').default);

Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('play', require('./components/Play.vue').default);

Vue.component('search-games', require('./components/SearchGames.vue').default);


Vue.component('games', require('./components/Games.vue').default);
Vue.component('game', require('./components/Game.vue').default);

Vue.component('custom-game', require('./components/Games/Custom/index.vue').default);
Vue.component('test-game', require('./components/Games/Custom/indexTest.vue').default);

Vue.component('chat', require('./components/Chat.vue').default);
Vue.component('podium', require('./components/Podium.vue').default);
Vue.component('answers', require('./components/Answers.vue').default);
Vue.component('ranking', require('./components/Ranking.vue').default);

Vue.component('stats-game-type', require('./components/User/StatGameType.vue').default);

Vue.component('game-edit', require('./components/GameEdit.vue').default);


// ADMIN
Vue.component('admin-tracks', require('./components/Admin/Tracks.vue').default);

// ANALYTICS
Vue.component('total-visitors-and-page-views', require('./components/Analytics/TotalVisitorsAndPageViews.vue').default);
Vue.component('user-types', require('./components/Analytics/UserTypes.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});