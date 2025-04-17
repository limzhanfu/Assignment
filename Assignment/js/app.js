
$(() => {

    // Autofocus

    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();

   
    // Reset form
   
    $('[type=reset]').on('click' , e=>{
        e.preventDefault();
        location = location;
    })


});