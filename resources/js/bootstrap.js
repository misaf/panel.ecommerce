const request = (path, options = {}) => fetch(`/api/${path.replace(/^\//, '')}`, {
    ...options,
    headers: {
        Accept: 'application/ld+json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...options.headers,
    },
});

window.api = {
    request,
    get: (path, options = {}) => request(path, { ...options, method: 'GET' }),
    post: (path, body, options = {}) => request(path, {
        ...options,
        method: 'POST',
        body: JSON.stringify(body),
    }),
};
