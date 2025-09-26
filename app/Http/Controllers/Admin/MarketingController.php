<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MarketingStoreRequest;
use App\Http\Requests\Admin\MarketingUpdateRequest;
use App\Models\Marketing;
use App\Models\MarketingAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MarketingController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        $items = Marketing::query()
            ->when($q, fn($x) => $x->where(function ($y) use ($q) {
                $y->where('name', 'like', "%$q%")
                    ->orWhere('code', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            }))
            ->when($status, fn($x) => $x->where('status', $status))
            ->withCount('agents')
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Admin/Marketing/Index', [
            'items' => $items,
            'filters' => ['q' => $q, 'status' => $status],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Marketing/Form', [
            'item' => [
                'name' => '',
                'phone' => '',
                'email' => '',
                'status' => 'active',
                'notes' => '',
                'agents' => []
            ],
            'isEdit' => false,
            'suggestedCode' => Marketing::nextCode()
        ]);
    }

    public function store(MarketingStoreRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $marketing = Marketing::create([
                'code' => Marketing::nextCode(),
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($data['agents'] ?? [] as $ag) {
                $marketing->agents()->create([
                    'name' => $ag['name'],
                    'phone' => $ag['phone'] ?? null,
                    'email' => $ag['email'] ?? null,
                    'commission_rate' => $ag['commission_rate'] ?? 0,
                    'status' => $ag['status'] ?? 'active',
                    'notes' => $ag['notes'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.marketing.index')->with('success', 'Marketing berhasil dibuat.');
    }

    public function edit(Marketing $marketing)
    {
        $marketing->load('agents');

        return Inertia::render('Admin/Marketing/Form', [
            'item' => [
                'id' => $marketing->id,
                'code' => $marketing->code,
                'name' => $marketing->name,
                'phone' => $marketing->phone,
                'email' => $marketing->email,
                'status' => $marketing->status,
                'notes' => $marketing->notes,
                'agents' => $marketing->agents->map(fn($a) => [
                    'id' => $a->id,
                    'name' => $a->name,
                    'phone' => $a->phone,
                    'email' => $a->email,
                    'commission_rate' => $a->commission_rate,
                    'status' => $a->status,
                    'notes' => $a->notes
                ])->values(),
            ],
            'isEdit' => true,
            'suggestedCode' => $marketing->code,
        ]);
    }

    public function update(MarketingUpdateRequest $request, Marketing $marketing)
    {
        $data = $request->validated();

        DB::transaction(function () use ($marketing, $data) {
            $marketing->update([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Sinkronisasi agents (upsert + delete)
            $existingIds = [];
            foreach ($data['agents'] ?? [] as $ag) {
                if (!empty($ag['_delete'])) {
                    if (!empty($ag['id'])) {
                        MarketingAgent::where('id', $ag['id'])->where('marketing_id', $marketing->id)->delete();
                    }
                    continue;
                }

                if (!empty($ag['id'])) {
                    $agent = MarketingAgent::where('id', $ag['id'])->where('marketing_id', $marketing->id)->firstOrFail();
                    $agent->update([
                        'name' => $ag['name'],
                        'phone' => $ag['phone'] ?? null,
                        'email' => $ag['email'] ?? null,
                        'commission_rate' => $ag['commission_rate'] ?? 0,
                        'status' => $ag['status'] ?? 'active',
                        'notes' => $ag['notes'] ?? null,
                    ]);
                    $existingIds[] = $agent->id;
                } else {
                    $agent = $marketing->agents()->create([
                        'name' => $ag['name'],
                        'phone' => $ag['phone'] ?? null,
                        'email' => $ag['email'] ?? null,
                        'commission_rate' => $ag['commission_rate'] ?? 0,
                        'status' => $ag['status'] ?? 'active',
                        'notes' => $ag['notes'] ?? null,
                    ]);
                    $existingIds[] = $agent->id;
                }
            }
            // (opsional) hapus agent yang tidak terkirim (jika ingin strict sync)
            // MarketingAgent::where('marketing_id',$marketing->id)->whereNotIn('id',$existingIds)->delete();
        });

        return redirect()->route('admin.marketing.index')->with('success', 'Marketing berhasil diperbarui.');
    }

    public function destroy(Marketing $marketing)
    {
        $marketing->delete();
        return back()->with('success', 'Marketing dihapus.');
    }
}
