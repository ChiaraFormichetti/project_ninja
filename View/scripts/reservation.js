import { resetCalendar, updateCalendar, writeDeleteCalendar } from './calendar.js';
import { openmodal, closemodal } from './modal.js'

//funzione per aggiungere una nuova prenotazione
export function addNewReservation(allReservations, modal, calendarContainer) {
    let name = document.getElementById("newName").value;
    let seats = document.getElementById("newSeats").value;
    let enter = document.getElementById("newEnter").value;
    let exit = document.getElementById("newExit").value;
    let shortName = name.trim().split(" ");
    let validName = /^[a-zA-Z]+$/.test(name);
    let validSeats = /^[1-9]+$/.test(seats);
    let userEnterDate = new Date (enter);
    let userExitDate = new Date (exit);
    let currentDate = new Date();

    if (name === "" || seats === "" || enter === "" || exit === "") {
        alert(`Devi inserire tutti i dati !`);
    } else if (shortName.length > 2 || !validName){
        alert ("Il nome inserito non è valido!");
    } else if (seats > 5 || !validSeats){
        alert ("Il numero di posti inserito non è valido!");
    } else if (userEnterDate < currentDate){
        alert ("La data di ingresso inserita non è valida!");
    } else if (userExitDate <= userEnterDate){
        alert ("la data di uscita inserita non è valida!")
    } else {
        let newReservation = {
            id: generateNewId(allReservations),
            nome: name,
            posti: seats,
            ingresso: enter,
            uscita: exit,
        };
        allReservations.push(newReservation);
        updateCalendar(allReservations, calendarContainer);
        closemodal(modal);
        if (close) {
            alert(`Prenotazione aggiunta con successo!`);
        }
    }
}


// funzione per generare il nuovo Id da assegnare ad una nuova reservation
export function generateNewId(allReservations) {
    let lastReservation = allReservations[allReservations.length - 1];
    let newId = lastReservation ? lastReservation.id + 1 : 1;
    return newId;
};

//funzione per modificare una reservation
export function edit(allReservations, reservation, modal, calendarContainer) {
    let editName;
    let editSeats;
    let editEnter;
    let editExit;
    let index = "";
    let reservationToEdit = {};
            index = allReservations.indexOf(reservation) + 1;
            reservationToEdit = JSON.parse(JSON.stringify(reservation));
    //qui sarà tutto diverso perchè modificheremo completamente la modale
            //cerca direttamente da modal
            editName = document.getElementById("newName");
            editName.value = reservation.nome;
            editSeats = document.getElementById("newSeats");
            editSeats.value = reservation.posti;
            editEnter = document.getElementById("newEnter");
            editEnter.value = reservation.ingresso;
            editExit = document.getElementById("newExit");
            editExit.value = reservation.uscita;
        
    const form = document.getElementById("newReservationForm");
    const editButton = document.createElement("button");
    editButton.textContent = "Modifica";
    editButton.id = "editButton";
    form.appendChild(editButton);
    editButton.addEventListener("click", () => {
        //mettere modal.
        let name = document.getElementById("newName").value;
        let seats = document.getElementById("newSeats").value;
        let enter = document.getElementById("newEnter").value;
        let exit = document.getElementById("newExit").value;
        //rinominala
        let reservationEdited = {
            id: id,
            nome: name,
            posti: seats,
            ingresso: enter,
            uscita: exit,
        };
        if (name != "") {
            allReservations[index].nome = reservationEdited.nome;
        }
        if (seats != "") {
            allReservations[index].posti = reservationEdited.posti;
        }
        if (enter != "") {
            allReservations[index].ingresso = reservationEdited.ingresso;
        }
        if (exit != "") {
            allReservations[index].uscita = reservationEdited.uscita;
        }
        updateCalendar(allReservations, calendarContainer);
        
        closemodal(modal);
        
        if ( JSON.stringify(allReservations[index]) != JSON.stringify(reservationToEdit)) {
            
            alert(`reservation modificata con successo`);
        }
        
    });
    openmodal(modal);
}

//Funzione per cancellare logicamente una reservation
export function moveToTrash(allReservations, id, calendarContainer) {
    allReservations[id - 1].cancellato = 1;
    // useremo il metodo patch qua
    updateCalendar(allReservations, calendarContainer);
    console.log(allReservations);
}

//Funzione per cancellare definitivamente una reservation
export function deleteforEver(deleteReservations, id, calendarContainer) {
    deleteReservations.forEach((reservation, i) => {
        if (reservation.id == id) {
            deleteReservations.splice(i, 1);
        }
    })
    resetCalendar();
    writeDeleteCalendar(deleteReservations, calendarContainer);
}

export function restoreReservation(deleteReservations, id, calendarContainer) {
    deleteReservations.forEach((reservation, i) => {
        if (reservation.id == id) {
            reservation.cancellato = 0
        };
    })
    //deleteReservations[id-1].cancellato = 0;
    resetCalendar();
    writeDeleteCalendar(deleteReservations, calendarContainer);
    console.log(deleteReservations);
}