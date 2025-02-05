@extends('admin.index')

@section('title')
    Məktublar
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Məktublar
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Məktublar</h3>
                    <a href="{{ route('admin.envelopes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Əlavə et
                    </a>
                    <div class="block-options">
                        <div class="block-options-item">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fs-4 fas fa-search p-2"></i>
                                <form action="{{route('admin.envelopes.index')}}" method="GET"
                                      class="d-flex gap-1">
                                    <input type="text" class="form-control ml-4" id="example-text-input"
                                           name="search" placeholder="Kod..." value="{{ request('search') }}">
                                    <select class="form-control" name="type" id="filter_type">
                                        <option value="">--Növü seçin--</option>
                                        @foreach(getEnvelopeTypes() as $key => $type)
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
                            <th>Kod</th>
                            <th>Kimdən</th>
                            <th>Kimə</th>
                            <th>Tarixi</th>
                            <th>Tipi</th>
                            <th class="text-center" style="width: 100px;">
                                İDARƏ
                            </th>
                        </tr>
                        </thead>
                        <tbody class="js-table-sections-header">
                        @foreach ($envelopes as $envelope)
                            <tr>
                                <th class="text-center"
                                    scope="row">{{ $envelopes->firstItem() + $loop->index }}</th>
                                <td class="fs-sm">
                                    {{ $envelope->code }}
                                </td>
                                <td class="fs-sm">
                                    @if($envelope->from_company_name)
                                        {{ $envelope->from_company_name }}
                                    @elseif($envelope->from_company_id)
                                        {{ $envelope->fromCompany?->company_name }}
                                    @endif
                                </td>
                                <td class="fs-sm">
                                    @if($envelope->to_company_name)
                                        {{ $envelope->to_company_name }}
                                    @elseif($envelope->to_company_id)
                                        {{ $envelope->toCompany?->company_name }}
                                    @endif
                                </td>
                                <td class="fs-sm">
                                    {{ \Carbon\Carbon::parse($envelope->sent_at)->format('Y-m-d') }}
                                </td>
                                <td class="fs-sm">
                                    {{ getLabelValue($envelope->type, getEnvelopeTypes())['label'] }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.envelopes.edit', $envelope->id) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="Edit"
                                           data-bs-original-title="Edit" title="Düzəliş et">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.envelopes.destroy', $envelope->id) }}"
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
                    {{ $envelopes->appends(request()->input())->links() }}
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
                title: 'Məktub silinsin?',
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
