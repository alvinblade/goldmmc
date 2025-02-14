@extends('admin.index')

@section('title')
    İstifadəçi - {{ $user->name . ' ' . $user->surname }}
@endsection

@section('content')
    <!-- Main Container -->
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-image" style="background-color: #1c2b48;
        ;background-position:top;">
            <div class="content content-full text-center">
                <div class="my-3">
                    <img class="img-avatar img-avatar-thumb"
                         src="{{ asset('assets/admin/media/avatars/avatar13.jpg') }}" alt="">
                </div>
                <h1 class="h2 text-white mb-0">{{ $user->name . ' ' . $user->surname }}</h1>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary m-2">Düzəliş et</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline m-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger js-swal-confirm">Sil</button>
                </form>
            </div>
        </div>
        </div>
        <!-- END Hero -->

        <!-- Stats -->
        <div class="bg-body-extra-light">
            <div class="content content-boxed">
                <div class="row items-push text-center">
                    <div class="col-6 col-md-3">
                        <div class="fs-sm fw-semibold text-muted text-uppercase">E-POÇT</div>
                        <a class="link-fx fs-6" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="fs-sm fw-semibold text-muted text-uppercase">ƏLAQƏ NÖMRƏSİ</div>
                        <a class="link-fx fs-6" href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="fs-sm fw-semibold text-muted text-uppercase">İstifadəçi adı</div>
                        <a class="link-fx fs-6"
                           href="javascript:void(0)">{{ $user->username }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Stats -->
    </main>
    <!-- END Main Container -->

    <script>
        $('button.js-swal-confirm').on('click', function () {
            var form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: 'İstifadəçi silinsin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Ləğv et',
                confirmButtonText: 'Sil'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            })
        });
    </script>
@endsection
