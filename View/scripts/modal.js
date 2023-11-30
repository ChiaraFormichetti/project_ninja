
//funzione per aprire la modale
export function openmodal(modal) {
    modal.style.display = "block";
    //const addReservation = document.getElementById("addReservation");
    //const editReservationButton = document.getElementById("editReservation");
    //if (addingMode) {
    //    addReservation.classList.remove('hidden');
    //    editReservationButton.classList.add('hidden');
    //} else {
    //    addReservation.classList.add('hidden');
    //    editReservationButton.classList.remove('hidden');
    //}

};

//funzione per chiudere la modale
export function closemodal(modal) {
    modal.style.display = "none";
    resetModal();

}

//funzione per resettare la modale
export function resetModal() {
    let name = document.getElementById("newName");
    name.value = "";
    let seats = document.getElementById("newSeats");
    seats.value = "";
    let enter = document.getElementById("newEnter");
    enter.value = "";
    let exit = document.getElementById("newExit");
    exit.value = "";
    let addButton = document.getElementById("addButton");
    let editButton = document.getElementById("editButton");
    if (addButton) {
        addButton.remove();
    }
    if (editButton) {
        editButton.remove();
    }
}
