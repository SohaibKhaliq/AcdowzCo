<?php

namespace Botble\Base\Supports;

class Filter extends ActionHookEvent
{
    public function fire(string $action, array $args)
    {
        $value = $args[0] ?? ''; // get the value, the first argument is always the value

        $filters = $this->getListeners();

        if (! $filters) {
            return $value;
        }

        foreach ($filters as $hook => $listeners) { // go through each of the priorities
            ksort($listeners);
            foreach ($listeners as $arguments) { // loop all hooks
                if ($hook === $action) { // if the hook responds to the current filter
                    $parameters = [$value];
                    for ($index = 1; $index < $arguments['arguments']; $index++) {
                        if (isset($args[$index])) {
                            $parameters[] = $args[$index]; // add arguments if it is there
                        }
                    }
                    // filter the value
                    $value = call_user_func_array($this->getFunction($arguments['callback']), $parameters);
                }
            }
        }

        return $value;
    }
}
