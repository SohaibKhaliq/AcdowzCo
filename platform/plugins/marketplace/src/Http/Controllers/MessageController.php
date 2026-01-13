<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Models\Message;
use Botble\Marketplace\Tables\MessageTable;

class MessageController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/marketplace::message.name'), route('marketplace.messages.index'));
    }

    public function index(MessageTable $messageTable)
    {
        abort_unless(MarketplaceHelper::isEnabledMessagingSystem(), 404);

        $this->pageTitle(trans('plugins/marketplace::message.name'));

        return $messageTable->renderTable();
    }

    public function show(string $id)
    {
        abort_unless(MarketplaceHelper::isEnabledMessagingSystem(), 404);

        $message = Message::query()
            ->with(['store', 'customer'])
            ->findOrFail($id);

        $this->pageTitle(trans('plugins/marketplace::message.viewing_message', ['id' => $message->getKey()]));

        return view('plugins/marketplace::messages.show', compact('message'));
    }

    public function destroy(string $id)
    {
        abort_unless(MarketplaceHelper::isEnabledMessagingSystem(), 404);

        $message = Message::query()->findOrFail($id);

        return DeleteResourceAction::make($message);
    }
}
