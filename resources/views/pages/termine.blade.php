

@extends('base.base')
@section('title', 'Termine')
@section('content')

<table class="table">
    <thead>
      <tr class="table-primary">
        <th scope="col">#</th>
        <th scope="col">First</th>
        <th scope="col">Last</th>

      </tr>
    </thead>
    <tbody>
        @foreach ($ausrueckungen as $ausrueckung)
    <tr>
        <th scope="row">{{ $ausrueckung->name }}</th>
        <td>{{(new DateTime($ausrueckung->vonDatum))->format('d. M Y')}} || {{\Carbon\Carbon::parse($ausrueckung->vonDatum)->locale('de_DE')->format('d. M Y')}}</td>
        <td> {{ $ausrueckung->vonZeit }}</td>

      </tr>
@endforeach

    </tbody>
  </table>


@stop
