<form action="/konfigurasi/updatejamkerja" method="POST" id="frmJK">
    @csrf
    <div class="row">
      <div class="col-12">
          <div class="input-icon mb-3">
              <span class="input-icon-addon">
                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
              </span>
              <input type="text" value="{{ $jamkerja->kode_jam_kerja }}" id="kode_jam_kerja" class="form-control" name="kode_jam_kerja" placeholder="Kode Jam Kerja">
            </div>
      </div>
  </div>
  <div class="row">
    <div class="col-12">
        <div class="input-icon mb-3">
            <span class="input-icon-addon">
              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
            </span>
            <input type="text" value="{{ $jamkerja->nama_jam_kerja }}" id="nama_jam_kerja" class="form-control" name="nama_jam_kerja" placeholder="Nama Jam Kerja">
          </div>
    </div>
</div>
<div class="row">
  <div class="col-12">
      <div class="input-icon mb-3">
          <span class="input-icon-addon">
            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
          </span>
          <input type="text" value="{{ $jamkerja->awal_jam_masuk }}" id="awal_jam_masuk_edit" class="form-control" name="awal_jam_masuk" placeholder="Awal Jam Masuk">
        </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
      <div class="input-icon mb-3">
          <span class="input-icon-addon">
            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
          </span>
          <input type="text" value="{{ $jamkerja->jam_masuk }}" id="jam_masuk_edit" class="form-control" name="jam_masuk" placeholder="Jam Masuk">
        </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
      <div class="input-icon mb-3">
          <span class="input-icon-addon">
            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
          </span>
          <input type="text" value="{{ $jamkerja->akhir_jam_masuk }}" id="akhir_jam_masuk_edit" class="form-control" name="akhir_jam_masuk" placeholder="Akhir Jam Masuk">
      </div>
  </div>
</div>
<div class="row mb-3">
    <div class="col-12">
      <div class="form-group">
        <select name="status_istirahat" id="status_istirahat_edit" class="form-select">
          <option value="">Istirahat</option>
          <option value="1">Ada</option>
          <option value="0">Tidak</option>
        </select>
      </div>
    </div>
</div>
<div class="row setjamistirahat">
    <div class="col-12">
        <div class="input-icon mb-3">
            <span class="input-icon-addon">
              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
            </span>
            <input type="text" value="" id="awal_jam_istirahat_edit" class="form-control" name="awal_jam_istirahat" placeholder="Awal Jam Istirahat">
        </div>
    </div>
</div>
<div class="row setjamistirahat">
    <div class="col-12">
        <div class="input-icon mb-3">
            <span class="input-icon-addon">
              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
            </span>
            <input type="text" value="" id="akhir_jam_istirahat_edit" class="form-control" name="akhir_jam_istirahat" placeholder="Akhir Jam istirahat">
        </div>
    </div>
</div>
<div class="row">
  <div class="col-12">
      <div class="input-icon mb-3">
          <span class="input-icon-addon">
            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
          </span>
          <input type="text" value="{{ $jamkerja->jam_pulang }}" id="jam_pulang_edit" class="form-control" name="jam_pulang" placeholder="Jam Pulang">
      </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
      <div class="input-icon mb-3">
          <span class="input-icon-addon">
            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
          </span>
          <input type="text" value="{{ $jamkerja->total_jam }}" id="total_jam_edit" class="form-control" name="total_jam" placeholder="Total Jam">
      </div>
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

<script>
    function showsetjamistirahat(){
        var status_istirahat = $("#status_istirahat_edit").val();
        if (status_istirahat == "1") {
          $(".setjamistirahat").show();
        } else {
          $(".setjamistirahat").hide();
        }
      }

      $("#status_istirahat_edit").change(function() {
        showsetjamistirahat();
      });
      showsetjamistirahat();
    $("#awal_jam_masuk_edit, #jam_masuk_edit, #akhir_jam_masuk_edit, #awal_jam_istirahat_edit, #akhir_jam_istirahat_edit, #jam_pulang_edit, #total_jam_edit").mask("00:00");
    $("#frmJK_edit").submit(function() {
    var kode_jam_kerja = $("#kode_jam_kerja_edit").val();
    var nama_jam_kerja = $("#nama_jam_kerja_edit").val();
    var awal_jam_masuk = $("#awal_jam_masuk_edit").val();
    var jam_masuk = $("#jam_masuk_edit").val();
    var akhir_jam_masuk = $("#akhir_jam_masuk_edit").val();
    var status_istirahat = $("#status_istirahat_edit").val();
    var awal_jam_istirahat = $("#awal_jam_istirahat_edit").val();
    var akhir_jam_istirahat = $("#akhir_jam_istirahat_edit").val();
    var jam_pulang = $("#jam_pulang_edit").val(); 
    var total_jam = $("#total_jam_edit").val();
    if (kode_jam_kerja == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Kode Jam Kerja Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#kode_jam_kerja").focus();
                    });
                    return false;
                } else if (nama_jam_kerja == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Jam Kerja Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#nama_jam_kerja").focus();
                    });
                    return false;
                } else if (awal_jam_masuk == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Awal Jam Masuk Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#awal_jam_masuk").focus();
                    });
                    return false;
                } else if (jam_masuk == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Masuk Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#jam_masuk").focus();
                    });
                    return false;
                } else if (akhir_jam_masuk == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Akhir Jam Masuk Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#akhir_jam_masuk").focus();
                    });
                    return false;
                } else if (status_istirahat == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Status Istirahat Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#status_istirahat").focus();
                    });
                    return false;
                } else if (awal_jam_istirahat == "" && status_istirahat == "1"){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Awal Jam Istirahat Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#awal_jam_istirahat").focus();
                    });
                    return false;
                } else if (akhir_jam_istirahat == "" && status_istirahat == "1"){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Akhir Jam Istirahat Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#akhir_jam_istirahat").focus();
                    });
                    return false;
                } else if (jam_pulang == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Pulang Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#jam_pulang").focus();
                    });
                    return false;
                } else if (total_jam == ""){
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Total Jam Kerja Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result)=> {
                        $("#total_jam").focus();
                    });
                    return false;
                }
            });

            $(".edit").click(function(){
                var kode_jam_kerja = $(this).attr('kode_jam_kerja');
                $.ajax({
                    type:'POST',
                    url:'/konfigurasi/editjamkerja',
                    cache:false,
                    data:{
                        _token:"{{ csrf_token(); }}",
                        kode_jam_kerja: kode_jam_kerja
                    },
                    success:function(respond){
                        $("#loadeditform").html(respond);
                    }
                });
                $("#modal-editjk").modal("show");
  });
</script>