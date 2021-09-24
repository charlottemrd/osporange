
function loadfiler()
{
    let filtdiv = document.getElementById("bigbox");
    if(getComputedStyle(filtdiv).display != "none"){
        filtdiv.style.display = "none";
    } else {
        filtdiv.style.display = "block";
    }
}

$('.js-example-basic-single').select2({
    allowClear: true,
    language: 'fr',
    multiple:true,
    style:'width',
});
$('#phases').select2({
    placeholder: "Selectionnez une phases"
});
$('#fournisseurs').select2({
    placeholder: "Selectionnez un fournisseur"
});
$('#bu').select2({
    placeholder: "Selectionnez un type de BU"
});
$('#risques').select2({
    placeholder: "Selectionnez un risque"
});
$('#priority').select2({
    placeholder: "Selectionnez une priorité"
});


let bouton1 = document.getElementById("bouton1");
let dive1 = document.getElementById("dive1");
bouton1.addEventListener("click", () => {
    if(getComputedStyle(dive1).display != "none"){
        dive1.style.display = "none";
    } else {
        dive1.style.display = "block";
    }
})