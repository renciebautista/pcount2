$(".userStatus").bootstrapSwitch();

$(".userStatus").on('switchChange.bootstrapSwitch', function (event, state) {

    var userId = $(this).attr('id');
    var state = (state) ? '1' : '0';


    $.post('store_user/changestatus', {id: userId, active: state})
        .done(function (data) {

        });
});