import { openmodal } from './modal.js'
import { getReservationById } from './get.js';
import { postNewReservation, postEditReservation } from './post.js';
import createHtml from './element.js';
import { commonSelector } from './commonSelector.js';

export function errorManager(name, seats, enter, exit) {
    //prima togliamo gli spazi vuoti davanti e dietro e poi lo trasformiamo in array così da poter contare da quante parole è composto
    let shortName = name.trim().split(" ");
    let validName = /^[a-zA-Z]+( [a-zA-Z]+){0,2}$/.test(name);
    let validSeats = /^[1-9]+$/.test(seats);
    let userEnterDate = new Date(enter);
    let userExitDate = new Date(exit);
    let currentDate = new Date();
    let errors = [];
    
    const formData = new FormData();
    formData.append('nome', name);
    formData.append('posti', seats);
    formData.append('ingresso', enter);
    formData.append('uscita', exit);
    debugger;
    if (name === "" || seats === "" || enter === "" || exit === "") {
        errors.push = ("Devi inserire tutti i dati !")
    } else if (shortName.length > 2 || !validName) {
        errors.push("Il nome inserito non è valido!");
    } else if (seats > 9 || !validSeats) {
        errors.push("Il numero di posti inserito non è valido!");
    } else if (userEnterDate < currentDate) {
        errors.push("La data di ingresso inserita non è valida!");
    } else if (userExitDate <= userEnterDate) {
        errors.push("la data di uscita inserita non è valida!")
    } 
    return {
        formData: errors.length === 0 ? formData : null,
        errors: errors
    }
}

//funzione per aggiungere una nuova prenotazione
export async function addNewReservation(calendarContainer) {
    const modalForm = commonSelector.modalForm;
    let name = modalForm.querySelector("#newName").value;
    let seats = modalForm.querySelector("#newSeats").value;
    let enter = modalForm.querySelector("#newEnter").value;
    let exit = modalForm.querySelector("#newExit").value;
    const result = errorManager(name, seats, enter, exit);
    postNewReservation(result, calendarContainer);
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




