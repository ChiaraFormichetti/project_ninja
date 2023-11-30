export const requestManager = {
    get: async function (url) {
        return fetch(url)
            .then(response => response.json())
            .then(result => result.data);
    }
}