import { commonSelector } from "./commonSelector.js";
import { requestManager } from "./requestManager.js"
import createHtml from "./reservations/element.js";

const body = commonSelector.body;
const form = body.querySelector("#couponForm");
const inputCode = form.querySelector("#code");
let beneficiaries = {};
let selectedGiftId = null;
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const codeValue = inputCode.value.trim();
    //url per vedere se c'è il codice all'interno della tabella beneficiari
    let url = commonSelector.apiCodesURL;
    url += `check/${codeValue}`;
    try {
        const codeCoupon = await requestManager.get(url);
        if (!codeCoupon.length) {
            alert("Il coupon inserito non è valido");
        } else {
            const insertDiv = body.querySelector(".coupon-insert");
            while (insertDiv.firstChild) {
                insertDiv.removeChild(insertDiv.firstChild);
            }
            debugger;
            const couponDiv = [
                {
                    tagName: 'h3',
                    id: 'verified',
                    parentElement: insertDiv,
                    content: 'Coupon verificato',

                },
                {
                    tagName: 'h4',
                    content: `Codice coupon: ${codeCoupon[0].code}, tipo di coupon: ${codeCoupon[0].type}, data di scadenza: ${codeCoupon[0].expiration}`,
                    parentId: 'verified',
                    id: 'couponData',

                },
                {
                    tagName: 'div',
                    id: 'beneficiariesDiv',
                    attributes: {
                        class: 'beneficiaries-div',
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
                            content: 'verifica',
                            attributes: {
                                class: 'verify',
                            },
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
            createHtml(couponDiv);
            let gifturl = commonSelector.apiGiftsURL;
            gifturl += `type/${codeCoupon[0].type}`;
            try {
                const gifts = await requestManager.get(gifturl);
                if (!gifts) {
                    alert("Non ci sono regali validi");
                } else {
                    debugger
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

                }

            } catch (error) {
                console.error("Errore nel recupero dei regali disponibili");
            }
        }
    } catch (error) {
        console.error("Errore nel controllo del coupon");

    }
});

function giftSelection(id) {
    debugger;
    console.log(id);
    const insertDiv = body.querySelector(".coupon-insert");
    const verifiedDiv = insertDiv.querySelector(".gift-div");
    const gift = verifiedDiv.querySelector(`#gift-${id}`);

    const allGifts = verifiedDiv.querySelectorAll('.gift');
    allGifts.forEach(g => g.classList.remove('selected-gift'));

    gift.classList.add('selected-gift');

    selectedGiftId = id;

};

function verifyInput() {
    debugger;
    const insertDiv = body.querySelector(".coupon-insert");
    const beneficiariesDiv = insertDiv.querySelector('#beneficiariesDiv');
    const nameRegex = /^[a-zA-Z]{1,30}$/;
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const name = beneficiariesDiv.querySelector('#name');
    const surname = beneficiariesDiv.querySelector('#surname');
    const email = beneficiariesDiv.querySelector('#email');

    if (nameRegex.test(name.value.trim()) && nameRegex.test(surname.value.trim()) &&
        emailRegex.test(email.value.trim())) {
        const verifyButton = beneficiariesDiv.querySelector('.verify');
        verifyButton.style.display = 'none';

        const verificationText = [
            {
                tagName: 'p',
                content: 'Dati verificati !',
                parentElement: beneficiariesDiv,

            },
        ]
        createHtml(verificationText);

        const today = new Date().toLocaleDateString();

        beneficiaries = {
            'name': name.value.trim(),
            'surname': surname.value.trim(),
            'email': email.value.trim(),
            'date': today
        };
        return beneficiaries;
    } else {
        alert('Dati non validi. Si prega di controllare nuovamente i campi.');
    };
};
