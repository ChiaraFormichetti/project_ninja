import { resetCalendar, writeCalendar, writeDeleteCalendar, writeHistoricCalendar } from './calendar.js';
import { openmodal, closemodal } from './modal.js';
import { addNewReservation, deleteforEver } from './reservation.js';
import { search, noSearch } from './search.js';
import { requestManager } from './requestManager.js';
import createHtml from './element.js';

const body = document.querySelector("body");
const modal = document.getElementById("myModal");
const openModal = document.getElementById("add");
const closeModal = document.getElementById("closeModal");
const searchButton = document.getElementById("searchButton");
const noSearchButton = document.getElementById("noSearch");
const modalForm = document.getElementById("newReservationForm");
const deleteButton = document.getElementById("trash");
const historicButton = document.getElementById("historic");

const createCalendarContainer = [
    {
        tagName : 'div',
        id: 'calendar',
        parentElement : body,
    }
];
createHtml(createCalendarContainer);
const calendarContainer = document.getElementById("calendar");

async function fetchData(){
    let url = 'http://chiara-test.com/api/reservation';
    try {      
        const allReservations = await requestManager.get(url);
        
        searchButton.addEventListener("click", () => search(allReservations, calendarContainer));
        noSearchButton.addEventListener("click", () => noSearch(allReservations, calendarContainer));

        closeModal.addEventListener("click", () => closemodal(modal));
        //funzione per chiudere la modale cliccando fuori 
        window.addEventListener("click", (event) => {
            if (event.target == modal) {
                closemodal(modal);
            }
        });
        
        openModal.addEventListener("click", () => {
         /*   const modalElement = [
            {
                tagName: 'form',
                id : 'newReservationForm',
                parentId : 'modalContent'
                children : [
                    {
                        tagName:'div',
                        children: [
                            {
                                tagName: 'label',
                                content : 'Nome:',
                                attributes : {
                                    for : "name",
                                }
                            },
                            {
                                tagName: 'input',
                                id: "newName",
                                attributes : {
                                    name : 'name',
                                    required_max_length : "30"
                                }
                            },
                        ]
                    }
                    {
                        tagName:'div',
                        children: [
                            {
                                tagName: 'label',
                                content : 'Posti:',
                                attributes : {
                                    for : "seats",
                                }
                            },
                            {
                                tagName: 'input',
                                id: "newSeats",
                                attributes : {
                                    name : 'seats',
                                    required_max : "5",                    
                                }
                            },
                        ]
                    }
                    {
                        tagName:'div',
                        children: [
                            {
                                tagName: 'label',
                                content : 'Data di ingresso:',
                                attributes : {
                                    for : "enter",
                                }
                            },
                            {
                                tagName: 'input',
                                id: "newEnter",
                                attributes : {
                                    name : 'enter',
                                    required : true,
                                }
                            },
                        ]
                    }
                    {
                        tagName:'div',
                        children: [ 
                            {
                                tagName: 'label',
                                content : 'Data di uscita:',
                                attributes : {
                                    for : "exit",
                                }
                            },
                            {
                                tagName: 'input',
                                id: "newExit",
                                attributes : {
                                    name : 'exit',
                                    required : true,
                                }
                            },
                        ]
                    }
                    {
                        tagName: 'button',
                        events: [
                            {
                                eventName: 'click',
                                callbackName: addNewReservation,
                                parameters: [
                                    allReservations,
                                    modal,
                                    calendarContainer,
                                ]
                            }
                        ],
                        content: 'Aggiungi',
                        attributes: {
                            className: 'add',
                        },
                    }
                ]
            }
        ];

               
        createHtml(modalElement);*/
            const addButton = document.createElement("button");
            addButton.textContent = "Aggiungi";
            addButton.id = "addButton";
            modalForm.appendChild(addButton);
            addButton.addEventListener("click", () => addNewReservation(allReservations, modal, calendarContainer));
            openmodal(modal);
        })

        writeCalendar(allReservations, calendarContainer);
    } catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
}
fetchData();

//Qui facciamo la chiamata ajax al cestino
deleteButton.addEventListener("click", () => {
    let deleteReservations;
    let urlDelete = 'View/prenotazioniCancellate.json';
    let requestDelete = new XMLHttpRequest();
    requestDelete.open('GET', urlDelete);
    requestDelete.responseType = 'json';

    //qui stampiamo il cestino
    requestDelete.onload = function () {
        deleteReservations = requestDelete.response;
        resetCalendar();
        writeDeleteCalendar(deleteReservations, calendarContainer);
    }
    requestDelete.send();
});

historicButton.addEventListener("click", () => {
  
    let historicReservations;
    let urlHistoric = 'View/prenotazioniStorico.json';
    let requestHistoric = new XMLHttpRequest();
    requestHistoric.open('GET', urlHistoric);
    requestHistoric.responseType = 'json';

    requestHistoric.onload = function () {
        historicReservations = requestHistoric.response;
        resetCalendar();
        writeHistoricCalendar(historicReservations,calendarContainer);
    }
    requestHistoric.send();
});
/*
children: [
    {
        tagName: 'button',
        events: [
            {
                eventName: 'click',
                callbackName: addNewReservation,
                parameters: [
                    allReservations,
                    modal,
                    calendarContainer,
                ]
            }
        ],
        content: 'Aggiungi',
        attributes: {
            className: 'add',
        }
    }
]*/

