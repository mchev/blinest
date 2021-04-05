
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

Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('search-games', require('./components/SearchGames.vue').default);
Vue.component('add-track', require('./components/AddTrack.vue').default);


Vue.component('games', require('./components/Games.vue').default);
Vue.component('online-custom-games', require('./components/OnlineCustomGames.vue').default);
Vue.component('settings', require('./components/Games/Settings.vue').default);

Vue.component('custom-game', require('./components/Games/Custom/index.vue').default);
Vue.component('public-game', require('./components/Games/Public/index.vue').default);

Vue.component('game-player', require('./components/Games/Player.vue').default);
Vue.component('game-answers', require('./components/Games/Answers.vue').default);
Vue.component('game-scores', require('./components/Games/Scores.vue').default);
Vue.component('game-podiums', require('./components/Games/Podiums.vue').default);

Vue.component('finnish', require('./components/Games/Finnish.vue').default);

Vue.component('donate', require('./components/Donate.vue').default);



// MODERATORS
Vue.component('create-ticket', require('./components/Moderators/CreateTicket.vue').default);
Vue.component('list-ticket', require('./components/Moderators/TicketList.vue').default);
Vue.component('moderator-chat', require('./components/Moderators/Chat.vue').default);
Vue.component('moderator-users', require('./components/Moderators/Users.vue').default);


Vue.component('stats-game-type', require('./components/User/StatGameType.vue').default);
Vue.component('game-edit', require('./components/GameEdit.vue').default);


// ADMIN
Vue.component('admin-tracks', require('./components/Admin/Tracks.vue').default);
Vue.component('admin-moderators', require('./components/Admin/Moderators.vue').default);

// ADSENSE
Vue.component('adsense', require('./components/AdSense.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});