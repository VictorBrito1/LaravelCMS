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
                    <textarea class="form-control col-sm-10 bodyfield" name="body">{{old('body')}}</textarea>
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

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea.bodyfield',
            height: 300,
            menubar: false,
            plugins: ['link', 'table', 'image', 'autoresize', 'lists'],
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | table | link image | bullist numlist',
            content_css: ['{{ asset('assets/css/content.css') }}'],
            images_upload_url: '{{ route('imageupload') }}',
            images_upload_credentials: true, //cookie
            convert_urls: false, //usa as URL's reais (nao converte em relativa)
        });
    </script>
@endsection
