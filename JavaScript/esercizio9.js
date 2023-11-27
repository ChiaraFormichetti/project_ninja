//questa funzione ci servirà!!!!!!!!!!!!!


const input = document.getElementById("search");
input.addEventListener("keyup",autocomplete);
const results = document.getElementById("results");
const names = [
    "chiara",
    "giulia",
    "andrea",
    "giordano",
    "martina",
    "caterina",
    "francesco",
    "flavio",
];

function autocompleteMatch(value){
    if(value == "") return[];
    const reg = new RegExp(value);
    return names.filter((name) => {
        if (name.match(reg)) return name;
    });
}
function autocomplete(event){
    let adviceNames = "";
    const names = autocompleteMatch(event.target.value);
    names.forEach((name) =>{
        adviceNames += `<li class="hover:bg-gray-200" onclick="select(event)">${name}</li>`;
    });
    results.innerHTML = `<ul>${adviceNames}</ul>`;
}
function select(event){
    results.innerHTML = "";
    input.value = event.target.textContent;
}