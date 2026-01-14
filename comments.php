<?php
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            if ($comments_number === 1) {
                echo '1 комментарий';
            } else {
                printf(
                    'Комментарии: %s',
                    number_format_i18n($comments_number)
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
            ));
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation">
                <div class="nav-previous"><?php previous_comments_link('← Предыдущие комментарии'); ?></div>
                <div class="nav-next"><?php next_comments_link('Следующие комментарии →'); ?></div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments">Комментарии закрыты.</p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'          => 'Оставить комментарий',
        'title_reply_to'       => 'Ответить %s',
        'cancel_reply_link'    => 'Отменить ответ',
        'label_submit'         => 'Отправить',
        'comment_field'        => '<p class="comment-form-comment"><label for="comment">Комментарий</label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>',
        'comment_notes_before' => '<p class="comment-notes">Ваш email не будет опубликован.</p>',
        'fields'              => array(
            'author' => '<p class="comment-form-author">' .
                        '<label for="author">Имя <span class="required">*</span></label>' .
                        '<input id="author" name="author" type="text" required /></p>',
            'email'  => '<p class="comment-form-email">' .
                        '<label for="email">Email <span class="required">*</span></label>' .
                        '<input id="email" name="email" type="email" required /></p>',
            'url'    => '<p class="comment-form-url">' .
                        '<label for="url">Сайт</label>' .
                        '<input id="url" name="url" type="url" /></p>',
        ),
    ));
    ?>
</div> 