import { resetCalendar, writeCalendar } from './calendar.js';

//funzione per cercare una prenotazione
export function search(allReservations, calendarContainer) {
    const searchName = document.getElementById("name");
    const searchEnter = document.getElementById("enter");
    if (searchName.value || searchEnter.value) {
        resetCalendar();
        let searchReservation = [];
        //oppure usi filter 
        allReservations.forEach((reservation) => {
            if (
                reservation.nome.toLowerCase() === searchName.value.toLowerCase() ||
                reservation.ingresso === searchEnter.value
            ) {
                console.log(reservation.ingresso, searchEnter.value);
                searchReservation.push(reservation);
            }
        });
        if (searchReservation.length >= 1) {
            writeCalendar(searchReservation, calendarContainer);
        } else {
            alert(`Non ci sono prenotazioni corrispondenti ai valori cercati`)
            writeCalendar(allReservations, calendarContainer);
        };

    };
};


//funzione per interrompere la ricerca
export function noSearch(allReservations, calendarContainer) {
    const searchName = document.getElementById("name");
    const searchEnter = document.getElementById("enter");
    searchName.value = "";
    searchEnter.value = "";
    resetCalendar();
    writeCalendar(allReservations, calendarContainer);
}


