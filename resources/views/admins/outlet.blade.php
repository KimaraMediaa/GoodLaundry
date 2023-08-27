@include('layouts.app')

    <div class="modal modal-blur fade" id="modalOutlet" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Form Tambah Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formOutlet" name="formOutlet">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="formOutletResult"></span>
                            <input type="hidden" id="formOutletId" name="formOutletId">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Nama Outlet</label>
                            <input type="text" class="form-control" id="formOutletNama" name="formOutletNama" placeholder="Masukan nama outlet disini...">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Area Outlet</label>
                            <select name="formOutletArea" id="formOutletArea" class="form-control">
                                <option value="disabled">Pilih Area Outlet</option>
                                @foreach ($area as $item)
                                    <option value="{{$item->idArea}}">{{$item->namaArea}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kontak Outlet</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    +62
                                </span>
                                <input type="text" class="form-control" id="formOutletKontak" name="formOutletKontak" placeholder="8XXXXXXXXX">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Alamat Outlet</label>
                            <input type="text" class="form-control" id="formOutletAlamat" name="formOutletAlamat" placeholder="Masukan alamat outlet disini...">
                            <input type="hidden" class="form-control" id="formOutletLat" name="formOutletLat">
                            <input type="hidden" class="form-control" id="formOutletLong" name="formOutletLong">
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Latitude Outlet</label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude Outlet</label>

                        </div>
                    </div> --}}
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="map" style="height: 400px"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary actionformCancel" data-bs-dismiss="modal">Cancel</a>
                <a href="#" class="btn btn-primary ms-auto actionformOutlet">Simpan</a>
            </div>
        </div>
        </div>
    </div>

      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2">
                <div class="col-6">
                  <!-- Page pre-title -->
                  <div class="page-pretitle">
                    <span id="titleSectionPage">Outlet</span>
                  </div>
                  <h2 class="page-title">
                    Data Outlet
                  </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                  <div class="btn-list">
                    <a href="#" class="btn btn-primary d-none d-sm-inline-block btnTambahData">
                      <i data-feather="plus"></i>
                      Tambah Data
                    </a>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div id="mapTable" style="height: 400px" width="100%"></div>
                    </div>
                    <div id="table-default">
                        <table class="table" id="tbOutlet">
                        <thead>
                            <tr>
                            <th>No</th>
                            <th>Nama Outlet</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Area</th>
                            <th>ID</th>
                            <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="table-tbody"></tbody>
                        </table>
                    </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
@include('layouts.footer')

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $.ajax({
                type: "GET",
                url: "{{route('mapsOutlet')}}",
                dataType: "JSON",
                success: function (data) {
                    for (let i = 0; i < data.length; i++) {var markerTable = new L.Marker([data[i].latOutlet, data[i].longOutlet]);
                        markerTable.addTo(layerGroupTable).bindPopup('<b>'+data[i].namaOutlet+'</b>');
                    }
                }
            });
        });

        //Maps Table
        var mapTable = L.map('mapTable').setView([-8.720000, 115.2000000], 11);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 20,
                attribution: '© OpenStreetMap'
            }).addTo(mapTable);

            var layerGroupTable = L.layerGroup().addTo(mapTable);
        //Maps Table

        //Maps Form
            var map = L.map('map').setView([-8.450000, 115.1000000], 9);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 20,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            L.control.locate().addTo(map);

            var layerGroup = L.layerGroup().addTo(map);

            map.on('click', addMarker);

            function addMarker(e){
                // Add marker to map at click location; add popup window
                // var newMarker = new L.marker(e.latlng).addTo(map);
                layerGroup.clearLayers(map);
                L.marker(e.latlng).addTo(layerGroup);

                document.getElementById("formOutletLat").value = e.latlng.lat;
                document.getElementById("formOutletLong").value = e.latlng.lng;

                // $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+e.latlng.lat+'&lon='+e.latlng.lng, function(data){
                //     document.getElementById("formOutletAlamat").value = data.address.road;
                // });

            }
        //Maps Form


        var tbOutlet = $('#tbOutlet').DataTable({
            processing: false,
            responsive: true,
            info:false,
            bFilter: false,
            bPaginate: false,
            lengthChange:false,
            pageLength : 10,
            order:[[0,'asc']],
            ajax: "{{ route('outlet.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},
                {data: 'namaOutlet', name: 'namaOutlet'},
                {data: 'kontakOutlet', name: 'kontakOutlet'},
                {data: 'alamatOutlet', name: 'alamatOutlet'},
                {data: 'namaArea', name: 'namaArea'},
                {data: 'idOutlet', name: 'idOutlet'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className:'float-right'},
            ]
        });

        $('.btnTambahData').click(function () {
            setTimeout(
                function(){
                    map.invalidateSize();
                    layerGroup.clearLayers(map);
                    map.setView([-8.450000, 115.1000000], 9);
                }, 500);
            $('#formOutletId').val('');
            $('#formOutletResult').html("");
            $('#formOutlet').trigger("reset");
            $('.modal-title').html('Tambah Data');
            $('#modalOutlet').modal('show');
        });

        $('.actionformCancel').click(function (e) {
            e.preventDefault();
            $('#formOutletId').val('');
            $('#formOutletResult').html("");
            $('#formOutlet').trigger("reset");
        });

        $('body').on('click', '.btnEditOutlet', function () {
            var id = $(this).data('id');
            $.get("{{route('outlet.index')}}" +'/' + id +'/edit', function (data) {
                setTimeout(
                    function(){
                        map.invalidateSize();
                        var marker = new L.Marker([data.latOutlet, data.longOutlet]);
                        layerGroup.clearLayers(map);
                        marker.addTo(layerGroup);
                        map.setView([data.latOutlet, data.longOutlet], 15);
                    },
                    500);
                $('.modal-title').html('Ubah Data');
                $('#modalOutlet').modal('show');
                $('#formOutletResult').html("");
                $('#formOutletId').val(data.idOutlet);
                $('#formOutletNama').val(data.namaOutlet);
                $('#formOutletAlamat').val(data.alamatOutlet);
                $('#formOutletKontak').val(data.kontakOutlet);
                $('#formOutletArea').val(data.idArea);
                $('#formOutletLat').val(data.latOutlet);
                $('#formOutletLong').val(data.longOutlet);
            })
        });

        $('.actionformOutlet').click(function (e) {
            e.preventDefault();
            $.ajax({
                data: $('#formOutlet').serialize(),
                url: "{{route('outlet.store')}}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    var html='';
                    if (data.errors) {
                        html = '<div class="alert alert-danger">';
                        for (var count = 0; count < data.errors.length; count++) {
                            html += '<p>' + data.errors[count] + '</p>'
                        }
                        html += '</div>';
                    }
                    if (data.success) {
                        // swal("Berhasil!", { icon: "success", });
                        $('#tbOutlet').DataTable().ajax.reload();
                        $('#formOutlet').trigger("reset");
                        $('#modalOutlet').modal('hide');
                        tbOutlet.draw();
                        setTimeout(
                        function(){
                            mapTable.invalidateSize();
                        },
                        500);
                        // $('.aksiBarang').width('8%');
                    }
                    $('#formOutletResult').html(html);
                },
            });
        });
    });
</script>
