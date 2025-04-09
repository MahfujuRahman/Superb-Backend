@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/css/tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/plugins/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
    <style>
        .bootstrap-tagsinput .badge {
            margin: 2px 2px !important;
        }
    </style>
@endsection

@section('page_title')
    Website Config
@endsection
@section('page_heading')
    SEO for HomePage
@endsection

@section('content')
<form class="needs-validation" method="POST" action="{{url('update/seo/homepage')}}" enctype="multipart/form-data">
    @csrf
    <div class="row">

        <div class="col-lg-6 col-xl-6">
            <div class="card" style="height: 630px;">
                <div class="card-body">
                    <h4 class="card-title mb-3">Search Engine Optimization for HomePage</h4>
                    <div class="row pt-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" id="meta_title" name="meta_title" value="{{$data->meta_title}}" class="form-control" placeholder="Enter Meta Title Here">
                                        <div class="invalid-feedback" style="display: block;">
                                            @error('meta_title')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="meta_keywords">Meta Keywords <small>("," Comma Separated)</small></label>
                                        <input type="text" id="meta_keywords" data-role="tagsinput" name="meta_keywords" value="{{$data->meta_keywords}}" class="form-control">
                                        <div class="invalid-feedback" style="display: block;">
                                            @error('meta_keywords')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea id="meta_description" name="meta_description" rows="5" class="form-control" placeholder="Write Meta Description Here">{{$data->meta_description}}</textarea>
                                        <div class="invalid-feedback" style="display: block;">
                                            @error('meta_description')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center pt-3">
                        <a href="{{url('/home')}}" style="width: 130px;" class="btn btn-danger d-inline-block text-white m-2" type="submit"><i class="mdi mdi-cancel"></i> Cancel</a>
                        <button class="btn btn-primary m-2" type="submit" style="width: 140px;"><i class="fas fa-save"></i> Update Info</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Meta Open Graph for HomePage</h4>
                    <div class="row pt-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="meta_og_title">Meta OG Title</label>
                                        <input type="text" id="meta_og_title" name="meta_og_title" value="{{$data->meta_og_title}}" class="form-control" placeholder="Enter Meta OG Title Here">
                                        <div class="invalid-feedback" style="display: block;">
                                            @error('meta_og_title')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="meta_og_description">Meta OG Description</label>
                                        <textarea id="meta_og_description" name="meta_og_description" rows="5" class="form-control" placeholder="Write Meta OC Description Here">{{$data->meta_og_description ? $data->meta_og_description : ''}}</textarea>
                                        <div class="invalid-feedback" style="display: block;">
                                            @error('meta_og_description')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="meta_og_image">Meta OG Image</label>
                                        <input type="file" name="meta_og_image" class="dropify" data-height="150" data-max-file-size="1M" accept="image/*"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center pt-3">
                        <a href="{{url('/home')}}" style="width: 130px;" class="btn btn-danger d-inline-block text-white m-2" type="submit"><i class="mdi mdi-cancel"></i> Cancel</a>
                        <button class="btn btn-primary m-2" type="submit" style="width: 140px;"><i class="fas fa-save"></i> Update Info</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection


@section('footer_js')
    <script src="{{url('assets')}}/plugins/dropify/dropify.min.js"></script>
    <script src="{{url('assets')}}/pages/fileuploads-demo.js"></script>
    <script src="{{url('assets')}}/js/tagsinput.js"></script>

    <script>
        @if($data->meta_og_image && file_exists(public_path($data->meta_og_image)))
            $(".dropify-preview").eq(0).css("display", "block");
            $(".dropify-clear").eq(0).css("display", "block");
            $(".dropify-filename-inner").eq(0).html("{{$data->meta_og_image}}");
            $("span.dropify-render").eq(0).html("<img src='{{url($data->meta_og_image)}}'>");
        @endif
    </script>

@endsection
