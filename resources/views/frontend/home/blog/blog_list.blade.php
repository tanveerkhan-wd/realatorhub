@extends('frontend.home.layout.app')
@section('title', $blog_data_seo['blog_meta_title'])
@section('content')
<style type="text/css">
    /*.main_header a.nav-link.active, .main_header a.nav-link:hover {
        color: #fff !important;
    }
    .hvr-underline-from-left:before {
        background-color: #fff !important;
    }
    .main_header .fixed-top.top-nav-collapse a.nav-link.active, .main_header .fixed-top.top-nav-collapse a.nav-link:hover{
        color: #4e43fc !important;
    }
    .fixed-top.top-nav-collapse .hvr-underline-from-left:before {
        background-color: #4e43fc !important;
    }
    .main_header .sideoff-off a.nav-link.active,  .main_header .sideoff-off a.nav-link:hover {
        color: #4e43fc !important;
    }
    .sideoff-off .hvr-underline-from-left:before {
        background-color: #4e43fc  !important;
    }*/
</style>
<section class="terms-banner  wow fadeIn" >
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Blogs</h2>
            </div>
        </div>
    </div>
</section>
<section class="blog-listing" id="blog">

    <div class="container">
        <div class="cat_btbs">
            @foreach($blog_category_data as $key => $value)
                <button type="button" value="{{$value['id']}}" id="blog_category"  onclick="blogCatgoryList(this)" class="cat_buttom grad_btn">{{ $value['title'] }}</button>
            @endforeach
        </div>
        <div class="row equal_height change_blog">
            <?php
            $allowedlimit = 75;
            ?>
            @foreach($blog_data as $key => $value)
            <div class="col-lg-4 col-md-6 equal_height_container blog-card">
                <div class="card wow zoomIn slow">
                    <a data-slug="blog-detail/{{ $value['slug'] }}"  href="{{ url('/blog-detail/'.$value['slug']) }}">
                        <div class="">
                            <div class="">
                                <div class="card-top" style="background: url({{ url('public/uploads/blog_image/'.$value['image']) }}) center center no-repeat ;">					
                                    <span></span>
                                </div>
                                <div class="card-body">
                                    <h4>{{$value['title']  }}</h4>
                                    @php
                                    $desc=(mb_strlen($value['description'] )>$allowedlimit) ? mb_substr(html_entity_decode(strip_tags($value['description'])),0,$allowedlimit).'....' : $value['description'] ;
                                    @endphp
                                    <p class="card-text">{!! $desc !!}  </p>
                                    <p class="card-date">{{date('d M y', strtotime($value['created_date'])) }}</p>
                                </div>

                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <a disabled="true" onclick="loadmorelist(this)" paginate="{{ $start_per_page }}" class="loadmore"> Load More <img src="{{ url('public/assets/images/ic_down.png') }}" class="wow wobble infinite">
                </a>
            </div>
        </div>
         <input type="hidden" name="blog_total_page" value="{{ $total_page }}">
        <input type="hidden" name="current_blog_category_id" value="">
    </div>
</section>
@endsection
@push('custom-scripts')
<script>
    var blog_total_page = 0;
    var blog_paginate_page = 0;

    function blogCatgoryList(blogid) {
        $('.cat_buttom').removeClass("active");
        // $('#allCategory').removeClass("active");
        let blog_html = blogid;
        let
        blog_category_id = $(blog_html).val();
        var base_url = $('#web_base_url').val();

        $.ajax({
            type: 'POST',
            url: '{{route("user.blog.list")}}',
            dataType: 'json',
            data: {
                blog_category_id: blog_category_id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // $(blog_html).val();
                // $(blog_html).addClass("active");
                $(blog_html).addClass(' active')
                $('input[name=current_blog_category_id]').val(blog_category_id);
                $('.change_blog').html(data.view);
                 $('.loadmore').show();
            }
        });

    }

    function loadmorelist(blogid) {
        // $('.cat_buttom').removeClass("active");
        // $('#allCategory').removeClass("active");
        let
        blog_html = blogid;
        // $(blog_html).attr('disabled', true);
        // $(blog_html).prop('disabled', true);
        let
        paginate = $(blog_html).attr('paginate');
        var total_page = $('input[name=blog_total_page]').val();
        var current_blog_category_id = $('input[name=current_blog_category_id]').val();
        var base_url = $('#web_base_url').val();

        if (total_page == paginate) {
            debugger;
            $('.loadmore').hide();
        }
        $(blog_html).hide();
        $.ajax({
            type: 'POST',
            url: '{{route("user.blog.load.more")}}',
            dataType: 'json',
            data: {
                page: parseInt(paginate),
                current_blog_category_id: current_blog_category_id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $(blog_html).show();
                var new_paginate = parseInt(paginate) + 1;

                if (total_page == paginate) {
                    $('.loadmore').hide();
                } else {
                    $(blog_html).attr('paginate', new_paginate);
                }

                // $(blog_html).val();
                // $(blog_html).addClass("active");
                if (data.view == '') {
                    $('.loadmore').hide();
                }
                console.log(data.view);
                $('.change_blog').append(data.view)
            }
        });

    }
</script>
@endpush
@push('custom-styles')
<meta property="og:title" content="{{ $blog_data_seo['blog_meta_title'] }}">
<meta name="description" content="{{ $blog_data_seo['blog_meta_description'] }}">
<meta property="og:description" content="{{ $blog_data_seo['blog_meta_description'] }}">
@endpush