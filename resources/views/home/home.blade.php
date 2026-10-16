@extends('layouts.layout')

@section('titulo', 'contenido')

@section('contenido')
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h1 class="h2 py-3">
             BIENVENIDO!
                {{\Illuminate\Support\Facades\Auth::user()->nombre}}
            </h1>
        </div>
    </div>
@endsection
