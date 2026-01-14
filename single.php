<?php get_header(); ?>

<main class="site-content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail-full">
                    <?php the_post_thumbnail('full', array('fetchpriority' => 'high', 'loading' => 'eager')); ?>
                </div>
            <?php endif; ?>
            
            <div class="container">
                <?php 
                $has_toc = !empty(get_toc_data());
                if ($has_toc) : 
                ?>
                    <div class="single-post-wrapper">
                        <article class="single-post">
                            <h1 class="post-title"><?php the_title(); ?></h1>
                            <div class="post-date">
                                <?php the_time('m.d.Y'); ?>
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
                        
                        <?php render_toc_sidebar(); ?>
                    </div>
                <?php else : ?>
                    <article class="single-post">
                        <h1 class="post-title"><?php the_title(); ?></h1>
                        <div class="post-date">
                            <?php the_time('m.d.Y'); ?>
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
                <?php endif; ?>
            </div>

    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
