

const body = document.querySelector("body");
const modal = body.querySelector("#myModal");
const searchForm = body.querySelector("#search");
const modalForm = modal.querySelector("#newReservationForm");
const baseURL = "http://www.chiara-test/";
const apiURL = baseURL + "api/reservation";

export const commonSelector =
 {
    body: body,
    modal : modal,
    searchForm : searchForm,
    modalForm : modalForm,
    apiURL : apiURL,
    buttonOpenModal: body.querySelector("#add"),
    closeModal : modal.querySelector("#closeModal"),
    searchButton : body.querySelector("#searchButton"),
    noSearchButton : body.querySelector("#noSearch"),    
    deleteButton : body.querySelector("#trash"),
    historicButton : body.querySelector("#historic"),
    searchName : searchForm.querySelector("#name"),
    searchEnter : searchForm.querySelector("#enter"),
}