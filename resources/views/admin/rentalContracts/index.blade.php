@extends('admin.index')

@section('title')
    Kirayə müqavilələri
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Kirayə müqavilələri
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">KİRAYƏ MÜQAVİLƏLƏRİ</h3>
                    <a href="{{ route('admin.rentalContracts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Əlavə et
                    </a>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.rentalContracts.index')}}" method="GET"
                                      class="d-flex gap-1">
                                    <input type="text" class="form-control ml-4" id="example-text-input"
                                           name="search" placeholder="Ad/Kod/Qiymət..." value="{{ request('search') }}">
                                    <select class="form-control" name="type" id="filter_type">
                                        <option value="">--Növü seçin--</option>
                                        @foreach(getRentalTypes() as $key => $type)
                                            <option value="{{ $key }}"
                                                {{ request('type') == $key ? 'selected' : '' }}>{{ $type['label'] }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Axtar</button>
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
                            <th>Növ</th>
                            <th>Ad</th>
                            <th>Kod</th>
                            <th>Başlama tarixi</th>
                            <th>Bitmə tarixi</th>
                            <th>Qiymət</th>
                            <th>ƏDV</th>
                            <th>CƏM</th>
                            <th class="text-center" style="width: 100px;">
                                İDARƏ
                            </th>
                        </tr>
                        </thead>
                        <tbody class="js-table-sections-header">
                        @foreach ($rentalContracts as $contract)
                            <tr>
                                <th class="text-center"
                                    scope="row">{{ $rentalContracts->firstItem() + $loop->index }}</th>
                                <td class="fs-sm">
                                    {{ getLabelValue($contract->type,getRentalTypes())['label'] }}
                                </td>
                                <td class="fs-sm">
                                    {{ $contract->object_name }}
                                </td>
                                <td class="fs-sm">
                                    {{ $contract->object_code }}
                                </td>
                                <td class="fs-sm">
                                    {{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}
                                </td>
                                <td class="fs-sm">
                                    {{ \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}
                                </td>
                                <td class="fs-sm">
                                    {{ $contract->rental_price }}
                                </td>
                                <td class="fs-sm">
                                    {{ $contract->rental_price_with_vat ?? 'Yoxdur' }}
                                </td>
                                <td class="fs-sm">
                                    {{ $contract->rental_price_with_vat + $contract->rental_price }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.rentalContracts.edit', $contract->id) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="Edit"
                                           data-bs-original-title="Edit" title="Düzəliş et">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.rentalContracts.destroy', $contract->id) }}"
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
                    {{ $rentalContracts->appends(request()->input())->links() }}
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
                title: 'Müqavilə silinsin?',
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
