import { commonSelector } from "./commonSelector.js";
import { requestManager } from "./requestManager.js"
import createHtml from "./reservations/element.js";

const body = commonSelector.body;
const form = body.querySelector("#couponForm");
const inputCode = form.querySelector("#code");
const insertDiv = body.querySelector(".coupon-insert");
let beneficiaries = {};
let selectedGiftId = null;
let usedCode = null;
let usedType = null;
let reload = false;
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const codeValue = inputCode.value.trim();
    const couponRegex = /\d{3}[A-Z]{2}\d{2}/;
    if (couponRegex.test(codeValue)) {
        let coupon = await checkCodeAvailability(codeValue);
        if (coupon) {
            createCodeDiv(coupon);
            usedCode = coupon.code;
            usedType = coupon.type;
            createBeneficiariesForm(codeValue);
        }
    } else {
        alert("Il codice inserite non è nel formato richiesto!");
    }

});


async function createBeneficiariesForm(codeValue) {
    let gifturl = commonSelector.apiGiftsURL;
    gifturl += `type/${usedType}`;
    try {
        const gifts = await requestManager.get(gifturl);
        if (!gifts.length) {
            alert("Non ci sono regali validi");
            //partono le funzioni per la gestione della situazione in assenza di regali 
        } else {
            const createBeneficiariesForm = [
                {
                    tagName: 'form',
                    id: 'beneficiariesForm',
                    attributes: {
                        class: 'beneficiaries-form',
                    },
                    parentElement: insertDiv,
                    children: [
                        {
                            tagName: 'div',
                            attributes: {
                                class: 'spacer',
                            },
                            children: [
                                {
                                    tagName: 'label',
                                    content: 'Nome: ',
                                    attributes: {
                                        for: "name",
                                    }
                                },
                                {
                                    tagName: 'input',
                                    id: "name",
                                    attributes: {
                                        name: 'name',
                                        type: 'text',
                                        maxlength: "15",
                                    }
                                },

                            ]
                        },
                        {
                            tagName: 'div',
                            attributes: {
                                class: 'spacer',
                            },
                            children: [
                                {
                                    tagName: 'label',
                                    content: 'Cognome: ',
                                    attributes: {
                                        for: "surname",
                                    }
                                },
                                {
                                    tagName: 'input',
                                    id: 'surname',
                                    attributes: {
                                        name: 'surname',
                                        type: 'text',
                                        maxlength: "20",
                                    },
                                },
                            ],
                        },
                        {
                            tagName: 'div',
                            attributes: {
                                class: 'spacer',
                            },
                            children: [
                                {
                                    tagName: 'label',
                                    content: 'E-mail: ',
                                    attributes: {
                                        for: 'email',
                                    }
                                },
                                {
                                    tagName: 'input',
                                    id: 'email',
                                    attributes: {
                                        name: 'email',
                                        type: 'email',
                                    },
                                },
                            ],
                        },
                        {
                            tagName: 'button',
                            content: 'Conferma i tuoi dati',
                            attributes: {
                                class: 'verify',
                                type: 'button',
                            },
                            id: 'verify',
                            events: [
                                {
                                    eventName: "click",
                                    callbackName: verifyInput,
                                    parameters: [
                                    ]
                                },
                            ],
                        },
                    ],
                },
                {
                    tagName: 'div',
                    attributes: {
                        class: "gift-div",
                    },
                    parentElement: insertDiv,

                }

            ]
            createHtml(createBeneficiariesForm);
            createGiftDiv(codeValue);
        }
    } catch (error) {
        console.error("Errore nel controllo dei regali", error);
    };
}

async function checkCodeAvailability(codeValue) {

    let url = commonSelector.apiCodesURL;
    url += `check/${codeValue}`;
    try {
        const codeCoupon = await requestManager.get(url);
        if (!codeCoupon.length) {
            alert("Il coupon inserito non è più valido");
        } else {
            let coupon = codeCoupon[0];
            console.log(coupon);
            return coupon;
        }
    } catch (error) {
        console.error("Errore nel controllo del codice coupon", error);
    }
}


async function createCodeDiv(coupon) {

    while (insertDiv.firstChild) {
        insertDiv.removeChild(insertDiv.firstChild);
    }
    const couponDiv = [
        {
            tagName: 'h3',
            id: 'verified',
            parentElement: insertDiv,
            content: 'Coupon verificato',
        },
        {
            tagName: 'h4',
            content: `Codice coupon: ${coupon.code}, tipo di coupon: ${coupon.type}, data di scadenza: ${coupon.expiration}`,
            parentId: 'verified',
            id: 'couponData',
        }
    ]
    createHtml(couponDiv);
}

async function createGiftDiv(codeValue) {

    let gifturl = commonSelector.apiGiftsURL;
    gifturl += `type/${usedType}`;
    try {
        const gifts = await requestManager.get(gifturl);
        if (!gifts.length) {
            alert("Non ci sono regali validi");
            reloadPage();
        } else {

            const verifiedDiv = insertDiv.querySelector(".gift-div");
            gifts.forEach(gift => {
                const giftDiv = [
                    {
                        tagName: 'div',
                        content: `Tipo: ${gift.type}\nTitolo: ${gift.title}\nDescrizione: ${gift.description}`,
                        parentElement: verifiedDiv,
                        id: `gift-${gift.id}`,
                        attributes: {
                            class: "gift",
                        }
                    },
                    {
                        tagName: 'img',
                        attributes:{
                            src: gift.photo,
                            class: 'photo',
                        },
                        parentId: `gift-${gift.id}`,
                    },
                    {
                        tagName: 'button',
                        content: 'x',
                        attributes: {
                            class: "chosen",
                        },
                        parentId: `gift-${gift.id}`,
                        events: [
                            {
                                eventName: "click",
                                callbackName: giftSelection,
                                parameters: [
                                    gift.id,
                                ]
                            }
                        ]

                    }
                ];
                createHtml(giftDiv);

            });
            if (!reload) {
                const claimGift = [
                    {
                        tagName: 'button',
                        content: 'Riscuoti regalo',
                        parentElement: insertDiv,
                        attributes: {
                            class: "claim-gift",
                        },
                        events: [
                            {
                                eventName: "click",
                                callbackName: redeemGift,
                                parameters: [
                                    codeValue,
                                ]
                            }
                        ]
                    }
                ];
                createHtml(claimGift);
            }
        }

    } catch (error) {
        console.error("Errore nel recupero dei regali disponibili");
    }

}


async function redeemGift(codeValue) {
    let check = checkBeneficiaries();
    debugger;
    let lastCheckForCode = await checkCodeAvailability(codeValue);
    if (lastCheckForCode) {
        if (selectedGiftId && check) {
            let beneficiariesUrl = commonSelector.apiBeneficiariesURL;
            beneficiariesUrl += `id/${selectedGiftId}`;
            try {
                const checkAvailability = await requestManager.get(beneficiariesUrl);
                if (checkAvailability.length) {
                    alert("Il regalo non è più disponibile, scegline un altro !");

                    const verifiedDiv = insertDiv.querySelector(".gift-div");
                    while (verifiedDiv.firstChild) {
                        verifiedDiv.removeChild(verifiedDiv.firstChild);
                    }
                    reload = true;
                    createGiftDiv();
                } else {
                    beneficiaries.code = usedCode;
                    beneficiaries.id = selectedGiftId;
                    //facciamo la nostra post
                    const formData = new FormData();
                    Object.keys(beneficiaries).forEach(key => {
                        formData.append(key, beneficiaries[key]);
                    });
                    let beneficiariesURL = commonSelector.apiBeneficiariesURL;
                    beneficiariesURL += 'add';
                    try {
                        const addBeneficiaries = await requestManager.post(beneficiariesURL, formData);
                        if (addBeneficiaries) {
                            //cancelliamo tutto e mostriamo un resoconto
                            alert("L'operazione è andata a buon fine!");

                            while (insertDiv.firstChild) {
                                insertDiv.removeChild(insertDiv.firstChild);
                            }
                            const successDiv = [
                                {
                                    tagName: 'div',
                                    content: 'Operazione avvenuta con successo.\nVuoi riscuotere un altro coupon?',
                                    parentElement: insertDiv,
                                    id: 'successDiv',
                                    attributes: {
                                        class: 'success',
                                    },
                                    children: [
                                        {
                                            tagName: 'button',
                                            content: 'torna alla pagina iniziale',
                                            events: [
                                                {
                                                    eventName: "click",
                                                    callbackName: reloadPage,
                                                    parameters: [

                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                            createHtml(successDiv);
                        } else {
                            alert("C'è stato un errore nell'operazione");
                        }

                    } catch (error) {
                        console.error("Errore durante l'aggiunta del beneficiario", error);
                    }

                }
            } catch (error) {
                console.error("Errore durante la verifica della disponibilità del regalo", error)
            }
            //prima fetch per controllare che il regalo sia ancora disponibile e poi operazione per aggiungere il beneficiario
        } else if (!selectedGiftId) {
            alert("Scegli un regalo!");
        } else {
            alert("Inserisci i tuoi dati!");
        }
    }
    else {
        reloadPage();
    }

}
function reloadPage() {
    location.reload();
}

function checkBeneficiaries() {
    const requiredFields = ['name', 'surname', 'email', 'date'];
    if (Object.keys(beneficiaries).length === requiredFields.length) {
        for (const field of requiredFields) {
            if (!beneficiaries[field] || beneficiaries[field].trim() === '') {
                return false;
            }
        } return true;
    } return false;
}

function giftSelection(id) {
    const verifiedDiv = insertDiv.querySelector(".gift-div");
    const gift = verifiedDiv.querySelector(`#gift-${id}`);

    const allGifts = verifiedDiv.querySelectorAll('.gift');
    allGifts.forEach(g => g.classList.remove('selected-gift'));

    gift.classList.add('selected-gift');

    selectedGiftId = id;
    console.log(selectedGiftId);
    return selectedGiftId;

};

function verifyInput(modified = false) {

    const beneficiariesForm = insertDiv.querySelector('#beneficiariesForm');
    const nameRegex = /^[a-zA-Z]{1,30}$/;
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const name = beneficiariesForm.querySelector('#name');
    const surname = beneficiariesForm.querySelector('#surname');
    const email = beneficiariesForm.querySelector('#email');

    if (nameRegex.test(name.value.trim()) && nameRegex.test(surname.value.trim()) &&
        emailRegex.test(email.value.trim())) {
        const verifyButton = beneficiariesForm.querySelector('#verify');
        verifyButton.style.display = 'none';

        if (!modified) {
            const verificationText = [
                {
                    tagName: 'p',
                    content: 'Dati confermati!',
                    parentElement: beneficiariesForm,

                },
                {
                    tagName: 'button',
                    content: 'Modifica',
                    attributes: {
                        class: 'verify',
                        type: 'button',
                    },
                    id: 'modify',
                    parentElement: beneficiariesForm,
                    events: [
                        {
                            eventName: "click",
                            callbackName: modifyInput,
                            parameters: [

                            ],
                        },
                    ],
                }
            ]
            createHtml(verificationText);
        }
        const today = new Date();
        const year = today.getFullYear();
        const month = (today.getMonth() + 1).toString().padStart(2, '0'); // Aggiunge uno zero iniziale se il mese è inferiore a 10
        const day = today.getDate().toString().padStart(2, '0'); // Aggiunge uno zero iniziale se il giorno è inferiore a 10

        const formattedDate = `${year}-${month}-${day}`;

        beneficiaries = {
            'name': name.value.trim(),
            'surname': surname.value.trim(),
            'email': email.value.trim(),
            'date': formattedDate,
        };
        if (modified) {
            alert("Dati modificati");
        }
        console.log(beneficiaries);
        return beneficiaries;
    } else {
        alert('Dati non validi. Si prega di controllare nuovamente i campi.');
    };
};

function modifyInput() {
    debugger;
    const beneficiariesForm = insertDiv.querySelector('#beneficiariesForm');
    const name = beneficiariesForm.querySelector('#name');
    const surname = beneficiariesForm.querySelector('#surname');
    const email = beneficiariesForm.querySelector('#email');
    if (name.value.trim() != beneficiaries.name || surname.value.trim() != beneficiaries.surname || email.value.trim() != beneficiaries.email) {
        let modified = true;
        verifyInput(modified);
    } else {
        alert("Non hai modificato nessun dato !");
    };
};
