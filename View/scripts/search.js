import { resetCalendar, writeCalendar } from './calendar.js';
import { requestManager } from './requestManager.js';
import { fetchData } from './homepage.js';

//funzione per cercare una prenotazione
export async function search(calendarContainer) {
    const searchForm = document.getElementById('search');
    const searchName = searchForm.querySelector("#name");
    const searchEnter = searchForm.querySelector("#enter");
    if (searchName.value || searchEnter.value) {
      try {
        const params = new URLSearchParams();
        if(searchName.value){
            params.append('name',searchName.value);
        }
        if(searchEnter.value){
            params.append('enter',searchEnter.value);
        }
        const url = `http://www.chiara-test/api/reservation?${params.toString()}`;
        const searchReservation = await requestManager.get(url);
        if (searchReservation.length >= 1) {
            resetCalendar();
            writeCalendar(searchReservation, calendarContainer);
        } else {
            alert(`Non ci sono prenotazioni corrispondenti ai valori cercati`)
           // fetchData();
        };
      } catch (error){
        console.error('Errore durante la ricerca: ',error);
      }
    } else {
        alert('Non sono stti inseriti dei campi validi');
        //gestisci il caso in cui non sono stati inseriti criteri di ricerca
    }
};


//funzione per interrompere la ricerca
export async function noSearch() {
    const searchForm = document.getElementById('search');
    const searchName = searchForm.querySelector("#name");
    const searchEnter = searchForm.querySelector("#enter");
    if(searchName.value!="" || searchEnter.value!=""){
        searchName.value = "";
        searchEnter.value = "";
        resetCalendar();
        fetchData();
    } 
}


