@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.success.title') }}
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('themes/default/assets/css/shop.css') }}">
@endpush

@section('content-wrapper')

    <div class="order-success-content" style="min-height: 300px;">
        <h1>{{ __('cashu::app.error.order-failure') }}</h1>

        <p>{{ __('cashu::app.error.failure-message') }}</p>

        <div class="misc-controls">
            <a style="display: inline-block" href="{{ route('shop.checkout.onepage.index') }}" class="btn btn-lg btn-primary">
                {{ __('cashu::app.error.checkout-again') }}
            </a>
        </div>
    </div>
@endsection