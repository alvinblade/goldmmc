@extends('admin.index')

@section('title')
    İstifadəçi - {{ $user->name }} {{ $user->surname }}
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
                            {{ $user->roles->first()->display_name_az }} - {{ $user->name }} {{ $user->surname }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.users.update',$user->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="name">Ad <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="..." value="{{ old('name',$user->name) }}">
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
                                           placeholder="..." value="{{ old('surname',$user->surname) }}">
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
                                           placeholder="..." value="{{ old('father_name',$user->father_name) }}">
                                    @error('father_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="username">İstifadəçi adı <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           placeholder="..." value="{{ old('username',$user->username) }}">
                                    @error('username')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="email">Elekton poçt <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="..." value="{{ old('email',$user->email) }}">
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
                                           placeholder="+994XXXXXXXXX" value="{{ old('phone',$user->phone) }}">
                                    @error('phone')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="password">Şifrə <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="...">
                                    @error('password')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="password_confirmation">Təkrar şifrə <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                           name="password_confirmation" placeholder="...">
                                    @error('password_confirmation')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="previous_job">Öncəki iş yeri</label>
                                    <input type="text" class="form-control" id="previous_job" name="previous_job"
                                           placeholder="..." value="{{ old('previous_job',$user->previous_job) }}">
                                    @error('previous_job')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="phone">Təhsil <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="education" name="education">
                                        <option value="">--Seçin--</option>
                                        @foreach(getEducationTypes() as $education)
                                            <option value="{{ $education['value'] }}"
                                                @selected(old('education') || $user->education == $education['value'])
                                            >{{ $education['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('phone')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="birth_date">Doğum tarixi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="birth_date"
                                           name="birth_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('birth_date', $user->birth_date) }}">
                                    @error('birth_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="role_name">Rol <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="role_name" name="role_name">
                                        <option value="">--Seçin--</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}"
                                                @selected(old('role_name') == $role->name || $user->hasRole($role->name))
                                            >{{ $role->display_name_az }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label" for="account_status">Status</label>
                                    <select class="form-select" id="account_status" name="account_status">
                                        <option value="">--Seçin--</option>
                                        @foreach(getAccountStatusTypes() as $status)
                                            <option value="{{ $status['value'] }}"
                                                @selected(old('account_status') || $user->account_status == $status['value'])
                                            >{{ $status['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_status')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="company_ids">Xidmət olunacaq şirkətlər</label>
                                    <select class="js-select2 form-select" id="company_ids"
                                            name="company_ids[]" style="width: 100%;"
                                            data-placeholder="..." multiple>
                                        <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}"
                                                @selected(in_array($company->id,$companiesServedIds))>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_ids')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('company_ids.*'))
                                        @foreach($errors->get('company_ids.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="cv_files">CV faylları</label>
                                    <input type="file" class="form-control" id="cv_files" name="cv_files[]" multiple>
                                    @error('cv_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('cv_files.*'))
                                        @foreach($errors->get('cv_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                    <div class="row mt-3">
                                        @foreach($user->cv_files as $file)
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
                                                        onclick="deleteCVFile(this.parentElement,{{ json_encode($file) }})">
                                                    <i class="fa fa-times text"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="certificate_files">Sertifikat faylları</label>
                                    <input type="file" class="form-control" id="certificate_files"
                                           name="certificate_files[]" multiple>
                                    @error('certificate_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('certificate_files.*'))
                                        @foreach($errors->get('certificate_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                    <div class="row mt-3">
                                        @foreach($user->certificate_files as $file)
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
                                                        onclick="deleteCertificateFile(this.parentElement,{{ json_encode($file) }})">
                                                    <i class="fa fa-times text"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="education_files">Təhsil faylları</label>
                                    <input type="file" class="form-control" id="education_files"
                                           name="education_files[]" multiple>
                                    @error('education_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('education_files.*'))
                                        @foreach($errors->get('education_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                    <div class="row mt-3">
                                        @foreach($user->education_files as $file)
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
                                                        onclick="deleteEducationFile(this.parentElement, {{ json_encode($file) }})">
                                                    <i class="fa fa-times text"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="self_photo_files">Şəkil faylları</label>
                                    <input type="file" class="form-control" id="self_photo_files"
                                           name="self_photo_files[]" multiple>
                                    @error('self_photo_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('self_photo_files.*'))
                                        @foreach($errors->get('self_photo_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                    <div class="row mt-3">
                                        @foreach($user->self_photo_files as $file)
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
                                                        onclick="deleteSelfPhotoFile(this.parentElement,{{ json_encode($file) }})">
                                                    <i class="fa fa-times text"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Yenilə</button>
                        </div>
                    </div>
                    <input type="hidden" name="delete_certificate_files" id="delete_certificate_files">
                    <input type="hidden" name="delete_education_files" id="delete_education_files">
                    <input type="hidden" name="delete_self_photo_files" id="delete_self_photo_files">
                    <input type="hidden" name="delete_cv_files" id="delete_cv_files">
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
    <script>
        const deleteCertificateFiles = [];
        const deleteEducationFiles = [];
        const deleteSelfPhotoFiles = [];
        const deleteCVFiles = [];

        function deleteCertificateFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteCertificateFiles.includes(JSON.stringify(file))) {
                    deleteCertificateFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_certificate_files').val(deleteCertificateFiles);
            }
        }

        function deleteEducationFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteEducationFiles.includes(JSON.stringify(file))) {
                    deleteEducationFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_education_files').val(deleteEducationFiles);
            }
        }

        function deleteSelfPhotoFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteSelfPhotoFiles.includes(JSON.stringify(file))) {
                    deleteSelfPhotoFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_self_photo_files').val(deleteSelfPhotoFiles);
            }
        }

        function deleteCVFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteCVFiles.includes(JSON.stringify(file))) {
                    deleteCVFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_cv_files').val(deleteCVFiles);
            }
        }
    </script>
@endsection
