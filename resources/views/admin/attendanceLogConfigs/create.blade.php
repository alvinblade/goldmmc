@extends('admin.index')

@section('title')
    Tabel şablonu əlavə et
@endsection
@section('page_styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href={{asset('assets/admin/js/plugins/select2/css/select2.min.css')}}>
    <style>
        .fixed-width-col {
            width: 145px !important; /* İstədiyiniz eni təyin edin */
            flex: 0 0 auto !important; /* Bootstrap-in avtomatik enini sıfırlayır */
        }
    </style>
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
                {{--                <form action="{{ route('admin.attendanceLogConfigs.store') }}" method="POST"--}}
                {{--                      enctype="multipart/form-data">--}}
                {{--                    @csrf--}}
                {{--                    <input type="hidden" name="year" value="{{ request('year') }}">--}}
                {{--                    @foreach($config as $key => $data)--}}
                {{--                        <h3>{{ mb_ucfirst($data['month_name']) }}</h3>--}}
                {{--                        <input type="hidden" name="config[{{$key}}][month]" value="{{ $data['month'] }}">--}}
                {{--                        <input type="hidden" name="config[{{$key}}][month_name]" value="{{ $data['month_name'] }}">--}}
                {{--                        <div class="row">--}}
                {{--                            @foreach($dayNames as $dayName)--}}
                {{--                                <div class="col m-2">--}}
                {{--                                    <label for="config[{{$key}}][days][{{$dayName}}][status]"--}}
                {{--                                           class="m-0 rounded-0 bg-success text-white w-100 text-center"--}}
                {{--                                           style="cursor: pointer">--}}
                {{--                                        {{ mb_ucfirst($dayName) }}--}}
                {{--                                    </label>--}}
                {{--                                </div>--}}
                {{--                            @endforeach--}}
                {{--                        </div>--}}
                {{--                        @foreach(array_chunk($data['days'],7) as  $days)--}}
                {{--                            <div class="row">--}}
                {{--                                @foreach($days as  $d => $day)--}}
                {{--                                    @php--}}
                {{--                                        $status = $day['status'];--}}
                {{--                                    @endphp--}}
                {{--                                    <div class="col m-2">--}}
                {{--                                        <div class="input-group d-flex flex-column align-items-center">--}}
                {{--                                            <label for="config[{{$key}}][days][{{$d}}][status]"--}}
                {{--                                                   class="m-0 rounded-0 bg-primary text-white w-100 text-center"--}}
                {{--                                                   style="cursor: pointer">--}}
                {{--                                                {{$day['day']}}--}}
                {{--                                            </label>--}}
                {{--                                            <input type="hidden" name="config[{{$key}}][days][{{$d}}][day]"--}}
                {{--                                                   value="{{ $day['day'] }}">--}}
                {{--                                            <select name="config[{{$key}}][days][{{$d}}][status]"--}}
                {{--                                                    id="config[{{$key}}][days][{{$d}}][status]"--}}
                {{--                                                    class="form-control w-100 text-center">--}}
                {{--                                                <option value="9" @selected($status == '9')>9</option>--}}
                {{--                                                <option value="8" @selected($status == '8')>8</option>--}}
                {{--                                                <option--}}
                {{--                                                    value="DAY_OF_CELEBRATION" @selected($status == 'DAY_OF_CELEBRATION')>--}}
                {{--                                                    B--}}
                {{--                                                </option>--}}
                {{--                                                <option value="REST_DAY" @selected($status == 'REST_DAY')>İ</option>--}}
                {{--                                            </select>--}}
                {{--                                            <label for="config[{{$key}}][days][{{$d}}][status]"--}}
                {{--                                                   class="m-0 rounded-0 bg-warning text-white w-100 text-center"--}}
                {{--                                                   style="cursor: pointer">--}}
                {{--                                                {{ mb_ucfirst($day['day_name']) }}--}}
                {{--                                            </label>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                @endforeach--}}
                {{--                            </div>--}}
                {{--                            <hr>--}}
                {{--                        @endforeach--}}
                {{--                    @endforeach--}}
                {{--                    <button type="submit" class="btn btn-primary">Əlavə et</button>--}}
                {{--                </form>--}}
                <hr>
                <form action="{{ route('admin.attendanceLogConfigs.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="year" value="{{ request('year') }}">
                    @foreach($config as $key => $data)
                        <input type="hidden" name="config[{{$key}}][month]" value="{{ $data['month'] }}">
                        <input type="hidden" name="config[{{$key}}][month_name]" value="{{ $data['month_name'] }}">
                        <!-- Ayın adı -->
                        <h3>{{ mb_ucfirst($data['month_name']) }}</h3>

                        <!-- Həftənin günləri -->
                        <div class="row">
                            @foreach($dayNames as $dayName)
                                <div
                                    class="col fixed-width-col text-center text-white fw-bold p-1 bg-success border-end">
                                    {{ mb_ucfirst($dayName) }}
                                </div>
                            @endforeach
                        </div>

                        <!-- Ayın günləri -->
                        @php
                            // Ayın ilk gününü və həftənin günü nömrəsini tapın (1 = Bazar ertəsi, 7 = Bazar)
                            $firstDayOfMonth = Carbon\Carbon::createFromDate(request('year'), $data['month'], 1);
                            $firstDayName = $firstDayOfMonth->dayOfWeekIso; // 1-7 arası dəyər (Bazar ertəsi = 1)
                        @endphp

                        <div class="row">
                            <!-- Boş günlər (ayın ilk günündən əvvəlki boşluqlar) -->
                            @for($i = 1; $i < $firstDayName; $i++)
                                <div class="col fixed-width-col text-center p-2 border"></div>
                            @endfor

                            <!-- Ayın günləri -->
                            @foreach($data['days'] as $d => $day)
                                @php
                                    $status = $day['status'];
                                @endphp
                                <div class="col fixed-width-col text-center p-2 border">
                                    <!-- Gün nömrəsi -->
                                    <label for="config[{{$key}}][days][{{$d}}][status]"
                                           class="m-0 rounded-0 bg-primary text-white w-100 text-center"
                                           style="cursor: pointer">
                                        {{ $day['day'] }}
                                    </label>
                                    <input type="hidden" name="config[{{$key}}][days][{{$d}}][day]"
                                           value="{{ $day['day'] }}">

                                    <!-- Status seçimi -->
                                    <select name="config[{{$key}}][days][{{$d}}][status]"
                                            id="config[{{$key}}][days][{{$d}}][status]"
                                            class="form-control w-100 text-center rounded-0">
                                        <option value="6" @selected($status == '6')>6</option>
                                        <option value="7" @selected($status == '7')>7</option>
                                        <option value="8" @selected($status == '8')>8</option>
                                        <option value="9" @selected($status == '9')>9</option>
                                        <option value="DAY_OF_CELEBRATION" @selected($status == 'DAY_OF_CELEBRATION')>
                                            B
                                        </option>
                                        <option value="REST_DAY" @selected($status == 'REST_DAY')>İ</option>
                                    </select>

                                    <!-- Gün adı -->
                                    <label for="config[{{$key}}][days][{{$d}}][status]"
                                           class="m-0 rounded-0 bg-warning text-white w-100 text-center"
                                           style="cursor: pointer">
                                        {{ mb_ucfirst($day['day_name']) }}
                                    </label>
                                </div>

                                <!-- Hər 7 gündən sonra yeni sətir -->
                                @if(($d + $firstDayName) % 7 == 0)
                        </div>
                        <div class="row">
                            @endif
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
