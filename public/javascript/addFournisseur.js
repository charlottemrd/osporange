

$(document).ready(function () {




    var $collectionHolder;
    var $boutonAjouter = $('<button type="button" className="btn-toolbar add_profil_link sm btn btn-sm text-black"style="background-color: lightgrey">Ajouter un profil</button>');
    var $nouveau = $('<li></li>').append($boutonAjouter )
;
    jQuery(document).ready(function () {$collectionHolder = $('ul.profils');
    $collectionHolder.find('li').each(function () {
    addProfilFormDeleteLink($(this));
});
    $collectionHolder.append($nouveau);
    $collectionHolder.data('index', $collectionHolder.find('input').length);
    $boutonAjouter.on('click', function (e) {addProfilForm($collectionHolder, $nouveau);
});

    function addProfilForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    addProfilFormDeleteLink($newFormLi);
    document.getElementById('smartwizard').style.height = "200%";
    document.getElementById('tab-content').style.height = "200%";


}
    function addProfilFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn-toolbar btn btn-sm text-black" style="background-color: lightgrey; margin-bottom: 2%; font-size: 1em ">Supprimer le profil</button>');
    $tagFormLi.append($removeFormButton);
    $removeFormButton.on('click', function (e) {
    $tagFormLi.remove();
});
}
})
;


    var btnFinish = $('<button></button>').text('Ajouter').addClass('btn btn-info sw-btn-group-extra d-none')
    .on('click', function(){
    onsubmit();


});



    $('#smartwizard').smartWizard({
    selected: 0, // Initial selected step, 0 = first step
    theme: 'arrows', // theme for the wizard, related css need to include for other than default theme
    justified: true, // Nav menu justification. true/false
    darkMode:false, // Enable/disable Dark Mode if the theme supports. true/false
    autoAdjustHeight: true, // Automatically adjust content height
    cycleSteps: false, // Allows to cycle the navigation of steps
    backButtonSupport: true, // Enable the back button support
    enableURLhash: false, // Enable selection of the step based on url hash
    transition: {
    animation: 'none', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
    speed: '400', // Transion animation speed
    easing:'' // Transition animation easing. Not supported without a jQuery easing plugin
},
    toolbarSettings: {
    toolbarPosition: 'bottom', // none, top, bottom, both
    toolbarButtonPosition: 'right', // left, right, center
    showNextButton: true, // show/hide a Next button
    showPreviousButton: true, // show/hide a Previous button
    toolbarExtraButtons: [btnFinish] // Extra buttons to show on toolbar, array of jQuery input/buttons elements

},
    anchorSettings: {
    anchorClickable: true, // Enable/Disable anchor navigation
    enableAllAnchors: false, // Activates all anchors clickable all times
    markDoneStep: true, // Add done state on navigation
    markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
    removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
    enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
},
    keyboardSettings: {
    keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
    keyLeft: [37], // Left key code
    keyRight: [39] // Right key code
},
    lang: { // Language variables for button
    next: 'Suivant',
    previous: 'Précédant'
},
    disabledSteps: [], // Array Steps disabled
    errorSteps: [], // Highlight step with errors
    hiddenSteps: [], // Hidden steps

});





    $("#myForm").validate( {
    rules: {
    name: {
    required: true
}
},
});

    $('#smartwizard').on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
    if ($('#myForm').valid()) {
    if(stepDirection == "2") //here is the final step: Note: 0,1,2
{

    $('.sw-btn-group-extra').removeClass('d-none');
}
    else
{
    $('.sw-btn-group-extra').addClass('d-none');
    return true;
}

} else {
    return false
}
    return true;

})
    $('.fournisseur_devise').change(function (){
        document.getElementById('profildevise').innerText='Profils du fournisseur en ' + document.getElementById('fournisseur_devise').value+ ' :';
    });


})