@extends('admin.index')


@section('title')
    Ölçü vahidləri
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
                            Ölçü vahidləri
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">ÖLÇÜ VAHİDLƏRİ</h3>
                    <a data-bs-toggle="modal"
                       data-bs-target="#modal-block-measure-create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Əlavə et
                    </a>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.measures.index')}}" method="GET">
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
                            <th class="text-center" style="width: 100px;">İDARƏ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($measures as $measure)
                            <tr>
                                <th class="text-center" scope="row">{{ $measures->firstItem() + $loop->index }}</th>
                                <td class="fw-semibold fs-sm">
                                    {{ $measure->name }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           aria-label="Edit" data-bs-original-title="Edit"
                                           title="Düzəliş et" data-bs-toggle="modal"
                                           data-bs-target="#modal-block-measure-edit{{$measure->id}}">
                                            <i class="fa fa-fw fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.measures.destroy', $measure->id) }}"
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
                            <!-- Normal Block Modal -->
                            <div class="modal" id="modal-block-measure-edit{{$measure->id}}" tabindex="-1"
                                 role="dialog"
                                 aria-labelledby="modal-block-measure-edit{{$measure->id}}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="block block-rounded block-transparent mb-0">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">DÜZƏLİŞ ET</h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="block-content fs-sm pt-0">
                                                <form
                                                    action="{{ route('admin.measures.update', $measure->id) }}"
                                                    method="POST" id="updateForm{{$measure->id}}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="my-4">
                                                        <label class="form-label" for="name">Ad</label>
                                                        <input type="text" class="form-control"
                                                               id="name" name="name" placeholder="..."
                                                               value="{{ $measure->name }}">
                                                        @error('name')
                                                        <small class="text-danger">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="block-content block-content-full text-end bg-body">
                                                <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                                                        data-bs-dismiss="modal">Bağla
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        onclick="$('#updateForm{{$measure->id}}').submit()">
                                                    Təsdiqlə
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Normal Block Modal -->
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Normal Block Modal -->
                    <div class="modal" id="modal-block-measure-create" tabindex="-1"
                         role="dialog"
                         aria-labelledby="modal-block-measure-create" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="block block-rounded block-transparent mb-0">
                                    <div class="block-header block-header-default">
                                        <h3 class="block-title">ÖLÇÜ VAHİDİ ƏLAVƏ ET</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content fs-sm pt-0">
                                        <form
                                            action="{{ route('admin.measures.store') }}"
                                            method="POST" id="storeForm">
                                            @csrf
                                            <div class="my-4">
                                                <label class="form-label" for="name">Ad</label>
                                                <input type="text" class="form-control"
                                                       id="name" name="name" placeholder="...">
                                                @error('name')
                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>
                                                @enderror
                                            </div>
                                        </form>
                                    </div>
                                    <div class="block-content block-content-full text-end bg-body">
                                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                                                data-bs-dismiss="modal">Bağla
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary"
                                                onclick="$('#storeForm').submit()">
                                            Təsdiqlə
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Normal Block Modal -->
                    {{ $measures->appends(request()->input())->links() }}
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
                title: 'Ölçü vahidi silinsin?',
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
