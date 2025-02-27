@extends('admin.index')

@section('title')
    Elektron qaimələr
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Elektron qaimələr
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">ELEKTRON QAİMƏLƏR</h3>
                    <a href="{{ route('admin.electronInvoices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Əlavə et
                    </a>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.electronInvoices.index')}}" method="GET"
                                      class="d-flex gap-1">
                                    <input type="text" class="form-control ml-4" id="example-text-input"
                                           name="search" placeholder="Kod..." value="{{ request('search') }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    <table class="js-table-sections table table-sm table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">№</th>
                            <th>Kod</th>
                            <th>Şirkət</th>
                            <th>VÖEN</th>
                            <th>Ümumi</th>
                            <th>Tarix</th>
                            <th class="text-center" style="width: 100px;">
                                İDARƏ
                            </th>
                        </tr>
                        </thead>
                        <tbody class="js-table-sections-header">
                        @foreach ($electronInvoices as $invoice)
                            <tr>
                                <th class="text-center"
                                    scope="row">{{ $electronInvoices->firstItem() + $loop->index }}</th>
                                <td class="fs-sm">
                                    {{ $invoice->invoice_number }}
                                </td>
                                <td class="fs-sm">
                                    {{ $invoice->company?->company_name }}
                                </td>
                                <td class="fs-sm">
                                    {{ $invoice->company?->tax_id_number }}
                                </td>
                                <td class="fs-sm">
                                    {{ $invoice->total }}
                                </td>
                                <td class="fs-sm">
                                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.electronInvoices.show', $invoice->id) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="Show"
                                           data-bs-original-title="Show" title="Bax">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.electronInvoices.edit', $invoice->id) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="Edit"
                                           data-bs-original-title="Edit" title="Düzəliş et">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.electronInvoices.destroy', $invoice->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="js-swal-confirm btn btn-sm
                                                    btn-alt-secondary js-bs-tooltip-enabled"
                                                    data-bs-toggle="tooltip" aria-label="Remove"
                                                    data-bs-original-title="Remove" title="Sil">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $electronInvoices->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </main>
@endsection
@section('page_scripts')
    <script>
        $('button.js-swal-confirm').on('click', function (event) {
            const form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: 'E-qaimə silinsin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Ləğv et',
                confirmButtonText: 'Sil'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            })
        });
    </script>
@endsection
