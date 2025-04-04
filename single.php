<?php
/**
 * قالب عرض المقال الفردي
 *
 * @package Fahad_Blog
 * @version 2.0
 */

get_header();
?>

<main id="primary" class="site-main single-post-content">
    <div class="container">
        <?php
        /**
         * عرض فتات التنقل (Breadcrumbs)
         */
        fahad_blog_breadcrumbs();
        
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('article-content'); ?>>
                <header class="entry-header">
                    <div class="entry-meta">
                        <?php
                        // عرض التصنيفات
                        fahad_blog_post_categories();
                        ?>
                        
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        
                        <?php
                        // عرض معلومات المقال (التاريخ، الكاتب، التعليقات، وقت القراءة)
                        fahad_blog_post_meta();
                        ?>
                    </div>
                </header>

                <div class="entry-content">
                    <?php
                    // عرض جدول المحتويات إذا كان مفعلاً
                    if (get_theme_mod('fahad_blog_show_toc', true)) {
                        fahad_blog_table_of_contents();
                    }
                    
                    // عرض محتوى المقال
                    the_content();
                    
                    // عرض ترقيم الصفحات إذا كان المقال مقسمًا
                    wp_link_pages([
                        'before' => '<div class="page-links">' . esc_html__('الصفحات:', 'fahad-blog'),
                        'after'  => '</div>',
                    ]);
                    ?>
                </div>
                
                <footer class="entry-footer">
                    <?php
                    // عرض وسوم المقال
                    fahad_blog_post_tags();
                    
                    // عرض أزرار المشاركة الاجتماعية
                    fahad_blog_social_sharing();
                    ?>
                </footer>
                
                <?php 
                // عرض معلومات الكاتب إذا كان مفعلاً
                if (get_theme_mod('fahad_blog_show_author_bio', true)) {
                    fahad_blog_author_bio();
                }
                
                // عرض المقالات ذات الصلة إذا كان مفعلاً
                if (get_theme_mod('fahad_blog_show_related_posts', true)) {
                    fahad_blog_related_posts();
                }
                ?>
            </article>
            
            <?php
            // عرض التعليقات
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            
            // عرض التنقل بين المقالات
            fahad_blog_post_navigation();
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();