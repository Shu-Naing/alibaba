<h5 class="fw-bold mb-4">Add Products</h5>
        <div class="input-group rounded w-25 mb-3">
            <div>
                <!-- <div class="" id="distributedId"></div> -->
                <input type="hidden" id="distributedId" value="{{ $distribute->id }}">
                <input type="text" class="form-control" id="searchInput" data-id="{{ $distribute->from_outlet }}" placeholder="Search...">
                <div id="searchResults"></div>
            </div>

            <!-- <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
            <span class="input-group-text border-0" id="search-addon">
                <i class="bi bi-search"></i>
            </span> -->
        <!-- </div> -->

        <!-- <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                <span class="input-group-text border-0" id="search-addon">
                    <i class="bi bi-search"></i>
                </span> -->
        </div>

        <?php $total = 0; ?>
        <div id="show_dsProduct">
            @foreach ($distribute_products as $product)
                <?php
                $subtotal = $product->purchased_price * $product->quantity;
                $total += $subtotal;
                ?>
                <table class="table table-bordered text-center shadow rounded">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 30%;">Product Name</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Purchased Price</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle" style="text-align: left;">
                                {{ $product->product_name }}
                            </td>
                            <td class="align-middle text-center" style="text-align: left;">
                                {{ $product->item_code }}
                            </td>
                            <!-- <td class="align-middle"> 6Pcs + -</td> -->
                            <td class="align-middle">
                                <div class="qty-box border rounded">
                                    <div class="row gx-0">
                                        <div class="col">
                                            <div class="border p-2"><input type="number" class="number number-box" min="1" 
                                                    value="{{ $product->quantity }}" data-id="[{{ $product->id }}, {{ $product->variant_id }}, {{$variant_qty[$product->variant_id]}}]"></div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" id="increase-type" data-id="increase"
                                                onclick="increaseValue(this, {{ $product->id }}, {{ $product->variant_id }}, {{$variant_qty[$product->variant_id]}})" value="Increase Value">+
                                                <!-- <div id="loading-indicator">Loading...</div> -->
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" id="decrease-type" data-id="decrease"
                                                onclick="decreaseValue(this, {{ $product->id }} , {{ $product->variant_id }})" value="Decrease Value">-
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">{{ $product->purchased_price }}</td>
                            <td class="align-middle">{{ $product->subtotal }}</td>
                            <td class="align-middle"><a href="javascript:void(0)" class="text-danger"
                                    onclick="deleteDisValue({{ $product->id }})">Delete</a></td>
                        </tr>
                    </tbody>
                </table>
            @endforeach
        </div>

        <div class="d-flex gap-4 px-4 justify-content-end my-5">
            <div class="">
                <!-- <label for="remark" class="d-block mb-2">Remark</label>
                        <textarea name="remark" id="" cols="40" rows="4"></textarea> -->
                {!! Form::label('remark', 'Remark', ['class' => 'form-label']) !!}
                {!! Form::textarea('remark', $distribute->remark, [
                    'class' => 'form-control',
                    'id' => 'remark',
                    'cols' => '40',
                    'rows' => '4',
                ]) !!}
            </div>
            <div class="align-items-center d-flex">
                <h4 class="fw-bolder">Total Amount: <span id="total"
                        class="ms-3 inline-block text-blue">{{ $total }}</span></h4>
            </div>
        </div>

        <div class="text-center my-5">
            <!-- <a class="btn btn-red" href="{{ url()->previous() }}">Back</a> -->
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="distribute" class="btn btn-blue ms-2">Save</button>
        </div>