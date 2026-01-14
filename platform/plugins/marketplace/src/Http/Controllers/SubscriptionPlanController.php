<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Forms\SubscriptionPlanForm;
use Botble\Marketplace\Http\Requests\SubscriptionPlanRequest;
use Botble\Marketplace\Models\SubscriptionPlan;
use Botble\Marketplace\Tables\SubscriptionPlanTable;
use Illuminate\Http\Request;

class SubscriptionPlanController extends BaseController
{
    public function index(SubscriptionPlanTable $table)
    {
        PageTitle::setTitle(trans('plugins/marketplace::subscription.plans.name'));

        return $table->renderTable();
    }

    public function create()
    {
        PageTitle::setTitle(trans('plugins/marketplace::subscription.plans.create'));

        return SubscriptionPlanForm::create()->renderForm();
    }

    public function store(SubscriptionPlanRequest $request)
    {
        $plan = SubscriptionPlan::query()->create($request->validated());

        event(new CreatedContentEvent('SUBSCRIPTION_PLAN', $request, $plan));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.subscription-plans.index'))
            ->setNextUrl(route('marketplace.subscription-plans.edit', $plan->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id)
    {
        $plan = SubscriptionPlan::query()->findOrFail($id);

        PageTitle::setTitle(trans('plugins/marketplace::subscription.plans.edit', ['name' => $plan->name]));

        return SubscriptionPlanForm::createFromModel($plan)->renderForm();
    }

    public function update(int|string $id, SubscriptionPlanRequest $request)
    {
        $plan = SubscriptionPlan::query()->findOrFail($id);
        $plan->update($request->validated());

        event(new UpdatedContentEvent('SUBSCRIPTION_PLAN', $request, $plan));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.subscription-plans.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id)
    {
        $plan = SubscriptionPlan::query()->findOrFail($id);

        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/marketplace::subscription.plans.cannot_delete_active'));
        }

        $plan->delete();

        event(new DeletedContentEvent('SUBSCRIPTION_PLAN', request(), $plan));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.subscription-plans.index'))
            ->withDeletedSuccessMessage();
    }
}
