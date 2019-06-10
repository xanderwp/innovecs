@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{__('form-page.title')}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="alert alert-danger" style="display:none">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div id="result" style="display: none;"></div>
                        
                    <form id="add-transactions-form" action="{{route('add-transactions')}}">
                        <div class="form-group">
                            <label>{{__('form-page.email')}}:</label>
                            <input type="email" name="email" class="form-control" placeholder="{{__('form-page.email')}}" value="{{ old('email') }}" required="">
                        </div>
                        <div class="form-group">
                            <label>{{__('form-page.amount')}}:</label>
                            <input type="number" name="amount" class="form-control" placeholder="0.00" value="{{ old('amount') }}" required="">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-submit">{{__('form-page.send')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
