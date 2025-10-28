@extends('errors.layout')

@section('code', '401')

@if (isSystemInstalled())
    @section('title', translate('Unauthorized'))
    @section('message', translate('Sorry, you are not authorized to access this page.'))
@else
    @section('title', __('Unauthorized'))
    @section('message', __('Sorry, you are not authorized to access this page.'))
@endif
