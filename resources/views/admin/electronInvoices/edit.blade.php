@extends('admin.index')

@section('title')
    Elektron qaimə düzəlişi et
@endsection
@section('page_styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href={{asset('assets/admin/js/plugins/select2/css/select2.min.css')}}>
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Elektron qaimə düzəlişi et
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block-content p-0">
                <form action="{{ route('admin.electronInvoices.update', $electronInvoice->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="invoice_number">E-qaimə kodu <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="invoice_number"
                                           name="invoice_number" placeholder="..."
                                           value="{{ old('invoice_number', $electronInvoice->invoice_number) }}">
                                    @error('invoice_number')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="invoice_date">E-qaimə tarixi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="invoice_date"
                                           name="invoice_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('invoice_date', $electronInvoice->invoice_date) }}">
                                    @error('invoice_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="e_invoice_files">E-qaimə faylları</label>
                                    <input type="file" class="form-control" id="e_invoice_files"
                                           name="e_invoice_files[]" multiple>
                                    @error('e_invoice_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('e_invoice_files.*'))
                                        @foreach($errors->get('e_invoice_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($electronInvoice->e_invoice_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteElectronInvoiceFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="overflow-scroll">
                                <table class="w-100">
                                    <thead>
                                    <tr class="text-center">
                                        <th class="fs-sm fw-bolder">Malın (işin, xidmətin) adı</th>
                                        <th class="fs-sm fw-bolder">Bar kod</th>
                                        <th class="fs-sm fw-bolder">Ölçü vahidi</th>
                                        <th class="fs-sm fw-bolder">Miqdarı, həcmi</th>
                                        <th class="fs-sm fw-bolder">Vahid qiyməti (AZN)</th>
                                        {{--                                        <th class="fs-sm fw-bolder">Cəmi (AZN)</th>--}}
                                        <th class="fs-sm fw-bolder">Aksiz dərəcəsi (%)</th>
                                        {{--                                        <th class="fs-sm fw-bolder">Aksiz məbləği (AZN)</th>--}}
                                        {{--                                        <th class="fs-sm fw-bolder">Cəmi</th>--}}
                                        <th class="fs-sm fw-bolder">ƏDV-yə cəlb edilən</th>
                                        <th class="fs-sm fw-bolder">ƏDV-yə cəlb edilməyən</th>
                                        <th class="fs-sm fw-bolder">ƏDV-dən azad olunan</th>
                                        <th class="fs-sm fw-bolder">ƏDV-yə 0 dərəcə ilə cəlb edilən</th>
                                        {{--                                        <th class="fs-sm fw-bolder">ƏDV məbləği (AZN)</th>--}}
                                        <th class="fs-sm fw-bolder">Yol verigisi (AZN)</th>
                                        {{--                                        <th class="fs-sm fw-bolder">Yekun məbləğ (AZN)</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody id="invoice-body">
                                    @foreach($electronInvoice->electronInvoiceItems as $key => $item)
                                        <tr class="invoice-info" data-row-id="{{$key}}">
                                            <td>
                                                <input type="text" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][name]"
                                                       value="{{ old('invoice_infos.'.$key.'.name', $item->name) }}">
                                                @error('invoice_infos.'.$key.'.name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][code]"
                                                       value="{{ old('invoice_infos.'.$key.'.code', $item->code) }}">
                                                @error('invoice_infos.'.$key.'.code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select name="invoice_infos[{{$key}}][measure_id]"
                                                        class="form-control form-control-sm w-auto">
                                                    @foreach($measures as $measure)
                                                        <option
                                                            @selected(old('invoice_infos.'.$key.'.measure_id', $item->measure_id) == $measure->id) value="{{ $measure->id }}">{{ $measure->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('invoice_infos.'.$key.'.measure_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][quantity]" step="0.01" min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.quantity', $item->quantity) }}">
                                                @error('invoice_infos.'.$key.'.quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][unit_price]" step="0.01" min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.unit_price', $item->unit_price) }}">
                                                @error('invoice_infos.'.$key.'.unit_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            {{--                                        <td>--}}
                                            {{--                                            <input type="number" class="form-control form-control-sm w-auto"--}}
                                            {{--                                                   name="invoice_infos[0][total_price]" step="0.01"--}}
                                            {{--                                                   value="{{ old('invoice_infos.0.total_price') }}" disabled>--}}
                                            {{--                                            @error('invoice_infos.0.total_price')--}}
                                            {{--                                            <div class="invalid-feedback">{{ $message }}</div>--}}
                                            {{--                                            @enderror--}}
                                            {{--                                        </td>--}}
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][excise_tax_rate]" step="0.01"
                                                       min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.excise_tax_rate', $item->excise_tax_rate) }}">
                                                @error('invoice_infos.0.excise_tax_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            {{--                                        <td>--}}
                                            {{--                                            <input type="number" class="form-control form-control-sm w-auto"--}}
                                            {{--                                                   name="invoice_infos[0][total_price_with_excise]" step="0.01"--}}
                                            {{--                                                   value="{{ old('invoice_infos.0.total_price_with_excise', 0) }}"--}}
                                            {{--                                                   disabled>--}}
                                            {{--                                            @error('invoice_infos.0.total_price_with_excise')--}}
                                            {{--                                            <div class="invalid-feedback">{{ $message }}</div>--}}
                                            {{--                                            @enderror--}}
                                            {{--                                        </td>--}}
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][vat_involved]" step="0.01" min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.vat_involved', $item->vat_involved) }}">
                                                @error('invoice_infos.'.$key.'.vat_involved')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][vat_not_involved]" step="0.01"
                                                       min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.vat_not_involved', $item->vat_not_involved) }}">
                                                @error('invoice_infos.'.$key.'.vat_not_involved')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][vat_released]" step="0.01" min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.vat_released', $item->vat_released) }}">
                                                @error('invoice_infos.'.$key.'.vat_released')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][vat_involved_with_zero_rate]"
                                                       step="0.01"
                                                       min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.vat_involved_with_zero_rate', $item->vat_involved_with_zero_rate) }}">
                                                @error('invoice_infos.'.$key.'.vat_involved_with_zero_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            {{--                                        <td>--}}
                                            {{--                                            <input type="number" class="form-control form-control-sm w-auto"--}}
                                            {{--                                                   name="invoice_infos[0][total_vat]" step="0.01" min="0"--}}
                                            {{--                                                   value="{{ old('invoice_infos.0.total_vat', 0) }}" disabled>--}}
                                            {{--                                            @error('invoice_infos.0.total_vat')--}}
                                            {{--                                            <div class="invalid-feedback">{{ $message }}</div>--}}
                                            {{--                                            @enderror--}}
                                            {{--                                        </td>--}}
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       name="invoice_infos[{{$key}}][road_tax]" step="0.01" min="0"
                                                       value="{{ old('invoice_infos.'.$key.'.road_tax', $item->road_tax) }}">
                                                @error('invoice_infos.'.$key.'.road_tax')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            {{-- <td>
                                            {{--                                            <input type="number" class="form-control form-control-sm w-auto"--}}
                                            {{--                                                   name="invoice_infos[0][final_amount]" step="0.01" min="0"--}}
                                            {{--                                                   value="{{ old('invoice_infos.0.final_amount', 0) }}" disabled>--}}
                                            {{--                                            @error('invoice_infos.0.final_amount')--}}
                                            {{--                                            <div class="invalid-feedback">{{ $message }}</div>--}}
                                            {{--                                            @enderror--}}
                                            {{--                                        </td>--}}
                                            @if($key !== 0)
                                                <td>
                                                    <button type="button" class="remove-invoice-info"
                                                            onclick="removeInvoiceInfo(this)"><i
                                                            class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-info w-100 my-3" id="add-invoice">Artır <i
                                    class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-primary w-100">Yadda saxla</button>
                        </div>
                    </div>
                    <input type="hidden" id="delete_electron_invoice_files" name="delete_electron_invoice_files"/>
                </form>
            </div>
        </div>
    </main>
@endsection
@section('page_scripts')
    <script>
        function removeInvoiceInfo(event) {
            event.parentElement.parentElement.remove();
        }

        document.addEventListener('DOMContentLoaded', function () {
            let invoiceIndex = {{$electronInvoice->electronInvoiceItems->count()}};
            document.getElementById('add-invoice').addEventListener('click', function () {
                let invoiceBody = document.getElementById('invoice-body');
                let invoiceData = `
    <td>
        <input type="text" name="invoice_infos[${invoiceIndex}][name]" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="text" name="invoice_infos[${invoiceIndex}][code]" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <select name="invoice_infos[${invoiceIndex}][measure_id]" class="form-control form-control-sm w-auto">
            @foreach($measures as $measure)
                <option value="{{ $measure->id }}">{{ $measure->name }}</option>
            @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="invoice_infos[${invoiceIndex}][quantity]" step="0.01" min="0" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="number" name="invoice_infos[${invoiceIndex}][unit_price]" step="0.01" min="0" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="number" name="invoice_infos[${invoiceIndex}][excise_tax_rate]" step="0.01" min="0" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="number" name="invoice_infos[${invoiceIndex}][vat_involved]" step="0.01" min="0" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="number" name="invoice_infos[${invoiceIndex}][vat_not_involved]" step="0.01" min="0" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="number" name="invoice_infos[${invoiceIndex}][vat_released]" step="0.01" min="0" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="number"  name="invoice_infos[${invoiceIndex}][vat_involved_with_zero_rate]" min="0" step="0.01" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <input type="number"  name="invoice_infos[${invoiceIndex}][road_tax]" min="0" step="0.01" value="0" class="form-control form-control-sm w-auto">
    </td>
    <td>
        <button type="button" class="remove-invoice-info" onclick="removeInvoiceInfo(this)"><i class="fa fa-trash"></i></button>
    </td>
`;
                let tr = document.createElement('tr');
                tr.classList.add('invoice-info');
                tr.setAttribute('data-row-id', invoiceIndex.toString());
                tr.innerHTML = invoiceData;
                invoiceBody.appendChild(tr);
                invoiceIndex++;
            });
        });


        deleteElectronInvoiceFiles = [];

        function deleteElectronInvoiceFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteElectronInvoiceFiles.includes(JSON.stringify(file))) {
                    deleteElectronInvoiceFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_electron_invoice_files').val(deleteElectronInvoiceFiles);
            }
        }
    </script>
    <script src="{{asset('assets/admin/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/az.js"></script>
    <script>
        flatpickr.localize(flatpickr.l10ns.az);
        One.helpersOnLoad(['js-flatpickr', 'jq-select2']);
    </script>
@endsection
