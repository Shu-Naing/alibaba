@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Pos</h4>
            <div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="row">
                    @foreach ($variations as $variation)
                       
                        <div class="col-lg-4">
                            <div class="card p-2">
                                <img src="{{ asset('storage/' .$variation->image) }}" alt="">
                                <span class="fw-bolder text-center mt-2 pb-0">{{ $variation->product->product_name }}</span>
                                <small class="fw-bolder text-center">{{ $variation->select }} : {{ $variation->value }}</small>
                                <small class="fw-bolder text-center">Point : {{ $variation->points }}</small>
                                <small class="fw-bolder text-center">Ticket : {{ $variation->tickets }}</small>
                                <button class="btn btn-primary btn-sm mt-2" onclick="addPosItem('<?php echo $variation->id ?>')">Added</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header fw-bolder">New Invoice</div>
                    <div class="card-body">

                        <div class="card mb-3 pos-item p-2">
                            <div class="row g-0 d-flex align-items-center">
                              <div class="col-md-3">
                                <img src="{{ asset('storage/variations/3TOTy8m1bEd0RNN4hcj0wgBR1ZafiJtdQUPjy4PT.jpg') }}" class="img-fluid rounded-start" alt="...">
                              </div>
                              <div class="col-md-8">
                                <div>
                                    <span class="fw-bolder d-block">One Piece Figure</span>
                                    <small class="fw-bold">points : 100</small>
                                </div>
                              </div>
                            </div>
                          </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .pos-item img{
            width: 60px;
            height: 60px;
        }
    </style>

<script>

    function addPosItem(variationId){

        $.ajax({
        url: '{{ route("productdata.get", ":variationId") }}'.replace(':variationId', variationId),
        type: 'GET',
        success: function(data) {
            console.log(data);
            var template = `
                <div class="card mb-3 pos-item p-2">
                <div class="row g-0 d-flex align-items-center">
                    <div class="col-md-3">
                    <img src="{{ asset('storage/variations/3TOTy8m1bEd0RNN4hcj0wgBR1ZafiJtdQUPjy4PT.jpg') }}" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                    <div>
                        <span class="fw-bolder d-block">One Piece Figure</span>
                        <small class="fw-bold">points : 100</small>
                    </div>
                    </div>
                </div>
                </div>`;
            }
        },
        error: function(error) {
            console.error('Error:', error);
        }
        });
    }

    function posItemTemplate(){
        var template = `
                        <div class="card mb-3 pos-item p-2">
                        <div class="row g-0 d-flex align-items-center">
                            <div class="col-md-3">
                            <img src="{{ asset('storage/variations/3TOTy8m1bEd0RNN4hcj0wgBR1ZafiJtdQUPjy4PT.jpg') }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                            <div>
                                <span class="fw-bolder d-block">One Piece Figure</span>
                                <small class="fw-bold">points : 100</small>
                            </div>
                            </div>
                        </div>
                        </div>`;
            }

</script>
@endsection
