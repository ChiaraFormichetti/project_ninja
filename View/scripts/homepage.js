import { resetCalendar, writeCalendar, writeDeleteCalendar, writeHistoricCalendar } from './calendar.js';
import { openmodal, closemodal } from './modal.js';
import { addNewReservation, deleteforEver } from './reservation.js';
import { search, noSearch } from './search.js';
import { requestManager } from './requestManager.js';
import createHtml from './element.js';

const body = document.querySelector("body");
const modal = body.querySelector("#myModal");
const buttonOpenModal = body.querySelector("#add");
const closeModal = modal.querySelector("#closeModal");
const searchButton = body.querySelector("#searchButton");
const noSearchButton = body.querySelector("#noSearch");
const modalForm = modal.querySelector("#newReservationForm");
const deleteButton = body.querySelector("#trash");
const historicButton = body.querySelector("#historic");

const createCalendarContainer = [
    {
        tagName: 'div',
        id: 'calendar',
        parentElement: body,
    }
];
createHtml(createCalendarContainer);
const calendarContainer = body.querySelector("#calendar");

searchButton.addEventListener("click", async () => search(calendarContainer));
noSearchButton.addEventListener("click",async () => noSearch(calendarContainer));

//generalizzare ancors, quando clicchiamo su buttonOpenModal prima di usare la funzione opendModal creiamo tutta la modale
buttonOpenModal.addEventListener("click", () => {

    const addButton = [
        {
            tagName: 'button',
            id: 'addButton',           
            parentElement:modalForm,
            events: [
                {
                    eventName: 'click',
                    callbackName: addNewReservation,
                    parameters: [
                        modal,
                    ]
                }
            ],
            content: 'Aggiungi',
            attributes: {
                class: 'add',
            }
        }
    ] 
    createHtml(addButton);
    openmodal(modal);
});

closeModal.addEventListener("click", () => closemodal(modal));

window.addEventListener("click", (event) => {
    if (event.target == modal) {
        closemodal(modal);
    }
});
export async function fetchData() {
    let url = 'http://www.chiara-test/api/reservation';
    try {
        const allReservations = await requestManager.get(url);
        writeCalendar(allReservations, calendarContainer);
    } catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
}
fetchData();

//Qui facciamo la chiamata ajax al cestino
deleteButton.addEventListener("click", async () => fetchTrashData());
 export async function fetchTrashData() {

        let url = 'http://www.chiara-test/api/reservation/trashReservations';

        try {
            const trashReservations = await requestManager.get(url);
            resetCalendar();
            writeDeleteCalendar(trashReservations, calendarContainer);
        }
        catch (error) {
            console.error('Errore durante la fetch: ', error);
        }
    };
    


historicButton.addEventListener("click", async () =>  fetchHistoricData());
   export async function fetchHistoricData() {

        let url = 'http://www.chiara-test/api/reservation/historicReservations';

        try {
            const historicReservations = await requestManager.get(url);
            resetCalendar();
            writeHistoricCalendar(historicReservations, calendarContainer);
        }
        catch (error) {
            console.error('Errore durante la fetch: ', error);
        }
   };

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