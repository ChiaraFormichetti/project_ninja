import { resetCalendar, writeCalendar, writeDeleteCalendar } from './calendar.js';
import { openmodal, closemodal } from './modal.js';
import { addNewReservation, deleteforEver } from './reservation.js';
import { search, noSearch } from './search.js';

let reservationXML;
let url = '../view/prenotazioni.json';
let request = new XMLHttpRequest();
request.open('GET', url);
request.responseType = 'json';
const body = document.querySelector("body");
const div = document.createElement("div");
div.id = `calendar`;
body.appendChild(div);

const modal = document.getElementById("myModal");
const openModal = document.getElementById("add");
const closeModal = document.getElementById("closeModal");
//const addReservation = document.getElementById("addReservation");

const searchButton = document.getElementById("searchButton");
searchButton.addEventListener("click", () => search(reservationXML, div));
const noSearchButton = document.getElementById("noSearch");
noSearchButton.addEventListener("click", () => noSearch(reservationXML, div));

closeModal.addEventListener("click", () => closemodal(modal));
const form = document.getElementById("newReservationForm");
//funzione per chiudere la modale cliccando fuori 
window.addEventListener("click", (event) => {
    if (event.target == modal) {
        closemodal(modal);
    }
});

openModal.addEventListener("click", () => {
    const addButton = document.createElement("button");
    addButton.textContent = "Aggiungi";
    addButton.id = "addButton";
    form.appendChild(addButton);
    addButton.addEventListener("click", () => addNewReservation(reservationXML, modal, div));
    openmodal(modal);
})

request.onload = function () {
    reservationXML = request.response;
    writeCalendar(reservationXML, div);
}
request.send();

//Qui facciamo la chiamata ajax al cestino
const deleteButton = document.getElementById("trash");
deleteButton.addEventListener("click", () => {
    let deleteReservations;
    let urlDelete = '../view/prenotazioniCancellate.json';
    let requestDelete = new XMLHttpRequest();
    requestDelete.open('GET', urlDelete);
    requestDelete.responseType = 'json';

    //qui stampiamo il cestino
    requestDelete.onload = function () {
        deleteReservations = requestDelete.response;
        resetCalendar();
        writeDeleteCalendar(deleteReservations, div);
    }
    requestDelete.send();
});
