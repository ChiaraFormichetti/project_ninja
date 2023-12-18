import { openmodal } from './modal.js'
import { getReservationById } from './get.js';
import { postNewReservation, postEditReservation } from './post.js';
import createHtml from './element.js';
import { commonSelector } from './commonSelector.js';

export function errorManager(name, seats, enter, exit) {
    let shortName = name.trim().split(" ");
    let validName = /^[a-zA-Z]+$/.test(name);
    let validSeats = /^[1-9]+$/.test(seats);
    let userEnterDate = new Date(enter);
    let userExitDate = new Date(exit);
    let currentDate = new Date();

    if (name === "" || seats === "" || enter === "" || exit === "") {
        alert(`Devi inserire tutti i dati !`);
    } else if (shortName.length > 2 || !validName) {
        alert("Il nome inserito non è valido!");
    } else if (seats > 5 || !validSeats) {
        alert("Il numero di posti inserito non è valido!");
    } else if (userEnterDate < currentDate) {
        alert("La data di ingresso inserita non è valida!");
    } else if (userExitDate <= userEnterDate) {
        alert("la data di uscita inserita non è valida!")
    } else {
        const formData = new FormData();
        formData.append('nome', name);
        formData.append('posti', seats);
        formData.append('ingresso', enter);
        formData.append('uscita', exit);
        return formData;
    }
}

//funzione per aggiungere una nuova prenotazione
export async function addNewReservation(calendarContainer) {
    const modalForm = commonSelector.modalForm;
    let name = modalForm.querySelector("#newName").value;
    let seats = modalForm.querySelector("#newSeats").value;
    let enter = modalForm.querySelector("#newEnter").value;
    let exit = modalForm.querySelector("#newExit").value;
    //uso il metodo trim() per imuovere gli spazi bianchi all'inizio e alla fine della stringa
    //uso il metodo split per dividere la stringa in un array di sottostringhe usando lo spazio vuoto come separatore
    //in questo modo possiamo usare poi il metodo lenght degli array per contare quanti elemtni ci sono e imporgli un massimo
    const formData = errorManager(name, seats, enter, exit);
    postNewReservation(formData, calendarContainer);
}


//funzione per modificare una reservation
export async function edit(id, calendarContainer) {
    const modalForm = commonSelector.modalForm;
    const reservationToEdit = await getReservationById(id);
    if (reservationToEdit) {
        let editName = modalForm.querySelector("#newName");
        editName.value = reservationToEdit[0].nome;
        let editSeats = modalForm.querySelector("#newSeats");
        editSeats.value = reservationToEdit[0].posti;
        let editEnter = modalForm.querySelector("#newEnter");
        editEnter.value = reservationToEdit[0].ingresso;
        let editExit = modalForm.querySelector("#newExit")
        editExit.value = reservationToEdit[0].uscita;
    }
    else {
        console.error("Error");
    }

    const editButton = [
        {
            tagName: 'button',
            id: 'editButton',
            parentElement: modalForm,
            events: [
                {
                    eventName: 'click',
                    callbackName: postEditReservation,
                    parameters: [
                        id,
                        calendarContainer
                    ],
                },
            ],
            content: 'Modifica',
            attributes: {
                class: 'add',
            }
        }
    ]
    createHtml(editButton);
    openmodal();
}




