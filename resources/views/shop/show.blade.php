@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="card">
        <div class="checkout-layout">
            @if($product->image)
                <img class="thumb" style="height: 340px;" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="thumb product-placeholder" style="height: 340px;" aria-hidden="true">□</div>
            @endif

            <div>
                <div class="muted">
                    {{ $product->category?->name ?? 'Umum' }} | {{ $product->sku }}
                </div>

                <h1>{{ $product->name }}</h1>
                <p>{!! nl2br(e($product->description)) !!}</p>
                <p class="price">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                <p>Stok tersedia: <strong>{{ $product->stock }}</strong></p>

                @if($product->stock === 0)
                    <p class="badge cancelled">Stok habis. Cek kembali nanti.</p>
                @else
                    @auth
                        @if(auth()->user()->role === 'customer' && Route::has('cart.store'))
                            <form method="POST" action="{{ route('cart.store', $product) }}">
                                @csrf
                                <label>
                                    Jumlah
                                    <input class="input" style="max-width: 120px;" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="{{ old('quantity', 1) }}" required>
                                </label>
                                @error('quantity')
                                    <span class="muted" style="color: #dc2626; display: block; margin-bottom: 8px;">{{ $message }}</span>
                                @enderror

                                <button type="submit" class="btn">Tambah ke Keranjang</button>
                            </form>
                        @endif
                    @else
                        <p><a href="{{ route('login') }}">Login</a> untuk berbelanja.</p>
                    @endauth
                @endif
            </div>
        </div>
    </div>
@endsection