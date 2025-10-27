@extends('errors.layout')

@section('code', '403')

@if (env('SYSTEM_INSTALLED'))
    @section('title', translate('Forbidden'))
    @section('message', translate('Sorry, you do not have permission to access this page.'))
@else
    @section('title', __('Forbidden'))
    @section('message', __('Sorry, you do not have permission to access this page.'))
@endif
