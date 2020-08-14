window.closeCustomAlert = function() {
    const $this = $(this);
    const $parent = $this.closest('.custom-alert');

    $parent.animate({
        opacity: 0
    }, 350, function(){
        $parent.remove();
    })
}
