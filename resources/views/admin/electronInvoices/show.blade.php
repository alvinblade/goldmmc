@extends('admin.index')

@section('title')
    Elektron qaimə - {{$electronInvoice->invoice_number}}
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
                            Elektron qaimə - {{$electronInvoice->invoice_number}}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block-content p-0">
                <form>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="invoice_number">E-qaimə kodu</label>
                                    <input type="text" class="form-control" id="invoice_number"
                                           name="invoice_number" placeholder="..."
                                           value="{{ $electronInvoice->invoice_number }}" disabled>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="invoice_date">E-qaimə tarixi</label>
                                    <input type="text" class="form-control"
                                           value="{{ $electronInvoice->invoice_date }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div>
                                    <label class="form-label" for="e_invoice_files">E-qaimə faylları</label>
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
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="overflow-scroll">
                                <table class="w-100">
                                    <thead>
                                    <tr class="text-center">
                                        <th class="fs-sm fw-bolder">Malın adı</th>
                                        <th class="fs-sm fw-bolder">Bar kod</th>
                                        <th class="fs-sm fw-bolder">Ölçü vahidi</th>
                                        <th class="fs-sm fw-bolder">Miqdarı, həcmi</th>
                                        <th class="fs-sm fw-bolder">Vahid qiyməti (AZN)</th>
                                        <th class="fs-sm fw-bolder">Cəmi (AZN)</th>
                                        <th class="fs-sm fw-bolder">Aksiz dərəcəsi (%)</th>
                                        <th class="fs-sm fw-bolder">Aksiz məbləği (AZN)</th>
                                        <th class="fs-sm fw-bolder">Cəmi</th>
                                        <th class="fs-sm fw-bolder">ƏDV-yə cəlb edilən</th>
                                        <th class="fs-sm fw-bolder">ƏDV-yə cəlb edilməyən</th>
                                        <th class="fs-sm fw-bolder">ƏDV-dən azad olunan</th>
                                        <th class="fs-sm fw-bolder">0 dərəcəylə cəlb edilən</th>
                                        <th class="fs-sm fw-bolder">ƏDV məbləği (AZN)</th>
                                        <th class="fs-sm fw-bolder">Yol verigisi (AZN)</th>
                                        <th class="fs-sm fw-bolder">Yekun məbləğ (AZN)</th>
                                    </tr>
                                    </thead>
                                    <tbody id="invoice-body">
                                    @foreach($electronInvoice->electronInvoiceItems as $key => $item)
                                        <tr class="invoice-info" data-row-id="{{$key}}">
                                            <td>
                                                <input type="text" class="form-control form-control-sm w-auto"
                                                       value="{{ $item->name }}" disabled>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm w-auto"
                                                       value="{{ $item->code }}" disabled>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm w-auto"
                                                       value="{{$item->measure?->name}}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" min="0"
                                                       value="{{ $item->quantity }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" min="0"
                                                       value="{{ $item->unit_price }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01"
                                                       value="{{ $item->total_price }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" min="0" value="{{ $item->excise_tax_rate }}"
                                                       disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" min="0" value="{{ $item->excise_tax_amount }}"
                                                       disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" value="{{ $item->total_price_with_excise }}"
                                                       disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" value="{{  $item->vat_involved }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" value="{{ $item->vat_not_involved }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" value="{{ $item->vat_released }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" value="{{ $item->vat_involved_with_zero_rate }}"
                                                       disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01"
                                                       value="{{ $item->total_vat }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" value="{{ $item->road_tax }}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm w-auto"
                                                       step="0.01" value="{{ $item->final_amount }}" disabled>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
@section('page_scripts')
    <script src="{{asset('assets/admin/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/az.js"></script>
    <script>
        flatpickr.localize(flatpickr.l10ns.az);
        One.helpersOnLoad(['js-flatpickr', 'jq-select2']);
    </script>
@endsection
