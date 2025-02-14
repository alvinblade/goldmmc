@extends('admin.index')

@section('title')
    Tabel şablonu əlavə et
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
                            Tabel şablonu əlavə et - {{ request('year') }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <div class="row">
                    <h5>Gün statusları</h5>
                    <div class="col-lg-3">
                        <p>
                            <b>İ</b> - İstirahət günləri
                        </p>
                    </div>
                    <div class="col-lg-3">
                        <p>
                            <b>B</b> - Bayram günləri
                        </p>
                    </div>
                    <div class="col-lg-3">
                        <p>
                            <b>8</b> - 8 saatlıq iş qrafiki
                        </p>
                    </div>
                    <div class="col-lg-3">
                        <p>
                            <b>9</b> - 9 saatlıq iş qrafiki
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Gün statusları</h5>
                    <div class="col-lg-3">
                        <p>
                            <b>B.e</b> - Bazar ertəsi
                        </p>
                        <p>
                            <b>Ç.a</b> - Çərşənbə axşamı
                        </p>
                    </div>
                    <div class="col-lg-3">
                        <p>
                            <b>Ç</b> - Çərşənbə
                        </p>
                        <p>
                            <b>C.a</b> - Cümə axşamı
                        </p>
                    </div>
                    <div class="col-lg-3">
                        <p>
                            <b>C</b> - Cümə
                        </p>
                        <p>
                            <b>Ş</b> - Şənbə
                        </p>
                    </div>
                    <div class="col-lg-3">
                        <p>
                            <b>B</b> - Bazar
                        </p>
                    </div>
                </div>
                <hr>
                <form action="{{ route('admin.attendanceLogConfigs.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="year" value="{{ request('year') }}">
                    @foreach($config as $key => $data)
                        <h3>{{ mb_ucfirst($data['month_name']) }}</h3>
                        <input type="hidden" name="config[{{$key}}][month]" value="{{ $data['month'] }}">
                        <input type="hidden" name="config[{{$key}}][month_name]" value="{{ $data['month_name'] }}">
                        <div class="row">
                            @foreach($data['days'] as  $d => $day)
                                @php
                                    $status = $day['status'];
                                @endphp
                                <div class="col-lg-1 m-2">
                                    <div class="input-group d-flex flex-column align-items-center">
                                        <label for="config[{{$key}}][days][{{$d}}][status]"
                                               class="m-0 rounded-0 bg-primary text-white w-100 text-center"
                                               style="cursor: pointer">
                                            {{$day['day']}}
                                        </label>
                                        <input type="hidden" name="config[{{$key}}][days][{{$d}}][day]"
                                               value="{{ $day['day'] }}">
                                        <select name="config[{{$key}}][days][{{$d}}][status]"
                                                id="config[{{$key}}][days][{{$d}}][status]"
                                                class="form-control w-100 text-center">
                                            <option value="9" @selected($status == '9')>9</option>
                                            <option value="8" @selected($status == '8')>8</option>
                                            <option
                                                value="DAY_OF_CELEBRATION" @selected($status == 'DAY_OF_CELEBRATION')>
                                                B
                                            </option>
                                            <option value="REST_DAY" @selected($status == 'REST_DAY')>İ</option>
                                        </select>
                                        <label for="config[{{$key}}][days][{{$d}}][status]"
                                               class="m-0 rounded-0 bg-warning text-white w-100 text-center"
                                               style="cursor: pointer">
                                            {{ mb_ucfirst($day['day_name']) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Əlavə et</button>
                </form>
            </div>
        </div>
    </main>
@endsection
@section('page_scripts')
    <script>
        function validateInput(input) {
            input.value = input.value.replace(/[^İB]/g, ''); // Sadəcə "İ" və "B" qəbul edilir
        }
    </script>
    <script src="{{asset('assets/admin/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/az.js"></script>
    <script>
        flatpickr.localize(flatpickr.l10ns.az);
        One.helpersOnLoad(['js-flatpickr', 'jq-select2']);
    </script>
@endsection
