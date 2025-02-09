<?php

namespace App\Http\Controllers\GoldMMC\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\AwardOrder\AwardOrderStoreRequest;
use App\Http\Requests\Orders\AwardOrder\AwardOrderUpdateRequest;
use App\Models\Company\Company;
use App\Models\Company\Position;
use App\Models\Orders\AwardOrder;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PhpOffice\PhpWord\TemplateProcessor;

class AwardOrderController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $awardOrders = AwardOrder::query()
            ->where('company_id', $companyId)
            ->with('company')
            ->when($request->input('search'), function ($query) use ($request) {
                return $query->where('order_number', 'like', '%' . $request->input('search') . '%');
            })
            ->paginate($request->input('limit') ?? 10);

        return view('admin.awardOrders.index', compact('awardOrders'));
    }

    public function create(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();
        $zipArchie = new ZipArchive();
        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $positions = Position::query()->where('company_id', $companyId)->get();

        return view('admin.awardOrders.create', compact('positions'));
    }

    /**
     * @throws Exception
     * @throws CreateTemporaryFileException
     * @throws CopyFileException
     */
    public function store(AwardOrderStoreRequest $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $data = $request->validated();

        $company = $this->getCompany($companyId);
        $companyName = $company->company_name;
        $orderNumber = null;

        do {
            $orderNumber = generateOrderNumber(AwardOrder::class, $company->company_short_name);
        } while (AwardOrder::query()->where('order_number', $orderNumber)->exists());

        $orderDate = Carbon::parse($request->input('order_date'))->format('d.m.Y');
        $char = substr($orderDate, '-2');
        $lastCharOD = getNumberEnd($char);

        $data = array_merge($data, [
            'order_number' => $orderNumber,
            'company_name' => $companyName,
            'order_date' => $orderDate,
            'last_char_od' => $lastCharOD,
            'tax_id_number' => $company->tax_id_number,
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'company_id' => $companyId
        ]);

        $documentPath = public_path('assets/order_templates/AWARD.docx');
        $fileName = 'AWARD_ORDER_' . Str::slug($companyName . $orderNumber, '_') . '.docx';
        $filePath = public_path('assets/award_orders/' . $fileName);

        $templateProcessor = new TemplateProcessor($documentPath);
        $this->templateProcessor($templateProcessor, $filePath, $data);

        $awardOrder = AwardOrder::query()->create([
            'order_number' => $orderNumber,
            'company_id' => $companyId,
            'company_name' => $companyName,
            'tax_id_number' => $company->tax_id_number,
            'order_date' => $request->input('order_date'),
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'main_part_of_order' => $request->input('main_part_of_order'),
            'worker_infos' => $request->input('worker_infos')
        ]);

        $generatedFilePath = returnOrderFile('assets/award_orders/' . $fileName, $fileName, 'award_orders');

        $awardOrder->update([
            'generated_file' => $generatedFilePath
        ]);

        toast('Mükafat əmri uğurla yaradıldı', 'success');

        return redirect()->route('admin.awardOrders.index');
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function update(AwardOrderUpdateRequest $request, $awardOrder): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $data = $request->validated();

        $awardOrder = AwardOrder::query()
            ->where('company_id', $companyId)
            ->find($awardOrder);

        if (!$awardOrder) {
            toast('Mükafat əmri tapılmadı', 'error');
            return redirect()->route('admin.awardOrders.index');
        }

        $orderNumber = $awardOrder->order_number;
        $company = $this->getCompany($companyId);
        $companyName = $company->company_name;
        $orderDate = Carbon::parse($request->input('order_date'))->format('d.m.Y');

        $char = substr($orderDate, '-2');

        $lastCharOD = getNumberEnd($char);

        $data = array_merge($data, [
            'order_number' => $orderNumber,
            'tax_id_number' => $company->tax_id_number,
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'last_char_od' => $lastCharOD,
            'company_name' => $companyName,
            'order_date' => $orderDate,
            'company_id' => $companyId
        ]);

        $documentPath = public_path('assets/order_templates/AWARD.docx');
        $fileName = 'AWARD_ORDER_' . Str::slug($companyName . $orderNumber, '_') . '.docx';
        $filePath = public_path('assets/award_orders/' . $fileName);

        $currentFilePath = public_path($awardOrder->generated_file[0]['path']);
        if (file_exists($currentFilePath)) {
            unlink($currentFilePath);
        }

        $templateProcessor = new TemplateProcessor($documentPath);
        $this->templateProcessor($templateProcessor, $filePath, $data);

        $generatedFilePath = returnOrderFile('assets/award_orders/' . $fileName, $fileName, 'award_orders');

        $awardOrder->update([
            'company_id' => $companyId,
            'company_name' => $companyName,
            'tax_id_number' => $company->tax_id_number,
            'order_date' => $request->input('order_date'),
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'main_part_of_order' => $request->input('main_part_of_order'),
            'worker_infos' => $request->input('worker_infos'),
            'generated_file' => $generatedFilePath
        ]);

        toast('Mükafat əmri uğurla yeniləndi', 'success');

        return redirect()->route('admin.awardOrders.index');
    }

    public function show($awardOrder): RedirectResponse|View
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $awardOrder = AwardOrder::query()
            ->where('company_id', $companyId)->with(['company'])->find($awardOrder);

        if (!$awardOrder) {
            toast('Mükafat əmri tapılmadı', 'error');
            return redirect()->route('admin.awardOrders.index');
        }

        return view('admin.awardOrders.show', compact('awardOrder'));
    }

    public function edit($awardOrder): RedirectResponse|View
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $awardOrder = AwardOrder::query()
            ->where('company_id', $companyId)->with(['company'])->find($awardOrder);

        if (!$awardOrder) {
            toast('Mükafat əmri tapılmadı', 'error');
            return redirect()->route('admin.awardOrders.index');
        }

        return view('admin.awardOrders.edit', compact('awardOrder'));
    }

    private function getCompany($companyId): Builder|array|Collection|Model
    {
        return Company::query()->with(['mainEmployee', 'director'])->find($companyId);
    }

    private function templateProcessor(TemplateProcessor $templateProcessor, $filePath, $data): void
    {
        $templateProcessor->setValue('company_tax_id_number', $data['tax_id_number']);
        $templateProcessor->setValue('company_name', $data['company_name']);
        $templateProcessor->setValue('order_number', $data['order_number']);
        $templateProcessor->setValue('order_date', $data['order_date'] . $data['last_char_od']);
        $templateProcessor->setValue('d_name', $data['d_name']);
        $templateProcessor->setValue('d_surname', $data['d_surname']);
        $templateProcessor->setValue('d_father_name', $data['d_father_name']);
        $templateProcessor->setValue('main_part_of_order', $data['main_part_of_order']);
        $templateProcessor->cloneRowAndSetValues('position', $data['worker_infos']);
        $templateProcessor->saveAs($filePath);
    }

    public function destroy($awardOrder): RedirectResponse|View
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $awardOrder = AwardOrder::query()
            ->where('company_id', $companyId)
            ->find($awardOrder);

        if (!$awardOrder) {
            toast('Mükafat əmri tapılmadı', 'error');
            return redirect()->route('admin.awardOrders.index');
        }

        $filePath = public_path($awardOrder->generated_file[0]['path']);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $awardOrder->delete();

        toast('Mükafat əmri uğurla silindi', 'success');

        return redirect()->route('admin.awardOrders.index');
    }
}
