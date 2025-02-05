<?php

namespace App\Http\Controllers\GoldMMC\Users;

use App\Enums\PeriodTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Company\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with(['roles'])
            ->paginate(10);

        if ($request->filled('search')) {
            $users = User::query()
                ->with(['roles'])
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('surname', 'like', '%' . $request->search . '%')
                        ->orWhere('father_name', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%');
                })
                ->paginate(10);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::query()->get();
        $companies = Company::query()
            ->whereNull('accountant_id')
            ->get();

        return view('admin.users.create', compact('roles', 'companies'));
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $lowerCases = array_map('strtolower', $request->only('email', 'username'));
        $password = ['password' => Hash::make($request->password)];
        $birthDate = ['birth_date' => Carbon::parse($request->birth_date)->format('Y-m-d')];
        $data = array_merge($data, $lowerCases, $password, $birthDate);

        if ($request->hasFile('education_files', [])) {
            $eduFiles = $request->file('education_files');
            $data = array_merge($data, ['education_files' => returnFilesArray($eduFiles, 'education_files')]);
        }

        if ($request->hasFile('cv_files', [])) {
            $cvFiles = $request->file('cv_files');
            $data = array_merge($data, ['cv_files' => returnFilesArray($cvFiles, 'cv_files')]);
        }

        if ($request->hasFile('self_photo_files', [])) {
            $ppPhotos = $request->file('self_photo_files');
            $data = array_merge($data, ['self_photo_files' => returnFilesArray($ppPhotos, 'self_photo_files')]);
        }

        if ($request->hasFile('certificate_files', [])) {
            $ctFiles = $request->file('certificate_files');
            $data = array_merge($data, ['certificate_files' => returnFilesArray($ctFiles, 'certificate_files')]);
        }

        DB::beginTransaction();
        $user = User::query()->create($data);

        if ($request->input('role_name') != null) {
            $role = Role::query()
                ->where('name', '=', $request->input('role_name'))
                ->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

        $assignResult = null;
        $type = 'error';
        if (is_array($request->input('company_ids')) && !empty($request->input('company_ids'))) {
            $assignResult = $user->assignCompanies($request->input('company_ids'));
        }

        switch ($assignResult) {
            case 2:
                DB::rollBack();
                $message = "Ən çox 10 fiziki şirkətə xidmət göstərilə bilər";
                break;
            case 3:
                DB::rollBack();
                $message = 'Ən çox 5 hüquqi şirkətə xidmət göstərilə bilər';
                break;
            case 1:
                DB::commit();
                $type = 'success';
                $message = 'İstifadəçi uğurla əlavə olundu';
                break;
            default:
                DB::rollBack();
                $message = 'İstifadəçi əlavə olunmadı';
                break;
        }

        toast($message, $type);
        return redirect()->route('admin.users.index');
    }

    public function edit($user): View|RedirectResponse
    {
        $user = User::query()->with(['companiesServed'])->find($user);

        $companiesServedIds = $user->companiesServed->pluck('id')->toArray();

        if (!$user) {
            toast("İstifadəçi tapılmadı", 'error');
            return redirect()->route('admin.users.index');
        }

        $roles = Role::query()->get();
        $companies = Company::query()
            ->where('accountant_id', '=', $user->id)
            ->orWhereNull('accountant_id')
            ->get();

        return view('admin.users.edit', compact('user', 'roles', 'companies', 'companiesServedIds'));
    }

    public function update(UserUpdateRequest $request, $user): RedirectResponse
    {
        $data = $request->except('password');
        $lowerCases = array_map('strtolower', $request->only('email', 'username'));
        $birthDate = ['birth_date' => Carbon::parse($request->birth_date)->format('Y-m-d')];
        $data = array_merge($data, $lowerCases, $birthDate);

        if ($request->filled('password')) {
            $password = ['password' => Hash::make($request->password)];
            $data = array_merge($data, $lowerCases, $password);
        }

        $user = User::query()->with(['roles'])->find($user);

        if (!$user) {
            toast("İstifadəçi tapılmadı", 'error');
            return redirect()->route('admin.users.index');
        }

        DB::beginTransaction();

        if ($request->filled('delete_education_files') && $request->delete_education_files != null) {
            $deletedEduFiles = json_decode('[' . $request->input('delete_education_files') . ']', true) ?? [];
            $educationFiles = $user->education_files ?? [];
            $deletedFiles = deleteFiles($deletedEduFiles, $educationFiles);
            $user->education_files = array_values($deletedFiles);
        }
        if ($request->filled('delete_cv_files') && $request->delete_cv_files != null) {
            $deletedCVFiles = json_decode('[' . $request->input('delete_cv_files') . ']', true) ?? [];
            $cvFiles = $user->cv_files ?? [];
            $deletedFiles = deleteFiles($deletedCVFiles, $cvFiles);
            $user->cv_files = array_values($deletedFiles);
        }
        if ($request->filled('delete_self_photo_files') && $request->delete_self_photo_files != null) {
            $deletedPPs = json_decode('[' . $request->input('delete_self_photo_files') . ']', true) ?? [];
            $ppFiles = $user->self_photo_files ?? [];
            $deletedFiles = deleteFiles($deletedPPs, $ppFiles);
            $user->self_photo_files = array_values($deletedFiles);
        }
        if ($request->filled('delete_certificate_files') && $request->delete_certificate_files != null) {
            $deletedCTFiles = json_decode('[' . $request->input('delete_certificate_files') . ']', true) ?? [];
            $ctFiles = $user->certificate_files ?? [];
            $deletedFiles = deleteFiles($deletedCTFiles, $ctFiles);
            $user->certificate_files = array_values($deletedFiles);
        }

        if ($request->hasFile('education_files')) {
            $eduFiles = $request->file('education_files');
            $eduFilesArr = $user->education_files;
            $updatedFiles = returnFilesArray($eduFiles, 'education_files');
            $data = array_merge($data, ['education_files' => array_merge($eduFilesArr, $updatedFiles)]);
        }
        if ($request->hasFile('cv_files')) {
            $cvFiles = $request->file('cv_files');
            $cvArr = $user->cv_files;
            $updatedFiles = returnFilesArray($cvFiles, 'cv_files');
            $data = array_merge($data, ['cv_files' => array_merge($cvArr, $updatedFiles)]);
        }
        if ($request->hasFile('self_photo_files')) {
            $ppPhotos = $request->file('self_photo_files');
            $ppPhotosArr = $user->self_photo_files;
            $updatedFiles = returnFilesArray($ppPhotos, 'self_photo_files');
            $data = array_merge($data, ['self_photo_files' => array_merge($ppPhotosArr, $updatedFiles)]);
        }
        if ($request->hasFile('certificate_files')) {
            $ctFiles = $request->file('certificate_files');
            $ctFileUrls = $user->certificate_files;
            $updatedFiles = returnFilesArray($ctFiles, 'certificate_files');
            $data = array_merge($data, ['certificate_files' => array_merge($ctFileUrls, $updatedFiles)]);
        }

        $user->update($data);

        if ($request->input('role_name') !== null) {
            $role = Role::query()->where('name', $request->input('role_name'))->first();
            if ($role) {
                $user->syncRoles($role);
            }
        }

        $assignResult = null;

        if (is_array($request->input('company_ids')) && !empty($request->input('company_ids'))) {
            $assignResult = $user->assignCompanies($request->input('company_ids'));
        }

        $user->refresh();

        switch ($assignResult) {
            case 2:
                DB::rollBack();
                $message = "Ən çox 10 fiziki şirkətə xidmət göstərilə bilər";
                $type = 'error';
                break;
            case 3:
                DB::rollBack();
                $message = "Ən çox 5 hüquqi şirkətə xidmət göstərilə bilər";
                $type = 'error';
                break;
            default:
                DB::commit();
                $message = "İstifadəçi uğurla yeniləndi";
                $type = 'success';
                break;
        }

        toast($message, $type);

        return redirect()->route('admin.users.index');
    }

    public function show($user): View|RedirectResponse
    {
        $user = User::query()
            ->with(['roles', 'companiesServed'])
            ->find($user);

        if (!$user) {
            toast("İstifadəçi tapılmadı", "error");
            return redirect()->back();
        }

        return view('admin.users.show', compact('user'));
    }

    public function destroy($user): RedirectResponse
    {
        $user = User::query()->find($user);

        if (!$user) {
            toast("İstifadəçi tapılmadı", "error");
            return redirect()->back();
        }

        if ($user->cv_files != null && count($user->cv_files) > 0) {
            checkFilesAndDeleteFromStorage($user->cv_files);
        }

        if ($user->education_files != null && count($user->education_files) > 0) {
            checkFilesAndDeleteFromStorage($user->education_files);
        }

        if ($user->self_photo_files != null && count($user->self_photo_files) > 0) {
            checkFilesAndDeleteFromStorage($user->self_photo_files);
        }

        if ($user->certificate_files != null && count($user->certificate_files) > 0) {
            checkFilesAndDeleteFromStorage($user->certificate_files);
        }

        $user->delete();

        toast("İstifadəçi uğurla silindi", "success");
        return redirect()->route('admin.users.index');
    }

    public function changeStatusOfUser(Request $request, $user): JsonResponse
    {
        $request->validate([
            'account_status' => ['required', 'in:APPROVED,PENDING,REJECTED']
        ]);

        $user = User::query()->find($user);

        if (!$user) {
            return $this->error(message: "İstifadəçi tapılmadı", code: 404);
        }

        $user->update(['account_status' => $request->account_status]);

        return $this->success(message: "İstifadəçinin statusu uğurla yeniləndi");
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = "INVOICE-" . strtoupper(uniqid() . Str::random(5));
        } while (Receipt::query()->where('code', $code)->exists());

        return $code;
    }
}
