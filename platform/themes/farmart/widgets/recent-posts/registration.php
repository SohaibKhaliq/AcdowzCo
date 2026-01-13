<?php

if (is_plugin_active('blog')) {
    require_once __DIR__ . '/recent-posts.php';

    register_widget(RecentPostsWidget::class);
}
