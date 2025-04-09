@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page_title')
    Promo Code
@endsection
@section('page_heading')
    Add New Promo Code
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Promo Code Entry Form</h4>

                    <form class="needs-validation" method="POST" action="{{url('save/promo/code')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="icon">Promo Icon</label>
                                    <input type="file" name="icon" class="dropify" data-height="250" data-max-file-size="1M" accept="image/*"/>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="25% OFF Promo" required>
                                    <div class="invalid-feedback" style="display: block;">
                                        @error('title')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Short Description</label>
                                    <textarea id="description" rows="8" name="description" class="form-control" placeholder="During this sale, we're offering 25% OFF this summary. Make sure you don't miss it."></textarea>
                                    <div class="invalid-feedback" style="display: block;">
                                        @error('description')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="effective_date">Effective Date <span class="text-danger">*</span></label>
                                            <input type="text" id="effective_date" name="effective_date" class="form-control" placeholder="d/m/Y" required>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('effective_date')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="expire_date">Expiry Date <span class="text-danger">*</span></label>
                                            <input type="text" id="expire_date" name="expire_date" class="form-control" placeholder="d/m/Y" required>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('expire_date')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="type" required>
                                                <option value="">Select One</option>
                                                <option value="1">Amount (৳) Based</option>
                                                <option value="2">Percentage (%) Based</option>
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('type')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="value">Value <span class="text-danger">*</span></label>
                                            <input type="number" id="value" name="value" class="form-control" required>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('value')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="minimum_order_amount">Minimum Order Amount</label>
                                    <input type="number" id="minimum_order_amount" name="minimum_order_amount" class="form-control">
                                    <div class="invalid-feedback" style="display: block;">
                                        @error('minimum_order_amount')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="code">Code <span class="text-danger">*</span></label>
                                    <input type="text" id="code" name="code" class="form-control" placeholder="SNNY22" required>
                                    <div class="invalid-feedback" style="display: block;">
                                        @error('code')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group text-center pt-3">
                            <button class="btn btn-primary" type="submit">Save Promo Code</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script src="{{url('assets')}}/plugins/dropify/dropify.min.js"></script>
    <script src="{{url('assets')}}/pages/fileuploads-demo.js"></script>
    <script src="{{url('assets')}}/js/jquery.datetimepicker.full.min.js"></script>
    <script>
        $("#effective_date").datetimepicker({
            timepicker: false,
            // minDate: "-1970/01/01",
            format: "d/m/Y",
        });

        $("#expire_date").datetimepicker({
            timepicker: false,
            // minDate: "-1970/01/01",
            format: "d/m/Y",
        });
    </script>
@endsection

