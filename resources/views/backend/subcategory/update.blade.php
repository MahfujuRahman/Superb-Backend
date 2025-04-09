@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page_title')
    Subcategory
@endsection
@section('page_heading')
    Update Subcategory
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Subcategory Update Form</h4>

                    <form class="needs-validation" method="POST" action="{{url('update/subcategory')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$subcategory->id}}">

                        <div class="form-group row">
                            <label for="colFormLabe0" class="col-sm-2 col-form-label">Select Category <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select name="category_id" class="form-control" id="colFormLabe0" required>
                                    @php
                                        echo App\Models\Category::getDropDownList('name', $subcategory->category_id);
                                    @endphp
                                </select>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('category_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{$subcategory->name}}" class="form-control" id="colFormLabel" placeholder="Subcategory Title" required>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Subcategory Icon</label>
                            <div class="col-sm-10">
                                <input type="file" name="icon" class="dropify" data-height="100" accept="image/*"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Subcategory Image</label>
                            <div class="col-sm-10">
                                <input type="file" name="image" class="dropify" data-height="100" accept="image/*"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="slug" class="col-sm-2 col-form-label">Slug <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="slug" value="{{$subcategory->slug}}" class="form-control" id="slug" placeholder="Subcategory Slug" required>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('slug')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabe0" class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select name="status" class="form-control" id="colFormLabe0" required>
                                    <option value="">Select One</option>
                                    <option value="1" @if($subcategory->status == 1) selected @endif>Active</option>
                                    <option value="0" @if($subcategory->status == 0) selected @endif>Inactive</option>
                                </select>
                                <div class="invalid-feedback" style="display: block;">
                                    @error('status')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabe0" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button class="btn btn-primary" type="submit">Update Subcategory</button>
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
        @if($subcategory->icon && file_exists(public_path($subcategory->icon)))
            $(".dropify-preview").eq(0).css("display", "block");
            $(".dropify-clear").eq(0).css("display", "block");
            $(".dropify-filename-inner").eq(0).html("{{$subcategory->icon}}");
            $("span.dropify-render").eq(0).html("<img src='{{url($subcategory->icon)}}'>");
        @endif

        @if($subcategory->image && file_exists(public_path($subcategory->image)))
            $(".dropify-preview").eq(0).css("display", "block");
            $(".dropify-clear").eq(0).css("display", "block");
            $(".dropify-filename-inner").eq(0).html("{{$subcategory->image}}");
            $("span.dropify-render").eq(0).html("<img src='{{url($subcategory->image)}}'>");
        @endif
    </script>
@endsection
