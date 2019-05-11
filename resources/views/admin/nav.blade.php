<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-play-circle"></i>{{ trans('template.menu_seo_playlist') }}</a>
                        <div class="dropdown-menu dropdown-menu-arrow">
                            <a href="{{ action('ChannelController@render') }}" class="dropdown-item ">{{ trans('template.menu_sub_channels') }}</a>
                            <a href="{{ action('SinglePlaylistController@render') }}" class="dropdown-item ">{{ trans('template.menu_sub_add_playlist') }}</a>
                            <a href="{{ action('MultiplePlayListController@view_index') }}" class="dropdown-item ">{{ trans('template.menu_sub_add_multiple_playlist') }}</a>
                            <a href="{{ action('ListPlaylistController@render') }}" class="dropdown-item ">{{ trans('template.menu_sub_playlist') }}</a>
                            <a href="{{ action('DetailPlayListController@index') }}" class="dropdown-item ">{{ trans('template.menu_sub_add_video_to_playlist') }}</a>
                            <a href="./pricing-cards.html" class="dropdown-item ">{{ trans('template.menu_sub_videos') }}</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ action('APIKeyController@render') }}" class="nav-link"><i class="fe fe-lock"></i> {{ trans('template.menu_api_key') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
