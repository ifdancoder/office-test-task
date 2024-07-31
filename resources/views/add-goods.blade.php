@extends('components.layout')

@section('content')
    <div class="container mt-4">
        <h1>Загрузка Excel-документа</h1>
        <form action="{{ route('goods.add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="fileInput">Выберите файл:</label>
                <input type="file" class="form-control-file" id="fileInput" name="file">
            </div>
            <button type="submit" class="btn btn-primary">Отправить файл на обработку</button>
        </form>
    </div>
@endsection
