<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\TaxRuleForm;
use Botble\Ecommerce\Http\Requests\TaxRuleRequest;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Models\TaxRule;
use Botble\Ecommerce\Tables\TaxRuleTable;
use Illuminate\Http\Request;

class TaxRuleController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::tax.name'), route('ecommerce.settings.taxes'));
    }

    public function index(Tax $tax, TaxRuleTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::tax.rule.name', ['title' => $tax->title]));

        return $dataTable->renderTable();
    }

    public function create(Request $request)
    {
        $this->pageTitle(trans('plugins/ecommerce::tax.rule.create'));

        $form = TaxRuleForm::create()->renderForm();
        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData(['html' => $form])
                ->setMessage(PageTitle::getTitle(false));
        }

        return $form;
    }

    public function store(TaxRuleRequest $request)
    {
        $rule = TaxRule::query()->create($request->input());

        event(new CreatedContentEvent(TAX_RULE_MODULE_SCREEN_NAME, $request, $rule));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.edit', $rule->tax_id))
            ->setNextUrl(route('tax.rule.edit', $rule->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(TaxRule $rule, Request $request)
    {
        $this->breadcrumb()
            ->add($rule->tax->title, route('tax.edit', $rule->tax->id));

        $this->pageTitle(trans('plugins/ecommerce::tax.rule.edit', ['title' => '#' . $rule->getKey()]));

        $form = TaxRuleForm::createFromModel($rule)->renderForm();

        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData(['html' => $form])
                ->setMessage(PageTitle::getTitle(false));
        }

        return $form;
    }

    public function update(TaxRule $rule, TaxRuleRequest $request)
    {
        $rule->fill($request->input());
        $rule->save();

        event(new UpdatedContentEvent(TAX_RULE_MODULE_SCREEN_NAME, $request, $rule));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.edit', $rule->tax_id))
            ->setNextUrl(route('tax.rule.edit', $rule->id))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(TaxRule $rule)
    {
        return DeleteResourceAction::make($rule);
    }
}
