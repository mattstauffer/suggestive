export default [
	{
		path: '/',
        component: require('./components/user-dashboard.vue')
    },
    {
    	path: '/topics/:topic_id',
        component: require('./components/topic.vue')
    },
    {
    	path: '/admin-dashboard',
        component: require('./components/admin-dashboard.vue')
    },
    {
    	path: '/suggest-topic',
        component: require('./components/suggest-topic.vue')
    },
    {
    	path: '/suggested-topics',
        component: require('./components/suggested-topics.vue'),
        meta: {
            adminOnly: true
        }
    },
    {
    	path: '/accepted-topics',
        component: require('./components/accepted-topics.vue'),
        meta: {
            adminOnly: true
        }
    },
   	{
    	path: '/episodes',
        component: require('./components/episodes.vue'),
        meta: {
            adminOnly: true
        }
    },
    {
    	path: '/episodes/create',
        component: require('./components/create-episode.vue'),
        meta: {
            adminOnly: true
        }
    },
    {
    	path: '/episodes/:episode_number',
        component: require('./components/episode.vue'),
        meta: {
            adminOnly: true
        }
    },
]