<?php

namespace App\Http\Controllers\GoldMMC;

use App\Enums\CompanyMainDocuments;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainDocumentController extends Controller
{
    public function companyMainDocuments(Request $request): View|RedirectResponse
    {
        $request->validate([
            'type' => ['nullable', 'string', 'in:' . CompanyMainDocuments::toString()]
        ]);

        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $company = Company::query()->find($companyId);

        $type = $request->input('type');

        if ($company) {
            return match ($type) {
                CompanyMainDocuments::tax_id_number_files->value
                => view('admin.dashboard', ['tax_id_number_files' => $company->tax_id_number_files]),
                CompanyMainDocuments::charter_files->value
                => view('admin.dashboard', ['charter_files' => $company->charter_files]),
                CompanyMainDocuments::extract_files->value
                => view('admin.dashboard', ['extract_files' => $company->extract_files]),
                CompanyMainDocuments::director_id_card_files->value
                => view('admin.dashboard', ['director_id_card_files' => $company->director_id_card_files]),
                CompanyMainDocuments::creators_files->value
                => view('admin.dashboard', ['creators_files' => $company->creators_files]),
                CompanyMainDocuments::fixed_asset_files->value
                => view('admin.dashboard', ['fixed_asset_files' => $company->fixed_asset_files]),
                CompanyMainDocuments::founding_decision_files->value
                => view('admin.dashboard', ['founding_decision_files' => $company->founding_decision_files]),
                default => view('admin.dashboard', [
                    'tax_id_number_files' => $company->tax_id_number_files,
                    'charter_files' => $company->charter_files,
                    'extract_files' => $company->extract_files,
                    'director_id_card_files' => $company->director_id_card_files,
                    'creators_files' => $company->creators_files,
                    'fixed_asset_files' => $company->fixed_asset_files,
                    'founding_decision_files' => $company->founding_decision_files,
                ]),
            };
        } else {
            toast('Sənəd tapılmadı', 'error');
            return redirect()->back();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function downloadCompanyMainDocument($company, $type)
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        $company = Company::query()->find($companyId);

        if ($company) {
            $file = match ($type) {
                CompanyMainDocuments::tax_id_number_files->value => $company->tax_id_number_files,
                CompanyMainDocuments::charter_files->value => $company->charter_files,
                CompanyMainDocuments::extract_files->value => $company->extract_files,
                CompanyMainDocuments::director_id_card_files->value => $company->director_id_card_files,
                CompanyMainDocuments::creators_files->value => $company->creators_files,
                CompanyMainDocuments::fixed_asset_files->value => $company->fixed_asset_files,
                CompanyMainDocuments::founding_decision_files->value => $company->founding_decision_files,
            };

            if ($file) {
                $s3 = App::make('aws')->createClient('s3');

                $object = $s3->getObject([
                    'Bucket' => $type,
                    'Key' => $company->$type[0]['generated_name'],
                ]);

                return response($object->get('Body'), 200, [
                    'Content-Type' => $object['@metadata']['headers']['content-type'],
                    'Content-Length' => $object['@metadata']['headers']['content-length']
                ]);
            }
        }
    }
}
