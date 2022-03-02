@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card">
                
                <div class="card-header">{{ __('Dashboard') }}</div>
                
                <div class="col-md-4">
                    <a class='btn btn-info float-right mt-3 mb-3 ml-4 mr-4' href="">Add Product</a>
                </div>
                
                
                <div class="card-body">
                    
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    
                    
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
