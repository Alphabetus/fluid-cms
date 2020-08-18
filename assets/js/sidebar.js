

$(document).ready(function(){
    resizeSidebar();
});




function resizeSidebar() {
    const $sidebar = $('#sidebar');
    const $body = $('body');
    const bodyHeight = eval($body.outerHeight() + 300);

    $sidebar.css('height', bodyHeight);
}