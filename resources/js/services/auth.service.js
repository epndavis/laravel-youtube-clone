export {
    login,
    logout
}

function login(params) {
    return axios.post('login', params)
}

function logout() {
    return axios.post('logout')
}
