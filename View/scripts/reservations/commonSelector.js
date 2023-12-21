

const body = document.querySelector("body");
const modal = body.querySelector("#myModal");
const searchForm = body.querySelector("#search");
const modalForm = modal.querySelector("#newReservationForm");
const baseURL = "http://www.chiara-test/";
const apiURL = baseURL + "api/reservation";
const divPage = body.querySelector('.page');

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
    homepageButton : body.querySelector("#return"),
    searchName : searchForm.querySelector("#name"),
    searchEnter : searchForm.querySelector("#enter"),
    preButton : divPage.querySelector("#pre"),
    succButton : divPage.querySelector("#succ"),
    currentPageViews : divPage.querySelector("#currentPage"),
    itemsViews : divPage.querySelector("#items"),
    selectPage : divPage.querySelector("#forPage"),



}   