import { edit, moveToTrash, deleteforEver, restoreReservation } from './reservation.js';
import createHtml from './element.js';

//funzione per cancellare la lista delle prenotazioni
export function resetCalendar() {
    const body = document.querySelector('body');
    let calendarElement = body.querySelector("#calendar");

    while (calendarElement.firstChild) {
        calendarElement.removeChild(calendarElement.firstChild);
    }
}

//funzione per aggiornare la lista prenotazioni
export function updateCalendar(allReservations, calendarContainer) {

    resetCalendar();
    writeCalendar(allReservations, calendarContainer);
}

//funzione per raggruppare le prenotazioni prima di stamparle
export function groupReservations(allReservations) {
    if (allReservations && allReservations.length) {
        let groupedReservations = {};
        allReservations.forEach((element) => {
            const enter = `${element.ingresso}`;
            if (!groupedReservations[enter]) {
                groupedReservations[enter] = [];
            }
            groupedReservations[enter].push({
                nome: element.nome,
                id: element.id,
                posti: element.posti,
                uscita: element.uscita
            });
        });
        return groupedReservations;
    }
}

export function writeCalendar(allReservations, calendarContainer) {

    if (allReservations != "") {

        const modal = document.getElementById("myModal");
        let bookYear = "";
        const createDivReservation = [
            {
                tagName: 'div',
                id: 'divReservation',
                parentElement: calendarContainer
            },
        ];
        createHtml(createDivReservation);
        const divReservation = calendarContainer.querySelector('#divReservation');

        let groupedReservations = groupReservations(allReservations);

        Object.keys(groupedReservations).sort().forEach((fullDate, i) => {
            //usiamo l'oggetto date per convertire la stringa della data fullDate nell'oggetto Date e poter utlizzare i metodi
            //dell'oggetto
            const dateEnter = new Date(fullDate);
            const year = dateEnter.getFullYear();
            //aggiungo 1 perchè il metodo getMonth restituisce un numero da 0 a 11
            const month = dateEnter.getMonth() + 1;
            //inizialmente convertiamo il valore numerico del mese in una stringa, dopodichè con 
            //il metodo pad start aggiungiamo caratteri all'inizio della stringa finchè non raggiungiamo la lunghezza desiserata
            //nel nostro caso 2 caratteri e aggiungelo zero.
            const formattedMonth = String(month).padStart(2,'0');
            const day = dateEnter.getDate();
            const formattedDay = String(day).padStart(2,'0');

            //let year = fullDate.substr(0, 4);
            //let date = fullDate.substr(-5);//modificare le date
            if (year != bookYear) {
                const createh3Year = [
                    {
                        tagName: 'h3',
                        id: 'year',
                        parentElement: divReservation,
                        content: year,
                        attributes: {
                                class: "year-title",
                            }
                        
                    }
                ]
                createHtml(createh3Year);
            }
            bookYear = year;

            const createDate = [
                {
                    tagName: 'h4',
                    parentElement: divReservation,
                    id: 'date',
                    content: `${formattedMonth}-${formattedDay}`,
                    attributes:{
                            class: "reservations-group-title"
                        },
                    
                },
                {
                    tagName: 'ul',
                    parentElement: divReservation,
                    id: `list-${i}`,
                },
            ];
            createHtml(createDate);
            const list = divReservation.querySelector(`#list-${i}`);

            groupedReservations[fullDate].forEach((reservation) => {
                const calendarElements = [
                    {
                        tagName: 'li',
                        id: `point-${reservation.id}`,
                        parentElement: list,
                    },

                    {
                        tagName: 'div',
                        id: `reservation.${reservation.id}`,
                        content: `Nome della prenotazione: ${reservation.nome}, posti: ${reservation.posti}, data di uscita: ${reservation.uscita}`,
                        parentId: `point-${reservation.id}`,
                    },

                    {
                        tagName: 'button',
                        events: [

                            {
                                eventName: 'click',
                                callbackName: edit,
                                parameters: [
                                    reservation.id,
                                    modal,
                                ],
                            },
                        ],
                        content: 'modifica prenotazione',
                        attributes: {
                            class: 'add',
                        },
                        parentId: `reservation.${reservation.id}`
                    },

                    {
                        tagName: 'button',
                        events: [{
                            eventName: 'click',
                            callbackName: moveToTrash,
                            parameters: [
                                reservation.id
                            ],
                        }],
                        content: 'sposta nel cestino',
                        attributes: {
                            class: 'add',
                        },
                        parentId: `reservation.${reservation.id}`,
                    },
                ];

                createHtml(calendarElements);
            })
        })
    } else {

        calendarContainer.textContent = `Non ci sono prenotazioni nello storage !`
        searchButton.setAttribute("disabled");
    }
}

//funzione per stampare la lista delle prenotazioni del cestino
export function writeDeleteCalendar(deleteReservations, calendarContainer) {
    if (deleteReservations != "") {

        let bookYear = "";
        const createDivReservation = [
            {
                tagName: 'div',
                id: 'divReservation',
                parentElement: calendarContainer
            },
        ];
        createHtml(createDivReservation);
        const divReservation = calendarContainer.querySelector('#divReservation');

        let groupedReservations = groupReservations(deleteReservations);

        Object.keys(groupedReservations).sort().forEach((fullDate, i) => {
            //usiamo l'oggetto date per convertire la stringa della data fullDate nell'oggetto Date e poter utlizzare i metodi
            //dell'oggetto
            const dateEnter = new Date(fullDate);
            const year = dateEnter.getFullYear();
            //aggiungo 1 perchè il metodo getMonth restituisce un numero da 0 a 11
            const month = dateEnter.getMonth() + 1;
            const formattedMonth = String(month).padStart(2,'0');
            const day = dateEnter.getDate();
            const formattedDay = String(day).padStart(2,'0');
            if (year != bookYear) {
                const createh3Year = [
                    {
                        tagName: 'h3',
                        id: 'year',
                        parentElement: divReservation,
                        content: year,
                        attributes: {
                                class: "year-title",
                            }
                        
                    }
                ]
                createHtml(createh3Year);
            }
            bookYear = year;

            const createDate = [
                {
                    tagName: 'h4',
                    parentElement: divReservation,
                    id: 'date',
                    content: `${formattedMonth}-${formattedDay}`,
                    attributes:{
                            class: "reservations-group-title"
                        },
                    
                },
                {
                    tagName: 'ul',
                    parentElement: divReservation,
                    id: `list-${i}`,
                },
            ];
            createHtml(createDate);
            const list = divReservation.querySelector(`#list-${i}`);

            groupedReservations[fullDate].forEach((reservation) => {

                const calendarElements = [
                    {
                        tagName: 'li',
                        id: `point-${reservation.id}`,
                        parentElement: list,
                    },

                    {
                        tagName: 'div',
                        id: reservation.id,
                        content: `Nome della prenotazione: ${reservation.nome}, posti: ${reservation.posti}, data di uscita: ${reservation.uscita}`,
                        parentId: `point-${reservation.id}`,
                    },

                    {
                        tagName: 'button',
                        events: [

                            {
                                eventName: 'click',
                                callbackName: deleteforEver,
                                parameters: [
                                    reservation.id,
                                ],
                            },
                        ],
                        content: 'cancella definitivamente',
                        attributes: {
                            class: 'add',
                        },
                        parentId: reservation.id
                    },

                    {
                        tagName: 'button',
                        events: [
                            {
                                eventName: 'click',
                                callbackName: restoreReservation,
                                parameters: [
                                    reservation.id,
                                ],
                            },
                        ],
                        content: 'ripristina prenotazione',
                        attributes: {
                            class: 'add',
                        },
                        parentId: reservation.id,
                    },
                ];

                createHtml(calendarElements);
            })
        })


    } else {

        calendarContainer.textContent = `Non ci sono prenotazioni nel cestino !`
        searchButton.setAttribute("disabled");
    }
}

export function writeHistoricCalendar(historicReservations, calendarContainer) {
    if (historicReservations != "") {

        let bookYear = "";
        const createDivReservation = [
            {
                tagName: 'div',
                id: 'divReservation',
                parentElement: calendarContainer
            },
        ];
        createHtml(createDivReservation);
        const divReservation = calendarContainer.querySelector('#divReservation');

        let groupedReservations = groupReservations(historicReservations);

        Object.keys(groupedReservations).sort().forEach((fullDate, i) => {
            //usiamo l'oggetto date per convertire la stringa della data fullDate nell'oggetto Date e poter utlizzare i metodi
            //dell'oggetto
            const dateEnter = new Date(fullDate);
            const year = dateEnter.getFullYear();
            //aggiungo 1 perchè il metodo getMonth restituisce un numero da 0 a 11
            const month = dateEnter.getMonth() + 1;
            const formattedMonth = String(month).padStart(2,'0');
            const day = dateEnter.getDate();
            const formattedDay = String(day).padStart(2,'0');
            if (year != bookYear) {
                const createh3Year = [
                    {
                        tagName: 'h3',
                        id: 'year',
                        parentElement: divReservation,
                        content: year,
                        attributes: {
                                class: "year-title",
                            }
                        
                    }
                ]
                createHtml(createh3Year);
            }
            bookYear = year;

            const createDate = [
                {
                    tagName: 'h4',
                    parentElement: divReservation,
                    id: 'date',
                    content: `${formattedMonth}-${formattedDay}`,
                    attributes:{
                            class: "reservations-group-title"
                        },
                    
                },
                {
                    tagName: 'ul',
                    parentElement: divReservation,
                    id: `list-${i}`,
                },
            ];
            createHtml(createDate);
            const list = divReservation.querySelector(`#list-${i}`);

            groupedReservations[fullDate].forEach((reservation) => {

                const calendarElements = [
                    {
                        tagName: 'li',
                        id: `point-${reservation.id}`,
                        parentElement: list,
                    },

                    {
                        tagName: 'div',
                        id: reservation.id,
                        content: `Nome della prenotazione: ${reservation.nome}, posti: ${reservation.posti}, data di uscita: ${reservation.uscita}`,
                        parentId: `point-${reservation.id}`,
                    },
                ];

                createHtml(calendarElements);
            })
        })


    } else {

        calendarContainer.textContent = `Non ci sono prenotazioni nello storico !`
        searchButton.setAttribute("disabled");
    }
}

