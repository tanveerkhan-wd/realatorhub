@extends('frontend.home.layout.app')
@section('title',$faq_meta['faq_meta_title'])
@section('content')
<section class="content-remove">
    <section class="terms-banner  wow fadeIn" >
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>FAQs</h2>
                </div>
            </div>
        </div>
    </section>
    <section class="faq-list wow zoomIn slow" >
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h5>Have Questions? <br>We have Answer.</h5>
                </div>
                <div class="col-lg-12">
                    <div class="accordions">
                          @foreach($get_faqs as $key => $value)
                            <div class="accordions_title"><span></span><h3>{{ $value['title'] }}</h3></div>
                            <div class="accordions_content">
                                <p>{!! $value['description'] !!}</p>
                            </div>
                            @endforeach
                    </div>
                </div>					
            </div>
        </div>
    </section>
    <input type="hidden" name="current_url">
</section>
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{ url('public/js/home/faq/faq.js') }}"></script>
@endpush
@push('custom-styles')
<meta property="og:title" content="{{ $faq_meta['faq_meta_title'] }}">
<meta name="description" content="{{ $faq_meta['faq_meta_description'] }}">
<meta property="og:description" content="{{ $faq_meta['faq_meta_description'] }}">
@endpush
