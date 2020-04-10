@extends('adminlte::page')

@section('title', 'Novo Usuário')

@section('content_header')
    <h1>Novo Usuário</h1>
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
            <form action="{{ route('users.store') }}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Nome Completo</label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control col-sm-10 @error('name') is-invalid @enderror">
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">E-mail</label>
                    <input type="email" class="form-control col-sm-10 @error('email') is-invalid @enderror" name="email" value="{{old('email')}}">
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Senha</label>
                    <input type="password" class="form-control col-sm-10 @error('password') is-invalid @enderror" name="password">
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Confirmação da senha</label>
                    <input type="password" class="form-control col-sm-10 @error('password') is-invalid @enderror" name="password_confirmation">
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2"></label>
                    <div class="col-sm-10">
                        <input type="submit" value="Cadastrar" class="btn btn-success">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
