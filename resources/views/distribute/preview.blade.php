<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribute Product Preview</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 1cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }


        th {
            height: 40px;
        }

        td {
            height: 30px;
            text-align: center;
        }

        .text-right {
            text-align: left;
            padding: 0px 20px;
        }

        .company-logo {
            height: 70px;
            width: 200px;
        }

        .logo-style {
            padding: 20px;
            text-align: start;
            width: 200px;
        }



        .footer-section {
            display: flex;
            justify-content: space-between;

        }

        .first-footer {
            padding: 30px;
        }

        .last-footer {
            padding: 0px 30px;
        }

        .tb-border-none {
            border: none;
        }

        .print-btn{
            background-color: #c22926;
            color:white;
            border: none;
            padding: 7px 15px;
            text-decoration: none;
            margin-right: 5px;
        }

        .back-btn{
            background-color: blue;
            color:white;
            border: none;
            padding: 7px 15px;
            text-decoration: none;
        }

        .top-box {
            display: flex;
            justify-content: end;
            margin: 10px 0px;
        }

        @media print
        {    
            .print-btn,.back-btn
            {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div id="print-content">
        <table>
            <thead>
                <tr>
                    <th class="logo-style tb-border-none">
                        <img src="{{ asset('images/logo_1.png') }}" alt="" class="company-logo">
                    </th>
                    <th class="tb-border-none text-left">
                        <div class="top-box">
                            <a href="javascript:void(0);" onclick="printTable()" class="print-btn">Print</a>
                            <a href="{{ url()->previous() }}" class="back-btn">Back</a>
                        </div>
                    </th>
                    <!-- <th>
                        <p>Issued Date: <br> {{ isset($distribute->date) ? $distribute->date : '' }}</p>
                    </th>
                    <th>
                        <p>{{ isset($distribute->reference_No) ? $distribute->reference_No : '' }}</p>
                    </th>
                    <th>
                        <p>Revision Date: <br> {{ isset($distribute->date) ? $distribute->date : '' }}</p>
                    </th> -->
                </tr>
            </thead>
        </table>
        <table>
            <thead>
                <tr>
                    <th colspan="7">
                        <h2>Stock Transfer Form</h2>
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">
                        <p>To: {{ get_outlet_name($distribute->to_outlet) }}</p>
                        <p>From: {{ get_outlet_name($distribute->from_outlet) }}</p>
                    </th>
                    <th colspan="3" class="text-right">
                        <p>Voucher No: {{ str_replace(' ','-',get_outlet_name($distribute->from_outlet)) . '-' . $distribute->reference_No }}</p>
                        <p>Date: {{ $distribute->date }}</p>
                    </th>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th>UOM</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $fixed_min_row_count = 14;
                    $distribute_product_count = count($distribute->distribute_porducts);
                    $left_count = $fixed_min_row_count - $distribute_product_count;
                    $no = 1;
                    $quantitySum = 0;
                    $subtotalSum = 0;
                @endphp

                @foreach ($distribute->distribute_porducts as $distribute_product)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $distribute_product->variant->item_code }}</td>
                        <td>{{ $distribute_product->variant->product->unit->name }}</td>
                        <td>{{ $distribute_product->quantity }}</td>
                        <td class="text-left">{{ $distribute_product->purchased_price }}</td>
                        <td class="text-left">{{ $distribute_product->subtotal }}</td>
                        <td>{{ $distribute->remark }}</td>
                    </tr>
                    @php
                        $quantitySum += $distribute_product->quantity;
                        $subtotalSum += $distribute_product->subtotal;
                    @endphp
                @endforeach
                @for ($i = 1; $i < $left_count; $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
                    <tr>                          
                        <td colspan="3" class="text-end">Total</td>
                        <td>{{$quantitySum}}</td>
                        <td></td>
                        <td>{{$subtotalSum}}</td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
        <div class="first-footer footer-section">
            <div>Prepared By</div>
            <div>Acknowledged By</div>
            <div>Received By</div>
        </div>

        <div class="last-footer footer-section">
            <div>Alibaba Amusement</div>
            <div>ALB-IVT-FRM-007-01</div>
        </div>
    </div>

    

    <script>
        function printTable() {
            var printContent = document.getElementById("print-content").innerHTML;
            var originalContent = document.body.innerHTML;

            // Set the body content to the table content
            document.body.innerHTML = printContent;

            // Print the table
            window.print();

            // Restore the original content
            document.body.innerHTML = originalContent;
        }
    </script>
</body>

</html>
