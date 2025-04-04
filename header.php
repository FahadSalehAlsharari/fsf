<?php
/**
 * رأس الصفحة لقالب فهد الشراري للمدونة
 *
 * يحتوي على رأس الصفحة وقائمة التنقل
 *
 * @package Fahad_Blog
 * @version 2.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- ربط الصفحة الرئيسية بالمدونة -->
    <link rel="alternate" href="<?php echo esc_url(get_theme_mod('fahad_blog_main_site_url', 'https://fahadalsharari.com')); ?>" hreflang="ar-sa">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('no-js'); ?>>
    <?php wp_body_open(); ?>
    <script>document.body.classList.remove('no-js');</script>
    
    <!-- رابط تخطي لمحتوى الصفحة لدعم إمكانية الوصول -->
    <a href="#main-content" class="skip-link visually-hidden"><?php esc_html_e('تخطي إلى المحتوى الرئيسي', 'fahad-blog'); ?></a>
    
    <!-- زر تبديل الوضع (داكن/فاتح) -->
    <?php if (get_theme_mod('fahad_blog_enable_theme_switch', true)) : ?>
        <?php fahad_blog_theme_toggle(); ?>
    <?php endif; ?>

    <header id="masthead" class="site-header" role="banner">
        <div class="container">
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('القائمة الرئيسية', 'fahad-blog'); ?>">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                    ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" aria-label="<?php esc_attr_e('الشعار', 'fahad-blog'); ?>">
                            <?php echo esc_html(get_theme_mod('fahad_blog_logo_text', 'FS')); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <button class="menu-toggle" id="menu-toggle" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e('فتح قائمة التنقل', 'fahad-blog'); ?>">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>
                
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'fallback_cb'    => function() {
                        echo '<ul id="primary-menu" class="nav-menu">';
                        echo '<li><a href="' . esc_url(home_url('/')) . '" aria-current="page">' . esc_html__('الرئيسية', 'fahad-blog') . '</a></li>';
                        echo '<li><a href="' . esc_url(get_theme_mod('fahad_blog_main_site_url', 'https://fahadalsharari.com')) . '">' . esc_html__('الموقع الرئيسي', 'fahad-blog') . '</a></li>';
                        echo '</ul>';
                    },
                ]);
                ?>
            </nav>
        </div>
    </header>
    
    <?php if (is_singular() && has_post_thumbnail()) : ?>
        <div class="featured-image-header">
            <?php the_post_thumbnail('blog-featured', [
                'class' => 'featured-image',
                'alt' => get_the_title(),
            ]); ?>
            <div class="featured-image-overlay"></div>
        </div>
    <?php endif; ?>
    
    <div id="content" class="site-content">
        <div id="main-content"></div>