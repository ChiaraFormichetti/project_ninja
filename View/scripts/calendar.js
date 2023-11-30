import { edit, moveToTrash, deleteforEver, restoreReservation } from './reservation.js';
import createHtml from './element.js';

//funzione per cancellare la lista delle prenotazioni
export function resetCalendar() {
    let calendarElement = document.getElementById("calendar");

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
            let enter = element.ingresso;
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
/*.reduce(
    (obj, key) => {
        obj[key] = groupedReservationsUnordered[key];
        return obj;
    },
    {}
);*/

//funzione per scrivere la lista delle prenotazioni
export function writeCalendar(allReservations, calendarContainer) {

    if (allReservations != "") {

        const modal = document.getElementById("myModal");
        let bookYear = "";
        let list;
        const divReservation = document.createElement("div");
        calendarContainer.appendChild(divReservation);

        let groupedReservations = groupReservations(allReservations);
       
        Object.keys(groupedReservations).sort().forEach((fullDate) => {
            let year = fullDate.substr(0, 4);
            let date = fullDate.substr(-5);//modificare le date
            if (year != bookYear) {
                const h3year = document.createElement("h3");
                h3year.textContent = year;
                h3year.className = "year-title";
                divReservation.appendChild(h3year);
            }
            bookYear = year;

            const reservationsGroupTitle= document.createElement("h4");
            reservationsGroupTitle.textContent = date;
            reservationsGroupTitle.className = "reservations-group-title"
            divReservation.appendChild(reservationsGroupTitle);
            list = document.createElement("ul");
            divReservation.appendChild(list);

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
                                    allReservations,
                                    reservation,
                                    modal,
                                    calendarContainer
                                ],
                            },
                        ],
                        content: 'modifica prenotazione',
                        attributes: {
                            className: 'add',
                        },
                        parentId: `reservation.${reservation.id}`
                    },

                    {
                        tagName: 'button',
                        events: {
                            eventName: 'click',
                            callbackName: moveToTrash,
                            parameters: [
                                allReservations,
                                reservation,
                                calendarContainer
                            ],
                        },
                        content: 'sposta nel cestino',
                        attributes: {
                            className: 'add',
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
    /*     groupedReservations[fullDate].forEach((reservation) => {
           const reservation = document.createElement("a");
           const editButton = document.createElement("button");
           const trashButton = document.createElement("button");
           editButton.textContent = `modifica reservation`;
           trashButton.textContent = `sposta nel cestino`;
           editButton.className = "add";
           trashButton.className = "add";
           reservation.textContent = `Nome della reservation: ${reservation.nome}, posti: ${reservation.posti}, data di uscita: ${reservation.uscita}`;
           point.appendChild(reservation);
           reservation.appendChild(editButton);
           reservation.appendChild(trashButton);
           editButton.addEventListener("click", () => edit( allReservations,reservation.id, addingMode, modal,calendarContainer));
           trashButton.addEventListener("click", () => moveToTrash(allReservations,reservation.id, calendarContainer));
       })
   })  */
}

//funzione per stampare la lista delle prenotazioni del cestino
export function writeDeleteCalendar(deleteReservations, calendarContainer) {
    if (deleteReservations != "") {

        let bookYear = "";
        let list;
        const divReservation = document.createElement("div");
        calendarContainer.appendChild(divReservation);

        let groupedReservationsUnordered = groupReservations(deleteReservations);
        let groupedReservations = Object.keys(groupedReservationsUnordered).sort().reduce(
            (obj, key) => {
                obj[key] = groupedReservationsUnordered[key];
                return obj;
            },
            {}
        );

        Object.keys(groupedReservations).forEach((fullDate) => {
            let year = fullDate.substr(0, 4);
            let date = fullDate.substr(-5);
            if (year != bookYear) {
                const h3year = document.createElement("h3");
                divReservation.appendChild(h3year);
                h3year.textContent = year;
                h3year.className = "year-title";
            }
            bookYear = year;

            const reservationsGroupTitle = document.createElement("h4");
            reservationsGroupTitle.textContent = date;
            reservationsGroupTitle.className="reservations-group-title"
            divReservation.appendChild(reservationsGroupTitle);
            list = document.createElement("ul");
            divReservation.appendChild(list);

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
                                    deleteReservations,
                                    reservation.id,
                                    calendarContainer
                                ],
                            },
                        ],
                        content: 'cancella definitivamente',
                        attributes: {
                            className: 'add',
                        },
                        parentId: reservation.id
                    },

                    {
                        tagName: 'button',
                        events: {
                            eventName: 'click',
                            callbackName: restoreReservation,
                            parameters: [
                                deleteReservations,
                                reservation.id,
                                calendarContainer
                            ],
                        },
                        content: 'ripristina prenotazione',
                        attributes: {
                            className: 'add',
                        },
                        parentId: reservation.id,
                    },
                ];

                createHtml(calendarElements);
            })
            /*
            const point = document.createElement("li");
            const reservation = document.createElement("a");
            const deleteComplete = document.createElement("button");
            const ripReservation = document.createElement("button");
            deleteComplete.textContent = `Cancella definitivamente`;
            ripReservation.textContent = `Ripristina reservation`;
            deleteComplete.className = "add";
            ripReservation.className = "add";
            reservation.textContent = `Nome della reservation: ${reservation.nome}, posti: ${reservation.posti}, data di uscita: ${reservation.uscita}`;
            divReservation.appendChild(list);
            list.appendChild(point);
            point.appendChild(reservation);
            reservation.appendChild(deleteComplete);
            reservation.appendChild(ripReservation);
            deleteComplete.addEventListener("click", () => deleteforEver(deleteReservations, reservation.id, div));
            ripReservation.addEventListener("click", () => restoreReservation(deleteReservations, reservation.id, div));
            */
        })


    } else {

        calendarContainer.textContent = `Non ci sono prenotazioni nel cestino !`
        searchButton.setAttribute("disabled");
    }
}

export function writeHistoricCalendar (historicReservations,calendarContainer){
    if (historicReservations != "") {

        let bookYear = "";
        let list;
        const divReservation = document.createElement("div");
        calendarContainer.appendChild(divReservation);

        let groupedReservationsUnordered = groupReservations(historicReservations);
        let groupedReservations = Object.keys(groupedReservationsUnordered).sort().reduce(
            (obj, key) => {
                obj[key] = groupedReservationsUnordered[key];
                return obj;
            },
            {}
        );

        Object.keys(groupedReservations).forEach((fullDate) => {
            let year = fullDate.substr(0, 4);
            let date = fullDate.substr(-5);
            if (year != bookYear) {
                const h3year = document.createElement("h3");
                divReservation.appendChild(h3year);
                h3year.textContent = year;
                h3year.className = "year-title";
            }
            bookYear = year;

            const reservationsGroupTitle = document.createElement("h4");
            reservationsGroupTitle.textContent = date;
            reservationsGroupTitle.className="reservations-group-title"
            divReservation.appendChild(reservationsGroupTitle);
            list = document.createElement("ul");
            divReservation.appendChild(list);

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

                    /*{
                        tagName: 'span',
                        events: [

                            {
                                eventName: 'click',
                                callbackName: deleteforEver,
                                parameters: [
                                    deleteReservations,
                                    reservation.id,
                                    calendarContainer
                                ],
                            },
                        ],
                        content: 'cancella definitivamente',
                        attributes: {
                            className: 'fa fa-star checked',
                        },
                        parentId: reservation.id
                    },

                    {
                        tagName: 'button',
                        events: {
                            eventName: 'click',
                            callbackName: restoreReservation,
                            parameters: [
                                deleteReservations,
                                reservation.id,
                                calendarContainer
                            ],
                        },
                        content: 'ripristina prenotazione',
                        attributes: {
                            className: 'add',
                        },
                        parentId: reservation.id,
                    },*/
                ];

                createHtml(calendarElements);
            })
        })


    } else {

        calendarContainer.textContent = `Non ci sono prenotazioni nello storico !`
        searchButton.setAttribute("disabled");
    }
}

