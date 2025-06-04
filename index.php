<?php get_header(); ?>

<main class="site-content">
    <div class="container">
        <div class="posts-list">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article class="post-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="post-card-content">
                        <h2 class="post-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
						<div class="post-date">
                    <?php the_time('d.m.Y'); ?>
                </div>
                        <div class="post-card-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="read-more">Читать дальше...</a>
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
    </div>
</main>

<?php get_footer(); ?>