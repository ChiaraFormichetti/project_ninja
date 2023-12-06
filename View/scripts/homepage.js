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
        tagName: 'div',
        id: 'calendar',
        parentElement: body,
    }
];
createHtml(createCalendarContainer);
const calendarContainer = document.getElementById("calendar");

searchButton.addEventListener("click", async () => search(calendarContainer));
noSearchButton.addEventListener("click",async () => noSearch(calendarContainer));

export async function fetchData() {
    let url = 'http://www.chiara-test.com/api/reservation';
    try {
        const allReservations = await requestManager.get(url);


        closeModal.addEventListener("click", () => closemodal(modal));

        window.addEventListener("click", (event) => {
            if (event.target == modal) {
                closemodal(modal);
            }
        });

        openModal.addEventListener("click", () => {
            const addButton = document.createElement("button");
            addButton.textContent = "Aggiungi";
            addButton.id = "addButton";
            modalForm.appendChild(addButton);
            addButton.addEventListener("click", () => addNewReservation( modal));
            openmodal(modal);
        })

        writeCalendar(allReservations, calendarContainer);
    } catch (error) {
        console.error('Errore durante la fetch: ', error);
    }
}
fetchData();

//Qui facciamo la chiamata ajax al cestino
deleteButton.addEventListener("click", async () => fetchTrashData());
 export async function fetchTrashData() {

        let url = 'http://www.chiara-test.com/api/reservation/trashReservations';

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

        let url = 'http://www.chiara-test.com/api/reservation/historicReservations';

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