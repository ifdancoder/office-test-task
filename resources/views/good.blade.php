@extends('components.layout')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 align-self-center">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @foreach ($good->images as $index => $image)
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}"
                                class="@if ($index == 0) active @endif"></li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @foreach ($good->images as $index => $image)
                            <div class="carousel-item @if ($index == 0) active @endif">
                                <img src="{{ $image->getUrl() }}" class="d-block w-100" alt="{{ $good->name }}">
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Предыдущая</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Следующая</span>
                    </a>
                </div>
                <div class="mt-4">
                    <div
                        style="display: flex;flex-direction: row;justify-content: center; align-items: center; color: rgb(224, 61, 61)">
                        @if ($good->discount > 0)
                            <h5 style="text-decoration: line-through; margin-right: 5px;transform: rotate(-5deg); color: rgb(103, 103, 103)">
                                {{ $good->price }} ₽</h5>
                            <h2 style="margin-right: 5px">{{ $good->getPriceWithDiscount() }} ₽</h2>
                            <span
                                style="background-color: rgb(224, 61, 61);color: white;border-radius: 20px;padding: 10px;font-weight: bold;transform: rotate(25deg);">-{{ (int) (($good->discount / $good->price) * 100) }}%</span>
                        @else
                            <h2>{{ $good->price }} ₽</h2>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h1>{{ $good->name }}</h1>
                <strong>
                    <h6 class="text-muted">Внешний код: {{ $good->external_code }}</h6>
                </strong>
                <p>{{ $good->description }}</p>
                <div class="characteristics">
                    <h3>Характеристики:</h3>
                    <ul class="list-unstyled">
                        @foreach ($good->attributes as $attribute)
                            <li class="characteristic-item">
                                <strong><span class="characteristic-key">{{ mb_ucfirst($attribute->key) }}:</span></strong>
                                <span class="characteristic-value">{{ $attribute->value }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
