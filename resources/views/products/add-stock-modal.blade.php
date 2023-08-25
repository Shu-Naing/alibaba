<div class="modal fade" id="addStockModal{{ $variation->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header modal-bg">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Stock</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.add-stock', $variation->id) }}" method="POST"
                    id="addStockForm{{ $variation->id }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="item_code">Item Code</label>
                            <input type="text" class="form-control" value="{{ $variation->item_code }}" disabled>
                        </div>
                        <div class="col-lg-6">
                            <label for="points">New Point</label>
                            <input type="number" class="form-control" name="points">
                        </div>
                        <div class="col-lg-6 my-2">
                            <label for="tickets">New Ticket</label>
                            <input type="number" class="form-control" name="tickets">
                        </div>
                        <div class="col-lg-6 my-2">
                            <label for="kyat">New Kyat</label>
                            <input type="number" class="form-control" name="kyat">
                        </div>
                        <div class="col-lg-6 my-2">
                            <label for="purchased_price">New Purchased Price</label>
                            <input type="number" class="form-control" name="purchased_price">
                        </div>
                        <div class="col-lg-6 my-2">
                            <label for="new_qty">New Qty</label>
                            <input type="number" class="form-control" name="new_qty">
                        </div>
                        <div class="col-lg-6 my-2">
                            <label for="grn_no">Grn No</label>
                            <input type="text" class="form-control" name="grn_no">
                        </div>
                        <div class="col-lg-6 my-2">
                            <label for="received_date">Received Date</label>
                            <input type="date" class="form-control" name="received_date">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-muted text-white px-4 py-2 btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn delmodal-comfirm-btn cs-bg-primary text-white px-4 py-2 btn-sm"
                    onclick="addStockBtn('<?php echo $variation->id; ?>')">Add</button>
            </div>
        </div>
    </div>
</div>
