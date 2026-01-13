<?php

use Botble\SimpleSlider\Support\SimpleSliderSupport;

app()->booted(function (): void {
    if (is_plugin_active('simple-slider')) {
        SimpleSliderSupport::registerResponsiveImageSizes();
    }
});
