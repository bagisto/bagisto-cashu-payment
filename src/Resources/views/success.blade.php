	 <form action="{{ route('shop.checkout.success') }}" method="GET" id="cashusuccess">
		<input type="hidden" value="{{$order->id}}" name="order_id">
    	 </form>
    	     <script type="text/javascript">
        	document.getElementById("cashusuccess").submit();
    	     </script>
    	     @exit
@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.success.title') }}
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('themes/default/assets/css/shop.css') }}">
@endpush

@section('content-wrapper')
    <div class="order-success-content" style="min-height: 300px;">
        <h1>{{ __('shop::app.checkout.success.thanks') }}</h1>

        <p>{{ __('shop::app.checkout.success.order-id-info', ['order_id' => $order->id]) }}</p>

        <p>{{ __('shop::app.checkout.success.info') }}</p>

        <div class="misc-controls">
            <a style="display: inline-block" href="{{ route('shop.home.index') }}" class="btn btn-lg btn-primary">
                {{ __('shop::app.checkout.cart.continue-shopping') }}
            </a>
        </div>
    </div>
@endsection

