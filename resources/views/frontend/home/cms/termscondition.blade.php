@extends('frontend.home.layout.app')
@section('title',$terms_data->seo_meta_title)
@section('content')
<section class="content-remove">
    <section class="terms-banner  wow fadeIn" >
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>{{!empty($terms_data->title)?$terms_data->title:''}}</h2>						
                </div>
            </div>
        </div>
    </section>
    <section class="terms-text wow zoomIn slow"  >
        <div class="container">
            <div class="row">					
                <div class="col-lg-12">		
                    <!--<h4>Header Name</h4>-->
                    <p>{!! !empty($terms_data->description)?$terms_data->description:'' !!}</p>
                </div>					
            </div>
        </div>
    </section>
    <input type="hidden" name="current_url">
</section>
@endsection
@push('custom-styles')
<meta property="og:title" content="{{ !empty($terms_data->seo_meta_title)?$terms_data->seo_meta_title:''}}">
<meta name="description" content="{{ !empty($terms_data->seo_meta_description)?$terms_data->seo_meta_description:'' }}">
<meta property="og:description" content="{{ !empty($terms_data->seo_meta_description)?$terms_data->seo_meta_description:'' }}">
@endpush