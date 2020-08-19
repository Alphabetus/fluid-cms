
window.selectHomepage = function() {
    const $this = $(this);
    const puid = $this.val();
    const endpoint = $this.data('endpoint');
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
    const $this = $(this);
    const value = $this.val();
    const endpoint = $this.data('endpoint');
    const payload = { maintenance: value }

    $.ajax({
        url: endpoint,
        type: "POST",
        data: payload,
        success: function(d) { displayMaintenanceChangeAlert() },
        error: function(d) { console.log(d) }
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
    console.log("called")
    const $alert = $('#maintenanceChangeAlert');
    $alert.removeClass("d-none");
    setTimeout(function(){
        $alert.addClass("d-none");
    }, 5000)
}