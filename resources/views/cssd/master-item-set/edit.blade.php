@extends('layouts.app')
@push('title')
    Master CSSD
@endpush
@push('sub-title')
    Edit Item Set
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Edit Master Item Set
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="editForm" action="{{ route('cssd-item-set.update', $data->id) }}"
            method="POST" accept-charset="utf-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="nama_set" class="col-2 col-form-label">* Nama Set</label>
                            <div class="col-6">
                                <select class="form-control select2  {{ $errors->has('NamaSet') ? 'is-invalid' : '' }}"
                                    name="NamaSet">
                                    @foreach ($NamaSet as $ns)
                                        <option value="{{ $ns->id }}" @if ($data->Nama == $ns->id) selected @endif>{{ $ns->Nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('NamaSet'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('NamaSet') }}
                                    </div>
                                @endif
                            </div>
                            {{-- <div class="col-2">
                                <button type="button" class="btn btn-primary" id="btnSetBaru">
                                    + Set Baru
                                </button>
                                <button type="button" class="btn btn-secondary" id="btnCancelSetBaru">
                                    Cancel
                                </button>
                            </div> --}}
                        </div>
                        <div class="form-group row" id="namaSetBaruGroup" style="display: none;">
                            <label for="nama_set" class="col-2 col-form-label">* Nama Set Baru</label>
                            <div class="col-8">
                                <input type="text" name="setBaru" class="form-control" placeholder="Masukan Nama Set Baru">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label"><b>* Item dalam Set</b></label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="30%">Nama Item</th>
                                            <th width="25%">Merk</th>
                                            <th width="25%">SN</th>
                                            <th width="10%">Qty</th>
                                            <th width="5%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->DetailItem as $i => $detail)
                                            <tr>
                                                <td class="text-center row-number">{{$i + 1}}</td>
                                                <td>
                                                    <select class="form-control select2 Itemset" name="Item[]" required>
                                                        <option value="">Pilih Item</option>
                                                        @foreach ($items as $item)
                                                            <option value="{{ $item->id }}" {{ $detail->ItemId == $item->id ? 'selected' : '' }}>
                                                                {{ $item->getNama->Nama }} - {{ $item->SerialNumber }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="Merk[]" placeholder="Merk"
                                                        value="{{ $detail->MasterItem->getMerk->Merk ?? '' }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="Tipe[]" placeholder="Tipe"
                                                        value="{{ $detail->MasterItem->SerialNumber ?? '' }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="Qty[]" placeholder="Qty"
                                                        min="1" value="{{$detail->Qty}}" required readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-md remove-row text-center"
                                                        onclick="removeRow(this)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success btn-sm" onclick="addRow()">
                                    <i class="fa fa-plus"></i> Tambah Baris
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ route('cssd-item-set.index') }}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('#btnSetBaru').on('click', function () {
                // select2 otomatis pilih "setbaru"
                $('#NamaSetSelect').val('setbaru').trigger('change');
                // tampilkan input
                $('#namaSetBaruGroup').show();
            });

            $('#btnCancelSetBaru').on('click', function () {
                // sembunyikan input
                $('#namaSetBaruGroup').hide();
                // kosongkan input
                $('input[name="setBaru"]').val('');
                // reset select2 ke default (kosong)
                $('#NamaSetSelect').val('').trigger('change');
            });
        });
    </script>
    <script>
        let itemsData = @json($items);
        let rowCount = $('#itemTable tbody tr').length || 1;

        function addRow() {
            const tbody = document.querySelector('#itemTable tbody');
            const newRow = document.createElement('tr');

            let itemOptions = '<option value="">Pilih Item</option>';
            itemsData.forEach(item => {
                itemOptions += `<option value="${item.id}">${item.get_nama.Nama} - ${item.SerialNumber} - ${item.Kode}</option>`;
            });

            newRow.innerHTML = `
                                                                                                                                                                                                                                                        <td class="text-center row-number">${rowCount + 1}</td>
                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                            <select class="form-control select2 Itemset" name="Item[]" required>
                                                                                                                                                                                                                                                                ${itemOptions}
                                                                                                                                                                                                                                                            </select>
                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                            <input type="text" class="form-control" name="Merk[]" placeholder="Merk" readonly>
                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                            <input type="text" class="form-control" name="Tipe[]" placeholder="Tipe" readonly>
                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                            <input type="number" class="form-control" name="Qty[]" placeholder="Qty" min="1" value="1" readonly required>
                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                        <td class="text-center">
                                                                                                                                                                                                                                                            <button type="button" class="btn btn-danger btn-md remove-row" onclick="removeRow(this)">
                                                                                                                                                                                                                                                                <i class="fa fa-trash"></i>
                                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                    `;

            tbody.appendChild(newRow);
            rowCount++;

            // Reinitialize select2 untuk baris baru
            $(newRow).find('.select2').select2();

            // Update nomor baris
            updateRowNumbers();
        }

        function removeRow(button) {
            const row = button.closest('tr');
            const tbody = document.querySelector('#itemTable tbody');

            // Tidak boleh menghapus jika hanya tersisa satu baris
            if (tbody.children.length > 1) {
                row.remove();
                updateRowNumbers();
            } else {
                alert('Minimal harus ada 1 item dalam set');
            }
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#itemTable tbody tr');
            rows.forEach((row, index) => {
                const rowNumber = row.querySelector('.row-number');
                rowNumber.textContent = index + 1;
            });
        }

        // Auto-fill merk dan tipe ketika item dipilih


        // Inisialisasi select2 saat halaman load
        $(document).ready(function () {
            $(document).on('change', '.Itemset', function () {
                const row = $(this).closest('tr');
                const itemId = $(this).val();
                if (itemId) {
                    $.ajax({
                        url: '{{ route("cssd-item-set.getItem") }}',
                        method: 'GET',
                        data: { id: itemId },
                        success: function (data) {
                            row.find('input[name="Merk[]"]').val(data.Merk || '');
                            row.find('input[name="Tipe[]"]').val(data.Tipe || '');
                        },
                        error: function () {
                            row.find('input[name="Merk[]"]').val('');
                            row.find('input[name="Tipe[]"]').val('');
                        }
                    });
                } else {
                    row.find('input[name="Merk[]"]').val('');
                    row.find('input[name="Tipe[]"]').val('');
                }
            });
            $('.select2').select2();
        });
    </script>

    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .remove-row {
            padding: 0.25rem 0.5rem;
        }

        .table td {
            vertical-align: middle;
        }

        .row-number {
            font-weight: 600;
        }
    </style>
@endpush