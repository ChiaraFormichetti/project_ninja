import { resetCalendar, writeCalendar } from './calendar.js';
import { requestManager } from './requestManager.js';
import { fetchData } from './homepage.js';

//funzione per cercare una prenotazione
export async function search(calendarContainer) {
    const searchName = document.getElementById("name");
    const searchEnter = document.getElementById("enter");
    if (searchName.value || searchEnter.value) {
        resetCalendar();
      try {
        const params = new URLSearchParams();
        if(searchName.value){
            params.append('name',searchName.value);
        }
        if(searchEnter.value){
            params.append('enter',searchEnter.value);
        }
        const url = `http://www.chiara-test.com/api/reservation?${params.toString()}`;
        const searchReservation = await requestManager.get(url);
        if (searchReservation.length >= 1) {
            writeCalendar(searchReservation, calendarContainer);
        } else {
            alert(`Non ci sono prenotazioni corrispondenti ai valori cercati`)
            fetchData();
        };
      } catch (error){
        console.error('Errore durante la ricerca: ',error);
      }
    } else {
        //gestisci il caso in cui non sono stati inseriti criteri di ricerca
    }
};


//funzione per interrompere la ricerca
export async function noSearch(calendarContainer) {
    const searchName = document.getElementById("name");
    const searchEnter = document.getElementById("enter");
    searchName.value = "";
    searchEnter.value = "";
    resetCalendar();
    fetchData();
}


