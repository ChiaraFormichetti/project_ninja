import { requestManager } from "../requestManager.js";
import { writeCalendar, writeDeleteCalendar, writeHistoricCalendar, resetCalendar } from "./calendar.js";
import { commonSelector } from './commonSelector.js';
import { pageManager } from "./calendar.js";

const apiURL = commonSelector.apiURL;

//riusare questa 
export async function getReservations(calendarContainer, currentPages = 1, reservationForPages = 10) {
    let url = apiURL;
    if (currentPages) {
        url += `/${currentPages}`;
    }
    if (reservationForPages) {
        url += `/${reservationForPages}`;
    }

    try {
        const response = await requestManager.get(url);
        const totalPages = response.totalPages;
        const reservations = response.reservations;
        const count = response.count;
        resetCalendar(calendarContainer);
        writeCalendar(reservations, calendarContainer);
        pageManager(totalPages, currentPages, count, reservationForPages);
    } catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
};

export async function getTrashReservations(calendarContainer, currentPages = 1, reservationForPages = 10) {

    let url = apiURL + '/trashReservations';
    if (currentPages) {
        url += `/${currentPages}`;
    }
    if (reservationForPages) {
        url += `/${reservationForPages}`;
    }

    try {

        const response = await requestManager.get(url);
        const totalPages = response.totalPages;
        const trashReservations = response.reservations;
        const count = response.count;
        resetCalendar(calendarContainer);
        writeDeleteCalendar(trashReservations, calendarContainer);
        pageManager(totalPages, currentPages, count, reservationForPages);
    }
    catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
};

export async function getHistoricReservations(calendarContainer, currentPages = 1, reservationForPages = 10) {
    let url = apiURL + '/historicReservations';
    if (currentPages) {
        url += `/${currentPages}`;
    }
    if (reservationForPages) {
        url += `/${reservationForPages}`;
    }

    try {
        const response = await requestManager.get(url);
        const totalPages = response.totalPages;
        const historicReservations = response.reservations;
        const count = response.count;
        resetCalendar(calendarContainer);
        writeHistoricCalendar(historicReservations, calendarContainer);
        pageManager(totalPages, currentPages, count, reservationForPages);
    }
    catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
};

export async function getSearchReservation(url, calendarContainer) {
    try {
        const searchReservations = await requestManager.get(url);
        if (searchReservations.length >= 1) {
            resetCalendar(calendarContainer);
            writeCalendar(searchReservations, calendarContainer);
        } else {
            alert(`Non ci sono prenotazioni corrispondenti ai valori cercati`)
        };
    } catch (error) {
        console.error('Errore durante la ricerca: ', error);
    }
};

export async function getReservationById(id) {
    try {
        let url = apiURL + '/reservationById' + `/${id}`;
        const reservation = await requestManager.get(url);
        return reservation;
    } catch (error) {
        console.error("Errore durante la ricerca della prenotazione tramite l'id");
    }
}
