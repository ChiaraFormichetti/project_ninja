
//prima fetch per controllare che tra i beneficiari non ci sia un codice uguale
//(e quindi che il codice sia già stato usato)

import { requestManager } from "./requestManager";

//nella prima fetch dobbiamo :
/*
get:
(
    -> inviamo il codice e vediamo se ne esiste uno uguale nella table beneficiers
    -> se la risposta è success => prendiamo il codice e torniamo tutti i suoi valori relativi nella tabella coupon
    ->fetchiamo ora con il type che ci ha restituito la fetch la tabella dei regali, stampando tutti quelli che hanno type = type;
)
*/

/*
potremmo far aprire un modulo cliccando su un bottone con la diciutra
inserisci le tue credianziali
a quel punto si apre una modale con un form (nome, cognome, email, data).
quando la modale è stata compilata con dati validi il bottone diventa verde con una v
a quel punto cliccando sul regalo aggiungiamo il codice al mofalForm, dopodichè controlliamo se il regalo è ancora disponibile
(usiamo la stessa fetch di prima), se succes => facciamo partire la post di aggiunta beneficiario(e in automatica si deve cancellare la riga con codice x in code relativo)
se success=> è stata inviata una mail al beneficiario con i dettagli per la spedizione, cliccando su ok si torna alla schermata iniziale.

*/

const body = document.querySelector("body");
const form = body.querySelector("form");
const inputCode = form.querySelector("#code");
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const codeValue = inputCode.ariaValueMax.trim();
    let url = apiURL + `/${codeValue}`;
    try{
        const response = await requestManager.get(url);
        
    }
})