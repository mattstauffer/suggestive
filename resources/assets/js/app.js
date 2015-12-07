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
var AddTopic = require('./components/add-topic.vue');

var App = Vue.extend({
    data: function() {
        return {
            topics: []
        };
    },
    created: function () {
        this.$http.get('topics', function (data) {
            this.topics = data;
        }).error(function (data, status, request) {
            console.log('error', data);
        });
    }
});

var router = new VueRouter();

router.map({
    '/': {
        component: Dashboard
    },
    '/add-topic': {
        component: AddTopic
    }
});

router.start(App, '#app')
