<?php
/**
 * دوال مساعدة لقوالب قالب فهد الشراري للمدونة
 *
 * @package Fahad_Blog
 * @version 2.0
 */

// منع الوصول المباشر للملف
if (!defined('ABSPATH')) {
    exit;
}

/**
 * عرض شارة المقالات الملصقة 
 */
function fahad_blog_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date('c')),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date('c')),
        esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
        /* translators: %s: تاريخ النشر */
        esc_html_x('نُشر في %s', 'تاريخ النشر', 'fahad-blog'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>';
}

/**
 * عرض اسم الكاتب مع رابط
 */
function fahad_blog_posted_by() {
    $byline = sprintf(
        /* translators: %s: اسم الكاتب */
        esc_html_x('بواسطة %s', 'اسم الكاتب', 'fahad-blog'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline">' . $byline . '</span>';
}

/**
 * عرض روابط التصنيفات والوسوم
 */
function fahad_blog_entry_footer() {
    // التصنيفات
    if (has_category()) :
        $categories_list = get_the_category_list(esc_html__(', ', 'fahad-blog'));
        if ($categories_list) {
            /* translators: 1: قائمة التصنيفات. */
            printf('<span class="cat-links"><i class="fas fa-folder-open" aria-hidden="true"></i> ' . esc_html__('التصنيفات: %1$s', 'fahad-blog') . '</span>', $categories_list);
        }
    endif;

    // الوسوم
    if (has_tag()) :
        $tags_list = get_the_tag_list('', esc_html__(', ', 'fahad-blog'));
        if ($tags_list) {
            /* translators: 1: قائمة الوسوم. */
            printf('<span class="tags-links"><i class="fas fa-tags" aria-hidden="true"></i> ' . esc_html__('الوسوم: %1$s', 'fahad-blog') . '</span>', $tags_list);
        }
    endif;

    // رابط التعديل
    edit_post_link(
        sprintf(
            /* translators: %s: اسم المقال */
            esc_html__('تعديل %s', 'fahad-blog'),
            '<span class="screen-reader-text">' . get_the_title() . '</span>'
        ),
        '<span class="edit-link"><i class="fas fa-pencil-alt" aria-hidden="true"></i> ',
        '</span>'
    );
}

/**
 * عرض صورة المقال
 */
function fahad_blog_post_thumbnail() {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    
    if (is_singular()) :
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('blog-featured', [
                'class' => 'featured-image',
                'alt' => the_title_attribute(['echo' => false]),
            ]); ?>
        </div>
        <?php
    else :
        ?>
        <a class="post-thumbnail-link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php the_post_thumbnail('blog-thumbnail', [
                'class' => 'lazy-load',
                'alt' => the_title_attribute(['echo' => false]),
                'loading' => 'lazy',
            ]); ?>
        </a>
        <?php
    endif;
}

/**
 * دالة مخصصة لعرض التعليقات
 */
function fahad_blog_comment_callback($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                    $comment_author_url = get_comment_author_url($comment);
                    $comment_author     = get_comment_author($comment);
                    $avatar             = get_avatar($comment, $args['avatar_size']);
                    
                    if (empty($comment_author_url)) {
                        echo $avatar . '<span class="fn">' . esc_html($comment_author) . '</span>';
                    } else {
                        printf(
                            '<a href="%s" class="url" rel="external nofollow">%s<span class="fn">%s</span></a>',
                            esc_url($comment_author_url),
                            $avatar,
                            esc_html($comment_author)
                        );
                    }
                    ?>
                    
                    <span class="says screen-reader-text"><?php esc_html_e('يقول:', 'fahad-blog'); ?></span>
                </div><!-- .comment-author -->

                <div class="comment-metadata">
                    <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            /* translators: 1: تاريخ التعليق، 2: وقت التعليق */
                            printf(esc_html__('%1$s في %2$s', 'fahad-blog'), esc_html(get_comment_date('', $comment)), esc_html(get_comment_time()));
                            ?>
                        </time>
                    </a>
                    <?php edit_comment_link(esc_html__('تعديل', 'fahad-blog'), '<span class="edit-link">', '</span>'); ?>
                </div><!-- .comment-metadata -->

                <?php if ('0' === $comment->comment_approved) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e('تعليقك بانتظار الموافقة.', 'fahad-blog'); ?></p>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
            if ('div' !== $args['style']) :
                comment_reply_link(
                    array_merge(
                        $args,
                        [
                            'add_below'  => 'div-comment',
                            'depth'      => $depth,
                            'max_depth'  => $args['max_depth'],
                            'before'     => '<div class="reply"><i class="fas fa-reply" aria-hidden="true"></i> ',
                            'after'      => '</div>',
                        ]
                    )
                );
            endif;
            ?>
        </article><!-- .comment-body -->
    <?php
}

/**
 * عرض معلومات الكاتب في صفحة المقال
 */
function fahad_blog_author_bio() {
    if (!is_singular('post')) {
        return;
    }
    
    ?>
    <div class="author-bio">
        <div class="author-avatar">
            <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
        </div>
        <div class="author-info">
            <h4 class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </h4>
            <?php
            $author_description = get_the_author_meta('description');
            if (!empty($author_description)) :
                ?>
                <p class="author-description"><?php echo esc_html($author_description); ?></p>
                <?php
            endif;
            ?>
            <div class="author-posts">
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                    <i class="fas fa-pen" aria-hidden="true"></i>
                    <?php esc_html_e('عرض جميع المقالات', 'fahad-blog'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}

/**
 * عرض المقالات ذات الصلة
 */
function fahad_blog_related_posts() {
    if (!is_singular('post')) {
        return;
    }
    
    $related_posts_count = absint(get_theme_mod('fahad_blog_related_posts_count', 3));
    $related_query = fahad_blog_get_related_posts(get_the_ID(), $related_posts_count);
    
    if (!$related_query || !$related_query->have_posts()) {
        return;
    }
    
    ?>
    <div class="related-posts">
        <h3 class="related-posts-title"><?php esc_html_e('مقالات ذات صلة', 'fahad-blog'); ?></h3>
        <div class="related-posts-grid">
            <?php
            while ($related_query->have_posts()) :
                $related_query->the_post();
                
                // استدعاء قالب المقالات ذات الصلة
                get_template_part('template-parts/content', 'related');
            endwhile;
            
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}

/**
 * عرض وقت القراءة المقدر
 */
function fahad_blog_reading_time() {
    $reading_time = fahad_blog_calculate_reading_time(get_the_ID());
    
    printf(
        '<span class="reading-time"><i class="fas fa-clock" aria-hidden="true"></i> %d %s</span>',
        $reading_time,
        esc_html(_n('دقيقة للقراءة', 'دقائق للقراءة', $reading_time, 'fahad-blog'))
    );
}

/**
 * عرض زر مشاركة المقال على منصات التواصل الاجتماعي
 */
function fahad_blog_social_sharing() {
    $sharing_links = fahad_blog_get_sharing_links();
    
    echo '<div class="social-sharing">';
    echo '<span class="share-label"><i class="fas fa-share-alt" aria-hidden="true"></i> ' . esc_html__('مشاركة:', 'fahad-blog') . '</span>';
    
    foreach ($sharing_links as $network => $data) {
        printf(
            '<a href="%s" class="share-%s" target="_blank" rel="noopener noreferrer" aria-label="%s"><i class="%s" aria-hidden="true"></i></a>',
            esc_url($data['url']),
            esc_attr($network),
            esc_attr($data['label']),
            esc_attr($data['icon'])
        );
    }
    
    echo '</div>';
}

/**
 * عرض الفتات التنقل (Breadcrumbs)
 */
function fahad_blog_breadcrumbs() {
    echo fahad_blog_get_breadcrumbs();
}

/**
 * عرض رابط المقال السابق والتالي في صفحة المقال
 */
function fahad_blog_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    
    echo '<div class="post-navigation">';
    
    if ($next_post) {
        $next_thumbnail = get_the_post_thumbnail($next_post->ID, 'thumbnail', ['class' => 'navigation-thumbnail']);
        echo '<div class="nav-next">';
        echo '<a href="' . esc_url(get_permalink($next_post->ID)) . '" rel="next">';
        echo '<span class="nav-direction"><i class="fas fa-angle-right" aria-hidden="true"></i> ' . esc_html__('المقال السابق', 'fahad-blog') . '</span>';
        if ($next_thumbnail) {
            echo '<div class="nav-thumbnail">' . $next_thumbnail . '</div>';
        }
        echo '<span class="nav-title">' . esc_html(get_the_title($next_post->ID)) . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    if ($prev_post) {
        $prev_thumbnail = get_the_post_thumbnail($prev_post->ID, 'thumbnail', ['class' => 'navigation-thumbnail']);
        echo '<div class="nav-previous">';
        echo '<a href="' . esc_url(get_permalink($prev_post->ID)) . '" rel="prev">';
        echo '<span class="nav-direction">' . esc_html__('المقال التالي', 'fahad-blog') . ' <i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        if ($prev_thumbnail) {
            echo '<div class="nav-thumbnail">' . $prev_thumbnail . '</div>';
        }
        echo '<span class="nav-title">' . esc_html(get_the_title($prev_post->ID)) . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * إظهار تصنيفات المقال مع تنسيقات
 */
function fahad_blog_post_categories() {
    $categories = get_the_category();
    
    if (empty($categories)) {
        return;
    }
    
    echo '<div class="entry-categories">';
    
    foreach ($categories as $category) {
        printf(
            '<a href="%s" class="entry-category">%s</a>',
            esc_url(get_category_link($category->term_id)),
            esc_html($category->name)
        );
    }
    
    echo '</div>';
}

/**
 * إظهار وسوم المقال مع تنسيقات
 */
function fahad_blog_post_tags() {
    $tags = get_the_tags();
    
    if (empty($tags)) {
        return;
    }
    
    echo '<div class="entry-tags">';
    echo '<span class="tags-label"><i class="fas fa-tags" aria-hidden="true"></i> ' . esc_html__('الوسوم:', 'fahad-blog') . '</span>';
    
    foreach ($tags as $tag) {
        printf(
            '<a href="%s" class="entry-tag">%s</a>',
            esc_url(get_tag_link($tag->term_id)),
            esc_html($tag->name)
        );
    }
    
    echo '</div>';
}

/**
 * إظهار معلومات المقال (التاريخ، الكاتب، التعليقات، وقت القراءة)
 */
function fahad_blog_post_meta() {
    ?>
    <div class="post-meta">
        <span class="post-date">
            <i class="fas fa-calendar-alt" aria-hidden="true"></i>
            <?php echo get_the_date(); ?>
        </span>
        
        <span class="post-author">
            <i class="fas fa-user" aria-hidden="true"></i>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                <?php the_author(); ?>
            </a>
        </span>
        
        <?php if (comments_open()) : ?>
            <span class="post-comments">
            <i class="fas fa-comment" aria-hidden="true"></i>
                <a href="<?php echo esc_url(get_permalink() . '#comments'); ?>">
                    <?php comments_number('0 تعليق', '1 تعليق', '% تعليق'); ?>
                </a>
            </span>
        <?php endif; ?>
        
        <span class="post-reading-time">
            <i class="fas fa-clock" aria-hidden="true"></i>
            <?php 
            $reading_time = fahad_blog_calculate_reading_time(get_the_ID());
            printf(
                _n('%d دقيقة للقراءة', '%d دقائق للقراءة', $reading_time, 'fahad-blog'),
                $reading_time
            );
            ?>
        </span>
    </div>
    <?php
}

/**
 * عرض عنوان الصفحة مع تنسيقات
 */
function fahad_blog_page_title() {
    if (is_home()) {
        if (get_option('page_for_posts')) {
            $title = get_the_title(get_option('page_for_posts'));
        } else {
            $title = __('المدونة', 'fahad-blog');
        }
    } elseif (is_archive()) {
        $title = get_the_archive_title();
    } elseif (is_search()) {
        /* translators: %s: مصطلح البحث */
        $title = sprintf(__('نتائج البحث عن: %s', 'fahad-blog'), get_search_query());
    } elseif (is_404()) {
        $title = __('صفحة غير موجودة', 'fahad-blog');
    } else {
        $title = get_the_title();
    }

    echo '<h1 class="page-title">' . esc_html($title) . '</h1>';
}

/**
 * عرض جدول المحتويات للمقال
 */
function fahad_blog_table_of_contents() {
    if (!is_singular('post')) {
        return;
    }

    global $post;
    $content = get_post_field('post_content', $post->ID);
    $toc = fahad_blog_get_table_of_contents($content);

    if (empty($toc)) {
        return;
    }

    echo $toc;
}

/**
 * عرض نموذج بحث متجاوب
 */
function fahad_blog_search_form() {
    $unique_id = uniqid('search-form-');
    ?>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
        <label for="<?php echo esc_attr($unique_id); ?>" class="screen-reader-text"><?php esc_html_e('البحث عن:', 'fahad-blog'); ?></label>
        <div class="search-form-container">
            <input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr_x('ابحث هنا...', 'placeholder', 'fahad-blog'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('بحث', 'fahad-blog'); ?>">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </div>
    </form>
    <?php
}

/**
 * عرض زر العودة إلى الأعلى
 */
function fahad_blog_scroll_to_top() {
    echo '<button id="scroll-to-top" class="scroll-to-top" aria-label="' . esc_attr__('العودة للأعلى', 'fahad-blog') . '">';
    echo '<i class="fas fa-arrow-up" aria-hidden="true"></i>';
    echo '</button>';
}

/**
 * عرض زر تبديل الوضع (داكن/فاتح)
 */
function fahad_blog_theme_toggle() {
    echo '<button id="theme-toggle" class="theme-toggle" aria-label="' . esc_attr__('تبديل المظهر', 'fahad-blog') . '">';
    echo '<i class="fas fa-moon" aria-hidden="true" id="dark-icon"></i>';
    echo '<i class="fas fa-sun" aria-hidden="true" id="light-icon"></i>';
    echo '</button>';
}

/**
 * عرض تغيير حجم النص للقراءة المريحة
 */
function fahad_blog_font_size_controls() {
    ?>
    <div class="font-size-controls">
        <button class="font-size-control decrease" aria-label="<?php esc_attr_e('تصغير حجم النص', 'fahad-blog'); ?>">
            <i class="fas fa-minus" aria-hidden="true"></i>
        </button>
        <button class="font-size-control reset" aria-label="<?php esc_attr_e('إعادة ضبط حجم النص', 'fahad-blog'); ?>">
            <i class="fas fa-font" aria-hidden="true"></i>
        </button>
        <button class="font-size-control increase" aria-label="<?php esc_attr_e('تكبير حجم النص', 'fahad-blog'); ?>">
            <i class="fas fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <?php
}

/**
 * عرض وصف الصفحة أو القسم
 */
function fahad_blog_page_description() {
    if (is_home() || is_front_page()) {
        echo '<p class="site-description">' . esc_html(get_bloginfo('description')) . '</p>';
    } elseif (is_category() || is_tag() || is_tax()) {
        $term_description = term_description();
        if (!empty($term_description)) {
            echo '<div class="archive-description">' . wp_kses_post($term_description) . '</div>';
        }
    } elseif (is_author()) {
        $author_description = get_the_author_meta('description');
        if (!empty($author_description)) {
            echo '<div class="author-description">' . wp_kses_post($author_description) . '</div>';
        }
    }
}