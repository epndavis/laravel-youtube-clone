export {
    getList,
    get
}

function getList(params) {
    let config = {};

    if (typeof params !== 'undefined') {
        config.params = {
            x: params.ignore,
            q: params.query
        }
    }

    return axios.get('/api/videos', config)
}  

function get(id) {
    return axios.get(`/api/videos/${id}`)
}
