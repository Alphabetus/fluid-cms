window.closeCustomAlert = function() {
    const $this = $(this);
    const $parent = $this.closest('.custom-alert');

    $parent.animate({
        opacity: 0
    }, 350, function(){
        $parent.remove();
    })
}


window.closeCustomAlertAuto = function() {
    const $alert = $('.custom-alert');

    $alert.animate({
        opacity: 0
    }, 350, function(){
        $alert.remove();
    })
}