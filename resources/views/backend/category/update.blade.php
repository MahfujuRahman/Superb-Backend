@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection


@section('page_title')
    Category
@endsection
@section('page_heading')
    Update Category
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Category Update Form</h4>

                    <form class="needs-validation" method="POST" action="{{url('update/category')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$category->id}}">

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" value="{{$category->name}}" id="colFormLabel" placeholder="Category Name" required>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Change Icon</label>
                            <div class="col-sm-10">
                                <input type="file" name="icon" class="dropify" data-height="100" data-max-file-size="1M" accept="image/*"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Change Banner</label>
                            <div class="col-sm-10">
                                <input type="file" name="banner_image" class="dropify" data-height="200" data-max-file-size="1M" accept="image/*"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabe0" class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <select name="status" class="form-control" id="colFormLabe0" required>
                                    <option value="">Select One</option>
                                    <option value="1" @if($category->status == 1) selected @endif>Active</option>
                                    <option value="0" @if($category->status == 0) selected @endif>Inactive</option>
                                </select>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('status')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="featured" class="col-sm-2 col-form-label">Feature</label>
                            <div class="col-sm-3">
                                <select name="featured" class="form-control" id="featured">
                                    <option value="">Select One</option>
                                    <option value="1" @if($category->featured == 1) selected @endif>Yes Featured</option>
                                    <option value="0" @if($category->featured == 0) selected @endif>Not Featured</option>
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
                                <select name="show_on_navbar" class="form-control" id="show_on_navbar" required>
                                    <option value="">Select One</option>
                                    <option value="1" @if($category->show_on_navbar == 1) selected @endif>Yes</option>
                                    <option value="0" @if($category->show_on_navbar == 0) selected @endif>No</option>
                                </select>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('show_on_navbar')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Slug</label>
                            <div class="col-sm-3">
                                <input type="text" name="slug" class="form-control" value="{{$category->slug}}" required/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabe0" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Update Category</button>
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
    <script src="{{url('assets')}}/plugins/dropify/dropify.min.js"></script>
    <script src="{{url('assets')}}/pages/fileuploads-demo.js"></script>
    <script>
        @if($category->icon && file_exists(public_path($category->icon)))
            $(".dropify-preview").eq(0).css("display", "block");
            $(".dropify-clear").eq(0).css("display", "block");
            $(".dropify-filename-inner").eq(0).html("{{$category->icon}}");
            $("span.dropify-render").eq(0).html("<img src='{{url($category->icon)}}'>");
        @endif

        @if($category->banner_image && file_exists(public_path($category->banner_image)))
            $(".dropify-preview").eq(1).css("display", "block");
            $(".dropify-clear").eq(1).css("display", "block");
            $(".dropify-filename-inner").eq(1).html("{{$category->banner_image}}");
            $("span.dropify-render").eq(1).html("<img src='{{url($category->banner_image)}}'>");
        @endif
    </script>
@endsection
