@extends('admin.base')
@section('page-title')
    {{ trans('template.menu_sub_playlist') }}
@endsection
@section('page-content')
    <div id="section-list-playlist"></div>
    <script src="{{ asset('js/PageListPlaylist.react.js') }}"></script>
@endsection