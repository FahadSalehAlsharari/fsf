<?php
/**
 * قالب لعرض التعليقات
 *
 * @package Fahad_Blog
 * @version 2.0
 */

// إذا كانت التعليقات محمية بكلمة مرور، توقف وعدم عرض التعليقات
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $fahad_blog_comment_count = get_comments_number();
            
            if ('1' === $fahad_blog_comment_count) {
                printf(
                    /* translators: 1: عنوان */
                    esc_html__('تعليق واحد على "%1$s"', 'fahad-blog'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: عدد التعليقات، 2: عنوان */
                    esc_html(_n('%1$s تعليق على "%2$s"', '%1$s تعليقات على "%2$s"', $fahad_blog_comment_count, 'fahad-blog')),
                    number_format_i18n($fahad_blog_comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'fahad_blog_comment_callback',
            ]);
            ?>
        </ol>

        <?php
        // ترقيم صفحات التعليقات
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
            ?>
            <nav id="comment-nav-below" class="navigation comment-navigation">
                <h3 class="screen-reader-text"><?php esc_html_e('تصفح التعليقات', 'fahad-blog'); ?></h3>
                <div class="nav-links">
                    <div class="nav-previous"><?php previous_comments_link(esc_html__('التعليقات السابقة', 'fahad-blog')); ?></div>
                    <div class="nav-next"><?php next_comments_link(esc_html__('التعليقات التالية', 'fahad-blog')); ?></div>
                </div>
            </nav>
        <?php endif; ?>

        <?php
        // إذا كانت التعليقات مغلقة ومع ذلك توجد تعليقات
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('التعليقات مغلقة.', 'fahad-blog'); ?></p>
            <?php
        endif;
    endif; // Check for have_comments().

    // نموذج التعليق مع تخصيصات للغة العربية
    $fahad_blog_comment_args = [
        'title_reply'        => esc_html__('إضافة تعليق', 'fahad-blog'),
        'title_reply_to'     => esc_html__('إضافة رد على %s', 'fahad-blog'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'class_submit'       => 'submit btn-primary',
        'label_submit'       => esc_html__('إرسال التعليق', 'fahad-blog'),
        'comment_notes_before' => '<p class="comment-notes">' . esc_html__('لن يتم نشر بريدك الإلكتروني.', 'fahad-blog') . '</p>',
        'comment_field'      => '<div class="comment-form-comment"><label for="comment">' . esc_html__('التعليق', 'fahad-blog') . '<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></div>',
        'fields'             => [
            'author' => '<div class="comment-form-author"><label for="author">' . esc_html__('الاسم', 'fahad-blog') . '<span class="required">*</span></label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author'] ?? '') . '" size="30" required /></div>',
            'email'  => '<div class="comment-form-email"><label for="email">' . esc_html__('البريد الإلكتروني', 'fahad-blog') . '<span class="required">*</span></label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email'] ?? '') . '" size="30" required /></div>',
            'url'    => '<div class="comment-form-url"><label for="url">' . esc_html__('الموقع الإلكتروني', 'fahad-blog') . '</label><input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url'] ?? '') . '" size="30" /></div>',
            'cookies' => '<div class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" ' . (empty($commenter['comment_author_email']) ? '' : 'checked') . ' /><label for="wp-comment-cookies-consent">' . esc_html__('احفظ اسمي وبريدي الإلكتروني في هذا المتصفح للمرة القادمة التي أعلق فيها.', 'fahad-blog') . '</label></div>',
        ],
    ];
    
    comment_form($fahad_blog_comment_args);
    ?>
</div><!-- #comments -->