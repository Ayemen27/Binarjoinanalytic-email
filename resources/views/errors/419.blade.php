@extends('errors.layout')

@section('code', '419')

@if (env('SYSTEM_INSTALLED'))
    @section('title', translate('Page Expired'))
    @section('message', translate('The page has expired. Please refresh and try again.'))
@else
    @section('title', __('Page Expired'))
    @section('message', __('The page has expired. Please refresh and try again.'))
@endif
