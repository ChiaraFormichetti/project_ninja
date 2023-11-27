class Table{
    nameColumns;
    data;

    constructor(nameColumns,data){
        this.nameColumns = nameColumns;
        this.data = data;
        this.buildTable();
    }
    
    buildTable(){
        const table = document.createElement("table");
        table.appendChild(this.buildColumns());
        table.appendChild(this.buildRows());
        document.querySelector("body").appendChild(table);

    }

    buildColumns(){
        const thead = document.createElement("thead");
        this.nameColumns.forEach((nameColumn) =>{
            const th = document.createElement("th");
            const text = document.createTextNode(nameColumn);
            th.appendChild(text);
            thead.appendChild(th);
        });
        return thead;
    }
    buildRows(){
        const tbody = document.createElement("tbody");
        this.data.forEach((row)=>{
            const tr = document.createElement("tr");
            Object.keys(row).forEach((key)=>{
                const cell = document.createElement("td");
                const text = document.createTextNode(row[key]);
                cell.appendChild(text);
                tr.appendChild(cell);
            });
            tbody.appendChild(tr);
        });
        return tbody;
    }
}

const nameColumn1 = [
    "id",
    "nome",
    "posti",
    "ingresso",
    "uscita",
];

const data1 = [
    {
        id: 1,
        nome: "Chiara",
        posti: 3,
        ingresso: "2023/12/22",
        uscita: "2023/12/26",
    },
    {
        id: 2,
        nome: "Giordano",
        posti: 2,
        ingresso: "2024/01/13",
        uscita: "2024/01/17",
    },
    {
        id: 3,
        nome: "Martina",
        posti: 4,
        ingresso: "2024/01/31",
        uscita: "2024/02/4",
    },
];

const nameColumn2 =["id","stanza","prezzo"];

const data2 = [
    {
        id: 1,
        stanza: "Suite royale '1'",
        prezzo: 800,
    },
    {
        id: 2,
        stanza: 204,
        prezzo: 100,
    },
    {
        id:3,
        stanza: "Suite basic '6'",
        prezzo: 800,
    },
];

const table1 = new Table(nameColumn1,data1);
const table2 = new Table(nameColumn2,data2);
