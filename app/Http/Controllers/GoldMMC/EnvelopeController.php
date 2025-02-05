<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Http\Requests\Envelope\EnvelopeStoreRequest;
use App\Http\Requests\Envelope\EnvelopeUpdateRequest;
use App\Models\Company\Company;
use App\Models\Company\Envelope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnvelopeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $envelopes = Envelope::query()
            ->where('company_id', $companyId)
            ->with([
                'fromCompany', 'toCompany'
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('code', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', '=', $request->type);
            })
            ->paginate(10);

        return view('admin.envelopes.index', compact('envelopes'));
    }

    public function show($envelope): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $envelope = Envelope::query()
            ->where('company_id', $companyId)
            ->with(['fromCompany', 'toCompany'])
            ->find($envelope);

        if (!$envelope) {
            toast("Sənəd tapılmadı", "error");
            return redirect()->back();
        }

        return view('admin.envelopes.show', compact('envelope'));
    }

    public function create(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $companies = Company::query()
            ->select('id', 'company_name')
            ->get();

        return view('admin.envelopes.create', compact('companies'));
    }

    public function store(EnvelopeStoreRequest $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $data = $request->validated();

        $data = array_merge($data, [
            'company_id' => $companyId
        ]);

        if ($request->hasFile('envelopes')) {
            $envelopes = $request->file('envelopes');
            $data = array_merge($data, ['envelopes' => returnFilesArray($envelopes,
                'envelopes')]);
        }

        $data = array_merge($data, [
            'code' => 'ENVP-' . Envelope::query()
                    ->where('type', '=', $request->type)
                    ->count() + 1 . '/' . date('Y') . '/' . $request->type,
            'sent_at' => now(),
            'creator_id' => auth()->user()->id
        ]);

        Envelope::query()->create($data);

        toast("Məktub uğurla əlavə olundu", "success");

        return redirect()->route('admin.envelopes.index');
    }

    public function edit($envelope): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $envelope = Envelope::query()
            ->where('company_id', $companyId)
            ->with(['fromCompany', 'toCompany'])
            ->find($envelope);

        if (!$envelope) {
            toast("Məktub tapılmadı", "error");
            return redirect()->back();
        }

        $companies = Company::query()
            ->select('id', 'company_name')
            ->get();

        return view('admin.envelopes.edit', compact('envelope', 'companies'));
    }

    public function update(EnvelopeUpdateRequest $request, $envelope): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $data = $request->validated();

        $data = array_merge($data, [
            'company_id' => $companyId
        ]);

        $envelope = Envelope::query()
            ->where('company_id', $companyId)->find($envelope);

        if (!$envelope) {
            toast("Məktub tapılmadı", "error");
            return redirect()->back();
        }

        if ($request->filled('delete_envelopes')) {
            $deletedEnvelopes = json_decode('[' . $request->input('delete_envelopes') . ']', true);
            $envelopes = $envelope->envelopes ?? [];
            $deletedFiles = deleteFiles($deletedEnvelopes, $envelopes);
            $envelope->envelopes = array_values($deletedFiles);
        }

        if ($request->hasFile('envelopes')) {
            $envelopes = $request->file('envelopes');
            $envelopesArr = $envelope->envelopes ?? [];
            $updatedFiles = returnFilesArray($envelopes, 'envelopes');
            $data = array_merge($data, ['envelopes' => array_merge($envelopesArr, $updatedFiles)]);
        }

        $envelope->update($data);

        toast("Məktub uğurla yeniləndi", "success");

        return redirect()->route('admin.envelopes.index');
    }

    public function destroy($envelope): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $envelope = Envelope::query()
            ->where('company_id', $companyId)
            ->find($envelope);

        if (!$envelope) {
            toast("Məktub tapılmadı", "error");
            return redirect()->back();
        }

        if ($envelope->envelopes != null && count($envelope->envelopes) > 0) {
            checkFilesAndDeleteFromStorage($envelope->envelopes);
        }

        $envelope->delete();

        toast("Məktub uğurla silindi", "success");

        return redirect()->route('admin.envelopes.index');
    }
}
