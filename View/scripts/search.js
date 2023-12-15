import { resetCalendar} from './calendar.js';
import { getReservations, getSearchReservation } from './get.js';
import { commonSelector } from './commonSelector.js';
//funzione per cercare una prenotazione
const searchName = commonSelector.searchName;
const searchEnter = commonSelector.searchEnter;

export async function search(calendarContainer) {
    if (searchName.value || searchEnter.value) {
        
          const params = new URLSearchParams();
          if(searchName.value){
              params.append('name',searchName.value);
          }
          if(searchEnter.value){
              params.append('enter',searchEnter.value);
          }
          const url = `http://www.chiara-test/api/reservation/search?${params.toString()}`;
          getSearchReservation(url, calendarContainer);
          
      } else {
          alert('Non sono stti inseriti dei campi validi');
          //gestisci il caso in cui non sono stati inseriti criteri di ricerca
      }
  };
  
  

//funzione per interrompere la ricerca
export async function noSearch(calendarContainer) {
    if (searchName.value != "" || searchEnter.value != "") {
        searchName.value = "";
        searchEnter.value = "";
        resetCalendar(calendarContainer);
        getReservations(calendarContainer);
    }
}


