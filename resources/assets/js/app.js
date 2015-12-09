window.Vue = require('vue');
window.VueRouter = require('vue-router');

Vue.use(require('vue-resource'));

Vue.http.options.root = '/api';
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf-token').getAttribute('content');

if (Suggestive.isAdmin) {
    var Dashboard = require('./components/admin-dashboard.vue');
} else {
    var Dashboard = require('./components/user-dashboard.vue');
}
var SuggestTopic = require('./components/suggest-topic.vue');

var App = Vue.extend({
    data: function() {
        return {
            topics: []
        };
    },
    created: function () {
        this.$http.get('topics', function (data, status, request) {
            this.topics = data;
        }).error(function (data, status, request) {
            console.log('error', data);
        });
    }
});

var router = new VueRouter({
    history: true,
    root: 'dashboard'
});

router.map({
    '/': {
        component: Dashboard
    },
    '/suggest-topic': {
        component: SuggestTopic
    }
});

router.start(App, '#app')
