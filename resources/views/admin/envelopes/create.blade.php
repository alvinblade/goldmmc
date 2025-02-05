@extends('admin.index')

@section('title')
    Məktub əlavə et
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
                            Məktub əlavə et
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.envelopes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="type">Məktub növü <span
                                            class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select">
                                        <option>--Seçin--</option>
                                        @foreach(getEnvelopeTypes() as $key => $type)
                                            <option value="{{ $key }}"
                                                @selected(old('type') == $key)>
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
                                <div class="col-lg-6 mb-4 to_company_id">
                                    <label class="form-label" for="to_company_id">Kimə <span
                                            class="text-danger">*</span></label>
                                    <select name="to_company_id" id="to_company_id"
                                            class="js-select2 form-select" style="width: 100%;"
                                            data-placeholder="Seçin">
                                        <option></option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}"
                                                @selected(old('to_company_id') == $company->id)>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_company_id')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-4 from_company_name">
                                    <label class="form-label" for="from_company_name">Kimdən <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="from_company_name"
                                           name="from_company_name" value="{{ old('from_company_name') }}"/>
                                    @error('from_company_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-4 to_company_name">
                                    <label class="form-label" for="to_company_name">Kimə <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="to_company_name"
                                           name="to_company_name" value="{{ old('to_company_name') }}"/>
                                    @error('to_company_name')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-4 from_company_id">
                                    <label class="form-label" for="from_company_id">Kimdən <span
                                            class="text-danger">*</span></label>
                                    <select name="from_company_id" id="from_company_id" class="js-select2 form-select"
                                            style="width: 100%;" data-placeholder="Seçin">
                                        <option></option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}"
                                                @selected(old('from_company_id') == $company->id)>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_company_id')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="envelopes">Məktub faylları</label>
                                    <input type="file" class="form-control" id="envelopes"
                                           name="envelopes[]" multiple>
                                    @error('envelopes')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @if($errors->has('envelopes.*'))
                                        @foreach($errors->get('envelopes.*') as $fileErrors)
                                            @foreach($fileErrors as $error)
                                                <div class="fs-6 text-danger">
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Əlavə et</button>
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
    <script>
        let typeSelect = document.querySelector('#type');
        let toCompanyId = document.querySelector('.to_company_id');
        let toCompanyName = document.querySelector('.to_company_name');
        let fromCompanyId = document.querySelector('.from_company_id');
        let fromCompanyName = document.querySelector('.from_company_name');

        function showElement(element) {
            element.classList.remove('d-none');
        }

        function hideElement(element) {
            element.classList.add('d-none');
            element.value = ''; // Eğer input ise, değerini sıfırla
        }

        [toCompanyId, toCompanyName, fromCompanyId, fromCompanyName].forEach(hideElement);

        typeSelect.addEventListener('change', function () {
            let type = this.value;

            [toCompanyId, toCompanyName, fromCompanyId, fromCompanyName].forEach(hideElement);

            if (type === 'INCOMING') {
                showElement(toCompanyId);
                showElement(fromCompanyName);
            } else if (type === 'OUTGOING') {
                showElement(toCompanyName);
                showElement(fromCompanyId);
            }
        });
    </script>
@endsection
