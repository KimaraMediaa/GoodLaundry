@include('layouts.app')

    <div class="modal modal-blur fade" id="modalArea" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Form Tambah Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="formArea" name="formArea">
                <span id="formAreaResult"></span>
                <input type="hidden" id="formAreaId" name="formAreaId">
                <div class="mb-3">
                    <label class="form-label">Nama Area</label>
                    <input type="text" class="form-control" id="formAreaNama" name="formAreaNama" placeholder="Masukan nama area disini...">
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary actionformCancel" data-bs-dismiss="modal">Cancel</a>
                <a href="#" class="btn btn-primary ms-auto actionformArea">Simpan</a>
            </div>
        </div>
        </div>
    </div>

      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                  <!-- Page pre-title -->
                  <div class="page-pretitle">
                    <span id="titleSectionPage">Area</span>
                  </div>
                  <h2 class="page-title">
                    Data Area
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
                  <div id="table-default">
                    <table class="table" id="tbArea">
                      <thead>
                        <tr>
                          <th width="10%">No</th>
                          <th width="75%">Nama Area</th>
                          <th width="5%">ID</th>
                          <th width="10%">Opsi</th>
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

        var tbArea = $('#tbArea').DataTable({
            processing: false,
            responsive: true,
            info:false,
            bFilter: false,
            bPaginate: false,
            lengthChange:false,
            pageLength : 10,
            order:[[0,'asc']],
            ajax: "{{ route('area.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},
                {data: 'namaArea', name: 'namaArea'},
                {data: 'idArea', name: 'idArea'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className:'float-right'},
            ]
        });

        $('.btnTambahData').click(function () {
            $('#formAreaId').val('');
            $('#formAreaResult').html("");
            $('#formArea').trigger("reset");
            $('.modal-title').html('Tambah Data');
            $('#modalArea').modal('show');
        });

        $('.actionformCancel').click(function (e) {
            e.preventDefault();
            $('#formAreaId').val('');
            $('#formAreaResult').html("");
            $('#formArea').trigger("reset");
        });

        $('body').on('click', '.btnEditArea', function () {
            var id = $(this).data('id');
            $.get("{{route('area.index')}}" +'/' + id +'/edit', function (data) {
                console.log(data);
                $('.modal-title').html('Ubah Data');
                $('#modalArea').modal('show');
                $('#formAreaResult').html("");
                $('#formAreaId').val(data.idArea);
                $('#formAreaNama').val(data.namaArea);
            })
        });

        $('.actionformArea').click(function (e) {
            e.preventDefault();
            $.ajax({
                data: $('#formArea').serialize(),
                url: "{{route('area.store')}}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    console.log(data);
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
                        $('#tbArea').DataTable().ajax.reload();
                        $('#formArea').trigger("reset");
                        $('#modalArea').modal('hide');
                        tbArea.draw();
                        // $('.aksiBarang').width('8%');
                    }
                    $('#formAreaResult').html(html);
                },
            });
        });
    });
</script>
