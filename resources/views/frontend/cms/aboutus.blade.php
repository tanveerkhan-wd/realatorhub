@extends('frontend.layout.app_without_login')
@section('content')@section('content')
@if(!empty($singleData['agency_banner_image']))
<div class="about_banner bg" style="background-image: url('<?= url('/public/uploads/agency_about_banner/').'/'.$singleData['agency_banner_image'] ?>');">
</div>
@endif
<section class="section aboutus">
	<div class="container">
		@if(!empty($singleData['agency_text']))
	    	{!! $singleData['agency_text'] !!}
	    @endif
	</div>
</section>		   
@endsection
@push('custom-scripts')

@endpush
@push('custom-styles')
@if(isset($singleData['about_us_seo_title']) && !empty($singleData['about_us_seo_title']))
<title>{{ $singleData['about_us_seo_title'] }}</title>
@endif
@if(isset($singleData['about_us_seo_title']) && !empty($singleData['about_us_seo_title']))
<meta property="og:title" content="{{ $singleData['about_us_seo_title'] }}">
@endif
@if(isset($singleData['about_us_seo_desc']) && !empty($singleData['about_us_seo_desc']))
<meta name="description" content="{{ $singleData['about_us_seo_desc'] }}">
@endif
@if(isset($singleData['about_us_seo_desc']) && !empty($singleData['about_us_seo_desc']))
<meta property="og:description" content="{{ $singleData['about_us_seo_desc'] }}">
@endif

@endpush