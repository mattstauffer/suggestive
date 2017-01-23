require('es6-promise/auto');
window.Vue = require('vue');
window.VueRouter = require('vue-router');
window._ = require('lodash');
window.moment = require('moment');

window.axios = require('axios');

Vue.config.debug = true;

Vue.$http = Vue.prototype.$http = axios.create({
    baseURL: "/api",
    headers: {'X-CSRF-TOKEN': document.querySelector('#csrf-token').getAttribute('content')}
});

var router = new VueRouter({
    history: true,
    root: 'app'
});

router.beforeEach(function (transition) {
    if (transition.to.adminOnly) {
        if (! Suggestive.isAdmin) {
            transition.abort(['YOU SHALL NOT PASS']);
        }
    }

    if (transition.to.path == '/' && Suggestive.isAdmin) {
        transition.redirect('/admin-dashboard');
    }

    // Catch vue-router bug
    if (transition.to.path == '') {
        transition.redirect('/');
    }

    return true;
});

Vue.component('suggest-topic-button', require('./components/suggest-topic-button.vue'));
Vue.component('suggested-topics', require('./components/suggested-topics.vue'));
Vue.component('nav-dropdown', require('./components/nav-dropdown.vue'));
Vue.component('suggest-topic-inline', require('./components/suggest-topic-inline.vue'));

import Topics from './topics.js';

var App = Vue.extend({
    data() {
        return {
            topics: [],
            episodes: [],
        }
    },
    created() {

        Topics.all().then(topics => {
            this.topics = topics;
        });

        if (Suggestive.isAdmin) {
            this.$http.get('episodes')
                .then(response => {
                    this.episodes = response.data;
                })
                .catch(function (response, status, request) {
                    console.log('error', response);
                });
        }
    },
});

router.map({
    '/': {
        component: require('./components/user-dashboard.vue')
    },
    '/topics/:topic_id': {
        component: require('./components/topic.vue')
    },
    '/admin-dashboard': {
        component: require('./components/admin-dashboard.vue')
    },
    '/suggest-topic': {
        component: require('./components/suggest-topic.vue')
    },
    '/suggested-topics': {
        component: require('./components/suggested-topics.vue'),
        adminOnly: true
    },
    '/accepted-topics': {
        component: require('./components/accepted-topics.vue'),
        adminOnly: true
    },
    '/episodes': {
        component: require('./components/episodes.vue'),
        adminOnly: true
    },
    '/episodes/create': {
        component: require('./components/create-episode.vue'),
        adminOnly: true
    },
    '/episodes/:episode_number': {
        component: require('./components/episode.vue'),
        adminOnly: true
    },
});

router.start(App, '#app');
