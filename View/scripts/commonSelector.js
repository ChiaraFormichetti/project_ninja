

const body = document.querySelector("body");
const baseURL = "http://www.chiara-test/";
const apiBeneficiariesURL = baseURL + "api/beneficiaries/";
const apiGiftsURL = baseURL + "api/gifts/";
const apiCodesURL = baseURL + "api/codes/";

export const commonSelector =
 {
    body: body,
    apiBeneficiariesURL : apiBeneficiariesURL,
    apiCodesURL : apiCodesURL,
    apiGiftsURL : apiGiftsURL,

}   