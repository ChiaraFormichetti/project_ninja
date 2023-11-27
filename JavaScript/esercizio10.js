const preview = document.getElementById("preview");
const colorPickers = document.querySelectorAll(".color-picker");

colorPickers.forEach((colorPicker)=>{
    colorPicker.addEventListener("mouseover",(e)=>{
        console.log(getComputedStyle(e.srcElement).backgroundColor);
        preview.style.backgroundColor = getComputedStyle(
            e.srcElement
        ).backgroundColor;
        
    });
});