<?php

namespace App\Http\Controllers\GoldMMC\Excel;

use App\Exports\FixedAsset\FixedAssetExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FixedAssetExcelController extends Controller
{

    public function exportFixedAssetsExcel(Request $request): JsonResponse|BinaryFileResponse
    {
//        $companyId = getHeaderCompanyId();
//
//        if (!$companyId) {
//            return $this->error(message: "Şirkət tapılmadı", code: 404);
//        }

        $req = $request->validate([
            'year' => ['required', 'integer']
        ]);

        $req['company_id'] = 1;

        return Excel::download(new FixedAssetExport($req), "amortizasiya_cədvəli_" . $req['year'] . ".xlsx");
    }
}
