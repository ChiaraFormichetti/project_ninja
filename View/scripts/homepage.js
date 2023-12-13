import { openmodal, closemodal } from './modal.js';
import { search, noSearch } from './search.js';
import createHtml from './element.js';
import { getReservations, getTrashReservations, getHistoricReservations, getPages } from './get.js';
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
const calendarContainer = body.querySelector("#calendar");
const searchButton = commonSelector.searchButton;
const noSearchButton = commonSelector.noSearchButton; 
//XXXXXXXXXX
//definiamo quali funzioni partiranno quando cliccheremo sui nostri bottoni di cerca e di annulla ricerca (se facciamo in tempo li spostiamo nell'index)
searchButton.addEventListener("click", async () => search(calendarContainer));
noSearchButton.addEventListener("click", async () => noSearch(calendarContainer));
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

getPages(calendarContainer);

//Qui facciamo la chiamata ajax al cestino
const deleteButton = commonSelector.deleteButton;
deleteButton.addEventListener("click", async () => getTrashReservations(calendarContainer));
const historicButton = commonSelector.historicButton;
historicButton.addEventListener("click", async () => getHistoricReservations(calendarContainer));
const homepageButton = commonSelector.homepageButton;
homepageButton.addEventListener("click", async () => getReservations(calendarContainer));

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