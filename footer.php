<?php
/**
 * تذييل الصفحة لقالب فهد الشراري للمدونة
 * 
 * يحتوي على معلومات حقوق النشر وروابط الشبكات الاجتماعية
 *
 * @package Fahad_Blog
 * @version 2.0
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widgets">
                    <div class="footer-widgets-area">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="site-info">
                <p class="copyright">
                    &copy; <?php echo date('Y'); ?> 
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                    - <?php echo esc_html(get_theme_mod('fahad_blog_copyright_text', __('جميع الحقوق محفوظة', 'fahad-blog'))); ?>
                </p>
                
                <div class="footer-links">
                    <a href="<?php echo esc_url(get_theme_mod('fahad_blog_main_site_url', 'https://fahadalsharari.com')); ?>">
                        <?php esc_html_e('الموقع الرئيسي', 'fahad-blog'); ?>
                    </a>
                    
                    <?php 
                    // رابط سياسة الخصوصية
                    $privacy_page_id = get_theme_mod('fahad_blog_privacy_page', '');
                    if (!empty($privacy_page_id)) :
                    ?>
                        <a href="<?php echo esc_url(get_permalink($privacy_page_id)); ?>">
                            <?php esc_html_e('سياسة الخصوصية', 'fahad-blog'); ?>
                        </a>
                    <?php elseif (get_privacy_policy_url()) : ?>
                        <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">
                            <?php esc_html_e('سياسة الخصوصية', 'fahad-blog'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>">
                        <?php esc_html_e('خريطة الموقع', 'fahad-blog'); ?>
                    </a>
                </div>
                
                <?php if (get_theme_mod('fahad_blog_show_social_footer', true)) : ?>
                    <div class="social-links show">
                        <?php
                        $social_links = fahad_blog_get_social_links();
                        
                        // إذا لم تكن هناك روابط اجتماعية محددة، استخدم الافتراضية
                        if (empty($social_links)) {
                            $social_links = [
                                'linkedin' => [
                                    'url' => 'https://linkedin.com/in/secexpertfahad',
                                    'label' => __('الملف الشخصي على LinkedIn', 'fahad-blog'),
                                    'icon' => 'fab fa-linkedin',
                                ],
                                'github' => [
                                    'url' => 'https://github.com/FahadSalehAlsharari',
                                    'label' => __('الملف الشخصي على GitHub', 'fahad-blog'),
                                    'icon' => 'fab fa-github',
                                ],
                            ];
                        }
                        
                        // عرض الروابط الاجتماعية
                        foreach ($social_links as $platform => $data) :
                        ?>
                            <a href="<?php echo esc_url($data['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($data['label']); ?>">
                                <i class="<?php echo esc_attr($data['icon']); ?>" aria-hidden="true"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <!-- زر العودة للأعلى -->
    <?php fahad_blog_scroll_to_top(); ?>
    
    <?php wp_footer(); ?>
</body>
</html>