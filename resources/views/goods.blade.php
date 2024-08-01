@extends('components.layout')

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($goods as $good)
                <a class="col-md-4 mb-4 expand-on-hover" href="{{ route('goods.show', ['good' => $good->id]) }}"
                    style="text-decoration: none; color: #000;">
                    <div class="card" style="min-height: 100%; max-height: 100%;">
                        <img src="{{ $good->getFirstImageURL() }}" class="img-fluid"
                            style="object-fit: cover;height: 60vh;object-fit: cover;width: 100%;" alt="{{ $good->name }}">
                        <div class="card-body">
                            <div
                                style="display: flex;flex-direction: row;justify-content: center; align-items: center; color: rgb(224, 61, 61)">
                                @if ($good->discount > 0)
                                    <h5 style="text-decoration: line-through; margin-right: 5px; transform: rotate(-5deg); color: rgb(103, 103, 103)">
                                        {{ $good->price }} ₽</h5>
                                    <h2 style="margin-right: 5px">{{ $good->getPriceWithDiscount() }} ₽</h2>
                                    <span
                                        style="background-color: rgb(224, 61, 61);color: white;border-radius: 20px;padding: 10px;font-weight: bold;transform: rotate(25deg);">-{{ (int) (($good->discount / $good->price) * 100) }}%</span>
                                @else
                                    <h2>{{ $good->price }} ₽</h2>
                                @endif
                            </div>
                            <h5 class="card-title mt-2">{{ $good->name }}</h5>
                            <p class="card-text">
                                {{ Str::limit($good->description, 150) }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="pagination-links">
            {{ $goods->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
