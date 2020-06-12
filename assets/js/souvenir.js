$('#add-photo').click(function () {
    const index = +$('#widgets-counter').val();
    const tmp1 = $('#souvenir_photos').data('prototype').replace(/__name__/g, index);
    $('#souvenir_photos').append(tmp1);
    $('#widgets-counter').val(index + 1);
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter(){
    const count = +$('#souvenir_photos div.form-group').length;
    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();