let defaultConfig = {
    env: 'development',
    http: {
        defaultRequest: {
            headers: {
                'X-Requested-With':     'XMLHttpRequest',
                'Accept':               'application/json'
            }
        }
    },
    debug: true
};

module.exports = Object.assign(defaultConfig, window.Laravel.config);