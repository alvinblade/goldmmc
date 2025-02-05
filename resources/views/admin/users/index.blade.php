@extends('admin.index')

@section('title')
    İstifadəçilər
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            İstifadəçilər
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">GOLD MMC İSTİFADƏÇİLƏR</h3>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Əlavə et
                    </a>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.users.index')}}" method="GET">
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
                            <th>Ad/Soyad</th>
                            <th>Vəzifə</th>
                            <th>Ata adı</th>
                            <th>İstifadəçi adı</th>
                            <th>Doğum tarixi</th>
                            <th>Telefon</th>
                            <th>E-poçt</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 100px;">
                                İDARƏ
                            </th>
                        </tr>
                        </thead>
                        <tbody class="js-table-sections-header">
                        @foreach ($users as $user)
                            <tr>
                                <th class="text-center" scope="row">{{ $users->firstItem() + $loop->index }}</th>
                                <td class="fs-sm">
                                    {{ $user->name }} {{ $user->surname }}
                                </td>
                                <td class="fs-sm">
                                    {{ $user->roles->first()->display_name_az }}
                                </td>
                                <td class="fs-sm">
                                    {{ $user->father_name }}
                                </td>
                                <td class="fs-sm">
                                    {{ $user->username }}
                                </td>
                                <td class="fs-sm">
                                    {{ \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') }}
                                </td>
                                <td class="fs-sm">
                                    <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                </td>
                                <td class="fs-sm">
                                    <a href="mailto:">{{ $user->email }}</a>
                                </td>
                                <td class="fs-sm">
                                    {{ $user->account_status }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        @if($user->type == 'client')
                                            <a href="javascript:void(0)"
                                               class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                               aria-label="Assign Service" data-bs-original-title="Assign Service"
                                               title="İstifadəçi əlavə et" data-bs-toggle="modal"
                                               data-bs-target="#modal-block-normal{{$user->id}}">
                                                <i class="fa fa-fw fa-plus"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="Edit Client"
                                           data-bs-original-title="Edit Client" title="Düzəliş et">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
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
                    {{ $users->appends(request()->input())->links() }}
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
                title: 'İstifadəçi silinsin?',
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
