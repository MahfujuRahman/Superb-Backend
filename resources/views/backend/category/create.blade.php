@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page_title')
    Category
@endsection
@section('page_heading')
    Add New Category
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Category Create Form</h4>

                    <form class="needs-validation" method="POST" action="{{url('save/new/category')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="colFormLabel" placeholder="Category Name" required>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Category Icon</label>
                            <div class="col-sm-10">
                                <input type="file" name="icon" class="dropify" data-height="100" data-max-file-size="1M" accept="image/*"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Category Banner</label>
                            <div class="col-sm-10">
                                <input type="file" name="banner_image" class="dropify" data-height="200" data-max-file-size="1M" accept="image/*"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="featured" class="col-sm-2 col-form-label">Feature Category</label>
                            <div class="col-sm-3">
                                <select name="featured" class="form-control" id="featured">
                                    <option value="">Select One</option>
                                    <option value="1">Yes Featured</option>
                                    <option value="0">Not Featured</option>
                                </select>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('featured')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="show_on_navbar" class="col-sm-2 col-form-label">Show On Navbar</label>
                            <div class="col-sm-3">
                                <select name="show_on_navbar" class="form-control" id="show_on_navbar">
                                    <option value="">Select One</option>
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('show_on_navbar')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="form-group row pt-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <button class="btn btn-primary" type="submit">Save Category</button>
                            </div>
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
    <script>
        // var route_prefix = "/filemanager";
        // {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/stand-alone-button.js')) !!}

        // $('#lfm').filemanager('image', {
        //     prefix: route_prefix
        // });

        // var loadFile = function(event) {
        //     var output = document.getElementById('output');
        //     output.src = URL.createObjectURL(event.target.files[0]);
        //     output.onload = function() {
        //         URL.revokeObjectURL(output.src) // free memory
        //     }
        // };
    </script>
@endsection
