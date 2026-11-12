@if(session('success'))
    <div class="alert alert-success" role="status" style="margin:16px 0;padding:12px 16px;border-radius:10px;background:#eaf7ef;color:#1d5c36;border:1px solid #bfe3cc">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error" role="alert" style="margin:16px 0;padding:12px 16px;border-radius:10px;background:#fdecec;color:#8e1818;border:1px solid #f2c0c0">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error" role="alert" style="margin:16px 0;padding:12px 16px;border-radius:10px;background:#fdecec;color:#8e1818;border:1px solid #f2c0c0">
        <strong>Periksa kembali isian Anda:</strong>
        <ul style="margin:6px 0 0 18px;padding:0">
            @foreach($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif