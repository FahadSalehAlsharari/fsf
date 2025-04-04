<?php
/**
 * قالب صفحة الخطأ 404
 *
 * @package Fahad_Blog
 * @version 2.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="error-404 not-found">
            <div class="error-container">
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                </div>
                
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('404', 'fahad-blog'); ?></h1>
                    <h2 class="error-subtitle"><?php esc_html_e('الصفحة غير موجودة', 'fahad-blog'); ?></h2>
                </header>
                
                <div class="page-content">
                    <p><?php esc_html_e('يبدو أن الصفحة التي تبحث عنها غير موجودة أو تم نقلها.', 'fahad-blog'); ?></p>
                    
                    <div class="error-actions">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <?php esc_html_e('العودة للصفحة الرئيسية', 'fahad-blog'); ?>
                        </a>
                    </div>
                    
                    <div class="search-form-container">
                        <h3><?php esc_html_e('أو جرب البحث', 'fahad-blog'); ?></h3>
                        <?php fahad_blog_search_form(); ?>
                    </div>
                </div>
                
                <div class="helpful-links">
                    <h3><?php esc_html_e('روابط قد تهمك', 'fahad-blog'); ?></h3>
                    <div class="link-columns">
                        <div class="link-column">
                            <h4><?php esc_html_e('التصنيفات الشائعة', 'fahad-blog'); ?></h4>
                            <ul>
                                <?php
                                wp_list_categories([
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'show_count' => true,
                                    'title_li'   => '',
                                    'number'     => 5,
                                ]);
                                ?>
                            </ul>
                        </div>
                        
                        <div class="link-column">
                            <h4><?php esc_html_e('أحدث المقالات', 'fahad-blog'); ?></h4>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts([
                                    'numberposts' => 5,
                                    'post_status' => 'publish',
                                ]);
                                
                                foreach ($recent_posts as $recent) {
                                    echo '<li><a href="' . esc_url(get_permalink($recent['ID'])) . '">' . esc_html($recent['post_title']) . '</a></li>';
                                }
                                
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();