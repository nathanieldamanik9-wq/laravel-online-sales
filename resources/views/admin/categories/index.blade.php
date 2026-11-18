@extends('layouts.admin')
@section('title', 'Kategori')
@section('content')
<div class="page-head">
    <div>
        <div class="eyebrow">Master Data</div>
        <h1>Kategori Produk</h1>
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <h2>Tambah Kategori Baru</h2>
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <label>Nama Kategori
            <input name="name" value="{{ old('name') }}" maxlength="100" required>
        </label>
        <label>Deskripsi
            <textarea name="description" rows="2">{{ old('description') }}</textarea>
        </label>
        <button type="submit" class="btn">Simpan Kategori</button>
    </form>
</div>

<div class="card">
    <h2>Daftar Kategori</h2>
    @if ($categories->isEmpty())
        <p class="muted">Belum ada kategori.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Jumlah Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.update', $category) }}" style="display:flex;gap:8px;align-items:center;">
                                @csrf
                                @method('PUT')
                                <input name="name" value="{{ $category->name }}" required>
                                <button type="submit" class="btn-light">Ubah</button>
                            </form>
                        </td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-light">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
