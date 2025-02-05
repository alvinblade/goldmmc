@extends('admin.index')

@section('title')
    İşçi düzəlişi et
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
                            İşçi düzəlişi et - {{ $employee->name . ' ' . $employee->surname }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.employees.update',$employee->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="name">Ad <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="..." value="{{ old('name',$employee->name) }}">
                                    @error('name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="surname">Soyad <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="surname" name="surname"
                                           placeholder="..." value="{{ old('surname',$employee->surname) }}">
                                    @error('surname')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="father_name">Ata adı <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="father_name" name="father_name"
                                           placeholder="..." value="{{ old('father_name',$employee->father_name) }}">
                                    @error('father_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="birth_date">Doğum tarixi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="birth_date"
                                           name="birth_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('birth_date',$employee->birth_date) }}">
                                    @error('birth_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="id_card_serial">ŞV seriyası (AZE/AA)<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="id_card_serial" name="id_card_serial"
                                           placeholder="..."
                                           value="{{ old('id_card_serial',$employee->id_card_serial) }}">
                                    @error('id_card_serial')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="fin_code">ŞV fin kodu <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="fin_code" name="fin_code"
                                           placeholder="..." value="{{ old('fin_code',$employee->fin_code) }}">
                                    @error('fin_code')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="id_card_date">ŞV verilmə tarixi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="id_card_date"
                                           name="id_card_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('id_card_date',$employee->id_card_date) }}">
                                    @error('id_card_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="ssn">SSN (Sosial Sığorta) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="ssn" name="ssn"
                                           placeholder="..." value="{{ old('ssn',$employee->ssn) }}">
                                    @error('ssn')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="start_date_of_employment">İşə qəbul tarixi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="start_date_of_employment"
                                           name="start_date_of_employment" placeholder="Gün-Ay-İl"
                                           data-date-format="Y-m-d"
                                           value="{{ old('start_date_of_employment',$employee->start_date_of_employment) }}">
                                    @error('start_date_of_employment')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="end_date_of_employment">İşdən çıxış tarixi</label>
                                    <input type="text" class="js-flatpickr form-control" id="end_date_of_employment"
                                           name="end_date_of_employment" placeholder="Gün-Ay-İl"
                                           data-date-format="Y-m-d"
                                           value="{{ old('end_date_of_employment',$employee->end_date_of_employment) }}">
                                    @error('end_date_of_employment')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="previous_job">Öncəki iş yeri</label>
                                    <input type="text" class="form-control" id="previous_job" name="previous_job"
                                           placeholder="..." value="{{ old('previous_job',$employee->previous_job) }}">
                                    @error('previous_job')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="work_experience">İş təcrübəsi (İL)</label>
                                    <input type="number" class="form-control" id="work_experience"
                                           name="work_experience"
                                           placeholder="..."
                                           value="{{ old('work_experience',$employee->work_experience) }}"
                                           step="0.1">
                                    @error('work_experience')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="education">Təhsil <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="education" name="education">
                                        <option value="">--Seçin--</option>
                                        @foreach(getEducationTypes() as $education)
                                            <option value="{{ $education['value'] }}"
                                                @selected(old('education',$employee->education) == $education['value'])
                                            >{{ $education['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('education')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="gender">Cins <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">--Seçin--</option>
                                        @foreach(getGenderTypes() as $gender)
                                            <option value="{{ $gender['value'] }}"
                                                @selected(old('gender',$employee->gender) == $gender['value'])
                                            >{{ $gender['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="email">Elekton poçt <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="..." value="{{ old('email',$employee->email) }}">
                                    @error('email')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="phone">Əlaqə nömrəsi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           placeholder="+994XXXXXXXXX" value="{{ old('phone',$employee->phone) }}">
                                    @error('phone')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="position_id">Vəzifə <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="position_id" name="position_id">
                                        <option value="">--Seçin--</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}"
                                                @selected(old('position_id',$employee->position_id) == $position->id)
                                            >{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="employee_type">İşçi tipi</label>
                                    <select class="form-select" id="employee_type" name="employee_type">
                                        <option value="">--Seçin--</option>
                                        @foreach(getEmployeeTypes() as $type)
                                            <option value="{{ $type['value'] }}"
                                                @selected(old('employee_type',$employee->employee_type) == $type['value'])
                                            >{{ $type['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('employee_type')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Yenilə</button>
                        </div>
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
