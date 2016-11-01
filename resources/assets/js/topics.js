let topics = null;

export default {
    all() {
        if (topics !== null) {
            return new Promise(function (resolve) {
                resolve(topics);
            });
        }

        return new Promise(function (resolve, reject) {
            Vue.http.get('topics').then(
                response => {
                    topics = response.data;
                    resolve(topics);
                },
                response => {
                    reject();
                    console.log('error', response);
                }
            );
        });
    },

    add(topic) {
        return new Promise( (resolve, reject) => {
            Vue.http.post('topics', {title: topic.title, description: topic.description})
                .then(
                    (response) => {
                        topics.push(response.data);
                        resolve(response);
                    },
                    (response) => {
                        reject(response);
                        console.log('error', response);
                    }
                );
        });

    },

    flag(topic, status) {
        Vue.http.patch('topics/' + topic.id, {status: status})
            .then(
                () => {
                    const matchedTopic = topics.find(candidate => candidate.id == topic.id);
                    matchedTopic.status = status;
                },
                response => {
                    console.log('error', response);
                }
            );
    },

    voteFor(topic) {
        Vue.http.post('topics/' + topic.id + '/votes', [])
            .then(
                (response) => {
                    // New vote
                    if (response.status == 200) {
                        const matchedTopic = topics.find(candidate => candidate.id == topic.id);
                        matchedTopic.votes++;
                    }

                    topic.userVotedFor = true;
                },
                (response) => {
                    console.log('error', response);
                }
            );
    }
}