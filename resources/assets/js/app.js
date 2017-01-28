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

import routes from './routes';

var router = new VueRouter({
    history: true,
    root: 'app',
    routes
});

router.beforeEach(function (to, from, next) {
    if (to.meta.adminOnly && ! Suggestive.isAdmin) {
        console.error('Going to Admin Only');
        return next(false);
    }

    if (to.path == '/' && Suggestive.isAdmin) {
        return next('/admin-dashboard');
    }

    // // Catch vue-router bug
    if (to.path == '') {
        return next('/');
    }

    return next();
});

Vue.component('suggest-topic-button', require('./components/suggest-topic-button.vue'));
Vue.component('suggested-topics', require('./components/suggested-topics.vue'));
Vue.component('nav-dropdown', require('./components/nav-dropdown.vue'));
Vue.component('suggest-topic-inline', require('./components/suggest-topic-inline.vue'));

import Topics from './topics.js';
import Bus from './bus.js';

var App = new Vue({
    data: {
        topicsUnsorted: [],
        episodesUnsorted: [],
    },
    router,
    computed: {
        topics(){
            return _.sortBy(this.topicsUnsorted, 'id').reverse();
        },
        episodes(){
            return _.sortBy(this.episodesUnsorted, 'number').reverse();
        }
    },
    created() {

        Topics.all().then(topics => {
            this.topicsUnsorted = topics;
        });

        if (Suggestive.isAdmin) {
            this.$http.get('episodes')
                .then(response => {
                    this.episodesUnsorted = response.data;
                })
                .catch(function (response, status, request) {
                    console.log('error', response);
                });
        }

        this.listen();
    },
    methods: {
        listen(){
            Bus.$on('add-episode', episode => {
                this.episodesUnsorted.push(episode);
            });
            Bus.$on('delete-episode', episode => {
                this.episodesUnsorted = _.without(this.episodesUnsorted, episode);
            });
            Bus.$on('update-topic', topic => {
                this.topicsUnsorted[this.topicsUnsorted.findIndex(t => {
                    return t.id === topic.id;
                })] = topic;
            });
            Bus.$on('add-topic', topic => {
                this.topicsUnsorted.push(topic);
            });
        }
    }
}).$mount("#app");
