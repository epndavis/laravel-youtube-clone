export {
    getList,
    get
}

function getList() {
    return axios.get('/api/videos')
}  

function get(id) {
    return axios.get(`/api/videos/${id}`)
}
