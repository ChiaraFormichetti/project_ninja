import { commonSelector } from './commonSelector.js';
//funzione per aprire la modale

const modal = commonSelector.modal;


export function openmodal() {
    modal.style.display = "block";
  
};

//funzione per chiudere la modale
export function closemodal() {
    modal.style.display = "none";
    resetModal();

}

//funzione per resettare la modale
export function resetModal() {
    const modal = commonSelector.modal;
    let name = modal.querySelector("#newName");
    name.value = "";
    let seats = modal.querySelector("#newSeats");
    seats.value = "";
    let enter = modal.querySelector("#newEnter");
    enter.value = "";
    let exit = modal.querySelector("#newExit");
    exit.value = "";
    let addButton = modal.querySelector("#addButton");
    let editButton = modal.querySelector("#editButton");
    if (addButton) {
        addButton.remove();
    }
    if (editButton) {
        editButton.remove();
    }
}
