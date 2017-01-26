import Bus from './bus';

export default {
    topics: null,

    all() {
        if (this.topics !== null) {
            return new Promise((resolve, reject) => {
                resolve(this.topics);
            });
        }

        return new Promise((resolve, reject) => {
            Vue.$http.get('topics')
                .then(response => {
                    this.topics = response.data;
                    resolve(this.topics);
                })
                .catch(response => {
                    console.log('error', response);
                    reject(response);
                });
        });
    },

    find(topicId) {
        return new Promise((resolve, reject) => {
            this.all()
                .then(
                    topics => {
                        const matchedTopic = this.topics.find(candidate => candidate.id == topicId);
                        resolve(matchedTopic);
                    },
                    response => {
                        console.log('error', response);
                        reject(response);
                    }
                );
        });
    },

    add(topic) {
        return new Promise((resolve, reject) => {
            Vue.$http.post('topics', {title: topic.title, description: topic.description})
                .then(response => {
                    this.topics.push(response.data);
                    Bus.$emit('add-topic', response.data);
                    resolve(response);
                })
                .catch(response => {
                    console.log('error', response);
                    reject(response);
                });
        });

    },

    flag(topic, status) {
        return new Promise((resolve, reject) => {
            Vue.$http.patch('topics/' + topic.id, {status: status})
                .then(response => {
                    const matchedTopic = this.topics.find(candidate => candidate.id == topic.id);
                    matchedTopic.status = status;
                    Bus.$emit('update-topic', matchedTopic);
                    resolve(matchedTopic);
                })
                .catch(response => {
                    console.log('error', response);
                    reject(response);
                });
        });
    },

    voteFor(topic) {
        return new Promise((resolve, reject) => {

            Vue.$http.post('topics/' + topic.id + '/votes', [])
                .then(response => {
                    let matchedTopic = this.topics.find(candidate => candidate.id == topic.id);

                    if (response.status == 200) {
                        matchedTopic.votes++;
                    }

                    matchedTopic.userVotedFor = true;
                    Bus.$emit('update-topic', matchedTopic);

                    resolve(matchedTopic);
                })
                .catch(err => {
                    console.log('error', err);
                    reject(err);
                });

        });
    }
}
