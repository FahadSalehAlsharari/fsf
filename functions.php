<?php
/**
 * ملف الوظائف الرئيسي لقالب فهد الشراري للمدونة (الإصدار 2.0)
 *
 * يحتوي هذا الملف على جميع الوظائف الأساسية لتشغيل القالب والتعريفات الأساسية
 *
 * @package Fahad_Blog
 * @version 2.0
 */

// منع الوصول المباشر للملف
if (!defined('ABSPATH')) {
    exit; // خروج إذا تم الوصول مباشرة
}

/**
 * تعريف الثوابت الأساسية للقالب
 */
// تعريف إصدار القالب للتحكم بالنسخ المخزنة
define('FAHAD_BLOG_VERSION', '2.0.0');
// مسار القالب
define('FAHAD_BLOG_DIR', get_template_directory());
// رابط القالب
define('FAHAD_BLOG_URI', get_template_directory_uri());
// مسار القوالب الجزئية
define('FAHAD_BLOG_TEMPLATE_PARTS', FAHAD_BLOG_DIR . '/template-parts');
// مسار ملفات الدوال الإضافية
define('FAHAD_BLOG_INC', FAHAD_BLOG_DIR . '/inc');

/**
 * تضمين الملفات الرئيسية للوظائف
 */
// الوظائف الأساسية للقالب
require_once FAHAD_BLOG_INC . '/core-functions.php';
// وظائف تحسين الأداء وتحسين محركات البحث
require_once FAHAD_BLOG_INC . '/optimization.php';
// وظائف قوالب مخصصة
require_once FAHAD_BLOG_INC . '/template-tags.php';
// وظائف مخصص القالب
require_once FAHAD_BLOG_INC . '/customizer.php';

/**
 * إعداد القالب ودعم الميزات
 */
function fahad_blog_setup() {
    // دعم الترجمة
    load_theme_textdomain('fahad-blog', FAHAD_BLOG_DIR . '/languages');

    // إضافة دعم العناوين التلقائية
    add_theme_support('title-tag');
    
    // دعم الشعار المخصص
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ]);
    
    // دعم الصور المميزة
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(800, 450, true);
    
    // تسجيل قوائم التنقل
    register_nav_menus([
        'primary' => esc_html__('القائمة الرئيسية', 'fahad-blog'),
        'footer'  => esc_html__('قائمة التذييل', 'fahad-blog'),
    ]);
    
    // دعم HTML5 للعناصر المختلفة
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    
    // إعدادات قالب محرر المحتوى
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
    
    // دعم للوحة مخصصات القالب
    add_theme_support('customize-selective-refresh-widgets');
    
    // إضافة صورة شاشة مصغرة للمقالات
    add_image_size('blog-thumbnail', 450, 300, true);
    add_image_size('blog-featured', 1200, 675, true);
    add_image_size('blog-medium', 600, 400, true);
    add_image_size('author-avatar', 100, 100, true);
}
add_action('after_setup_theme', 'fahad_blog_setup');

/**
 * تسجيل المناطق الجانبية (Sidebars)
 */
function fahad_blog_widgets_init() {
    register_sidebar([
        'name'          => esc_html__('الشريط الجانبي', 'fahad-blog'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('أضف الودجات هنا للظهور في الشريط الجانبي.', 'fahad-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    
    register_sidebar([
        'name'          => esc_html__('تذييل المدونة', 'fahad-blog'),
        'id'            => 'footer-1',
        'description'   => esc_html__('أضف الودجات هنا للظهور في تذييل المدونة.', 'fahad-blog'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'fahad_blog_widgets_init');

/**
 * إضافة ملفات CSS و JavaScript
 */
function fahad_blog_scripts() {
    // إضافة الخطوط من Google Fonts مع إعطاء أولوية للتحميل
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap', [], null);
    
    // إضافة Font Awesome وتحميله من CDN
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', [], '6.1.1');
    
    // إضافة ملف CSS الرئيسي
    wp_enqueue_style('fahad-blog-style', FAHAD_BLOG_URI . '/assets/css/main.css', [], FAHAD_BLOG_VERSION);
    
    // إضافة ملف RTL إذا كانت اللغة RTL
    if (is_rtl()) {
        wp_enqueue_style('fahad-blog-rtl', FAHAD_BLOG_URI . '/assets/css/rtl.css', ['fahad-blog-style'], FAHAD_BLOG_VERSION);
    }
    
    // إضافة سكربت القالب الرئيسي مع التبعيات
    wp_enqueue_script('fahad-blog-main', FAHAD_BLOG_URI . '/assets/js/theme.js', ['jquery'], FAHAD_BLOG_VERSION, true);
    
    // إضافة متغيرات JavaScript للقالب
    wp_localize_script('fahad-blog-main', 'fahadBlogSettings', [
        'ajaxUrl'      => admin_url('admin-ajax.php'),
        'themeUrl'     => FAHAD_BLOG_URI,
        'homeUrl'      => home_url('/'),
        'isRtl'        => is_rtl(),
        'translations' => [
            'search'       => esc_html__('بحث', 'fahad-blog'),
            'searchPlaceholder' => esc_html__('ابحث هنا...', 'fahad-blog'),
            'codeCopied'   => esc_html__('تم نسخ الكود', 'fahad-blog'),
            'copyCode'     => esc_html__('نسخ الكود', 'fahad-blog'),
            'tocTitle'     => esc_html__('محتويات المقال', 'fahad-blog'),
            'backToTop'    => esc_html__('العودة للأعلى', 'fahad-blog'),
        ]
    ]);
    
    // إضافة سكربت التعليقات إذا كانت التعليقات مفتوحة
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'fahad_blog_scripts');

/**
 * إضافة أيقونة البحث إلى القائمة الرئيسية
 */
function fahad_blog_add_search_icon($items, $args) {
    if ($args->theme_location == 'primary') {
        $items .= '<li class="menu-item search-icon-item"><a href="#" class="search-toggle" aria-label="' . esc_attr__('بحث', 'fahad-blog') . '"><i class="fas fa-search" aria-hidden="true"></i></a></li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'fahad_blog_add_search_icon', 10, 2);

/**
 * إضافة نموذج البحث في الهيدر
 */
function fahad_blog_add_search_form() {
    ?>
    <div class="header-search-form">
        <div class="container">
            <form role="search" method="get" class="search-form-header" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('ابحث هنا...', 'placeholder', 'fahad-blog'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                <button type="submit" class="search-submit"><i class="fas fa-search" aria-hidden="true"></i></button>
                <button type="button" class="search-close"><i class="fas fa-times" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'fahad_blog_add_search_form');

/**
 * تخصيص طول المقتطف
 */
function fahad_blog_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'fahad_blog_excerpt_length');

/**
 * تخصيص زر "اقرأ المزيد"
 */
function fahad_blog_excerpt_more($more) {
    return '... <a class="read-more" href="' . esc_url(get_permalink()) . '">' . esc_html__('اقرأ المزيد', 'fahad-blog') . ' <i class="fas fa-angle-left" aria-hidden="true"></i></a>';
}
add_filter('excerpt_more', 'fahad_blog_excerpt_more');

/**
 * إضافة دعم Schema.org للمقالات
 */
function fahad_blog_schema_org() {
    if (is_single()) {
        $schema = [
            '@context'  => 'https://schema.org',
            '@type'     => 'BlogPosting',
            'headline'  => get_the_title(),
            'image'     => get_the_post_thumbnail_url(null, 'full'),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'author'    => [
                '@type' => 'Person',
                'name'  => get_the_author(),
                'url'   => get_author_posts_url(get_the_author_meta('ID')),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name'  => get_bloginfo('name'),
                'logo'  => [
                    '@type'  => 'ImageObject',
                    'url'    => get_site_icon_url(),
                ],
            ],
            'mainEntityOfPage' => get_permalink(),
            'description' => get_the_excerpt(),
        ];
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'fahad_blog_schema_org');

/**
 * تحسين أداء ووردبريس
 */
function fahad_blog_performance_tweaks() {
    // إزالة إصدار ووردبريس
    remove_action('wp_head', 'wp_generator');
    
    // إزالة روابط RSD و WLW
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    
    // إزالة روابط المقالات المجاورة
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // إزالة روابط الإيموجي
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // تعطيل الإصدارات المضمنة في الروابط للمحافظة على ذاكرة التخزين المؤقت
    if (!is_admin()) {
        add_filter('script_loader_src', 'fahad_blog_remove_wp_ver_css_js', 9999);
        add_filter('style_loader_src', 'fahad_blog_remove_wp_ver_css_js', 9999);
    }
}
add_action('init', 'fahad_blog_performance_tweaks');

/**
 * إزالة معلمات الإصدار من الروابط
 */
function fahad_blog_remove_wp_ver_css_js($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * إضافة محتويات CSS الحرجة
 */
function fahad_blog_add_critical_css() {
    if (file_exists(FAHAD_BLOG_DIR . '/assets/css/critical.css')) {
        echo '<style id="fahad-blog-critical-css">';
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        echo file_get_contents(FAHAD_BLOG_DIR . '/assets/css/critical.css');
        echo '</style>';
    }
}
add_action('wp_head', 'fahad_blog_add_critical_css', 1);

/**
 * إضافة preload للخطوط
 */
function fahad_blog_preload_fonts() {
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" as="style" />';
    echo '<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" as="style" />';
}
add_action('wp_head', 'fahad_blog_preload_fonts', 1);

/**
 * تحسين الأداء: إضافة preconnect للموارد الخارجية
 */
function fahad_blog_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // إضافة preconnect لـ Google Fonts
        $urls[] = [
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => 'anonymous',
        ];
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        ];
        // إضافة preconnect لـ Font Awesome
        $urls[] = [
            'href' => 'https://cdnjs.cloudflare.com',
            'crossorigin' => 'anonymous',
        ];
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'fahad_blog_resource_hints', 10, 2);

/**
 * إضافة دعم Gutenberg
 */
function fahad_blog_gutenberg_support() {
    // إضافة دعم طيف الألوان الواسع
    add_theme_support('editor-color-palette', [
        [
            'name'  => esc_html__('اللون الرئيسي', 'fahad-blog'),
            'slug'  => 'primary',
            'color' => '#00adb5',
        ],
        [
            'name'  => esc_html__('اللون الثانوي', 'fahad-blog'),
            'slug'  => 'secondary',
            'color' => '#393e46',
        ],
        [
            'name'  => esc_html__('لون الخلفية', 'fahad-blog'),
            'slug'  => 'background',
            'color' => '#222831',
        ],
        [
            'name'  => esc_html__('لون النص', 'fahad-blog'),
            'slug'  => 'text',
            'color' => '#eeeeee',
        ],
    ]);
}
add_action('after_setup_theme', 'fahad_blog_gutenberg_support');