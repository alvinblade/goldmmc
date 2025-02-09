@extends('admin.index')

@section('title')
    Mükafat əmri düzəlişi et
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
                            Mükafat əmri düzəlişi et - {{ $awardOrder->order_number }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content">
            <div class="block-content block-content-full">
                <form action="{{ route('admin.awardOrders.update',$awardOrder->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="col-lg-12 mb-4">
                                <label class="form-label" for="order_date">Əmr tarixi <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-flatpickr form-control" id="order_date"
                                       name="order_date" placeholder="Gün-Ay-İl" data-date-format="Y-m-d"
                                       value="{{ old('order_date',$awardOrder->order_date) }}">
                                @error('order_date')
                                <div class="fs-6 text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="address">Əmrin əsas hissəsi</label>
                                    <textarea class="form-control" id="main_part_of_order" name="main_part_of_order"
                                              rows="4"
                                              placeholder="...">{{ old('main_part_of_order', $awardOrder->main_part_of_order) }}</textarea>
                                    @error('main_part_of_order')
                                    <div class="fs-6 text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div id="worker-container">
                                @foreach($awardOrder->worker_infos as $key => $info)
                                    <div class="worker-info row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Vəzifə</label>
                                            <input type="text"
                                                   class="form-control @error('worker_infos.'.$key.'.position') is-invalid @enderror"
                                                   name="worker_infos[{{$key}}][position]"
                                                   value="{{ old('worker_infos.'.$key.'.position',$info['position']) }}"
                                                   required>
                                            @error('worker_infos.'.$key.'.position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Maaş</label>
                                            <input type="number"
                                                   class="form-control @error('worker_infos.0.salary') is-invalid @enderror"
                                                   name="worker_infos[{{$key}}][salary]"
                                                   value="{{ old('worker_infos.'.$key.'.salary',$info['salary']) }}"
                                                   required>
                                            @error('worker_infos.'.$key.'.salary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-warning w-100 my-3" id="add-worker">Vəzifə/Maaş əlavə
                                et
                            </button>
                            <button class="btn btn-primary w-100">Yenilə</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let workerIndex = parseInt({{ count($awardOrder->worker_infos) }});

            document.getElementById('add-worker').addEventListener('click', function () {
                let workerContainer = document.getElementById('worker-container');
                let workerHtml = `
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Vəzifə</label>
                    <input type="text" class="form-control" name="worker_infos[${workerIndex}][position]" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Maaş</label>
                    <input type="text" class="form-control" name="worker_infos[${workerIndex}][salary]" required>
                </div>
                <button type="button" class="btn btn-danger w-100 remove-worker">Sil</button>
        `;
                let div = document.createElement('div');
                div.classList.add('worker-info', 'row');
                div.innerHTML = workerHtml;
                workerContainer.appendChild(div);
                workerIndex++;
            });

            document.getElementById('worker-container').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-worker')) {
                    event.target.parentElement.remove();
                }
            });
        });
    </script>
    <script src="{{asset('assets/admin/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/az.js"></script>
    <script>
        flatpickr.localize(flatpickr.l10ns.az);
        One.helpersOnLoad(['js-flatpickr', 'jq-select2']);
    </script>
@endsection
