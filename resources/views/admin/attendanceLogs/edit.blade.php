@extends('admin.index')

@section('title')
    Kirayə müqaviləsi düzəlişi et
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
                            Kirayə müqaviləsi düzəlişi et
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.rentalContracts.update', $rentalContract->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="object_name">Obyekt adı <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="object_name" name="object_name"
                                           placeholder="..."
                                           value="{{ old('object_name', $rentalContract->object_name) }}">
                                    @error('object_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="object_code">Obyekt kodu <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="object_code" name="object_code"
                                           placeholder="..."
                                           value="{{ old('object_code', $rentalContract->object_code) }}">
                                    @error('object_code')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="start_date">Başlama tarixi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="start_date"
                                           name="start_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('start_date', $rentalContract->start_date) }}">
                                    @error('start_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label" for="end_date">Bitmə tarixi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="end_date"
                                           name="end_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                           value="{{ old('end_date', $rentalContract->end_date) }}">
                                    @error('end_date')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="rental_area">Kirayə sahəsi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="rental_area" name="rental_area"
                                           placeholder="..."
                                           value="{{ old('rental_area', $rentalContract->rental_area) }}">
                                    @error('rental_area')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="rental_price">Kirayə qiyməti <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="rental_price" name="rental_price"
                                           placeholder="..."
                                           value="{{ old('rental_price', $rentalContract->rental_price) }}" step="0.01">
                                    @error('rental_price')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label" for="type">Kirayə növü <span
                                            class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="">--Seçin--</option>
                                        @foreach(getRentalTypes() as $key => $type)
                                            <option value="{{ $key }}"
                                                @selected(old('type', $rentalContract->type) == $key)>
                                                {{ $type['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="address">Ünvan</label>
                                    <textarea class="form-control" id="address" name="address" rows="4"
                                              placeholder="...">{{ old('address', $rentalContract->address) }}</textarea>
                                    @error('address')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="contract_files">Müqavilə faylları</label>
                                    <input type="file" class="form-control" id="contract_files"
                                           name="contract_files[]" multiple>
                                    @error('contract_files')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('contract_files.*'))
                                        @foreach($errors->get('contract_files.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row my-3">
                                    @foreach($rentalContract->contract_files as $file)
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
                                                    onclick="deleteContractFile(this.parentElement,{{ json_encode($file) }})">
                                                <i class="fa fa-times text"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Yenilə</button>
                        </div>
                    </div>
                    <input type="hidden" name="delete_contract_files" id="delete_contract_files"/>
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
        deleteContractFiles = [];

        function deleteContractFile(block, file) {
            if (confirm('Silmək istədiyinizə əminsiniz?')) {
                if (!deleteContractFiles.includes(JSON.stringify(file))) {
                    deleteContractFiles.push(JSON.stringify(file));
                    block.remove();
                }
                $('#delete_contract_files').val(deleteContractFiles);
            }
        }
    </script>
    <script>
        flatpickr.localize(flatpickr.l10ns.az);
        One.helpersOnLoad(['js-flatpickr', 'jq-select2']);
    </script>
@endsection
