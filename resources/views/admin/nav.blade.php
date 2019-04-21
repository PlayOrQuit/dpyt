<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
{{--            <div class="col-lg-3 ml-auto">--}}
{{--                <form class="input-icon my-3 my-lg-0">--}}
{{--                    <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">--}}
{{--                    <div class="input-icon-addon">--}}
{{--                        <i class="fe fe-search"></i>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-play-circle"></i>{{ trans('template.menu_seo_playlist') }}</a>
                        <div class="dropdown-menu dropdown-menu-arrow">
                            <a href="./cards.html" class="dropdown-item ">{{ trans('template.menu_sub_channels') }}</a>
                            <a href="./pricing-cards.html" class="dropdown-item ">{{ trans('template.menu_sub_add_playlist') }}</a>
                            <a href="./charts.html" class="dropdown-item ">{{ trans('template.menu_sub_add_multiple_playlist') }}</a>
                            <a href="./pricing-cards.html" class="dropdown-item ">{{ trans('template.menu_sub_playlist') }}</a>
                            <a href="./pricing-cards.html" class="dropdown-item ">{{ trans('template.menu_sub_add_video_to_playlist') }}</a>
                            <a href="./pricing-cards.html" class="dropdown-item ">{{ trans('template.menu_sub_videos') }}</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="./index.html" class="nav-link"><i class="fe fe-lock"></i> {{ trans('template.menu_api_key') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
