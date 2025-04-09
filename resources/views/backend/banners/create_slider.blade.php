@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page_title')
    Slider
@endsection
@section('page_heading')
    Add New Slider
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Slider Create Form</h4>

                    <form class="needs-validation" method="POST" action="{{url('save/new/slider')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="slider">Slider Image <span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="dropify" data-height="262" data-max-file-size="1M" accept="image/*" required/>
                                </div>

                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="sub_title">Sub Title</label>
                                            <input type="text" name="sub_title" id="sub_title" class="form-control" placeholder="Write Sub Title Here"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Write Title Here"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control" placeholder="Write Description Here"/>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="text_position">Text Position</label>
                                            <select class="form-control" name="text_position" id="text_position">
                                                <option value="">Select Option</option>
                                                <option value="left">Left</option>
                                                <option value="right">Right</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="link">Slider Link</label>
                                            <input type="text" name="link" class="form-control" id="link" placeholder="https://">
                                            <div class="invalid-feedback" style="display: block;">
                                                @error('link')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="btn_text">Button Text</label>
                                            <input type="text" name="btn_text" id="btn_text" class="form-control" placeholder="ex. New Collection"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="title">Button link</label>
                                            <input type="text" name="btn_link" class="form-control" id="btn_link" placeholder="https://">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12 text-center">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit"><i class="feather-save"></i> Save Slider</button>
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
@endsection
