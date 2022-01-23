@extends('frontend.home.layout.app')
@section('title',!empty($privacy_data->seo_meta_title)?$privacy_data->seo_meta_title:'')
@section('content')
<section class="content-remove">
<section class="terms-banner  wow fadeIn" >
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>{{!empty($privacy_data->title)?$privacy_data->title:''}}</h2>						
            </div>
        </div>
    </div>
</section>
<section class="terms-text wow zoomIn slow"  >
    <div class="container">
        <div class="row">					
            <div class="col-lg-12">		
                <!--<h4>Header Name</h4>-->
                <p>{!! (!empty($privacy_data->description)?$privacy_data->description:'') !!}</p>
            </div>					
        </div>
    </div>
</section>
<input type="hidden" name="current_url">
</section>
@endsection
@push('custom-styles')
<meta property="og:title" content="{{ !empty($privacy_data->seo_meta_title)?$privacy_data->seo_meta_title:''}}">
<meta name="description" content="{{ !empty($privacy_data->seo_meta_description)?$privacy_data->seo_meta_description:'' }}">
<meta property="og:description" content="{{ !empty($privacy_data->seo_meta_description)?$privacy_data->seo_meta_description:'' }}">
@endpush