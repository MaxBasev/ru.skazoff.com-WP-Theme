<?php get_header(); ?>

<main class="site-content">
    <div class="container main-container">
        <div class="posts-list">
            <?php if (have_posts()) : while (have_posts()) : the_post(); 
                // Check if this is a sticky post
                $is_sticky = is_sticky();
                $post_class = $is_sticky ? 'post-card pinned-post' : 'post-card';
            ?>
                <article class="<?php echo $post_class; ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($is_sticky) : ?>
                        <div class="pin-indicator">📌 Закреплено</div>
                    <?php endif; ?>
                    
                    <div class="post-card-content">
                        <h2 class="post-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="post-date">
                            <?php the_time('m.d.Y'); ?>
                        </div>
                        <div class="post-card-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="read-more">Читать далее...</a>
                    </div>
                </article>
            <?php endwhile; ?>

            <div class="pagination">
                <?php echo paginate_links(); ?>
            </div>

            <?php else : ?>
                <p><?php _e('Записей не найдено.'); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="lab-promo">
            <div class="lab-promo-inner">
                <p>Между экзистенциальным ужасом и кофеиновыми ломками я создаю разные штуки.</p>
                <a href="https://lab.maxbasev.com" class="lab-link">→ Вот что выжило</a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>