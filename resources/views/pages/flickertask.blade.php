@extends('layouts.master')
@section('content')

    <section class="mbr-gallery mbr-section mbr-section-nopadding mbr-slider-carousel" id="gallery4-7" data-filter="true">
        <div class="mbr-gallery-notification">

        </div>
        <!-- Filter -->
        <div class="mbr-gallery-filter container gallery-filter-active">
            <ul>
                {{--<li class="mbr-gallery-filter-all active">All</li>--}}
            </ul>
        </div>

        <!-- Gallery -->
        <div class="mbr-gallery-row container">
            <div class=" mbr-gallery-layout-default">
                <div>
                    <div id="active-gallery">

                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <!-- Lightbox -->
        <div data-app-prevent-settings="" class="mbr-slider modal fade carousel slide" tabindex="-1"
             data-keyboard="true" data-interval="false" id="lb-gallery4-7">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">

                        <div class="carousel-inner">

                        </div>
                        <a class="left carousel-control" role="button" data-slide="prev" href="#lb-gallery4-7">
                            <span class="icon-prev" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" role="button" data-slide="next" href="#lb-gallery4-7">
                            <span class="icon-next" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>

                        <a class="close" href="#" role="button" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">Close</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection