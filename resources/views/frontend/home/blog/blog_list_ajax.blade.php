<?php
    $allowedlimit = 75;
    ?>
    @foreach($blog_data as $key => $value)
    <div class="col-lg-4 col-md-6 equal_height_container blog-card">
        <div class="card wow zoomIn slow">
            <a class="ajax_request" data-slug="blog-detail/{{ $value['slug'] }}"  href="{{ url('/blog-detail/'.$value['slug']) }}">
            
                <div class="">
                    <div class="card-top" style="background: url({{ url('public/uploads/blog_image/'.$value['image']) }}) center center no-repeat ;">					
                          {{-- <img src="images/blog1.png" class="card-img-top" alt=""> --}}
                          <span>12 Feb 19</span>
                    </div>
                  <div class="card-body">
                      <h4>{{$value['title']  }}</h4>
                      @php
                       $desc=(mb_strlen($value['description'] )>$allowedlimit) ? mb_substr(html_entity_decode(strip_tags($value['description'])),0,$allowedlimit).'....' : $value['description'] ;
                       @endphp
                    <p class="card-text">{!! $desc !!} </p>
                    <p class="card-text">{{date('d M y', strtotime($value['created_date'])) }}  </p>
                  </div>
                </div>
            </a>
        </div>
    </div>
    @endforeach