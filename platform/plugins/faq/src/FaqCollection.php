<?php

namespace Botble\Faq;

use Botble\Faq\Models\Faq;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class FaqCollection implements Arrayable
{
    protected array $items = [];

    final public function __construct()
    {
    }

    public static function make(Collection $faqs): static
    {
        $collection = new static();

        foreach ($faqs as $faq) {
            if ($faq instanceof Faq) {
                $collection->push(new FaqItem($faq->question, $faq->answer));
            }
        }

        return $collection;
    }

    public function push(FaqItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function toArray(): array
    {
        return $this->items;
    }
}
