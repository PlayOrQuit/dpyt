@extends('admin.base')
@section('page-title')
    {{ trans('template.menu_detail_playlist') }}
@endsection
@section('page-content')
    <div id="detail-playlist"></div>
    <script src="{{ asset('js/DetailPlayList.react.js') }}"></script>
@endsection