@extends('layouts.admin.tabler')
@section('content')
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
                            <div class="row mt-2">
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
                            <div class="row mt-2">
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
                            <div class="row mt-2">
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
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <a id="btnCetakSlipGaji" href="#" class="btn btn-primary w-100" target="_blank">
                                            Cetak
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
            // Ambil nilai bulan, tahun, dan NIK dari form
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();
            var nik = $('#nik').val();

            // Validasi input
            if (bulan === '' || tahun === '' || nik === '') {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Bulan, Tahun, dan NIK harus dipilih!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                e.preventDefault();
                return false;
            }

            // Buat URL dinamis
            var url = `/penggajian/slip/${nik}/${bulan}/${tahun}`;
            $(this).attr('href', url);
        });
    });
</script>
@endpush
