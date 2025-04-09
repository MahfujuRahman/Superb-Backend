@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page_title')
    Slider
@endsection
@section('page_heading')
    Update Slider
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Slider Update Form</h4>

                    <form class="needs-validation" method="POST" action="{{url('update/slider')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="slug" value="{{$data->slug}}">

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="slider">Slider Image <span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="dropify" data-height="262" data-max-file-size="1M" accept="image/*"/>
                                </div>
                                <div class="form-group">
                                    <label for="colFormLabe0">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" id="colFormLabe0" required>
                                        <option value="">Select One</option>
                                        <option value="1" @if($data->status == 1) selected @endif>Active</option>
                                        <option value="0" @if($data->status == 0) selected @endif>Inactive</option>
                                    </select>
                                    <div class="invalid-feedback" style="display: block;">
                                        @error('status')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="sub_title">Sub Title</label>
                                            <input type="text" name="sub_title" value="{{$data->sub_title}}" id="sub_title" class="form-control" placeholder="Write Sub Title Here"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" value="{{$data->title}}" id="title" class="form-control" placeholder="Write Title Here"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" value="{{$data->description}}" id="description" class="form-control" placeholder="Write Description Here"/>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="text_position">Text Position</label>
                                            <select class="form-control" name="text_position" id="text_position">
                                                <option value="">Select Option</option>
                                                <option value="left" @if($data->text_position == 'left') selected @endif>Left</option>
                                                <option value="right" @if($data->text_position == 'right') selected @endif>Right</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="link">Slider Link</label>
                                            <input type="text" name="link" value="{{$data->link}}" class="form-control" id="link" placeholder="https://">
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
                                            <input type="text" name="btn_text" value="{{$data->btn_text}}" id="btn_text" class="form-control" placeholder="ex. New Collection"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="title">Button link</label>
                                            <input type="text" name="btn_link" value="{{$data->btn_link}}" class="form-control" id="btn_link" placeholder="https://">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12 text-center">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit"><i class="feather-save"></i> Update Slider</button>
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
        @if($data->image && file_exists(public_path($data->image)))
            $(".dropify-preview").eq(0).css("display", "block");
            $(".dropify-clear").eq(0).css("display", "block");
            $(".dropify-filename-inner").eq(0).html("{{$data->image}}");
            $("span.dropify-render").eq(0).html("<img src='{{url($data->image)}}'>");
        @endif
    </script>
@endsection
