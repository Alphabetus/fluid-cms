
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
                            <select class="custom-select" name="desktop-breakpoint" onchange="javascript:window.block.resizeBlock.call(this)">
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
                            <select class="custom-select" name="mobile-breakpoint" onchange="javascript:window.block.resizeBlockMobile.call(this)">
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

        $block.remove();

        $.ajax({
            url: endpoint,
            type: 'POST',
            data: payload,
            success: function(data) { window.block.disableLoading() },
            error: function(d) { console.log(d) }
        })
    }

    resizeBlock() {
        const $this = $(this);
        const $block = $this.closest(".block");
        const value = $this.val();
        const breakpoint = new Breakpoint(value);
        const endpoint = $('#blocks__container').data("resizemd");
        const md_class = breakpoint.getMdClass();
        const payload = {
            breakpoint: md_class,
            buid: $block.attr('id')
        }

        $block.removeClass('col-md-12');
        $block.removeClass('col-md-6');
        $block.removeClass('col-md-4');
        $block.removeClass('col-md-3');
        $block.removeClass('col-md-8');
        $block.removeClass('col-md-9');

        $block.addClass(md_class);

        $.ajax({
            url: endpoint,
            type: 'POST',
            data: payload,
            success: function(data) { console.log(data) },
            error: function(data) { console.log(data) }
        })
    }

    resizeBlockMobile() {
        const $this = $(this);
        const $block = $this.closest(".block");
        const value = $this.val();
        const breakpoint = new Breakpoint(value);
        const endpoint = $('#blocks__container').data("resizemob");
        const mob_class = breakpoint.getMobileClass();
        const payload = {
            breakpoint: mob_class,
            buid: $block.attr('id')
        }

        $.ajax({
            url: endpoint,
            type: 'POST',
            data: payload,
            success: function(data) { console.log(data) },
            error: function(data) { console.log(data) }
        })
    }

    populateData(data) {
        const parsedData = JSON.parse(data);
        let $template = $(this.template);
        const $container = $('#blocks');

        for(var x = 0; x < parsedData.length; x++) {
            $container.append(`
                    <div id="${ parsedData[x].buid }" class="col-12 block mt-2 shadow-sm" data-priority="0">
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
                                        <select class="custom-select" name="desktop-breakpoint" onchange="javascript:window.block.resizeBlock.call(this)">
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
                                        <select class="custom-select" name="mobile-breakpoint" onchange="javascript:window.block.resizeBlockMobile.call(this)">
                                            <option value="1" selected>Full</option>
                                            <option value="2">Half</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            `)
        }
    }
}

class Breakpoint {
    constructor(givenMdClass) {
        this.givenMdClass = givenMdClass;
    }

    getMdClass() {
        let output;
        switch (this.givenMdClass) {
            case "1":
                output = "col-md-12";
                break;
            case "2":
                output = "col-md-6";
                break;
            case "3":
                output = "col-md-4";
                break;
            case "4":
                output = "col-md-3";
                break;
            case "5":
                output = "col-md-8";
                break;
            case "6":
                output = "col-md-9";
                break;
        }
        return output;
    }

    getMobileClass() {
        let output;
        switch (this.givenMdClass) {
            case "1":
                output = "col-12";
                break;
            case "2":
                output = "col-6";
                break;
        }
        return output;
    }
}


window.block = new Block();