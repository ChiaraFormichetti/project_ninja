
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


//sono indecisa su come gestire il front-end, quindi oggi ci focalizzeremo nel back-end, nel sistemare l'index e nel creare la classe router
//comunque penso che userò sempre il request manager, il commonselector di riferimento
//la struttura a moduli,
//e dei moduli molto simili per quanto riguarda il get, post, e delete
//potrei fare comunque tutto su una pagina gestita dinamicamente
//creare ogni volta che eccedo alla pagina con url base il form e l'h2
//dopodichè cancellarli e "creare" la secondo pagina
//o separare il lavoro in due pagine (soluzione più easy e compatta)
//la pagina coupon la voglio vuota, pulita e ordinata.
//h1 rimane, accorciamo il padding nel bottom e mettiamo il background color direttamente nel div
//quello che cambia è quello sotto l'header, inizialmente avremo un form, una volta che il form è stato valididato, si mette al posto del form un
//un h3, con scritto codice verificato !
//Sotto avremo i vari regali associati e un rettangolo con scritto, inserisci i tuoi dati,
//quando i dati saranno verificati  il rettangolo si colorerà di verde
//a quel punto con invia si controlla che il codice sia ancora valido e poi se si si invieranno i dati.
const body = document.querySelector("body");
const form = body.querySelector("form");
const inputCode = form.querySelector("#code");
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const codeValue = inputCode.ariaValueMax.trim();
    //url per vedere se c'è il codice all'interno della tabella beneficiari
    let url = apiURL + `/${codeValue}`;
    try{
        const response = await requestManager.get(url);
        if (response){
            alert("Il coupon inserito non è più valido");
        } else {
            let url = apiURL +'/gifts';
            const gifts = await requestManager.get(url);
            if(gifts.items.length>0){
                
            }

        }
        
    } catch(error){

    }
})