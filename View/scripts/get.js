import { requestManager } from "./requestManager";
import { writeCalendar, writeDeleteCalendar, writeHistoricCalendar, resetCalendar } from "./calendar";
import { commonSelector } from './commonSelector.js';
import { pageManager } from "./calendar";

const apiURL = commonSelector.apiURL;

export async function getPages (calendarContainer, currentPages=1, trash=false, historic=false, name=false, enter=false,reservationForPages=null ){
   try{
       const parameters = new URLSearchParams();
        if (currentPages){
            parameters.append('page',currentPages);
        }
        if(reservationForPages){
            parameters.append('number', reservationForPages);
        }
        if(trash){
            parameters.append('cancellazione', 1);
        }  else if(historic){
            parameters.append('cancellazione', 0);
            parameters.append('time', '<');            
        } else {
            parameters.append('cancellazione', 0);
            parameters.append('time', '>=');
        }
        if(name){
            parameters.append('name',name);
        }
        if(enter){
            parameters.append('enter',enter);
        }

        let url = apiURL + `?${parameters.toString()}`;
        const response = await requestManager.get(url);
        const totalPages = response.totalPages;
        const reservations = response.reservations;
        resetCalendar(calendarContainer);
        if(trash){
            writeDeleteCalendar(reservations,calendarContainer);
        } else if (historic){
            writeHistoricCalendar(reservations,calendarContainer);
        } else {
            writeCalendar(reservations,calendarContainer);
        }
        pageManager(totalPages, currentPages);
    } 
    catch (error){
    console.error("Errore durante la paginazione");
    }
}
//riusare questa 
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
        let url = apiURL + '/reservationById'+ `/${id}`;
        const reservation = await requestManager.get(url);
        return reservation;
    } catch (error){
        console.error("Errore durante la ricerca della prenotazione tramite l'id");
    }
}
