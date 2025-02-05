<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user()->load('companiesServed');
        $query = Company::query()->with(['mainEmployee', 'director']);

        if (!$authUser->hasRole(['leading_expert', 'department_head'])) {
            $companiesServed = $authUser->companiesServed()->pluck('id')->toArray();
            $query->whereIn('id', $companiesServed);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('company_name', 'like', "%$search%")
                    ->orWhere('tax_id_number', 'like', "%$search%")
                    ->orWhere('company_short_name', 'like', "%$search%");
            });
        }

        $companies = $query->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        $accountants = User::query()->with(['roles'])->get();

        return view('admin.companies.create', compact('accountants'));
    }

    public function edit($company): View
    {
        $company = Company::query()->with(['mainEmployee', 'director'])->findOrFail($company);
        $accountants = User::query()->with(['roles'])->get();
        $employees = Employee::query()->where('company_id', '=', $company->id)->get();

        return view('admin.companies.edit', compact('company', 'accountants', 'employees'));
    }

    public function store(CompanyStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $taxIdNumber = $request->input('tax_id_number');
        $taxIdNumberEndRule = Str::endsWith($taxIdNumber, '1') || Str::endsWith($taxIdNumber, '2');
        $data['accountant_assign_date'] = Carbon::now();

        if ($taxIdNumberEndRule) {
            $taxIdNumberEndsWithBool = Str::endsWith($taxIdNumber, '1'); // True -> LEGAL, False -> INDIVIDUAL
            $taxIdNumberEndsWithBool ? $data['owner_type'] = 'LEGAL' : $data['owner_type'] = 'INDIVIDUAL';
        } else {
            toast("VÖEN sonu 1 və ya 2 ilə bitməlidir", 'error');
            return redirect()->back();
        }

        $fixedAssetFilesExists = $request->input('fixed_asset_files_exists') ?? true;

        if ($request->hasFile('tax_id_number_files')) {
            $tinFiles = $request->file('tax_id_number_files');
            $data = array_merge($data, ['tax_id_number_files' => returnFilesArray($tinFiles, 'tax_id_number_files')]);
        }
        if ($request->hasFile('charter_files')) {
            $charterFiles = $request->file('charter_files');
            $data = array_merge($data, ['charter_files' => returnFilesArray($charterFiles, 'charter_files')]);
        }
        if ($request->hasFile('extract_files')) {
            $extractFiles = $request->file('extract_files');
            $data = array_merge($data, ['extract_files' => returnFilesArray($extractFiles, 'extract_files')]);
        }
        if ($request->hasFile('director_id_card_files')) {
            $idCardFiles = $request->file('director_id_card_files');
            $data = array_merge($data, ['director_id_card_files' => returnFilesArray($idCardFiles,
                'director_id_card_files')]);
        }
        if ($request->hasFile('creators_files')) {
            $creatorFiles = $request->file('creators_files', []);
            $data = array_merge($data, ['creators_files' => returnFilesArray($creatorFiles,
                'creators_files')]);
        }
        if ($request->hasFile('founding_decision_files')) {
            $foundingDecisionFiles = $request->file('founding_decision_files', []);
            $data = array_merge($data, ['founding_decision_files' => returnFilesArray($foundingDecisionFiles,
                'founding_decision_files')]);
        }
        if ($request->hasFile('fixed_asset_files') && $fixedAssetFilesExists) {
            $fixedAssetFiles = $request->file('fixed_asset_files', []);
            $data = array_merge($data, ['fixed_asset_files' => returnFilesArray($fixedAssetFiles,
                'fixed_asset_files')]);
        }

        Company::query()->create($data);

        toast("Şirkət uğurla əlavə olundu", 'success');

        return redirect()->route('admin.companies.index');
    }

    public function show($company): View|RedirectResponse
    {
        $company = Company::query()->with(['mainEmployee', 'employees', 'activityCodes', 'director'])->find($company);

        if (!$company) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->route('admin.companies.index');
        }

        return view('admin.companies.show', compact('company'));
    }

    public function update(CompanyUpdateRequest $request, $company): RedirectResponse
    {
        $data = $request->validated();

        $company = Company::query()->find($company);

        if (!$company) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->route('admin.companies.index');
        }

        $taxIdNumber = $request->input('tax_id_number');
        $taxIdNumberEndRule = Str::endsWith($taxIdNumber, '1') || Str::endsWith($taxIdNumber, '2');

        if ($taxIdNumberEndRule) {
            $taxIdNumberEndsWithBool = Str::endsWith($taxIdNumber, '1'); // True -> LEGAL, False -> INDIVIDUAL
            $taxIdNumberEndsWithBool ? $data['owner_type'] = 'LEGAL' : $data['owner_type'] = 'INDIVIDUAL';
        } else {
            toast("VÖEN sonu 1 və ya 2 ilə bitməlidir", 'error');
            return redirect()->back();
        }

        $fixedAssetFilesExists = $request->input('fixed_asset_files_exists');

        if ($request->has('delete_tax_id_number_files') && $request->delete_tax_id_number_files != null) {
            $deletedTinFiles = json_decode('[' . $request->input('delete_tax_id_number_files') . ']', true) ?? [];
            $tinFiles = $company->tax_id_number_files ?? [];
            $deletedFiles = deleteFiles($deletedTinFiles, $tinFiles);
            $company->tax_id_number_files = array_values($deletedFiles);
        }
        if ($request->has('delete_charter_files') && $request->delete_charter_files != null) {
            $deletedCharterFiles = json_decode('[' . $request->input('delete_charter_files') . ']', true) ?? [];
            $charterFiles = $company->charter_files ?? [];
            $deletedFiles = deleteFiles($deletedCharterFiles, $charterFiles);
            $company->charter_files = array_values($deletedFiles);
        }
        if ($request->has('delete_extract_files') && $request->delete_extract_files != null) {
            $deletedExtractFiles = json_decode('[' . $request->input('delete_extract_files') . ']', true) ?? [];
            $extractFiles = $company->extract_files ?? [];
            $deletedFiles = deleteFiles($deletedExtractFiles, $extractFiles);
            $company->extract_files = array_values($deletedFiles);
        }
        if ($request->has('delete_director_id_card_files') && $request->delete_director_id_card_files != null) {
            $deletedDirectorCardFiles = json_decode('[' . $request->input('delete_director_id_card_files') . ']', true) ?? [];
            $directorCardFiles = $company->director_id_card_files ?? [];
            $deletedFiles = deleteFiles($deletedDirectorCardFiles, $directorCardFiles);
            $company->director_id_card_files = array_values($deletedFiles);

        }
        if ($request->has('delete_creators_files') && $request->delete_creators_files != null) {
            $deletedCreatorsFiles = json_decode('[' . $request->input('delete_creators_files') . ']', true) ?? [];
            $creatorsFiles = $company->creators_files ?? [];
            $deletedFiles = deleteFiles($deletedCreatorsFiles, $creatorsFiles);
            $company->creators_files = array_values($deletedFiles);
        }
        if ($request->has('delete_founding_decision_files') && $request->delete_founding_decision_files != null) {
            $deletedFoundingDecisionFiles =
                json_decode('[' . $request->input('delete_founding_decision_files') . ']', true) ?? [];
            $foundingDecisionFiles = $company->founding_decision_files ?? [];
            $deletedFiles = deleteFiles($deletedFoundingDecisionFiles, $foundingDecisionFiles);
            $company->founding_decision_files = array_values($deletedFiles);
        }
        if ($request->has('delete_fixed_asset_files') && $request->delete_fixed_asset_files != null) {
            $deletedFixedAssetFiles = json_decode('[' . $request->input('delete_fixed_asset_files') . ']', true) ?? [];
            $fixedAssetFiles = $company->fixed_asset_files ?? [];
            $deletedFiles = deleteFiles($deletedFixedAssetFiles, $fixedAssetFiles);
            $company->fixed_asset_files = array_values($deletedFiles);
        }

        if ($request->hasFile('tax_id_number_files')) {
            $tinFiles = $request->file('tax_id_number_files');
            $tinFilesArr = $company->tax_id_number_files ?? [];
            $updatedFiles = returnFilesArray($tinFiles, 'tax_id_number_files');
            $data = array_merge($data, ['tax_id_number_files' => array_merge($tinFilesArr, $updatedFiles)]);
        }
        if ($request->hasFile('charter_files')) {
            $charterFiles = $request->file('charter_files');
            $charterFilesArr = $company->charter_files ?? [];
            $updatedFiles = returnFilesArray($charterFiles, 'charter_files');
            $data = array_merge($data, ['charter_files' => array_merge($charterFilesArr, $updatedFiles)]);
        }
        if ($request->hasFile('extract_files')) {
            $extractFiles = $request->file('extract_files');
            $extractFilesArr = $company->extract_files ?? [];
            $updatedFiles = returnFilesArray($extractFiles, 'extract_files');
            $data = array_merge($data, ['extract_files' => array_merge($extractFilesArr, $updatedFiles)]);
        }
        if ($request->hasFile('director_id_card_files')) {
            $directorCardFiles = $request->file('director_id_card_files');
            $directorCardFilesArr = $company->director_id_card_files ?? [];
            $updatedFiles = returnFilesArray($directorCardFiles, 'director_id_card_files');
            $data = array_merge($data, ['director_id_card_files' => array_merge($directorCardFilesArr, $updatedFiles)]);
        }
        if ($request->hasFile('creators_files')) {
            $creatorsFiles = $request->file('creators_files');
            $creatorsFilesArr = $company->creators_files ?? [];
            $updatedFiles = returnFilesArray($creatorsFiles, 'creators_files');
            $data = array_merge($data, ['creators_files' => array_merge($creatorsFilesArr, $updatedFiles)]);
        }
        if ($request->hasFile('founding_decision_files')) {
            $foundingDecisionFiles = $request->file('founding_decision_files');
            $foundingDecisionFilesArr = $company->founding_decision_files ?? [];
            $updatedFiles = returnFilesArray($foundingDecisionFiles, 'founding_decision_files');
            $data = array_merge($data, ['founding_decision_files' => array_merge($foundingDecisionFilesArr,
                $updatedFiles)]);
        }
        if ($request->hasFile('fixed_asset_files')) {
            $fixedAssetFiles = $request->file('fixed_asset_files');
            $fixedAssetFilesArr = $company->fixed_asset_files ?? [];
            $updatedFiles = returnFilesArray($fixedAssetFiles, 'fixed_asset_files');
            if ($fixedAssetFilesExists) {
                $data = array_merge($data, ['fixed_asset_files' => array_merge($fixedAssetFilesArr, $updatedFiles)]);
            } else {
                $data = array_merge($data, ['fixed_asset_files' => $fixedAssetFilesArr]);
            }
        }

        $company->update($data);

        toast("Şirkət uğurla yeniləndi", 'success');
        return redirect()->route('admin.companies.index');
    }

    public function destroy($company): RedirectResponse
    {
        $company = Company::query()->find($company);

        if (!$company) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        if ($company->tax_id_number_files != null && count($company->tax_id_number_files) > 0) {
            checkFilesAndDeleteFromStorage($company->tax_id_number_files);
        }

        if ($company->charter_files != null && count($company->charter_files) > 0) {
            checkFilesAndDeleteFromStorage($company->charter_files);
        }

        if ($company->extract_files != null && count($company->extract_files) > 0) {
            checkFilesAndDeleteFromStorage($company->extract_files);
        }

        if ($company->director_id_card_files != null && count($company->director_id_card_files) > 0) {
            checkFilesAndDeleteFromStorage($company->director_id_card_files);
        }

        if ($company->creators_files != null && count($company->creators_files) > 0) {
            checkFilesAndDeleteFromStorage($company->creators_files);
        }

        if ($company->founding_decision_files != null && count($company->founding_decision_files) > 0) {
            checkFilesAndDeleteFromStorage($company->founding_decision_files);
        }

        if ($company->fixed_asset_files != null && count($company->fixed_asset_files) > 0) {
            checkFilesAndDeleteFromStorage($company->fixed_asset_files);
        }

        $company->delete();

        toast("Şirkət uğurla silindi", 'success');

        return redirect()->route('admin.companies.index');
    }

    public function hasNotAccountantCompanies(Request $request): JsonResponse
    {
        $companies = Company::query()
            ->where('accountant_id', '=', null)
            ->paginate($request->input('limit') ?? 10);

        return $this->success(data: new CompanyCollection($companies));
    }

    public function individualCompanies(Request $request): JsonResponse
    {
        $companies = Company::query()
            ->where('owner_type', '=', UserTypesEnum::INDIVIDUAL)
            ->paginate($request->input('limit') ?? 10);

        return $this->success(data: new CompanyCollection($companies));
    }

    public function legalCompanies(Request $request): JsonResponse
    {
        $companies = Company::query()
            ->where('owner_type', '=', UserTypesEnum::LEGAL)
            ->paginate($request->input('limit') ?? 10);

        return $this->success(data: new CompanyCollection($companies));
    }
}
