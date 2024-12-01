<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(''); ?></title>
    <?php if (get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true)) : ?>
        <meta name="description" content="<?php echo get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true); ?>">
    <?php else : ?>
        <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>><header class="<?php echo is_single() ? 'single-header' : 'main-header'; ?>">
    <div class="header-container">
        <?php if (!is_single()) : ?>
            <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
            <p><?php bloginfo('description'); ?></p>
            <hr>
        <?php else : ?>
            <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
        <?php endif; ?>
        
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="hamburger"></span>
        </button>
    </div>
    
    <div class="search-overlay">
        <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
            <input type="search" class="search-field" placeholder="Поиск..." value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="search-submit">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="search-close">
                <i class="fas fa-times"></i>
            </button>
        </form>
    </div>
    
    <nav class="main-navigation" role="navigation" aria-label="Главное меню">
        <?php wp_nav_menu(array(
            'theme_location' => 'main-menu',
            'container' => false,
            'menu_class' => 'horizontal-menu'
        )); ?>
        
        <!-- Форма поиска для мобильного меню -->
        <form role="search" method="get" class="mobile-search-form" action="<?php echo home_url('/'); ?>">
            <input type="search" class="mobile-search-field" placeholder="Поиск..." value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="mobile-search-submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Кнопка смены темы только в мобильном меню -->
        <button id="theme-toggle-mobile" class="theme-toggle theme-toggle-mobile" aria-label="Toggle dark mode">
            <i class="fas fa-moon"></i>
            <span>Сменить тему</span>
        </button>

        <!-- Добавляем кнопку поиска для десктопа -->
        <button class="search-toggle desktop-search" aria-label="Search">
            <i class="fas fa-search"></i>
        </button>
    </nav>

    <!-- Кнопка смены темы для десктопа -->
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark mode">
        <i class="fas fa-moon"></i>
    </button>
</header>
