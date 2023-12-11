export const requestManager = {
    get: async function (url) {
        return fetch(url)
        //la fetch restituisce una promise che risolve con un oggetto response(la risposta alla richiesta http)
        //quando la promise si risolve usiamo il then per gestire la risposta 
            .then(response => {
                if(!response.ok){
                    throw new Error('Errore durante la richiesta dei dati');
                }
                //per estrarre i dati dalla risposta usiamo i metodi di response (.json o .text) a seconda del formato
             return response.json();
            })
            //se la chiamata json viene completata con successo la promise restituita risolve con i dati ottenuti dalla risposta (result)
            //In caso di errori viene eseguito il blocco catch
            .then(result => result.data)
            .catch(error => {
                //cattura gli errori che si verificano nel blocco catch, se la richiesta http fallisce iene lanciata un'eccezione
                //all'interno del blocco then che verrà catturata dal blocco catch
                console.error('Errore durante la richiesta: ', error);
                throw new Error('Errore durante la richiesta');
            });
    },

    delete: async function (url){
        return fetch(url,{
            method: "DELETE",
        }).then(response => {
            if(!response.ok) {
                throw new Error('Errore durante la richiesta di eliminazione');
            }
            return true; //L'eliminazione è avvenuta con successo
        }).catch(error => {
            console.error('Errore durante la richiesta di eliminazione:', error);
            return false;//Errore duante l'eliminazione
        });
    },

    post: async function (url, formData = new FormData()){
        return fetch(url,{
            method: "POST",
            body: formData,
        }).then(response => {
            if(!response.ok){
                throw new Error('Errore durante la richiesta di modifica/aggiunta');
            }
            return response.json();
        }).catch(error => {
            console.error('Errore durante la richiesta di modifica/aggiunta: ',error);
            return false;
        });
    },
};
