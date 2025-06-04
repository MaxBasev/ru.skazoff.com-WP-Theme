<?php get_header(); ?>

<main class="site-content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-thumbnail-full">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>
        
        <div class="container">
            <article class="single-post">
                <h1 class="post-title"><?php the_title(); ?></h1>
				<div class="post-date">
                    <?php the_time('d.m.Y'); ?>
                </div>
                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <?php if (has_tag()) : ?>
                    <div class="post-tags">
                        <?php the_tags('', ' ', ''); ?>
                    </div>
                <?php endif; ?>

                <div class="comments-section">
                    <?php 
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </article>
        </div>
    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
