<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true)) : ?>
        <meta name="description" content="<?php echo get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true); ?>">
    <?php else : ?>
        <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php endif; ?>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="archive-notice-bar">
        Это архив старого блога на русском языке. Все новое можно читать вот тут: <a href="https://blog.maxbasev.com">blog.maxbasev.com</a>
    </div>
    <header class="<?php echo is_single() ? 'single-header' : 'main-header'; ?>">
    <div class="header-container">
        <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
        
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="hamburger"></span>
        </button>
    </div>
    
    <?php if (!is_single()) : ?>
        <p class="site-description"><?php bloginfo('description'); ?></p>
        <hr class="desktop-only">
    <?php endif; ?>
    
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
        
        <!-- Search form for mobile menu -->
        <form role="search" method="get" class="mobile-search-form" action="<?php echo home_url('/'); ?>">
            <input type="search" class="mobile-search-field" placeholder="Поиск..." value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="mobile-search-submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Theme switch button only in mobile menu -->
        <button id="theme-toggle-mobile" class="theme-toggle theme-toggle-mobile" aria-label="Переключить тему">
            <i class="fas fa-moon"></i>
            <span>Сменить тему</span>
        </button>
    </nav>

    <!-- Theme control buttons for desktop -->
    <div class="header-controls">
        <!-- Search button for desktop -->
        <button class="search-toggle desktop-search" aria-label="Поиск">
            <i class="fas fa-search"></i>
        </button>
        
        <!-- Theme switch button for desktop -->
        <button id="theme-toggle" class="theme-toggle" aria-label="Переключить тему">
            <i class="fas fa-moon"></i>
        </button>
    </div>
</header>
