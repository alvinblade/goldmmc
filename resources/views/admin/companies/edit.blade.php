@extends('admin.index')

@section('title')
    Şirkət düzəlişi et
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
                            Şirkət düzəlişi et
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.companies.update', $company->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="company_name">Ad <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_name" name="company_name"
                                           placeholder="..." value="{{ old('company_name', $company->company_name) }}">
                                    @error('company_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="company_short_name">Qısa adı <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_short_name"
                                           name="company_short_name"
                                           placeholder="..."
                                           value="{{ old('company_short_name', $company->company_short_name) }}">
                                    @error('company_short_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="company_category">Kateqoriya <span
                                                class="text-danger">*</span></label>
                                    <select class="form-select" id="company_category" name="company_category">
                                        <option value="">--Seçin--</option>
                                        @foreach(getCompanyCategoryTypes() as $category)
                                            <option value="{{ $category['value'] }}"
                                                    @selected(old('company_category', $company->company_category) == $category['value'])
                                            >{{ $category['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_category')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="company_obligation">Öhdəlik <span
                                                class="text-danger">*</span></label>
                                    <select class="form-select" id="company_obligation" name="company_obligation">
                                        <option value="">--Seçin--</option>
                                        @foreach(getCompanyObligationTypes() as $type)
                                            <option value="{{ $type['value'] }}"
                                                    @selected(old('company_obligation', $company->company_obligation) == $type['value'])
                                            >{{ $type['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_obligation')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="company_emails">Elektron poçtlar</label>
                                    <select class="js-select2 form-select" id="company_emails"
                                            name="company_emails[]" style="width: 100%;"
                                            data-placeholder="..." multiple>
                                        @foreach(old('company_emails', $company->company_emails) as $email)
                                            <option value="{{ $email }}" selected>{{ $email }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_emails')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('company_emails.*'))
                                        @foreach($errors->get('company_emails.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="tax_id_number">VÖEN</label>
                                    <input type="text" class="form-control" id="tax_id_number" name="tax_id_number"
                                           placeholder="..."
                                           value="{{ old('tax_id_number', $company->tax_id_number) }}">
                                    @error('tax_id_number')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="tax_id_number_date">VÖEN alınma tarixi <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="tax_id_number_date"
                                           name="tax_id_number_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('tax_id_number_date',$company->tax_id_number_date) }}">
                                    @error('tax_id_number_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="dsmf_number">DSMF nömrəsi</label>
                                    <input type="text" class="form-control" id="dsmf_number" name="dsmf_number"
                                           placeholder="..." value="{{ old('dsmf_number',$company->dsmf_number) }}">
                                    @error('dsmf_number')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="is_vat_payer">ƏDV ödəyicisidir? <span
                                                class="text-danger">*</span></label>
                                    <select class="form-select" id="is_vat_payer" name="is_vat_payer">
                                        <option value="0" @selected(old('is_vat_payer',$company->is_vat_payer) == 0)>
                                            Xeyr
                                        </option>
                                        <option value="1" @selected(old('is_vat_payer',$company->is_vat_payer) == 1)>
                                            Bəli
                                        </option>
                                    </select>
                                    @error('is_vat_payer')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="accountant_id">Mühasib</label>
                                    <select class="form-select" id="accountant_id" name="accountant_id">
                                        <option value="">
                                            --Seçin--
                                        </option>
                                        @foreach($accountants as $accountant)
                                            <option value="{{ $accountant->id }}"
                                                    @selected(old('accountant_id',$company->accountant_id) == $accountant->id)>
                                                {{ $accountant->name }} {{ $accountant->surname }}
                                                ({{ $accountant->roles->first()->display_name_az}})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('accountant_id')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="director_id">Direktor</label>
                                    <select class="form-select" id="director_id" name="director_id">
                                        <option value="">
                                            --Seçin--
                                        </option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                    @selected(old('director_id',$company->director_id) == $employee->id)>
                                                {{ $employee->name }} {{ $employee->surname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('director_id')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="main_employee_id">Səlahiyyətli şəxs</label>
                                    <select class="form-select" id="main_employee_id" name="main_employee_id">
                                        <option value="">
                                            --Seçin--
                                        </option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                    @selected(old('main_employee_id',$company->main_employee_id) == $employee->id)>
                                                {{ $employee->name }} {{ $employee->surname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('main_employee_id')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="asan_sign">ASAN imza nömrəsi <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="asan_sign" name="asan_sign"
                                           placeholder="..." value="{{ old('asan_sign',$company->asan_sign) }}">
                                    @error('asan_sign')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="asan_sign_start_date">ASAN imza başlama tarixi <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="asan_sign_start_date"
                                           name="asan_sign_start_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('asan_sign_start_date',$company->asan_sign_start_date) }}">
                                    @error('asan_sign_start_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="asan_sign_expired_at">ASAN imza bitmə tarixi <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="asan_sign_expired_at"
                                           name="asan_sign_expired_at" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('asan_sign_expired_at',$company->asan_sign_expired_at) }}">
                                    @error('asan_sign_expired_at')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="asan_id">ASAN ID <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="asan_id" name="asan_id"
                                           placeholder="..." value="{{ old('asan_id',$company->asan_id) }}">
                                    @error('asan_id')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 mb-4">
                                    <label class="form-label" for="pin1">PIN1 <span
                                                class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="pin1" name="pin1"
                                           placeholder="..." value="{{ old('pin1',$company->pin1) }}">
                                    @error('pin1')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-2 mb-4">
                                    <label class="form-label" for="pin2">PIN2 <span
                                                class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="pin2" name="pin2"
                                           placeholder="..." value="{{ old('pin2',$company->pin2) }}">
                                    @error('pin2')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-2 mb-4">
                                    <label class="form-label" for="puk">PUK <span
                                                class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="puk" name="puk"
                                           placeholder="..." value="{{ old('puk',$company->puk) }}">
                                    @error('puk')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="statistic_code">Statistika kodu <span
                                                class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="statistic_code" name="statistic_code"
                                           placeholder="..."
                                           value="{{ old('statistic_code',$company->statistic_code) }}">
                                    @error('statistic_code')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="statistic_password">Statistika şifrəsi <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="statistic_password"
                                           name="statistic_password"
                                           placeholder="..."
                                           value="{{ old('statistic_password',$company->statistic_password) }}">
                                    @error('statistic_password')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="operator_azercell_account">Azercell hesabı <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="operator_azercell_account"
                                           name="operator_azercell_account"
                                           placeholder="..."
                                           value="{{ old('operator_azercell_account',$company->operator_azercell_account) }}">
                                    @error('operator_azercell_account')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-2 mb-4">
                                    <label class="form-label" for="operator_azercell_password">Azercell şifrəsi
                                        <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="operator_azercell_password"
                                           name="operator_azercell_password"
                                           placeholder="..."
                                           value="{{ old('operator_azercell_password',$company->operator_azercell_password) }}">
                                    @error('operator_azercell_password')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="ydm_account_email">YDM e-poçtu</label>
                                    <input type="text" class="form-control" id="ydm_account_email"
                                           name="ydm_account_email"
                                           placeholder="..."
                                           value="{{ old('ydm_account_email',$company->ydm_account_email) }}">
                                    @error('ydm_account_email')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-2 mb-4">
                                    <label class="form-label" for="ydm_password">YDM şifrəsi</label>
                                    <input type="text" class="form-control" id="ydm_password" name="ydm_password"
                                           placeholder="..." value="{{ old('ydm_password',$company->ydm_password) }}">
                                    @error('ydm_password')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-2 mb-4">
                                    <label class="form-label" for="ydm_card_expired_at">YDM bitmə tarixi</label>
                                    <input type="text" class="js-flatpickr form-control" id="ydm_card_expired_at"
                                           name="ydm_card_expired_at" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('ydm_card_expired_at',$company->ydm_card_expired_at) }}">
                                    @error('ydm_card_expired_at')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="tax_id_number_files">VÖEN faylları</label>
                                    <input type="file" class="form-control" id="tax_id_number_files"
                                           name="tax_id_number_files[]" multiple>
                                    @error('tax_id_number_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('tax_id_number_files.*'))
                                        @foreach($errors->get('tax_id_number_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($company->tax_id_number_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteTaxIdNumberFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="charter_files">Nizamnamə faylları</label>
                                    <input type="file" class="form-control" id="charter_files"
                                           name="charter_files[]" multiple>
                                    @error('charter_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('charter_files.*'))
                                        @foreach($errors->get('charter_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($company->charter_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteCharterFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="extract_files">Çıxarış faylları</label>
                                    <input type="file" class="form-control" id="extract_files"
                                           name="extract_files[]" multiple>
                                    @error('extract_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('extract_files.*'))
                                        @foreach($errors->get('extract_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($company->extract_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteExtractFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="director_id_card_files">Direktor şəxsiyyət vəsiqəsi
                                        faylları</label>
                                    <input type="file" class="form-control" id="director_id_card_files"
                                           name="director_id_card_files[]" multiple>
                                    @error('director_id_card_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('director_id_card_files.*'))
                                        @foreach($errors->get('director_id_card_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($company->director_id_card_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteDirectorIdCardFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="creators_files">Təsisçi faylları</label>
                                    <input type="file" class="form-control" id="creators_files"
                                           name="creators_files[]" multiple>
                                    @error('creators_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('creators_files.*'))
                                        @foreach($errors->get('creators_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($company->creators_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteDirectorIdCardFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-2">
                                    <label class="form-label" for="fixed_asset_files_exists">Mülkiyyətdə olan əsas
                                        vəsaitlərin mövcudluğu</label>
                                    <select class="form-select" id="fixed_asset_files_exists"
                                            name="fixed_asset_files_exists">
                                        <option
                                                value="0"
                                                @selected(old('fixed_asset_files_exists',$company->fixed_asset_files_exists) == 0)>
                                            Yoxdur
                                        </option>
                                        <option
                                                value="1"
                                                @selected(old('fixed_asset_files_exists',$company->fixed_asset_files_exists) == 1)>
                                            Var
                                        </option>
                                    </select>
                                    @error('fixed_asset_files_exists')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="fixed_asset_files">Mülkiyyətdə olan əsas vəsaitlərin
                                        faylları</label>
                                    <input type="file" class="form-control" id="fixed_asset_files"
                                           name="fixed_asset_files[]" multiple>
                                    @error('fixed_asset_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('fixed_asset_files.*'))
                                        @foreach($errors->get('fixed_asset_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($company->fixed_asset_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteFixedAssetFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="founding_decision_files">Təsisçi qərarı
                                        faylları</label>
                                    <input type="file" class="form-control" id="founding_decision_files"
                                           name="founding_decision_files[]" multiple>
                                    @error('founding_decision_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('founding_decision_files.*'))
                                        @foreach($errors->get('founding_decision_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    @foreach($company->founding_decision_files as $file)
                                        <div class="col-lg-2 position-relative text-center">
                                            <a target="_blank"
                                               href="{{$file['path']}}">
                                                <div class="bg-primary-light rounded text-center p-4">
                                                    <i class="fa fa-file-alt fs-1 text-secondary"></i>
                                                </div>

                                                <small class="badge bg-primary">{{ $file['original_name'] }}</small>
                                            </a>
                                            <button class="btn btn-danger
                                                     btn-sm position-absolute top-0 start-0"
                                                    type="button"
                                                    onclick="deleteFoundingDecisionFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Yenilə</button>
                        </div>
                    </div>
                    <input type="hidden" id="delete_tax_id_number_files" name="delete_tax_id_number_files"/>
                    <input type="hidden" id="delete_charter_files" name="delete_charter_files"/>
                    <input type="hidden" id="delete_extract_files" name="delete_extract_files"/>
                    <input type="hidden" id="delete_director_id_card_files" name="delete_director_id_card_files"/>
                    <input type="hidden" id="delete_creators_files" name="delete_creators_files"/>
                    <input type="hidden" id="delete_fixed_asset_files" name="delete_fixed_asset_files"/>
                    <input type="hidden" id="delete_founding_decision_files" name="delete_founding_decision_files"/>
                </form>
            </div>
        </div>
    </main>
@endsection
@section('page_scripts')
    <script src="{{asset('assets/admin/js/lib/jquery.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/az.js"></script>
    <script>
        deleteTaxIdNumberFiles = [];
        deleteCharterFiles = [];
        deleteExtractFiles = [];
        deleteDirectorIdCardFiles = [];
        deleteCreatorsFiles = [];
        deleteFixedAssetFiles = [];
        deleteFoundingDecisionFiles = [];

        function deleteTaxIdNumberFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteTaxIdNumberFiles.includes(JSON.stringify(file))) {
                    deleteTaxIdNumberFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_tax_id_number_files').val(deleteTaxIdNumberFiles);
            }
        }

        function deleteCharterFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteCharterFiles.includes(JSON.stringify(file))) {
                    deleteCharterFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_charter_files').val(deleteCharterFiles);
            }
        }

        function deleteExtractFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteExtractFiles.includes(JSON.stringify(file))) {
                    deleteExtractFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_extract_files').val(deleteExtractFiles);
            }
        }

        function deleteDirectorIdCardFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteDirectorIdCardFiles.includes(JSON.stringify(file))) {
                    deleteDirectorIdCardFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_director_id_card_files').val(deleteDirectorIdCardFiles);
            }
        }

        function deleteCreatorFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteCreatorsFiles.includes(JSON.stringify(file))) {
                    deleteCreatorsFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_creators_files').val(deleteCreatorsFiles);
            }
        }

        function deleteFixedAssetFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteFixedAssetFiles.includes(JSON.stringify(file))) {
                    deleteFixedAssetFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_fixed_asset_files').val(deleteFixedAssetFiles);
            }
        }

        function deleteFoundingDecisionFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteFoundingDecisionFiles.includes(JSON.stringify(file))) {
                    deleteFoundingDecisionFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_founding_decision_files').val(deleteFoundingDecisionFiles);
            }
        }

        $("#company_emails").select2({
            tags: true
        });
        flatpickr.localize(flatpickr.l10ns.az);
        One.helpersOnLoad(['js-flatpickr']);
    </script>
@endsection
