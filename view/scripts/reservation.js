import { resetCalendar, updateCalendar, writeDeleteCalendar } from './calendar.js';
import { openmodal, closemodal } from './modal.js'


//funzione per aggiungere una nuova prenotazione
export function addNewReservation(reservationXML, modal, div) {
    let name = document.getElementById("newName").value;
    let seats = document.getElementById("newSeats").value;
    let enter = document.getElementById("newEnter").value;
    let exit = document.getElementById("newExit").value;
    let shortName = name.trim().split(/\s+/);
    let validName = /^[a-zA-Z]+$/.test(name);
    let validSeats = /^[1-9]+$/.test(seats);
    let userEnterDate = new Date (enter);
    let userExitDate = new Date (exit);
    let currentDate = new Date();

    if (name === "" || seats === "" || enter === "" || exit === "") {
        alert(`Devi inserire tutti i dati !`);
    } else if (shortName.length > 2 || !validName){
        alert ("Il nome inserito non è valido!");
    } else if (seats > 30 || !validSeats){
        alert ("Il numero di posti inserito non è valido!");
    } else if (userEnterDate < currentDate){
        alert ("La data di ingresso inserita non è valida!");
    } else if (userExitDate <= userEnterDate){
        alert ("la data di uscita inserita non è valida!")
    } else {
        let newReservation = {
            id: generateNewId(reservationXML),
            nome: name,
            posti: seats,
            ingresso: enter,
            uscita: exit,
        };
        reservationXML.push(newReservation);
        updateCalendar(reservationXML, div);
        closemodal(modal);
        if (close) {
            alert(`Prenotazione aggiunta con successo!`);
        }
    }
}


// funzione per generare il nuovo Id da assegnare ad una nuova reservation
export function generateNewId(reservationXML) {
    let lastReservation = reservationXML[reservationXML.length - 1];
    let newId = lastReservation ? lastReservation.id + 1 : 1;
    return newId;
};

//funzione per modificare una reservation
export function edit(reservationXML, id, modal, div) {
    let editName;
    let editSeats;
    let editEnter;
    let editExit;
    let index = "";
    let reservationY = {};

    reservationXML.forEach((reservation, i) => {
        if (id == reservation.id) {
            index = i;
            reservationY = reservation;
            editName = document.getElementById("newName");
            editName.value = reservation.nome;
            editSeats = document.getElementById("newSeats");
            editSeats.value = reservation.posti;
            editEnter = document.getElementById("newEnter");
            editEnter.value = reservation.ingresso;
            editExit = document.getElementById("newExit");
            editExit.value = reservation.uscita;
        }
    });
    const form = document.getElementById("newReservationForm");
    const editButton = document.createElement("button");
    editButton.textContent = "Modifica";
    editButton.id = "editButton";
    form.appendChild(editButton);
    openmodal(modal);
    editButton.addEventListener("click", () => {

        let name = document.getElementById("newName").value;
        let seats = document.getElementById("newSeats").value;
        let enter = document.getElementById("newEnter").value;
        let exit = document.getElementById("newExit").value;
        let reservationX = {
            id: id,
            nome: name,
            posti: seats,
            ingresso: enter,
            uscita: exit,
        };
        if (name != "") {
            reservationXML[index].nome = reservationX.nome;
        }
        if (seats != "") {
            reservationXML[index].posti = reservationX.posti;
        }
        if (enter != "") {
            reservationXML[index].ingresso = reservationX.ingresso;
        }
        if (exit != "") {
            reservationXML[index].uscita = reservationX.uscita;
        }
        updateCalendar(reservationXML, div);

        closemodal(modal);
        if (close && reservationXML[index] != reservationY) {
            alert(`reservation modificata con successo`);
        }

    });
}

//Funzione per cancellare logicamente una reservation
export function moveToTrash(reservationXML, id, div) {
    reservationXML[id - 1].cancellato = 1;
    // useremo il metodo patch qua
    updateCalendar(reservationXML, div);
    console.log(reservationXML);
}

//Funzione per cancellare definitivamente una reservation
export function deleteforEver(deleteReservations, id, div) {
    deleteReservations.forEach((reservation, i) => {
        if (reservation.id == id) {
            deleteReservations.splice(i, 1);
        }
    })
    resetCalendar();
    writeDeleteCalendar(deleteReservations, div);
}

export function restoreReservation(deleteReservations, id, div) {
    deleteReservations.forEach((reservation, i) => {
        if (reservation.id == id) {
            reservation.cancellato = 0
        };
    })
    //deleteReservations[id-1].cancellato = 0;
    resetCalendar();
    writeDeleteCalendar(deleteReservations, div);
    console.log(deleteReservations);
}