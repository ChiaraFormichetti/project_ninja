import { resetCalendar } from "./calendar";
import { getReservations, getTrashReservations } from "./get.js";
import { closemodal } from "./modal.js";
import { requestManager } from "./requestManager1.js";
import { commonSelector } from './commonSelector.js';
import { errorManager } from "./reservation.js";

const apiURL = commonSelector.apiURL;

export async function postNewReservation(result, calendarContainer) {
    if(result.errors.length>0){
        const errorMessage = result.errors.join('\n');
        alert(errorMessage);
        return;
    }
    try {
        let url = apiURL + '/add';
        const addReservation = await requestManager.post(url, result.formData);
        if (addReservation) {
            resetCalendar(calendarContainer);
            getReservations(calendarContainer);
            closemodal();
            alert('La prenotazione è stata aggiunta');
        } else {
            alert('Non è stato possibile aggiungere la prenotazione');
        }
    } catch (error) {
        console.error("Errore durante l'aggiunta: ", error);
    };
}

export async function postEditReservation(id, calendarContainer) {
    const modalForm = commonSelector.modalForm;
    let name = modalForm.querySelector("#newName").value;
    let seats = modalForm.querySelector("#newSeats").value;
    let enter = modalForm.querySelector("#newEnter").value;
    let exit = modalForm.querySelector("#newExit").value;
    const result = errorManager(name, seats, enter, exit);
    if(result.errors.length>0){
        const errorMessage = result.errors.join('\n');
        alert(errorMessage);
        return;
    }
    let url = apiURL + '/edit' + `/${id}`;
    try {
        const editReservation = await requestManager.post(url, result.formData);
        if (editReservation) {
            resetCalendar(calendarContainer);
            getReservations(calendarContainer);
            closemodal();
            alert('La prenotazione è stata modificata');
        } else {
            alert('Non è stato possibile modificare la prenotazione');
        }
    } catch (error) {
        console.error("Errore durantela modifica: ", error);
    };
}

//funzione per spostare una funzione nel cestino
export async function postMoveToTrash(id, calendarContainer) {
    try {
        let url = apiURL + '/trash' + `/${id}`;
        const trashReservation = await requestManager.post(url);
        if (trashReservation) {
            resetCalendar(calendarContainer);
            getReservations(calendarContainer);
            alert('La prenotazione è stata spostata nel cestino');
        } else {
            alert('Non è stato possibile spostare la prenotazione nel cestino');
        }
    } catch (error) {
        console.error("Errore durante lo spostamento nel cestino: ", error);
    };
}



export async function postRestoreReservation(id, calendarContainer) {
    try {
        let url = apiURL + '/restore' + `/${id}`;
        const restoreReservation = await requestManager.post(url);
        if (restoreReservation) {
            resetCalendar(calendarContainer);
            getTrashReservations(calendarContainer);
            alert('La prenotazione è stata ripristinata');
        } else {
            alert('Non è stato possibile ripistinare la prenotazione');
        }
    } catch (error) {
        console.error("Errore durante il ripristino: ", error);
    };

}

