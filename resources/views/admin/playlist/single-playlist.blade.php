@extends('admin.base')
@section('page-title')
    {{ trans('template.menu_sub_add_playlist') }}
@endsection
@section('page-content')
    <div id="section-single-playlist"></div>
    <script src="{{ asset('js/PageSinglePlaylist.react.js') }}"></script>
@endsection