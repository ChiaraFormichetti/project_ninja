import { resetCalendar } from "./calendar";
import { commonSelector } from "./commonSelector";
import { getTrashReservations, getPages } from "./get";
import { requestManager } from "./requestManager";

//Funzione per cancellare definitivamente una reservation
export async function deleteforEverById(id, calendarContainer) {
    try {
        const baseURL = 'http://www.chiara-test/api/reservation';
        const url = new URL(baseURL);
        url.searchParams.append('id', id);
        const deleteResevation = await requestManager.delete(url.toString());
        if (deleteResevation) {
            alert('La prenotazione è stata cancellata definitivamente');
            resetCalendar(calendarContainer);
            const currentPageViews = commonSelector.currentPageViews;
            let currentPage = +(currentPageViews.textContent);
            let trash = true;
            getPages(calendarContainer,currentPage,trash)
        } else {
            alert('Non è stato possibile cancellare la prenotazione');
        }
    } catch (error) {
        console.error("Errore durante l'eliminazione: ", error);
    };

}