<?php
/**
 * قالب عرض الصفحات العادية
 *
 * @package Fahad_Blog
 * @version 2.0
 */

get_header();
?>

<main id="primary" class="site-main page-content">
    <div class="container">
        <?php
        /**
         * عرض فتات التنقل (Breadcrumbs)
         */
        fahad_blog_breadcrumbs();
        
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-article'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail() && !has_post_thumbnail() && !is_front_page()) : ?>
                    <div class="page-thumbnail">
                        <?php the_post_thumbnail('blog-featured', [
                            'class' => 'page-featured-image',
                            'alt' => get_the_title(),
                        ]); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages([
                        'before' => '<div class="page-links">' . esc_html__('الصفحات:', 'fahad-blog'),
                        'after'  => '</div>',
                    ]);
                    ?>
                </div>
                
                <?php if (get_edit_post_link()) : ?>
                    <footer class="entry-footer">
                        <?php
                        edit_post_link(
                            sprintf(
                                /* translators: %s: اسم الصفحة */
                                esc_html__('تعديل %s', 'fahad-blog'),
                                the_title('<span class="screen-reader-text">"', '"</span>', false)
                            ),
                            '<span class="edit-link"><i class="fas fa-pencil-alt" aria-hidden="true"></i> ',
                            '</span>'
                        );
                        ?>
                    </footer>
                <?php endif; ?>
            </article>
            
            <?php
            // إذا كانت التعليقات مفتوحة أو يوجد تعليقات بالفعل
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();