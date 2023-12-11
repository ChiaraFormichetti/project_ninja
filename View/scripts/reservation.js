import { resetCalendar, updateCalendar, writeDeleteCalendar } from './calendar.js';
import { openmodal, closemodal } from './modal.js'
import { fetchData, fetchTrashData } from './homepage.js';
import { requestManager } from './requestManager.js';

//funzione per aggiungere una nuova prenotazione
export async function addNewReservation(modal) {

    let name = document.getElementById("newName").value;
    let seats = document.getElementById("newSeats").value;
    let enter = document.getElementById("newEnter").value;
    let exit = document.getElementById("newExit").value;
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

        try {
            const url = 'http://www.chiara-test/api/reservation';
            const formData = new FormData();
            formData.append('nome', name);
            formData.append('posti', seats);
            formData.append('ingresso', enter);
            formData.append('uscita', exit);
            const addReservation = await requestManager.post(url, formData);
            if (addReservation) {
                resetCalendar();
                fetchData();
                closemodal(modal);
                alert('La prenotazione è stata aggiunta');
            } else {
                alert('Non è stato possibile aggiungere la prenotazione');
            }
        } catch (error) {
            console.error("Errore durantel'aggiunta: ", error);
        };
    }
}
/*
// funzione per generare il nuovo Id da assegnare ad una nuova reservation
export function generateNewId(allReservations) {
    let lastReservation = allReservations[allReservations.length - 1];
    let newId = lastReservation ? lastReservation.id + 1 : 1;
    return newId;
};
*/
//funzione per modificare una reservation
export async function edit( id, modal) {
    //qui sarà tutto diverso perchè modificheremo completamente la modale
    //cerca direttamente da modal
    try {
        let url = 'http://www.chiara-test/api/reservation';
        const allReservations = await requestManager.get(url);
        const foundReservation = allReservations.find(reservation => reservation.id===id)

        if(foundReservation){
            let editName = document.getElementById("newName");
            editName.value = foundReservation.nome;
            let editSeats = document.getElementById("newSeats");
            editSeats.value = foundReservation.posti;
            let editEnter = document.getElementById("newEnter");
            editEnter.value = foundReservation.ingresso;
            let editExit = document.getElementById("newExit");
            editExit.value = foundReservation.uscita;
        }
    }
        catch (error) {
            console.error('Errore durante la fetch: ', error);
        }
        const form = document.getElementById("newReservationForm");
        const editButton = document.createElement("button");
        editButton.textContent = "Modifica";
        editButton.id = "editButton";
        form.appendChild(editButton);
        editButton.addEventListener("click", async () => {
            //mettere modal.
        let name = document.getElementById("newName").value;
        let seats = document.getElementById("newSeats").value;
        let enter = document.getElementById("newEnter").value;
        let exit = document.getElementById("newExit").value;
        try {
            const url = 'http://www.chiara-test/api/reservation';
            const formData = new FormData();
            formData.append('id',id)
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
            const editReservation = await requestManager.post(url, formData);
            if (editReservation) {
                resetCalendar();
                fetchData();
                closemodal(modal);
                alert('La prenotazione è stata modificata');
            } else {
                alert('Non è stato possibile modificare la prenotazione');
            }
        } catch (error) {
            console.error("Errore durantela modifica: ", error);
        };
    });
    openmodal(modal);
}

//Funzione per cancellare logicamente una reservation
export async function moveToTrash(id) {
    try {
        const url = 'http://www.chiara-test/api/reservation';
        const formData = new FormData();
        formData.append('id', id);
        const trashReservation = await requestManager.post(url, formData);
        if (trashReservation) {
            alert('La prenotazione è stata spostata nel cestino');
            resetCalendar();
            fetchData();
        } else {
            alert('Non è stato possibile spostare la prenotazione nel cestino');
        }
    } catch (error) {
        console.error("Errore durante lo spostamento nel cestino: ", error);
    };
}


//Funzione per cancellare definitivamente una reservation
export async function deleteforEver(id) {
    try {
        const baseURL = 'http://www.chiara-test/api/reservation';
        const url = new URL(baseURL);
        url.searchParams.append('id', id);
        const deleteResevation = await requestManager.delete(url.toString());
        if (deleteResevation) {
            alert('La prenotazione è stata cancellata definitivamente');
            resetCalendar();
            fetchTrashData();
        } else {
            alert('Non è stato possibile cancellare la prenotazione');
        }
    } catch (error) {
        console.error("Errore durante l'eliminazione: ", error);
    };

}

export async function restoreReservation(id) {
    try {
        const url = 'http://www.chiara-test/api/reservation';
        const formData = new FormData();
        formData.append('id', id);
        formData.append('cancellazione', 0);
        const restoreReservation = await requestManager.post(url, formData);
        if (restoreReservation) {
            alert('La prenotazione è stata ripristinata');
            resetCalendar();
            fetchTrashData();
        } else {
            alert('Non è stato possibile ripistinare la prenotazione');
        }
    } catch (error) {
        console.error("Errore durante il ripristino: ", error);
    };

}
