@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <h2 class="page-title">
            Data Karyawan
          </h2>
        </div>
      </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (Session::get('success'))
                                <div class="alert alert-success">
                                    {{ Session::get('success') }}
                                </div>
                                @endif

                                @if (Session::get('warning'))
                                <div class="alert alert-warning">
                                    {{ Session::get('warning') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @role('administrator','user')
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btnTambahkaryawan">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Tambah Data  
                                </a>
                            </div>
                        </div>
                        @endrole
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/karyawan" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="kode_dept" id="kode_dept" class="form-select">
                                                    <option value="">Departemen</option>
                                                    @foreach ($departemen as $d)
                                                        <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NIK</th>
                                            <th>NAMA</th>
                                            <th>JABATAN</th>
                                            <th>NO.HP</th>
                                            <th>FOTO</th>
                                            <th>DEPARTEMEN</th>
                                            <th>Location</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($karyawan as $d)
                                        @php
                                            $path = Storage::url('uploads/karyawan/'.$d->foto);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration + $karyawan->firstItem()-1 }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_lengkap }}</td>
                                            <td>{{ $d->jabatan }}</td>
                                            <td>{{ $d->no_hp }}</td>
                                            <td>
                                                @if (empty($d->foto))
                                                <img src="{{ asset('assets/img/nofoto.png') }}" class="avatar" alt="">
                                                @else 
                                                <img src="{{ url($path) }}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td>{{ $d->nama_dept }}</td>
                                            <td class="text-center">
                                                @if ($d->status_location == 1)
                                                    <a href="/karyawan/{{ $d->nik }}/lockandunlocklocation">
                                                        <span class="badge bg-sm bg-danger">
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-lock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                                                        </span>
                                                    </a>
                                                @else
                                                    <a href="/karyawan/{{ $d->nik }}/lockandunlocklocation">
                                                        <span class="badge bg-sm bg-success">
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-lock-open-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M9 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M13 11v-4a4 4 0 1 1 8 0v4" /></svg>
                                                        </span>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                    @role('administrator','user')
                                                    <a href="#" class="edit btn btn-info btn-sm" nik="{{ $d->nik }}">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    </a>
                                                    @endrole
                                                    <a href="/konfigurasi/{{ $d->nik }}/setjamkerja" class="btn btn-success btn-sm ml-2">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                                                    </a>
                                                    <a href="/karyawan/{{ Crypt::encrypt ($d->nik) }}/resetpassword" class="btn btn-sm btn-warning"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-key-off"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.17 6.159l2.316 -2.316a2.877 2.877 0 0 1 4.069 0l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.33 2.33" /><path d="M14.931 14.948a2.863 2.863 0 0 1 -1.486 -.79l-.301 -.302l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.863 2.863 0 0 1 -.794 -1.504" /><path d="M15 9h.01" /><path d="M3 3l18 18" /></svg>
                                                    </a>
                                                    </div>
                                                    @role('administrator','user')
                                                    <div>
                                                    <form action="/karyawan/{{ $d->nik }}/delete" method="POST" style="margin-left:5px">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm">
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                        </a>
                                                    </form>
                                                    </div>
                                                    @endrole
                                                </div>
                                            </td>
                                        </tr>                            
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $karyawan->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/karyawan/store" method="POST" id="frmkaryawan" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                            </span>
                            <input type="text" maxlength="5" value="" id="nik" class="form-control" name="nik" placeholder="NIK">
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            </span>
                            <input type="text" value="" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap">
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-analytics"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" /><path d="M7 20l10 0" /><path d="M9 16l0 4" /><path d="M15 16l0 4" /><path d="M8 12l3 -3l2 2l3 -3" /></svg>
                            </span>
                            <input type="text" value="" id="jabatan" class="form-control" name="jabatan" placeholder="Jabatan">
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                            </span>
                            <input type="text" value="" id="no_hp" class="form-control" name="no_hp" placeholder="Nomer Handphone">
                          </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <input type="file" name="foto" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <select name="kode_dept" id="kode_dept" class="form-select">
                            <option value="">Departemen</option>
                            @foreach ($departemen as $d)
                                <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
{{-- Modul Edit --}}
<div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadeditform">
        </div>
      </div>
    </div>
</div>
@endsection


@push('myscript')
    <script>
        $(function(){
            $("#nik").mask("00000");
            $("#no_hp").mask("0000000000000");

            $("#btnTambahkaryawan").click(function(){
                $("#modal-inputkaryawan").modal("show");
            });

            $(".edit").click(function(){
                var nik = $(this).attr('nik');
                $.ajax({
                    type:'POST',
                    url:'/karyawan/edit',
                    cache:false,
                    data:{
                        _token:"{{ csrf_token(); }}",
                        nik: nik
                    },
                    success:function(respond){
                        $("#loadeditform").html(respond);
                    }
                });
                $("#modal-editkaryawan").modal("show");
            });

            $(".delete-confirm").click(function(e){
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: "Apakah Anda Yakin Data Ini Mau Di Hapus?",
                    text: "Jika Iya, Maka Data Akan Terhapus Permanent!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus Saja!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di Hapus",
                        icon: "success"
                        });
                    }
                });
            });

            $("#frmkaryawan").submit(function(){
                var nik = $("#nik").val();
                var nama_lengkap = $("#nama_lengkap").val();
                var jabatan = $("#jabatan").val();
                var no_hp = $("#no_hp").val();
                var kode_dept = $("frmkaryawan").find("#kode_dept").val();
                if (nik == ""){
                    //alert('NIK Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'NIK Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#nik").focus();
                    });
                    return false;
                } else if (nama_lengkap == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#nama_lengkap").focus();
                    });
                    return false;
                } else if (jabatan == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jabatan Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#jabatan").focus();
                    });
                    return false;
                } else if (no_hp == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nomer Handphone Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#no_hp").focus();
                    });
                    return false;
                } else if (kode_dept == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Departemen Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#kode_dept").focus();
                    });
                    return false;
                }
            });
        });
    </script>
@endpush