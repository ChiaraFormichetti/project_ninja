export const requestManager = {
    get: async function (url) {
        return fetch(url)
            .then(response => response.json())
            .then(result => result.data);
    },
/*
    fetchDataFromURL: async function (url, containerResetFunction, calendarWriteFunction, calendarContainer){
        try {
            const data = await this.get(url);
            containerResetFunction();
            calendarWriteFunction(data, calendarContainer);
            return data;
        } catch (error){
            console.error('Errore durante la fetch: ', error);
        }

    }*/
}