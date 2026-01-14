<?php

namespace Tests\Unit;

use Botble\Table\Actions\Action;
use Tests\TestCase;

class TableActionDisplayIfTest extends TestCase
{
    public function test_display_if_hides_action_when_callback_false(): void
    {
        $action = Action::make('reverse')
            ->label('Reverse')
            ->displayIf(fn($item) => false);

        $model = new class {
            public $id = 1;
            public function getKey()
            {
                return $this->id;
            }
        };

        $action->setItem($model);

        $this->assertSame('', (string) $action);
    }

    public function test_display_if_shows_action_when_callback_true(): void
    {
        $action = Action::make('reverse')
            ->label('Reverse')
            ->displayIf(fn($item) => true)
            ->route('reseller-penalties.reverse');

        $model = new class {
            public $id = 1;
            public function getKey()
            {
                return $this->id;
            }
        };

        $action->setItem($model);

        $html = (string) $action;

        $this->assertStringContainsString('Reverse', $html);
    }
}
