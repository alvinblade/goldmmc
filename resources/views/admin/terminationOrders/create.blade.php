@extends('admin.index')

@section('title')
    Xitam əmri əlavə et
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
                            Xitam əmri əlavə et
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.terminationOrders.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mb-4">
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
                            <label class="form-label" for="employment_start_date">İşə başlama tarixi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="js-flatpickr form-control" id="employment_start_date"
                                   name="employment_start_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                   value="{{ old('employment_start_date') }}">
                            @error('employment_start_date')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label" for="termination_date">Xitam verilmə tarixi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="js-flatpickr form-control" id="termination_date"
                                   name="termination_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                   value="{{ old('termination_date') }}">
                            @error('termination_date')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label" for="days_count">Gün sayı (k.g) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="days_count"
                                   name="days_count"
                                   value="{{ old('days_count') }}">
                            @error('days_count')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <label class="form-label" for="main_part_of_order">Əsas(lar) <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="main_part_of_order" name="main_part_of_order"
                                      placeholder="X.X.Soyadinin ərizəsi və XX.XX.20XX il tarixli əmək müqaviləsi...">{{ old('main_part_of_order') }}</textarea>
                            @error('main_part_of_order')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary w-100">
                        Əlavə et
                    </button>
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
