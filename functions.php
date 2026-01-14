<?php
// Register menu support
register_nav_menus(array(
    'main-menu' => 'Главное меню'
));

// Add thumbnail support
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('html5', array('search-form'));

// Include scripts and styles
function skazoff_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap', array(), null);

    // Enqueue styles
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');
    wp_enqueue_style('skazoff-style', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css'));
    
    // Enqueue scripts
    // navigation.js does not depend on jQuery, removing dependency to allow async/defer
    wp_enqueue_script('navigation', get_template_directory_uri() . '/js/navigation.js', array(), filemtime(get_stylesheet_directory() . '/js/navigation.js'), true);
    wp_enqueue_script('theme-toggle', get_template_directory_uri() . '/js/theme-toggle.js', array(), filemtime(get_stylesheet_directory() . '/js/theme-toggle.js'), true);
    
    // Enqueue TOC script only on single posts
    if (is_single()) {
        wp_enqueue_script('toc', get_template_directory_uri() . '/js/toc.js', array(), filemtime(get_stylesheet_directory() . '/js/toc.js'), true);
    }
}
add_action('wp_enqueue_scripts', 'skazoff_scripts');

// Add resource hints for performance
function skazoff_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = 'https://fonts.gstatic.com';
        $urls[] = 'https://cdnjs.cloudflare.com';
    }
    return $urls;
}
add_filter('wp_resource_hints', 'skazoff_resource_hints', 10, 2);

// Async load CSS for performance
function skazoff_async_styles($tag, $handle) {
    // Add handles of styles to load asynchronously
    $async_styles = array('google-fonts', 'font-awesome');

    if (in_array($handle, $async_styles)) {
        return str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $tag) . "<noscript>" . str_replace("media='print' onload=\"this.media='all'\"", "media='all'", $tag) . "</noscript>";
    }
    return $tag;
}
add_filter('style_loader_tag', 'skazoff_async_styles', 10, 2);

// Add custom excerpt length
function custom_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

// Add custom excerpt "Read more" text
function new_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Add support for custom logo
add_theme_support('custom-logo', array(
    'height'      => 200,
    'width'       => 400,
    'flex-height' => true,
    'flex-width'  => true,
    'header-text' => array('site-title', 'site-description'),
));

// Register widget areas
function skazoff_widgets_init() {
    register_sidebar(array(
        'name'          => 'Сайдбар',
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'skazoff_widgets_init');

// Add Open Graph support
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

// Font loading optimization removed in favor of wp_resource_hints filter above

// Lazy loading for images
function add_lazy_loading($content) {
    return str_replace('<img', '<img loading="lazy"', $content);
}
add_filter('the_content', 'add_lazy_loading');

/**
 * TABLE OF CONTENTS FUNCTIONALITY
 */

// Add meta box for TOC toggle
function toc_meta_box() {
    add_meta_box(
        'toc_enable',
        'Оглавление',
        'toc_meta_box_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'toc_meta_box');

// Meta box callback
function toc_meta_box_callback($post) {
    wp_nonce_field('toc_meta_box', 'toc_meta_box_nonce');
    $value = get_post_meta($post->ID, '_enable_toc', true);
    ?>
    <label>
        <input type="checkbox" name="enable_toc" value="1" <?php checked($value, '1'); ?> />
        Включить оглавление
    </label>
    <p class="description">Показывать оглавление для заголовков H2 и H3 в этой записи.</p>
    <?php
}

// Save meta box data
function save_toc_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['toc_meta_box_nonce']) || !wp_verify_nonce($_POST['toc_meta_box_nonce'], 'toc_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save or delete meta
    if (isset($_POST['enable_toc'])) {
        update_post_meta($post_id, '_enable_toc', '1');
    } else {
        delete_post_meta($post_id, '_enable_toc');
    }
}
add_action('save_post', 'save_toc_meta_box');

// Add IDs to headings for TOC
function add_heading_ids($content) {
    if (!is_single() || get_post_meta(get_the_ID(), '_enable_toc', true) !== '1') {
        return $content;
    }
    
    // Match H2 and H3 tags that don't already have IDs
    preg_match_all('/<h([23])(?![^>]*id=)([^>]*)>(.*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
    
    if (empty($matches)) {
        return $content;
    }
    
    // Process in reverse order to preserve offsets
    $matches = array_reverse($matches);
    $heading_count = 0;
    
    foreach ($matches as $match) {
        $full_match = $match[0][0];
        $level = $match[1][0];
        $attributes = isset($match[2][0]) ? $match[2][0] : '';
        $text = $match[3][0];
        $offset = $match[0][1];
        
        $text_plain = strip_tags($text);
        $slug = sanitize_title($text_plain);
        if (empty($slug)) {
            $slug = 'heading-' . $heading_count;
        }
        
        $new_heading = '<h' . $level . ' id="' . $slug . '"' . $attributes . '>' . $text . '</h' . $level . '>';
        
        $content = substr_replace($content, $new_heading, $offset, strlen($full_match));
        
        $heading_count++;
    }
    
    return $content;
}
add_filter('the_content', 'add_heading_ids', 10);

// Get TOC data for rendering in sidebar
function get_toc_data() {
    if (!is_single() || get_post_meta(get_the_ID(), '_enable_toc', true) !== '1') {
        return array();
    }
    
    global $post;
    
    // Apply the_content filter to get processed content with IDs
    $content = apply_filters('the_content', $post->post_content);
    
    // Extract all H2 and H3 headings
    preg_match_all('/<h([23])(?:[^>]*id=["\']([^"\']+)["\'][^>]*|[^>]*)>(.*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER);
    
    $headings = array();
    foreach ($matches as $match) {
        $level = $match[1];
        $id = isset($match[2]) && !empty($match[2]) ? $match[2] : sanitize_title(strip_tags($match[3]));
        $text = strip_tags($match[3]);
        
        if (!empty($text)) {
            $headings[] = array(
                'level' => $level,
                'id' => $id,
                'text' => $text
            );
        }
    }
    
    return $headings;
}

// Render TOC sidebar
function render_toc_sidebar() {
    if (!is_single()) {
        return;
    }
    
    $headings = get_toc_data();
    
    if (empty($headings)) {
        return;
    }
    
    echo '<!-- TOC: Found ' . count($headings) . ' headings -->';
    ?>
    <aside class="toc-sidebar">
        <div class="table-of-contents">
            <div class="toc-header">
                <h2 class="toc-title"><i class="fas fa-list"></i> Оглавление</h2>
            </div>
            <nav class="toc-nav">
                <ol class="toc-list">
                    <?php
                    // Build nested list: H3 items under the latest H2
                    $open_sublist = false;
                    foreach ($headings as $heading) :
                        if ((int)$heading['level'] === 2) {
                            // Close previous sublist and li if any
                            if ($open_sublist) {
                                echo '</ol></li>';
                                $open_sublist = false;
                            }
                            ?>
                            <li class="toc-item toc-h2">
                                <button type="button" class="toc-toggle" aria-expanded="false" aria-label="Toggle section"></button>
                                <a href="#<?php echo esc_attr($heading['id']); ?>" class="toc-link h2-link">
                                    <?php echo esc_html($heading['text']); ?>
                                </a>
                                <ol class="toc-sublist" hidden>
                            <?php
                            $open_sublist = true;
                        } else {
                            // H3 item
                            ?>
                            <li class="toc-item toc-h3">
                                <a href="#<?php echo esc_attr($heading['id']); ?>" class="toc-link h3-link">
                                    <?php echo esc_html($heading['text']); ?>
                                </a>
                            </li>
                            <?php
                        }
                    endforeach;
                    if ($open_sublist) {
                        echo '</ol></li>';
                    }
                    ?>
                </ol>
            </nav>
        </div>
    </aside>
    <?php
}
?>