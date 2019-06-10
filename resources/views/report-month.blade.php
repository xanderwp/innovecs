@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{__('report-month.title')}}</div>

                    <div class="card-body">
                        <div class="container">
                            @if($reports)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{__('report-month.table.email')}}</th>
                                            <th>{{__('report-month.table.amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $email => $sum)
                                            <tr>
                                                <td>{{$email}}</td>
                                                <td>{{$sum}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <b>{{__('report-month.empty')}}</b>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
