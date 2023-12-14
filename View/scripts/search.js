import { resetCalendar } from './calendar.js';
import { getPages, getReservations, getSearchReservation } from './get.js';
import { commonSelector } from './commonSelector.js';
//funzione per cercare una prenotazione
const searchName = commonSelector.searchName;
const searchEnter = commonSelector.searchEnter;

export async function search(calendarContainer,trash=false ,historic=false ) {
    debugger
    if (searchName.value || searchEnter.value) {
        let name=false;
        let enter=false;
        if (searchName.value) {
            name = searchName.value;
        }
        if (searchEnter.value) {
            enter = searchEnter.value;
        }
        getPages(calendarContainer, 1, trash, historic ,name ,enter);
    } else {
        alert('Non sono stati inseriti dei campi validi');
        //gestisci il caso in cui non sono stati inseriti criteri di ricerca
    }
};


//funzione per interrompere la ricerca
export async function noSearch(calendarContainer, trash = false, historic = false) {
    if (searchName.value != "" || searchEnter.value != "") {
        searchName.value = "";
        searchEnter.value = "";
        resetCalendar(calendarContainer);
        getPages(calendarContainer, 1, trash, historic);
    }
}


