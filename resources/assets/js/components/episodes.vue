<template>
    <div>
        <a v-link="{ path: '/episodes/create' }" class="btn btn-primary add-button pull-right">
            Add episode
            <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
        </a>
        <h2>Episodes</h2>

        <p v-show="episodes.length == 0">No episodes.</p>
        <div v-for="episode in episodes | orderBy 'number' -1" class="row" style="margin-bottom: 2rem;">
            <div class="col-xs-3 col-sm-2 episode__number--in-list" style="text-align: right">
                {{ episode.number }}
            </div>
            <div class="col-xs-9 col-sm-10">
                <div class="panel panel-default episode episode--in-list">
                    <div class="panel-heading"><h3 class="episode__title">{{ episode.title }}</h3></div>
                </div>
            </div>
            <div class="col-xs-9 col-sm-10 col-xs-push-3 col-sm-push-2">
                @todo: List all topics here
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                episodes: []
            };
        },
        created: function () {
            this.$http.get('episodes', function (data, status, request) {
                this.episodes = data;
            }).error(function (data, status, request) {
                console.log('error', data);
            });
        }
    };
</script>
