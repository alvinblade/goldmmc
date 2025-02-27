@extends('admin.index')
@section('title')
    Anbar malları
@endsection
@section('page_styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Anbar malları - {{ $servedCompany->company_name }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">ANBAR MALLARI</h3>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.warehouseItems.index')}}" method="GET">
                                    <input type="text" class="form-control ml-4" id="example-text-input"
                                           name="search" placeholder="Axtar..." value="{{ request('search') }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">№</th>
                            <th>AD</th>
                            <th>KOD</th>
                            <th>ANBAR</th>
                            <th class="text-center" style="width: 100px;">İDARƏ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <th class="text-center" scope="row">{{ $items->firstItem() + $loop->index }}</th>
                                <td class="fw-semibold fs-sm">
                                    {{ $item->name }}
                                </td>
                                <td class="fw-semibold fs-sm">
                                    {{ $item->code }}
                                </td>
                                <td class="fw-semibold fs-sm">
                                    {{ $item->warehouse?->name }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <form action="{{ route('admin.warehouseItems.destroy', $item->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="js-swal-confirm btn btn-sm
                                                     btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip"
                                                    aria-label="Remove" data-bs-original-title="Remove" title="Sil">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $items->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </main>
@endsection
@section('page_scripts')
    <script>
        $('button.js-swal-confirm').on('click', function () {
            const form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: 'Mal/Material silinsin?',
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

        One.helpersOnLoad(['js-flatpickr']);
    </script>
@endsection
