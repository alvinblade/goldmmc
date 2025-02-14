@extends('admin.index')

@section('title')
    Ezamiyyət əmri əlavə et
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
                            Ezamiyyət əmri əlavə et
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.businessTripOrders.store') }}" method="POST"
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
                        <div class="col-lg-3 mb-4">
                            <label class="form-label" for="start_date">Ezamiyyətin başlama tarixi <span
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
                        <div class="col-lg-3 mb-4">
                            <label class="form-label" for="end_date">Ezamiyyətin bitmə tarixi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="js-flatpickr form-control" id="end_date"
                                   name="end_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                   value="{{ old('end_date') }}">
                            @error('end_date')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 mb-4">
                            <label class="form-label" for="order_date">Əmr tarixi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="js-flatpickr form-control" id="order_date"
                                   name="order_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                   value="{{ old('order_date') }}">
                            @error('order_date')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 mb-4">
                            <label class="form-label" for="city_name">Şəhər adı</label>
                            <input type="text" class="form-control" id="city_name" name="city_name"
                                   placeholder="Bakı şəhəri..." value="{{ old('city_name') }}">
                            @error('city_name')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <label class="form-label" for="first_part_of_order">İlk hissəsi <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="first_part_of_order" name="first_part_of_order"
                                      placeholder="Quba və Qusara biznes səfəri məqsədilə MMC-nin nizamnaməsinə uyğun olaraq...">{{ old('first_part_of_order') }}</textarea>
                            @error('first_part_of_order')
                            <div class="fs-6 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-12 mb-4">
                            <label class="form-label" for="business_trip_to">İkinci hissəsi (Hara ezamiyyətə
                                gedəcək)<span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="business_trip_to" name="business_trip_to"
                                      placeholder="Quba və Qusara...">{{ old('business_trip_to') }}</textarea>
                            @error('business_trip_to')
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
