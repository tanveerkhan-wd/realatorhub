@extends('frontend.home.layout.app')
@section('title',$singleData['about_meta_seo_title'])
@section('content')
<!-- 
View File for Front->About us Page
@package    Laravel
@subpackage View
@since      1.0
-->
<section class="about_header section">
        <!-- <div class="bg_animation about_header_vector1 animation rotateleft"><img src="assets/images/vector_1.png"></div>
        <div class="bg_animation about_header_vector2 animation rotateright"><img src="assets/images/vector_2.png"></div>
        <div class="bg_animation about_header_vector3 animation rotateleft"><img src="assets/images/vector_1.png"></div>
        <div class="bg_animation about_header_vector4 animation rotateright"><img src="assets/images/vector_4.png"></div> -->
    <div class="container text-center">
        <div class="row">
            <div class="col-xl-2"></div>
            <div class="col-xl-8 wow zoomIn">
                <h2 class="about_title">{{ $singleData['about_believe_title'] }}</h2>
                <h4 class="about_subtitle">{{ $singleData['about_beleive_title1'] }}</h4>
                <p>{{ $singleData['about_believe_description'] }}</p>	
            </div>
            <div class="col-xl-2"></div>
        </div>
    </div>
</section>
<section class="section our_mission_section">
    <div class="bg_animation round animation zoomin"><img src="{{ url('public/') }}/assets/images/shape4.png"></div>
    <div class="bg_animation vector3 animation movexy"><img src="{{ url('public/') }}/assets/images/shape5.png"></div>
    <div class="container text-center">
        <div class="row">
            <div class="col-xl-2"></div>
            <div class="col-xl-8 wow zoomIn">
                <h2 class="about_title">{{ $singleData['about_mission_title'] }}</h2>
                <p>{{ $singleData['about_mission_desctiption1'] }}</p>
                <p>{{ $singleData['about_mission_desctiption2'] }}</p>
            </div>
            <div class="col-xl-2"></div>
        </div>
    </div>
</section>

@endsection
@push('custom-styles')
<meta property="og:title" content="{{ $singleData['about_meta_seo_title'] }}">
<meta name="description" content="{{ $singleData['about_meta_description'] }}">
<meta property="og:description" content="{{ $singleData['about_meta_description'] }}">
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
@endpush
