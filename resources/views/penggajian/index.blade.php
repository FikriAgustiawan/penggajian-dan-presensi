@extends('layouts.admin.tabler')
@section('content')
<style>
    .form-select {
        border: none;
        border-bottom: 2px solid #ddd;
        padding: 10px 15px;
        background: transparent;
        font-size: 16px;
        margin-bottom: 20px;
        text-align: center;
        box-shadow: none;
    }

    .form-select:focus {
        border-bottom: 2px solid #007bff; /* Warna garis saat fokus */
        outline: none;
    }

    .page-body {
        margin-top: 20px;
    }

    .card {
        border-radius: 10px;
        box-shadow: none;
    }

    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        margin-bottom: 10px;
        font-weight: normal;
    }

    .btn-primary {
        padding: 12px;
        font-size: 16px;
        border-radius: 5px;
        background-color: #007bff;
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Cetak Slip Gaji
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form id="frmPenggajian" method="GET">
                            @csrf
                            <!-- Pilih Bulan -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Pilih Bulan</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                                    {{ $namabulan[$i] }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilih Tahun -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Pilih Tahun</option>
                                            @php
                                            $tahunMulai = 2022;
                                            $tahunSekarang = date('Y');
                                            @endphp
                                            @for ($tahun = $tahunMulai; $tahun <= $tahunSekarang; $tahun++)
                                                <option value="{{ $tahun }}" {{ date('Y') == $tahun ? 'selected' : '' }}>
                                                    {{ $tahun }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilih Karyawan -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="nik" id="nik" class="form-select">
                                            <option value="">Pilih Karyawan</option>
                                            @foreach ($karyawan as $d)
                                                <option value="{{ $d->nik }}">{{ $d->nama_lengkap }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Cetak -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <a id="btnCetakSlipGaji" href="#" class="btn btn-primary w-100">
                                            Cetak Slip Gaji
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnCetakSlipGaji').on('click', function(e) {
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();
            var nik = $('#nik').val();

            if (bulan === '' || tahun === '' || nik === '') {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Semua field harus diisi!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                e.preventDefault();
                return false;
            }

            var url = `/penggajian/slip/${nik}/${bulan}/${tahun}`;
            $(this).attr('href', url);
        });
    });
</script>
@endpush
