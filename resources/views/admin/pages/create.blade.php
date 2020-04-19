@extends('adminlte::page')

@section('title', 'Nova Página')

@section('content_header')
    <h1>Nova Página</h1>
@endsection

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                <h5>
                    <i class="icon fas fa-ban"></i>
                    Ocorreu um erro!
                </h5>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pages.store') }}" method="POST" class="form-horizontal">
                @csrf

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Título</label>
                    <input type="text" name="title" value="{{old('title')}}" class="form-control col-sm-10 @error('title') is-invalid @enderror">
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Corpo</label>
                    <textarea class="form-control col-sm-10" name="body">{{old('body')}}</textarea>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2"></label>
                    <div class="col-sm-10">
                        <input type="submit" value="Criar" class="btn btn-success">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
