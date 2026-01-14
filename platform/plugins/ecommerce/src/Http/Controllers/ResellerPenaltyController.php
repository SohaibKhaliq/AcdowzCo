<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ResellerPenalty;
use Botble\Ecommerce\Models\ResellerWallet;
use Botble\Ecommerce\Tables\ResellerPenaltyTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerPenaltyController extends BaseController
{
    public function index(ResellerPenaltyTable $table)
    {
        PageTitle::setTitle(trans('plugins/ecommerce::reseller.penalties.name'));

        return $table->renderTable();
    }

    public function create()
    {
        PageTitle::setTitle(trans('plugins/ecommerce::reseller.penalties.create'));

        $resellers = Customer::query()
            ->where('is_reseller_active', true)
            ->get()
            ->pluck('name', 'id');

        return view('plugins/ecommerce::reseller-penalties.create', compact('resellers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reseller_id' => 'required|exists:ec_customers,id',
            'order_id' => 'nullable|exists:ec_orders,id',
            'product_id' => 'nullable|exists:ec_products,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:1000',
        ]);

        $validated['issued_by'] = Auth::id();
        $validated['status'] = 'applied';

        $penalty = ResellerPenalty::query()->create($validated);

        // Apply penalty to wallet
        $wallet = ResellerWallet::query()->firstOrCreate(
            ['reseller_id' => $validated['reseller_id']],
            ['balance' => 0, 'is_blocked' => false]
        );

        $wallet->applyPenalty($validated['amount']);

        event(new CreatedContentEvent('RESELLER_PENALTY', $request, $penalty));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('ecommerce.reseller-penalties.index'))
            ->setNextUrl(route('ecommerce.reseller-penalties.index'))
            ->withCreatedSuccessMessage();
    }

    public function show(int|string $id)
    {
        $penalty = ResellerPenalty::query()
            ->with(['reseller', 'order', 'product', 'issuedBy', 'wallet'])
            ->findOrFail($id);

        PageTitle::setTitle(trans('plugins/ecommerce::reseller.penalties.view', ['id' => $id]));

        return view('plugins/ecommerce::reseller-penalties.show', compact('penalty'));
    }

    public function reverse(int|string $id)
    {
        $penalty = ResellerPenalty::query()->findOrFail($id);

        if ($penalty->status === 'reversed') {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::reseller.penalties.already_reversed'));
        }

        $penalty->reverse();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('ecommerce.reseller-penalties.index'))
            ->setMessage(trans('plugins/ecommerce::reseller.penalties.reversed_success'));
    }

    public function getOrders(Request $request)
    {
        $resellerId = $request->input('reseller_id');
        
        if (!$resellerId) {
            return response()->json([]);
        }

        $orders = Order::query()
            ->whereHas('resellerOrder', function ($query) use ($resellerId) {
                $query->where('reseller_id', $resellerId);
            })
            ->select('id', 'code')
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'text' => $order->code,
            ]);

        return response()->json($orders);
    }
}
