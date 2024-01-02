import { commonSelector } from "./commonSelector.js";
import { requestManager } from "./requestManager.js"
import createHtml from "./reservations/element.js";

const body = commonSelector.body;
const form = body.querySelector("form");
const inputCode = form.querySelector("#code");
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const codeValue = inputCode.value.trim();
    //url per vedere se c'è il codice all'interno della tabella beneficiari
    let url = commonSelector.apiCodesURL;
    url += `check/${codeValue}`;
    try {
        const codeCoupon = await requestManager.get(url);
        console.log(codeCoupon);
        if (!codeCoupon ) {
            alert("Il coupon inserito non è valido");
        } else {
            const insertDiv = body.querySelector(".coupon-insert");
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
                    content: `Codice coupon: ${codeCoupon[0].code}, tipo di coupon: ${codeCoupon[0].type}, data di scadenza: ${codeCoupon[0].expiration}`,
                    parentId: 'verified',
                    id: 'couponData',
                    
                },
                {
                    tagName: 'div',
                    attributes:{
                        class:"verified",
                    },
                    parentId:'couponData',

                },

            ];
            createHtml(couponDiv);
            debugger;
            let gifturl =commonSelector.apiGiftsURL;
            gifturl += `type/${codeCoupon[0].type}`;
            try{
                const gifts = await requestManager.get(gifturl);
                console.log(gifts);
                if(!gifts){
                    alert("Non ci sono regali validi");
                } else {
                    const verifiedDiv = insertDiv.querySelector(".verified");
                    gifts.forEach(gift => {
                        const giftDiv = [
                            {
                                tagName: 'div',
                                content: `Tipo: ${gift.type}, Titolo: ${gift.title}, Descrizione: ${gift.description}`,
                                parentElement: verifiedDiv,
                                id : `${gift.id}`,
                            }
                        ];
                        createHtml(giftDiv);
                        
                    });
    
                }

            } catch (error){
                console.error("Errore nel recupero dei regali disponibili");
            }
        }
    } catch (error) {
        console.error("Errore nel controllo del coupon");

    }
})
/*
            let url = apiURL +'/gifts';
            const gifts = await requestManager.get(url);
            if(gifts.items.length>0){
                
            }
 
  */