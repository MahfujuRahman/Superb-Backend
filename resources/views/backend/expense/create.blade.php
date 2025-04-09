@extends('backend.master')

@section('header_css')
    <link href="{{ url('assets') }}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/plugins/selecttree/select2totree.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/css/tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .select2-selection {
            height: 34px !important;
            border: 1px solid #ced4da !important;
        }

        .select2 {
            width: 100% !important;
        }

        .bootstrap-tagsinput .badge {
            margin: 2px 2px !important;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
    </style>
@endsection

@section('page_title')
    Expense
@endsection
@section('page_heading')
    Add an expense
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Expense</h4>

                    <form class="needs-validation" method="POST" action="{{ url('save/new/expense') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">

                                    <div class="col-lg-6">


                                        <div class="form-group">
                                            <label for="expense_date">Expense Date<span class="text-danger">*</span></label>
                                            <input type="date" id="expense_date" name="expense_date" maxlength="255"
                                                class="form-control" placeholder="Enter expense date Here"
                                                value="{{ date('Y-m-d') }}">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('expense_date')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="form-group">
                                            <label for="account_id">Expense Account<span
                                                    class="text-danger">*</span></label>
                                            <select id="expense_account_id" name="expense_account_id" class="form-control">
                                                <option></option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}"
                                                        {{ old('expense_account_id') == $account->id ? 'selected' : '' }}>
                                                        {{ $account->account_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('expense_account_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="form-group" style="margin-bottom: 30px;">
                                            <label for="expense_account_id">Expense Account<span class="text-danger">*</span></label>
                                            <select id="expense_account_id" name="expense_account_id" class="form-control">
                                                <option></option>
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('expense_account_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="form-group">
                                            <label for="asset_cash_account_id">Asset/Cash Account<span
                                                    class="text-danger">*</span></label>
                                            <select id="asset_cash_account_id" name="asset_cash_account_id"
                                                class="form-control">
                                                <option></option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}"
                                                        {{ old('asset_cash_account_id') == $account->id ? 'selected' : '' }}>
                                                        {{ $account->account_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('asset_cash_account_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div> --}}


                                        <div class="form-group" style="margin-bottom: 30px;">
                                            <label for="asset_cash_account_id">Asset/Cash Account<span class="text-danger">*</span></label>
                                            <select id="asset_cash_account_id" name="asset_cash_account_id" class="form-control">
                                                <option></option>
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('asset_cash_account_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="expense_category_id">Expense Category<span
                                                    class="text-danger">*</span></label>
                                            <select id="expense_category_id" name="expense_category_id"
                                                class="form-control">
                                                <option></option>
                                                @foreach ($expense_categories as $expense_category)
                                                    <option value="{{ $expense_category->id }}"
                                                        {{ old('expense_category_id') == $expense_category->id ? 'selected' : '' }}>
                                                        {{ $expense_category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('expense_category_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="expense_category_id">
                                                Payment Type<span class="text-danger">*</span>
                                            </label>
                                            <select id="payment_type_id" name="payment_type_id" class="form-control">
                                                <option></option>
                                                @foreach ($payment_types as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('payment_type_id') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->payment_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('payment_type_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>             


                                        <div class="form-group">
                                            <label for="expense_for">Expense For <span class="text-danger">*</span></label>
                                            <input type="text" id="expense_for" name="expense_for" class="form-control"
                                                placeholder="Expense for What">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('expense_for')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="expense_amt">Expense Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="expense_amt" name="expense_amt" maxlength="60"
                                                class="form-control" placeholder="Enter expense amount here">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('expense_amt')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="reference_no">Reference No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="reference_no" name="reference_no" maxlength="60"
                                                class="form-control" placeholder="Enter reference number here">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('reference_no')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>


                                        {{-- <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="text" id="email" name="email" maxlength="100"
                                                class="form-control" placeholder="Enter email Here">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('email')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <textarea id="note" name="note" class="form-control" placeholder="Enter Description Here"></textarea>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('note')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="row">
                            <div class="col--3">
                                <div class="form-group text-center pt-3">
                                    <a href="{{ url('view/all/expense') }}" style="width: 130px;"
                                        class="btn btn-danger d-inline-block text-white m-2" type="submit"><i
                                            class="mdi mdi-cancel"></i> Cancel</a>
                                    <button class="btn btn-primary m-2" style="width: 130px;" type="submit"><i
                                            class="fas fa-save"></i> Save </button>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer_js')
    <script src="{{ url('assets') }}/plugins/dropify/dropify.min.js"></script>
    <script src="{{ url('assets') }}/pages/fileuploads-demo.js"></script>
    <script src="{{ url('assets') }}/plugins/select2/select2.min.js"></script>
    <script src="{{ url('assets') }}/plugins/selecttree/select2totree.js" /></script>
    <script src="{{ url('assets') }}/js/tagsinput.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>



    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // $('#description').summernote({
        //     placeholder: 'Write Description Here',
        //     tabsize: 2,
        //     height: 350
        // });
    </script>

    <script>
        $(document).ready(function() {
          

            $('#expense_category_id').select2({
                placeholder: 'Select Expense Category',
                allowClear: true,
                width: '100%'
            });

            $('#payment_type_id').select2({
                placeholder: 'Select Payment Type',
                allowClear: true,
                width: '100%'
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('GetJsonAcAccountExpense') }}", // Define this route in web.php
                type: "GET",
                success: function(response) {
                    console.log("response.data", response.data);

                    $("#expense_account_id").select2ToTree({
                        treeData: {
                            dataArr: response
                        },
                        maximumSelectionLength: 3
                    }).select2ToTree({
                        placeholder: "Select Expense Account",
                        allowClear: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching account data:", error);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('GetJsonAcAccount') }}", // Define this route in web.php
                type: "GET",
                success: function(response) {
                    console.log("response.data", response.data);

                    $("#asset_cash_account_id").select2ToTree({
                        treeData: {
                            dataArr: response
                        },
                        maximumSelectionLength: 3
                    }).select2ToTree({
                        placeholder: "Select Asset/Cash Account",
                        allowClear: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching account data:", error);
                }
            });
        });
    </script>
@endsection
