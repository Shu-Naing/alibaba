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
                            <input type="text" class="form-control" value="{{ $variation->item_code }}" disabled>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" placeholder="Enter New Point" name="points">
                        </div>
                        <div class="col-lg-6 my-2">
                            <input type="text" class="form-control" placeholder="Enter New Ticket" name="tickets">
                        </div>
                        <div class="col-lg-6 my-2">
                            <input type="text" class="form-control" placeholder="Enter New Kyat" name="kyat">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" placeholder="Enter New Purchased Price"
                                name="purchased_price">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" placeholder="Enter New Qty" name="new_qty">
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
