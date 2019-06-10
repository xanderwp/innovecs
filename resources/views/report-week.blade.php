@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{__('report-week.title')}}</div>

                    <div class="card-body">

                        <div class="container">
                            @if($reports)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{__('report-week.table.email')}}</th>
                                            @foreach($days as $day)
                                                <th>{{$day}}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $email => $days_sum)
                                        <tr>
                                            <td>{{$email}}</td>
                                            @foreach($days as $key_day => $day)
                                                <td>{{ (isset($days_sum[$key_day])?$days_sum[$key_day]:0) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <b>{{__('report-week.empty')}}</b>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
