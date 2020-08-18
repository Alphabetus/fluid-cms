

window.createBlock = function() {
    if (window.event) { window.event.preventDefault() }
    const $this = $(this);
    const endpoint = $this.data('href');
    const type = $this.data('type');
    const page_id = $this.data('page');

    const payload = {
        page_id: page_id,
        type: type
    }

    $.ajax({
        url: endpoint,
        type: 'POST',
        data: payload,
        success: function(d) { console.log(d) },
        error: function(d) { console.log(d) }
    })
}

