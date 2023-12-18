import { openmodal, closemodal } from './modal.js';
import { search, noSearch } from './search.js';
import createHtml from './element.js';
import { getReservations, getTrashReservations, getHistoricReservations } from './get.js';
import { addNewReservation } from './reservation.js';
import { commonSelector } from './commonSelector.js';

//creiamo il div calendar e lo selezioniamo
const body = commonSelector.body
const createCalendarContainer = [
    {
        tagName: 'div',
        id: 'calendar',
        parentElement: body,
    }
];
createHtml(createCalendarContainer);

let trash = false;
let historic = false;

const calendarContainer = body.querySelector("#calendar");
const searchButton = commonSelector.searchButton;
const noSearchButton = commonSelector.noSearchButton;
//XXXXXXXXXX
//definiamo quali funzioni partiranno quando cliccheremo sui nostri bottoni di cerca e di annulla ricerca (se facciamo in tempo li spostiamo nell'index)
searchButton.addEventListener("click", async () => search(calendarContainer, trash, historic));
noSearchButton.addEventListener("click", async () => noSearch(calendarContainer, trash, historic));
//XXXXXXXXXX
//(generalizzare ancors, quando clicchiamo su buttonOpenModal prima di usare la funzione opendModal creiamo tutta la modale)
//quando clicchiamo nel bottone di aggiungi una nuova prenotazine aggiungiamo il bottone di aggiungi alla modale e poi la apriamo.
const buttonOpenModal = body.querySelector("#add");
const modalForm = commonSelector.modalForm;
buttonOpenModal.addEventListener("click", () => {
    const addButton = [
        {
            tagName: 'button',
            id: 'addButton',
            parentElement: modalForm,
            events: [
                {
                    eventName: 'click',
                    callbackName: addNewReservation,
                    parameters: [
                        calendarContainer,
                    ],
                },
            ],
            content: 'Aggiungi',
            attributes: {
                class: 'add',
            }
        }
    ]
    createHtml(addButton);
    openmodal();
});
//quando cliccheremo sull'elemento closeModal partirà la funzione che ci chiuderà la modale
const closeModal = commonSelector.closeModal;
closeModal.addEventListener("click", () => closemodal());
const modal = commonSelector.modal;
//se l'utente fa click all'esterno della finestra modale questa si chiuderà.
window.addEventListener("click", (event) => {
    if (event.target == modal) {
        closemodal();
    }
});


//fetch per prendere tutte le prenotazioni
getReservations(calendarContainer);
//Tutti gli event listeners associati ai bottoni per stampare tutte le prenotazioni valide, il cestino o lo storico
const deleteButton = commonSelector.deleteButton;
deleteButton.addEventListener("click", async () => {
    trash = true;
    historic = false;
    getTrashReservations(calendarContainer)
});

const historicButton = commonSelector.historicButton;
historicButton.addEventListener("click", async () => {
    trash = false;
    historic = true;
    getHistoricReservations(calendarContainer)
});

const homepageButton = commonSelector.homepageButton;
homepageButton.addEventListener("click", async () => {
    trash = false;
    historic = false;
    getReservations(calendarContainer)
});

const succButton = commonSelector.succButton;
const currentPageViews = commonSelector.currentPageViews;
const preButton = commonSelector.preButton;

//Qui ci sono i bottoni del paginatore, prima rimettiamo a posto le fetch del get e poi aggiungiamo il paginatore

succButton.addEventListener("click", () => {
    let currentPages = +(currentPageViews.textContent) + 1;
    if (trash) {
        getTrashReservations(calendarContainer, currentPages)
    } else if (historic) {
        getHistoricReservations(calendarContainer, currentPages)
    } else {
        getReservations(calendarContainer, currentPages);
    }
});

preButton.addEventListener("click", () => {
    let currentPages = +(currentPageViews.textContent) - 1;
    if (trash) {
        getTrashReservations(calendarContainer, currentPages)
    } else if (historic) {
        getHistoricReservations(calendarContainer, currentPages)
    } else {
        getReservations(calendarContainer, currentPages);
    }
});

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