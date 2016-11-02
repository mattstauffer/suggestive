let topics = null;

export default {
    all() {
        if (topics !== null) {
            return new Promise(function (resolve) {
                resolve(topics);
            });
        }

        return new Promise(function (resolve, reject) {
            Vue.http.get('topics')
                .then(
                    response => {
                        topics = response.data;
                        resolve(topics);
                    },
                    response => {
                        console.log('error', response);
                        reject();
                    }
                );
        });
    },

    add(topic) {
        return new Promise( (resolve, reject) => {
            Vue.http.post('topics', {title: topic.title, description: topic.description})
                .then(
                    response => {
                        topics.push(response.data);
                        resolve(topics);
                    },
                    response => {
                        console.log('error', response);
                        reject(response);
                    }
                );
        });

    },

    flag(topic, status) {
        return new Promise( (resolve, reject) => {
            Vue.http.patch('topics/' + topic.id, {status: status})
                .then(
                    response => {
                        const matchedTopic = topics.find(candidate => candidate.id == topic.id);
                        matchedTopic.status = status;
                        resolve(matchedTopic);
                    },
                    response => {
                        console.log('error', response);
                        reject(response);
                    }
                );
        });
    },

    voteFor(topic) {
        return new Promise( (resolve, reject) => {

            Vue.http.post('topics/' + topic.id + '/votes', [])
                .then(
                    response => {
                        const matchedTopic = topics.find(candidate => candidate.id == topic.id);

                        if (response.status == 200) {
                            matchedTopic.votes++;
                        }

                        matchedTopic.userVotedFor = true;

                        resolve(matchedTopic);
                    },
                    response => {
                        console.log('error', response);
                        reject(response);
                    }
                );

        });
    }
}