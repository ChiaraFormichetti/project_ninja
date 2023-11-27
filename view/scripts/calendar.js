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
export function updateCalendar(reservationXML, div) {

    resetCalendar();
    writeCalendar(reservationXML, div);
}

//funzione per raggruppare le prenotazioni prima di stamparle
export function groupReservations(reservationXML) {
    if (reservationXML && reservationXML.length) {
        let groupedReservations = {};
        reservationXML.forEach((element) => {
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

//funzione per scrivere la lista delle prenotazioni
export function writeCalendar(reservationXML, div) {

    if (reservationXML != "") {

        const modal = document.getElementById("myModal");
        let bookYear = "";
        let list;
        const divReservation = document.createElement("div");
        div.appendChild(divReservation);

        let groupedReservationsUnordered = groupReservations(reservationXML);
        //che poi in realtà la sort la fa direttamente sql con order by
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
            }
            bookYear = year;

            const h4 = document.createElement("h4");
            h4.textContent = date;
            divReservation.appendChild(h4);
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
                                callbackName: edit,
                                parameters: [
                                    reservationXML,
                                    reservation.id,
                                    modal,
                                    div
                                ],
                            },
                        ],
                        content: 'modifica prenotazione',
                        attributes: {
                            className: 'add',
                        },
                        parentId: reservation.id
                    },

                    {
                        tagName: 'button',
                        events: {
                            eventName: 'click',
                            callbackName: moveToTrash,
                            parameters: [
                                reservationXML,
                                reservation.id,
                                div
                            ],
                        },
                        content: 'sposta nel cestino',
                        attributes: {
                            className: 'add',
                        },
                        parentId: reservation.id,
                    },
                ];

                createHtml(calendarElements);
            })
        })
    } else {

        div.textContent = `Non ci sono prenotazioni nello storage !`
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
           editButton.addEventListener("click", () => edit( reservationXML,reservation.id, addingMode, modal,div));
           trashButton.addEventListener("click", () => moveToTrash(reservationXML,reservation.id, div));
       })
   })  */
}

//funzione per stampare la lista delle prenotazioni del cestino
export function writeDeleteCalendar(deleteReservations, div) {
    if (deleteReservations != "") {

        let bookYear = "";
        let list;
        const divReservation = document.createElement("div");
        div.appendChild(divReservation);

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
            }
            bookYear = year;

            const h4 = document.createElement("h4");
            h4.textContent = date;
            divReservation.appendChild(h4);
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
                                    div
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
                                div
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

        div.textContent = `Non ci sono prenotazioni nello storage !`
        searchButton.setAttribute("disabled");
    }
}