import { resetCalendar } from "./calendar";
import { commonSelector } from "./commonSelector";
import { getTrashReservations,} from "./get";
import { requestManager } from "./requestManager";

const apiURL = commonSelector.apiURL;

//Funzione per cancellare definitivamente una reservation
export async function deleteforEverById(id, calendarContainer) {
    try {
       let url = apiURL + `/reservation/${id}`;
        const deleteResevation = await requestManager.delete(url)
        if (deleteResevation) {
            alert('La prenotazione è stata cancellata definitivamente');
            resetCalendar(calendarContainer);
            const currentPageViews = commonSelector.currentPageViews;
            let currentPage = +(currentPageViews.textContent);
           getTrashReservations(calendarContainer, currentPage);
        } else {
            alert('Non è stato possibile cancellare la prenotazione');
        }
    } catch (error) {
        console.error("Errore durante l'eliminazione: ", error);
    };

}