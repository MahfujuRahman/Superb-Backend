@extends('backend.master')

@section('header_css')
    <link href="{{ url('assets') }}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
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
    Expense Category
@endsection
@section('page_heading')
    Add a Expense Category
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Expense Category</h4>

                    <form class="needs-validation" method="POST" action="{{ url('save/new/expense-category') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-12">

                                <div class="row">


                                    <div class="col-lg-6">

                                        {{-- <div class="form-group">
                                            <label for="customer_category_id">Payment Type <span
                                                    class="text-danger">*</span></label>
                                            <select id="customer_category_id" name="customer_category_id"
                                                class="form-control">                                                
                                                <option></option>
                                                @foreach ($customer_categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('customer_category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('customer_category_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        {{-- <div class="form-group">
                                            <label for="customer_source_type_id">Customer Source Type<span
                                                    class="text-danger">*</span></label>
                                            <select id="customer_source_type_id" name="customer_source_type_id"
                                                class="form-control">
                                                <option></option>
                                                @foreach ($customer_source_types as $customer_source_type)
                                                    <option value="{{ $customer_source_type->id }}"
                                                        {{ old('customer_source_type_id') == $customer_source_type->id ? 'selected' : '' }}>
                                                        {{ $customer_source_type->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('customer_source_type_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        {{-- <div class="form-group">
                                            <label for="reference_id">Reference By<span class="text-danger">*</span></label>
                                            <select id="reference_id" name="reference_id" class="form-control">
                                                <option></option>
                                                @foreach ($users as $reference)
                                                    <option value="{{ $reference->id }}"
                                                        {{ old('reference_id') == $reference->id ? 'selected' : '' }}>
                                                        {{ $reference->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('reference_id')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div> --}}


                                        <div class="form-group">
                                            <label for="category_name">Category Name <span class="text-danger">*</span></label>
                                            <input type="text" id="category_name" name="category_name"
                                                class="form-control" placeholder="Enter category name Here">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('category_name')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="category_code">Category Code <span class="text-danger">*</span></label>
                                            <input type="text" id="category_code" name="category_code" maxlength="60"
                                                class="form-control" placeholder="Enter category code here">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('category_code')
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
                                            <label for="description">Description</label>
                                            <textarea id="description" name="description" class="form-control" placeholder="Enter Description Here"></textarea>
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('description')
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
                                    <a href="{{ url('view/all/expense-category') }}" style="width: 130px;"
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
            $('#customer_category_id').select2({
                placeholder: 'Select Customer Category',
                allowClear: true,
                width: '100%'
            });

            $('#customer_source_type_id').select2({
                placeholder: 'Select Customer Source Type',
                allowClear: true,
                width: '100%'
            });

            $('#reference_id').select2({
                placeholder: 'Select Reference',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
