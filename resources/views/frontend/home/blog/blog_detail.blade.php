@extends('frontend.home.layout.app')
@section('title',$blog_detail_data->seo_meta_title)
@section('content')
<section class="content-remove">
<section class="aboutus-banner blog-detail  wow fadeIn" >
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="blue-back">
                </div>
                <div class="white-top" style="background:  linear-gradient(180deg, rgba(0,0,0,0.00) 0%, rgba(0,0,0,0.50) 100%),url({{ url('public/uploads/blog_image/'.$blog_detail_data->image) }}) no-repeat center center; background-size: cover;">
                    <div>
                        <h4>{{ $blog_detail_data->title }}</h4>
                        <p>{{date('d M y', strtotime($blog_detail_data->created_date)) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog-text wow zoomIn slow"  >
    <div class="container">
        <div class="row">					
            <div class="col-lg-12">		
               <p>{!! $blog_detail_data->description !!} </p>

            </div>					
        </div>
    </div>
</section>
<input type="hidden" name="current_url" value={{ url()->current() }}>
</section>
@endsection
@push('custom-styles')
<meta property="og:title" content="{{ $blog_detail_data->seo_meta_title }}">
<meta name="description" content="{{ $blog_detail_data->seo_meta_description }}">
<meta property="og:description" content="{{ $blog_detail_data->seo_meta_description }}">
<meta property="og:image" content="{{ url('public/uploads/blog_image/'.$blog_detail_data->image) }}">
@endpush
@push('custom-scripts')
<script async src="https://platform-api.sharethis.com/js/sharethis.js#property=5d0097614351e90012650407&product="sticky-share-buttons"></script>
@endpush