@extends('admin.index')

@section('title')
    Mükafat əmri əlavə et
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
                            Mükafat əmri əlavə et
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.hiringOrders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 mb-4">
                            <label class="form-label" for="start_date">Əmr tarixi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="js-flatpickr form-control" id="start_date"
                                   name="start_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                   value="{{ old('start_date') }}">
                            @error('start_date')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-5 mb-4">
                            <label class="form-label" for="employee_id">Şirkət işçiləri</label>
                            <select class="form-select" id="employee_id" name="employee_id">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->name . ' ' . $employee->surname }}
                                        - {{ $employee->position?->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label" for="salary">Maaş</label>
                            <input type="number" class="form-control" id="salary" name="salary"
                                   placeholder="Maaş" value="{{ old('salary') }}">
                            @error('salary')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button class="btn btn-primary w-100">
                            Əlavə et
                        </button>
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
