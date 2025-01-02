@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Cetak Slip Gaji</div>
    <div class="right"></div>
</div>

<style>
    .form-container {
        padding: 20px;
        margin-top: 60px;
        background-color: #fff;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

<<<<<<< HEAD
    .form-container .form-control {
        font-size: 20px;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        width: 90%;
        max-width: 300px;
        margin: 0 auto;
        text-align: center;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 3px;
    }

    .form-container .form-control:focus {
        border-color: #007bff;
        box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
    }

    .form-container .btn {
        margin-top: 15px;
        width: 90%;
        max-width: 300px;
    }

    .form-container label {
        font-weight: bold;
        margin-bottom: 5px;
=======
    .form-label {
>>>>>>> c5be696bc1e10da2c1a07c155b4a3aa1f8a71f9b
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .form-select,
    .form-control {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-primary:active {
        background-color: #004085;
    }

    /* Container for better spacing */
    .inner-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-container {
            margin-top: 50px;
            padding: 15px;
        }

        .inner-container {
            padding: 0.5rem;
        }
    }

    /* Fix for select dropdowns */
    select.form-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px;
        padding-right: 2.5rem;
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="inner-container">
        <form method="GET" action="{{ url('/penggajian/slip') }}" id="slipGajiForm">
            <div class="form-group">
                <label class="form-label" for="bulan">Pilih Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Pilih Bulan</option>
                    @foreach ($namabulan as $index => $bulan)
                        @if ($index > 0)
                            <option value="{{ $index }}" {{ Request('bulan') == $index ? 'selected' : '' }}>
                                {{ $bulan }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="tahun">Pilih Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Pilih Tahun</option>
                    @php
                        $tahun_awal = 2022;
                        $tahun_sekarang = date("Y");
                    @endphp
                    @for ($tahun = $tahun_sekarang; $tahun >= $tahun_awal; $tahun--)
                        <option value="{{ $tahun }}" {{ Request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Cetak Slip Gaji
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
<script>
$(function() {
    $('#slipGajiForm').on('submit', function(e) {
        const bulan = $('#bulan').val();
        const tahun = $('#tahun').val();

        if (!bulan || !tahun) {
            e.preventDefault();
            Swal.fire({
                title: 'Perhatian!',
                text: 'Bulan dan Tahun harus dipilih',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#007bff'
            });
            return false;
        }
    });
});
</script>
@endpush