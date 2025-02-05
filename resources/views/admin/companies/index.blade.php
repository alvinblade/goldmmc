@extends('admin.index')

@section('title')
    Şirkətlər
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Şirkətlər
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">ŞİRKƏTLƏR</h3>
                    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Əlavə et
                    </a>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.companies.index')}}" method="GET">
                                    <input type="text" class="form-control ml-4" id="example-text-input"
                                           name="search" placeholder="Axtar..." value="{{ request('search') }}">
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
                            <th>Ad</th>
                            <th>Qısa adı</th>
                            <th>Kateqoriya</th>
                            <th>Öhdəlik</th>
                            <th>Tipi</th>
                            <th>E-poçt</th>
                            <th>VÖEN</th>
                            <th class="text-center" style="width: 100px;">
                                İDARƏ
                            </th>
                        </tr>
                        </thead>
                        <tbody class="js-table-sections-header">
                        @foreach ($companies as $company)
                            <tr>
                                <th class="text-center" scope="row">{{ $companies->firstItem() + $loop->index }}</th>
                                <td class="fs-sm">
                                    {{ $company->company_name }}
                                </td>
                                <td class="fs-sm">
                                    {{ $company->company_short_name }}
                                </td>
                                <td class="fs-sm">
                                    {{ getLabelValue($company->company_category,getCompanyCategoryTypes())['label'] }}
                                </td>
                                <td class="fs-sm">
                                    {{ getLabelValue($company->company_obligation,getCompanyObligationTypes())['label'] }}
                                </td>
                                <td class="fs-sm">
                                    {{ getLabelValue($company->owner_type,getCompanyOwnerTypes())['label'] }}
                                </td>
                                <td class="fs-sm">
                                    @foreach($company->company_emails as $email)
                                        <a href="mailto:{{ $email }}" class="badge bg-secondary">
                                            {{ $email }}
                                        </a>
                                    @endforeach
                                </td>
                                <td class="fs-sm">
                                    {{ $company->tax_id_number }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.companies.edit', $company->id) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="Edit"
                                           data-bs-original-title="Edit Client" title="Düzəliş et">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.companies.destroy', $company->id) }}"
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
                    {{ $companies->appends(request()->input())->links() }}
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
                title: 'Şirkət silinsin?',
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
