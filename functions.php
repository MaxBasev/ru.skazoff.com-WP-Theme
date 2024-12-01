<?php
// Регистрируем поддержку меню
function theme_setup() {
    register_nav_menus(array(
        'main-menu' => 'Главное меню'
    ));
    
    // Добавляем поддержку миниатюр
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'theme_setup');

// Подключаем скрипты и стили
function theme_scripts() {
    // Подключаем основной файл стилей темы
    wp_enqueue_style('theme-style', get_stylesheet_uri(), array(), '1.0');
    
    // Подключаем скрипт навигации
    wp_enqueue_script('navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_scripts');

// Изменяем окончание excerpt
function custom_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'custom_excerpt_more');

// Добавить поддержку Open Graph
function add_open_graph_tags() {
    if (is_single()) {
        global $post;
        ?>
        <meta property="og:title" content="<?php the_title(); ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <meta property="og:image" content="<?php the_post_thumbnail_url('large'); ?>">
        <?php endif;
    }
}
add_action('wp_head', 'add_open_graph_tags');

// Оптимизация загрузки шрифтов
function optimize_font_loading() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php
}
add_action('wp_head', 'optimize_font_loading', 1);

// Отложенная загрузка изображений
function add_lazy_loading($content) {
    return str_replace('<img', '<img loading="lazy"', $content);
}
add_filter('the_content', 'add_lazy_loading');
?>