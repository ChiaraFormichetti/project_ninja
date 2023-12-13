import { requestManager } from "./requestManager";
import { writeCalendar, writeDeleteCalendar, writeHistoricCalendar, resetCalendar } from "./calendar";
import { commonSelector } from './commonSelector.js';
import { pageManager } from "./calendar";

const apiURL = commonSelector.apiURL;

export async function getPages (calendarContainer, currentPages=null, reservationForPages=null ){
   try{
       const parameters = new URLSearchParams();
        if (currentPages){
            parameters.append('page',currentPages);
        }
        if(reservationForPages){
            parameters.append('number', reservationForPages);
        }
        let url = apiURL + `?${parameters.toString()}`;
        const response = await requestManager.get(url);

        const totalPages = response.totalPages;
        const currentPage = response.currentPage;
        const reservations = response.reservations;
        resetCalendar(calendarContainer);
        writeCalendar(reservations, calendarContainer)
        pageManager(totalPages, currentPage);
    } 
    catch (error){
    console.error("Errore durante la paginazione");
    }
}

export async function  getReservations(calendarContainer) {
    let url = apiURL;
    try {
        const allReservations = await requestManager.get(url);
        resetCalendar(calendarContainer);
        writeCalendar(allReservations, calendarContainer);
    } catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
};

export async function getTrashReservations(calendarContainer) {

    let url = apiURL + '/trashReservations';

    try {
        const trashReservations = await requestManager.get(url);
        resetCalendar(calendarContainer);
        writeDeleteCalendar(trashReservations, calendarContainer);
    }
    catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
};

export async function getHistoricReservations(calendarContainer) {

    let url = apiURL + '/historicReservations';

    try {
        const historicReservations = await requestManager.get(url);
        resetCalendar(calendarContainer);
        writeHistoricCalendar(historicReservations, calendarContainer);
    }
    catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
};

export async function getSearchReservation (url, calendarContainer) {
    try {
        const searchReservation = await requestManager.get(url);
        if (searchReservation.length >= 1) {
            resetCalendar(calendarContainer);
            writeCalendar(searchReservation, calendarContainer);
        } else {
            alert(`Non ci sono prenotazioni corrispondenti ai valori cercati`)
        };
      } catch (error){
        console.error('Errore durante la ricerca: ',error);
      }
};

export async function getReservationById (id){
    try{
        const parameters = new URLSearchParams();
        parameters.append('id',id);
        let url = apiURL + `?${parameters.toString()}`;
        const reservation = await requestManager.get(url);
        return reservation;
    } catch (error){
        console.error("Errore durante la ricerca della prenotazione tramite l'id");
    }
}
