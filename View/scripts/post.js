import { resetCalendar } from "./calendar";
import { getReservations, getTrashReservations ,getPages} from "./get";
import { closemodal } from "./modal";
import { requestManager } from "./requestManager";
import { commonSelector } from './commonSelector.js';

export async function postNewReservation( url, formData, calendarContainer) {
    try {
        debugger
        const addReservation = await requestManager.post(url, formData);
        if (addReservation) {
            resetCalendar(calendarContainer);
            getPages(calendarContainer);
            closemodal();
            alert('La prenotazione è stata aggiunta');
        } else {
            alert('Non è stato possibile aggiungere la prenotazione');
        }
    } catch (error) {
        console.error("Errore durantel'aggiunta: ", error);
    };
}

export async function postEditReservation(id, calendarContainer) {
    const modalForm = commonSelector.modalForm;
    let name = modalForm.querySelector("#newName").value;
    let seats = modalForm.querySelector("#newSeats").value;
    let enter = modalForm.querySelector("#newEnter").value;
    let exit = modalForm.querySelector("#newExit").value;
    const formData = new FormData();
    formData.append('id', id)
    if (name != "") {
        formData.append('nome', name);
    }
    if (seats != "") {
        formData.append('posti', seats);
    }
    if (enter != "") {
        formData.append('ingresso', enter);
    }
    if (exit != "") {
        formData.append('uscita', exit);
    }
    const url = 'http://www.chiara-test/api/reservation';
    try {
        const editReservation = await requestManager.post(url, formData);
        if (editReservation) {
            resetCalendar(calendarContainer);
            const currentPageViews = commonSelector.currentPageViews;
            let currentPage = +(currentPageViews.textContent);
            //getPages(calendarContainer,currentPage);
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
        const url = 'http://www.chiara-test/api/reservation';
        const formData = new FormData();
        formData.append('id', id);
        const trashReservation = await requestManager.post(url, formData);
        if (trashReservation) {
            alert('La prenotazione è stata spostata nel cestino');
            resetCalendar(calendarContainer);
            const currentPageViews = commonSelector.currentPageViews;
            let currentPage = +(currentPageViews.textContent);
            //getPages(calendarContainer,currentPage);
            getReservations(calendarContainer);
        } else {
            alert('Non è stato possibile spostare la prenotazione nel cestino');
        }
    } catch (error) {
        console.error("Errore durante lo spostamento nel cestino: ", error);
    };
}



export async function postRestoreReservation(id, calendarContainer) {
    try {
        const url = 'http://www.chiara-test/api/reservation';
        const formData = new FormData();
        formData.append('id', id);
        formData.append('cancellazione', 0);
        const restoreReservation = await requestManager.post(url, formData);
        if (restoreReservation) {
            alert('La prenotazione è stata ripristinata');
            resetCalendar(calendarContainer);
            let trash = true;
            const currentPageViews = commonSelector.currentPageViews;
            let currentPage = +(currentPageViews.textContent);
            //getPages(calendarContainer,currentPage,trash)
            getReservations(calendarContainer);
        } else {
            alert('Non è stato possibile ripistinare la prenotazione');
        }
    } catch (error) {
        console.error("Errore durante il ripristino: ", error);
    };

}

