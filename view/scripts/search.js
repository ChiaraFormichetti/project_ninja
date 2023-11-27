import { resetCalendar, writeCalendar } from './calendar.js';

//funzione per cercare una prenotazione
export function search(reservationXML, div) {
    const searchName = document.getElementById("name");
    const searchEnter = document.getElementById("enter");
    if (searchName.value || searchEnter.value) {
        resetCalendar();
        let searchReservation = [];
        //oppure usi filter 
        reservationXML.forEach((reservation) => {
            if (
                reservation.nome.toLowerCase() === searchName.value.toLowerCase() ||
                reservation.ingresso === searchEnter.value
            ) {
                searchReservation.push(reservation);
            }
        });
        if (searchReservation.length >= 1) {
            writeCalendar(searchReservation, div);
        } else {
            alert(`Non ci sono prenotazioni corrispondenti ai valori cercati`)
            writeCalendar(reservationXML, div);
        };

    };
};


//funzione per interrompere la ricerca
export function noSearch(reservationXML, div) {
    const searchName = document.getElementById("name");
    const searchEnter = document.getElementById("enter");
    searchName.value = "";
    searchEnter.value = "";
    resetCalendar();
    writeCalendar(reservationXML, div);
}


