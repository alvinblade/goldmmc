@extends('admin.index')

@section('title')
    Tabel şablonları
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Tabel şablonları
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">TABEL ŞABLONLARI</h3>
                    <div class="d-flex flex-column">
                        <form action="{{ route('admin.attendanceLogConfigs.create') }}" method="GET"
                              class="d-flex gap-1">
                            <div>
                                <input type="number" class="form-control" name="year" value="{{ request('year') }}"
                                       placeholder="İl..."
                                       min="2000"
                                       max="2100">
                            </div>
                            <a href="javascript:void(0)" class="btn btn-primary"
                               onclick="this.closest('form').submit()">
                                <i class="fas fa-plus"></i> Əlavə et
                            </a>
                        </form>
                        @error('year')
                        <div class="fs-6 text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.attendanceLogConfigs.index')}}" method="GET"
                                      class="d-flex gap-1">
                                    <input type="text" class="form-control ml-4" id="example-text-input"
                                           name="search" placeholder="İl üzrə axtarış..."
                                           value="{{ request('search') }}">
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
                            <th>Şirkət</th>
                            <th>İl</th>
                            <th class="text-center" style="width: 100px;">
                                İDARƏ
                            </th>
                        </tr>
                        </thead>
                        <tbody class="js-table-sections-header">
                        @foreach ($attendanceLogConfigs as $attendanceLogConfig)
                            <tr>
                                <th class="text-center"
                                    scope="row">{{ $attendanceLogConfigs->firstItem() + $loop->index }}</th>
                                <td class="fs-sm">
                                    {{ $attendanceLogConfig->company?->company_name }}
                                </td>
                                <td class="fs-sm">
                                    {{ $attendanceLogConfig->year }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.attendanceLogConfigs.edit', $attendanceLogConfig->id) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="Edit"
                                           data-bs-original-title="Edit" title="Düzəliş et">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>
                                        <form
                                            action="{{ route('admin.attendanceLogConfigs.destroy', $attendanceLogConfig->id) }}"
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
                    {{ $attendanceLogConfigs->appends(request()->input())->links() }}
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
                title: 'Tabel şablonu silinsin?',
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
