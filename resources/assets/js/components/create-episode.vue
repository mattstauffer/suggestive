<style>
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <h2>Create an Episode</h2>

            <form @submit.prevent="createEpisode">
                <label>Title</label><br>
                <input type="text" v-model="title" class="form-control" length="255" autofocus v-el:episode-title-input required><br>

                <label>Number</label><br>
                <input type="number" v-model="number" class="form-control" length="5" required><br>

                <input type="submit" class="btn btn-primary" value="Add">
                <a v-link="{ path: '/' }" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                title: '',
                number: ''
            };
        },
        props: {
            episodes: {
                sync: true
            }
        },
        methods: {
            createEpisode: function () {
                var self = this;

                this.$http.post('episodes', { title: this.title, number: this.number }, function (data) {
                    self.title = '';
                    self.number = '';

                    self.episodes.push(data);

                    self.$route.router.go('/episodes');
                });
            }
        },
        ready: function () {
            this.$els.episodeTitleInput.focus();
        }
    };
</script>
