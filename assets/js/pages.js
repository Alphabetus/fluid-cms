
window.updatePassword = function() {
    const $this = $(this);
    const $pass_1 = $('[name="password"]').val();
    const $pass_2 = $('[name="confirm_password"]').val();
    const endpoint = Routing.generate("admin.settings.update.password");
    const payload = { password: $pass_1, confirm_password: $pass_2 }

    $.ajax({
        url: endpoint,
        type: "POST",
        data: payload,
        success: function(d) { displayPasswordChangeAlert(d) },
        error: function(d) { console.log(d) }
    })

}

window.selectHomepage = function() {
    const $select = $('[name="homepage"]');
    const puid = $select.val();
    const endpoint = Routing.generate("admin.settings.update.homepage");
    const payload = { puid: puid }

    $.ajax({
        url: endpoint,
        type: "POST",
        data: payload,
        success: function(d) { displayHomepageChangeAlert() },
        error: function(d) { console.log(d) }
    })
}

window.selectMaintenance = function() {
    const $select = $("[name='maintenance']");
    const value = $select.val();
    const endpoint = Routing.generate("admin.settings.update.maintenance");
    const payload = { maintenance: value }

    $.ajax({
        url: endpoint,
        type: "POST",
        data: payload,
        success: function(d) { displayMaintenanceChangeAlert() },
        error: function(d) { console.log(d) }
    })
}

window.selectTitle = function() {
    const $input = $('#websiteTitle');
    const title = $input.val();
    const endpoint = Routing.generate("admin.settings.update.title");
    const payload = {title: title}

    $.ajax({
        url: endpoint,
        type: 'POST',
        data: payload,
        success: function(d) { displayTitleUpdateAlert() },
        error: function(d) {}
    })
}

function displayHomepageChangeAlert() {
    const $alert = $('#homepageChangeAlert');
    $alert.removeClass('d-none');
    setTimeout(function(){
        $alert.addClass('d-none');
    }, 5000)
}

function displayMaintenanceChangeAlert() {
    const $alert = $('#maintenanceChangeAlert');
    $alert.removeClass("d-none");
    setTimeout(function(){
        $alert.addClass("d-none");
    }, 5000)
}

function displayTitleUpdateAlert() {
    const $alert = $('#titleChangeAlert');
    $alert.removeClass('d-none');
    setTimeout(function(){
        $alert.addClass('d-none');
    }, 5000);
}
function displayPasswordChangeAlert(data) {
    const $ok = $('#password_changed');
    const $not_ok = $('#password_not_changed');

    $ok.addClass("d-none")
    $not_ok.addClass("d-none")

    if (data == true) {
        $ok.removeClass("d-none");
    } else {
        $not_ok.removeClass("d-none");
    }
}