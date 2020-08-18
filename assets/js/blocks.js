
class Block {
    constructor() {
        this.template = `
        <div class="col-12 block mt-2 shadow-sm" data-priority="0">
            <div class="row h-100 mt-3">
                <div class="close" onclick="javascript:window.block.deleteBlock.call(this)">
                    &times;
                </div>
                <div class="col-12 align-self-center">
                    <div class="row m-0 justify-content-between">
                        <div class="col-6 p-0 align-self-center text-truncate pr-1">
                            <h6 class="m-0 overflow-hidden">Block Name</h6>
                            <small>type: text</small>
                        </div>
                        <div class="col-6 p-0 text-right align-self-center">
                            <button type="button" class="btn btn-sm btn-outline-light text-truncate" onclick="">edit content</button>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col-6 pl-0 align-self-center">
                            <label>Desktop</label>
                            <select class="custom-select" name="desktop-breakpoint" onchange="">
                                <option value="1" selected>Full</option>
                                <option value="2">Half</option>
                                <option value="3">Third</option>
                                <option value="4">Forth</option>
                                <option value="5">2 Thirds</option>
                                <option value="6">3 Forths</option>
                            </select>
                        </div>
                        <div class="col-6 pr-0 align-self-center">
                            <label>Mobile</label>
                            <select class="custom-select" name="mobile-breakpoint">
                                <option value="1" selected>Full</option>
                                <option value="2">Half</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    enableLoading() {
        $("#addBlocksButton").addClass("disabled");
        $('#blocks__load').css("display", "initial");
    }

    disableLoading() {
        $("#addBlocksButton").removeClass("disabled")
        $('#blocks__load').css("display", "none");
    }

    createBlock() {
        if (window.event) { window.event.preventDefault() }

        window.block.enableLoading();

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
            success: function(data) { window.block.printBlock(data) },
            error: function(d) { console.log(d) }
        })
    }

    printBlock(data) {
        let $block = $(this.template);
        const $block_container = $('#blocks');

        $block.attr('id', data);

        $block_container.append($block);
        window.block.disableLoading();
    }

    deleteBlock() {
        const $this = $(this);
        const container = $('#blocks__container');
        const $block = $this.closest('.block');
        const endpoint = container.data('delete');
        const payload = { buid: $block.attr("id") };

        window.block.enableLoading();

        console.log(payload);

        $.ajax({
            url: endpoint,
            type: 'POST',
            data: payload,
            success: function(data) { window.block.disableLoading() },
            error: function(d) { console.log(d) }
        })

        $block.animate({
            opacity: 0
        }, 250, function(){
            $block.remove();
        })
    }
}


window.block = new Block();