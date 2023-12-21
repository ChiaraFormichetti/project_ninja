import { resetCalendar } from './calendar.js';
import { getHistoricReservations, getReservations, getSearchReservation, getTrashReservations } from './get.js';
import { commonSelector } from './commonSelector.js';
//funzione per cercare una prenotazione
const searchName = commonSelector.searchName;
const searchEnter = commonSelector.searchEnter;
const apiURL = commonSelector.apiURL;

export async function search(calendarContainer, trash, historic) {
    let url = apiURL + '/search';
    if (trash) {
        url += '/trash';
    } else if (historic) {
        url += '/historic';
    }
    if (searchName.value || searchEnter.value) {

        const params = new URLSearchParams();
        if (searchName.value) {
            params.append('name', searchName.value);
        }
        if (searchEnter.value) {
            params.append('enter', searchEnter.value);
        }
        url += `?${params.toString()}`;
        getSearchReservation(url, calendarContainer);

    } else {
        alert('Non sono stti inseriti dei campi validi');
        //gestisci il caso in cui non sono stati inseriti criteri di ricerca
    }
};



//funzione per interrompere la ricerca
export async function noSearch(calendarContainer, trash, historic) {
    if (searchName.value != "" || searchEnter.value != "") {
        searchName.value = "";
        searchEnter.value = "";
        resetCalendar(calendarContainer);
        if (trash) {
            getTrashReservations(calendarContainer);
        } else if (historic) {
            getHistoricReservations(calendarContainer);
        } else {
            getReservations(calendarContainer);
        }
    }
}


