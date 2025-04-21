
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

     // Photo preview
     $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });

});