@extends('layouts.base')

@section('content')
    <form action="{{route('cal.store')}}" method="POST" role="form">
        {{csrf_field()}}
        <legend>
            Crear Evento
        </legend>
        <div class="form-group">
            <label for="title">
                Titulo
            </label>
            <input class="form-control" name="title" placeholder="Title" type="text">
        </div>
        <div class="form-group">
            <label for="title">
                Locación
            </label>
            <input class="form-control" name="location" placeholder="Locacion" type="text">
        </div>
        <div class="form-group">
            <label for="description">
                Descripción
            </label>
            <input class="form-control" name="description" placeholder="Description" type="text">
        </div>
        <div class="form-group">
            <label for="start_date">
                Fecha Inicio
            </label>
            <input class="form-control" name="start_date" placeholder="Start Date" type="text">
        </div>
        <div class="form-group">
            <label for="end_date">
                fecha fin
            </label>
            <input class="form-control" name="end_date" placeholder="End Date" type="text">
        </div>
        <button class="btn btn-primary" type="submit">
            Submit
        </button>
    </form>
@endsection