<?php
/**
 * القالب الرئيسي لعرض المقالات والأرشيف
 *
 * هذا القالب يُستخدم لعرض الصفحة الرئيسية وصفحات الأرشيف والبحث
 *
 * @package Fahad_Blog
 * @version 2.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        /**
         * عرض قسم المقدمة في الصفحة الرئيسية فقط
         */
        if (is_home() && !is_paged() && get_theme_mod('fahad_blog_show_intro', true)) : ?>
            <div class="blog-intro">
                <h1 class="page-title"><?php echo esc_html(get_theme_mod('fahad_blog_intro_title', get_bloginfo('name'))); ?></h1>
                <p class="site-description"><?php echo esc_html(get_theme_mod('fahad_blog_intro_desc', get_bloginfo('description'))); ?></p>
            </div>
        <?php
        /**
         * عرض عنوان الصفحة في صفحات الأرشيف والبحث
         */
        elseif (is_archive() || is_search()) : ?>
            <header class="archive-header">
                <?php
                if (is_archive()) {
                    the_archive_title('<h1 class="archive-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                } elseif (is_search()) {
                    printf(
                        '<h1 class="search-title">%s %s</h1>',
                        esc_html__('نتائج البحث عن:', 'fahad-blog'),
                        '<span>' . get_search_query() . '</span>'
                    );
                }
                ?>
            </header>
        <?php endif; ?>

        <div class="blog-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    
                    /**
                     * عرض المحتوى باستخدام القالب الفرعي المناسب
                     */
                    get_template_part('template-parts/content', get_post_type());
                endwhile;

                // ترقيم الصفحات
                echo '<div class="pagination-container">';
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '<i class="fas fa-angle-right" aria-hidden="true"></i> ' . esc_html__('السابق', 'fahad-blog'),
                    'next_text' => esc_html__('التالي', 'fahad-blog') . ' <i class="fas fa-angle-left" aria-hidden="true"></i>',
                ]);
                echo '</div>';

            else :
                /**
                 * عرض رسالة في حالة عدم وجود مقالات
                 */
                if (is_search()) :
                    get_template_part('template-parts/content', 'none-search');
                else :
                    get_template_part('template-parts/content', 'none');
                endif;
            endif;
            ?>
        </div>
    </div>
</main>

<?php
/**
 * عرض الشريط الجانبي إذا كان مفعلاً
 */
if (is_active_sidebar('sidebar-1') && !is_singular()) {
    get_sidebar();
}

get_footer();