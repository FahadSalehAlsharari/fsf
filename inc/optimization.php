<?php
/**
 * وظائف تحسين الأداء وSEO لقالب فهد الشراري للمدونة
 *
 * @package Fahad_Blog
 * @version 2.0
 */

// منع الوصول المباشر للملف
if (!defined('ABSPATH')) {
    exit;
}

/**
 * تحسين أداء ووردبريس بإزالة العناصر غير الضرورية
 */
function fahad_blog_performance_optimizations() {
    // إزالة إصدار ووردبريس من header
    remove_action('wp_head', 'wp_generator');
    
    // إزالة روابط XML-RPC والتعديلات عن بعد غير الضرورية
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    
    // إزالة روابط RSS
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    
    // إزالة روابط الإيموجي
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // إزالة oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    
    // إزالة روابط المقالات المتجاورة
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    
    // تعطيل REST API وروابطها للمستخدمين غير المصرح لهم
    if (!is_admin() && !is_user_logged_in()) {
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);
    }
}
add_action('init', 'fahad_blog_performance_optimizations');

/**
 * إزالة نسخ WordPress من روابط CSS و JavaScript
 */
function fahad_blog_remove_wp_version_from_assets($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'fahad_blog_remove_wp_version_from_assets', 9999);
add_filter('script_loader_src', 'fahad_blog_remove_wp_version_from_assets', 9999);

/**
 * تحسين أداء CSS بتمييز الأنماط الحرجة
 */
function fahad_blog_critical_css() {
    // الأنماط الأساسية للتحميل السريع
    echo '<style id="critical-css">';
    
    // أنماط أساسية لرأس الصفحة والتصميم العام
    ?>
    :root{--primary-color:#00adb5;--secondary-color:#393e46;--background-color:#222831;--text-color:#eeeeee;--card-bg-color:#2d343d;--transition-speed:0.3s;--shadow-color:rgba(0, 0, 0, 0.2);--border-radius:10px}[data-theme=light]{--primary-color:#00adb5;--secondary-color:#f5f5f5;--background-color:#ffffff;--text-color:#333333;--card-bg-color:#f9f9f9;--shadow-color:rgba(0, 0, 0, 0.1)}*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth;font-size:100%;scroll-padding-top:70px}body{font-family:'Cairo',sans-serif;line-height:1.6;direction:rtl;background-color:var(--background-color);color:var(--text-color);transition:background-color 0.5s,color 0.5s;text-rendering:optimizeSpeed;margin:0;padding:0}a{text-decoration:none;color:inherit}ul{list-style:none}.container{max-width:1200px;margin:0 auto;padding:0 20px;width:100%}.site-header{background-color:var(--background-color);padding:20px 0;border-bottom:1px solid rgba(255,255,255,0.05);position:relative}.site-header .container{display:flex;justify-content:space-between;align-items:center;position:relative}.main-navigation{display:flex;justify-content:space-between;align-items:center;width:100%;position:relative}.site-branding{display:flex;align-items:center}.logo{font-size:2rem;font-weight:700;color:var(--primary-color);position:relative;overflow:hidden}.nav-menu{display:flex;align-items:center;transition:all var(--transition-speed) ease-in-out}.nav-menu li{margin-left:20px}.nav-menu li a{font-size:1.1rem;transition:color var(--transition-speed);padding:5px 2px;display:inline-block;position:relative}
    <?php
    echo '</style>';
}
add_action('wp_head', 'fahad_blog_critical_css', 1);

/**
 * تحميل CSS غير الحرج بشكل متأخر
 */
function fahad_blog_defer_non_critical_css($tag, $handle, $src) {
    if ($handle !== 'fahad-blog-style') {
        return $tag;
    }
    
    // إستبدال <link rel="stylesheet"> بـ <link rel="preload"> ثم تحميله عبر JavaScript
    $preload_tag = '<link rel="preload" href="' . esc_url($src) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    $noscript_tag = '<noscript><link rel="stylesheet" href="' . esc_url($src) . '"></noscript>';
    
    return $preload_tag . $noscript_tag;
}
add_filter('style_loader_tag', 'fahad_blog_defer_non_critical_css', 10, 3);

/**
 * تحميل الخطوط بشكل متأخر
 */
function fahad_blog_defer_fonts($tag, $handle, $src) {
    if ($handle !== 'google-fonts') {
        return $tag;
    }
    
    return '<link rel="preload" href="' . esc_url($src) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' .
           '<noscript><link rel="stylesheet" href="' . esc_url($src) . '"></noscript>';
}
add_filter('style_loader_tag', 'fahad_blog_defer_fonts', 10, 3);

/**
 * تفعيل خاصية تحميل الخطوط الاحتياطي وتحسين تحميل الخطوط
 */
function fahad_blog_optimize_font_display() {
    ?>
    <style>
    /* تعريف خطوط احتياطية قبل تحميل الخطوط الأساسية لتجنب تأخر عرض النص */
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }
    
    /* استخدام font-display: swap لجميع الخطوط */
    @font-face {
        font-family: 'Cairo';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
    }
    
    @font-face {
        font-family: 'Cairo';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
    }
    </style>
    <?php
}
add_action('wp_head', 'fahad_blog_optimize_font_display', 1);

/**
 * إضافة تلميحات preconnect و dns-prefetch
 */
function fahad_blog_performance_resource_hints($urls, $relation_type) {
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
        // إضافة preconnect لـ CDN
        $urls[] = [
            'href' => 'https://cdnjs.cloudflare.com',
            'crossorigin' => 'anonymous',
        ];
    }
    
    if ('dns-prefetch' === $relation_type) {
        // إضافة dns-prefetch للمصادر الخارجية
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = 'https://fonts.gstatic.com';
        $urls[] = 'https://cdnjs.cloudflare.com';
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'fahad_blog_performance_resource_hints', 10, 2);

/**
 * تمكين ضغط GZIP للمحتوى
 */
function fahad_blog_enable_gzip_compression() {
    if (extension_loaded('zlib') && !ini_get('zlib.output_compression') && ini_get('output_handler') !== 'ob_gzhandler') {
        ob_start('ob_gzhandler');
    }
}
add_action('wp', 'fahad_blog_enable_gzip_compression');

/**
 * تحديد وقت انتهاء الصلاحية لملفات CSS و JavaScript
 */
function fahad_blog_expire_headers() {
    if (is_admin()) {
        return;
    }
    
    // تحديد وقت انتهاء الصلاحية للملفات الثابتة
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600*24*7)); // أسبوع واحد
    header('Cache-Control: public, max-age=' . (3600*24*7)); // أسبوع واحد
}
add_action('template_redirect', 'fahad_blog_expire_headers');

/**
 * تمكين التخزين المؤقت للصفحات للمستخدمين غير المسجلين
 */
function fahad_blog_enable_page_caching() {
    if (!is_user_logged_in() && !is_admin() && !is_customize_preview()) {
        header('Cache-Control: public, max-age=600'); // 10 دقائق
    } else {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    }
}
add_action('template_redirect', 'fahad_blog_enable_page_caching');

/**
 * تعزيز أمان موقع ووردبريس
 */
function fahad_blog_security_headers() {
    // إضافة رؤوس Content-Security-Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data:; connect-src 'self';");
    
    // إضافة رؤوس X-Content-Type-Options
    header("X-Content-Type-Options: nosniff");
    
    // إضافة رؤوس X-Frame-Options
    header("X-Frame-Options: SAMEORIGIN");
    
    // إضافة رؤوس X-XSS-Protection
    header("X-XSS-Protection: 1; mode=block");
    
    // إضافة رؤوس Referrer-Policy
    header("Referrer-Policy: strict-origin-when-cross-origin");
    
    // إضافة رؤوس Permissions-Policy
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
}
add_action('send_headers', 'fahad_blog_security_headers');

/**
 * إضافة العلامات الوصفية لـ SEO في رأس الصفحة
 */
function fahad_blog_seo_meta_tags() {
    global $post;
    
    // إضافة علامة الوصف (Description)
    if (is_singular()) {
        // استخدام مقتطف المقال إذا كان موجوداً أو الأحرف الأولى من المحتوى
        if (has_excerpt()) {
            $description = wp_strip_all_tags(get_the_excerpt(), true);
        } else {
            $content = get_post_field('post_content', get_the_ID());
            $content = strip_shortcodes($content);
            $content = wp_strip_all_tags($content, true);
            $description = wp_trim_words($content, 30, '...');
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        // استخدام وصف القسم أو الوسم
        $term_description = term_description();
        if (!empty($term_description)) {
            $description = wp_strip_all_tags($term_description, true);
        } else {
            $description = sprintf(
                __('مقالات في %s - %s', 'fahad-blog'),
                single_term_title('', false),
                get_bloginfo('name')
            );
        }
    } elseif (is_home() || is_front_page()) {
        // استخدام وصف الموقع
        $description = get_bloginfo('description');
    } else {
        // للصفحات الأخرى
        $description = get_bloginfo('description');
    }
    
    // تنظيف الوصف وتقصيره
    $description = substr($description, 0, 160);
    $description = trim($description);
    
    if (!empty($description)) {
        echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    }
    
    // إضافة الكلمات المفتاحية (Keywords) للمقالات
    if (is_singular() && has_tag()) {
        $tags = get_the_tags();
        $keywords = [];
        
        if ($tags) {
            foreach ($tags as $tag) {
                $keywords[] = $tag->name;
            }
            
            echo '<meta name="keywords" content="' . esc_attr(implode(', ', $keywords)) . '" />' . "\n";
        }
    }
    
    // إضافة صورة مميزة لـ Open Graph
    if (is_singular() && has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail = wp_get_attachment_image_src($thumbnail_id, 'large');
        
        if ($thumbnail) {
            echo '<meta property="og:image" content="' . esc_url($thumbnail[0]) . '" />' . "\n";
            echo '<meta property="og:image:width" content="' . esc_attr($thumbnail[1]) . '" />' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr($thumbnail[2]) . '" />' . "\n";
        }
    } elseif (is_singular()) {
        // إذا لم تكن هناك صورة مميزة، استخدم شعار الموقع
        $site_icon_url = get_site_icon_url(512);
        if ($site_icon_url) {
            echo '<meta property="og:image" content="' . esc_url($site_icon_url) . '" />' . "\n";
        }
    }
    
    // إضافة علامات Open Graph
    if (is_singular()) {
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if (!empty($description)) {
            echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
        }
        
        // إضافة بيانات المقال
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />' . "\n";
        
        // إضافة بيانات المؤلف
        $author_id = get_the_author_meta('ID');
        if ($author_id) {
            echo '<meta property="article:author" content="' . esc_url(get_author_posts_url($author_id)) . '" />' . "\n";
        }
        
        // إضافة التصنيفات كأقسام للمقال
        $categories = get_the_category();
        if ($categories) {
            foreach ($categories as $category) {
                echo '<meta property="article:section" content="' . esc_attr($category->name) . '" />' . "\n";
            }
        }
        
        // إضافة الوسوم كعلامات للمقال
        if (has_tag()) {
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />' . "\n";
                }
            }
        }
    } elseif (is_front_page() || is_home()) {
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\n";
        
        if (!empty($description)) {
            echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
        }
    }
    
    // إضافة بيانات Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    
    if (is_singular()) {
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        
        if (!empty($description)) {
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
        }
    } else {
        echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        if (!empty($description)) {
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
        }
    }
    
    // إضافة معلومات الموقع
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    
    // إضافة معلومات اللغة
    echo '<meta property="og:locale" content="ar_SA" />' . "\n";
    
    // إضافة الرابط الرئيسي
    echo '<link rel="canonical" href="' . esc_url(fahad_blog_get_canonical_url()) . '" />' . "\n";
}
add_action('wp_head', 'fahad_blog_seo_meta_tags', 1);

/**
 * الحصول على الرابط القانوني (Canonical URL)
 */
function fahad_blog_get_canonical_url() {
    if (is_home() && get_option('page_for_posts')) {
        return get_permalink(get_option('page_for_posts'));
    }
    
    if (is_front_page()) {
        return home_url('/');
    }
    
    if (is_singular()) {
        return get_permalink();
    }
    
    if (is_category() || is_tag() || is_tax()) {
        return get_term_link(get_queried_object());
    }
    
    if (is_author()) {
        return get_author_posts_url(get_queried_object_id());
    }
    
    if (is_year()) {
        return get_year_link(get_query_var('year'));
    }
    
    if (is_month()) {
        return get_month_link(get_query_var('year'), get_query_var('monthnum'));
    }
    
    if (is_day()) {
        return get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
    }
    
    return home_url($_SERVER['REQUEST_URI']);
}

/**
 * إضافة Schema.org للموقع بأكمله
 */
function fahad_blog_schema_org_website() {
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "<?php echo esc_url(home_url('/')); ?>",
        "name": "<?php echo esc_attr(get_bloginfo('name')); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo esc_url(home_url('/')); ?>?s={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    <?php
}
add_action('wp_head', 'fahad_blog_schema_org_website');

/**
 * إضافة Schema.org للمؤلف
 */
function fahad_blog_schema_org_author() {
    if (is_author()) {
        $author_id = get_queried_object_id();
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Person",
            "@id": "<?php echo esc_url(get_author_posts_url($author_id)); ?>#person",
            "name": "<?php echo esc_attr(get_the_author_meta('display_name', $author_id)); ?>",
            "url": "<?php echo esc_url(get_author_posts_url($author_id)); ?>"
            <?php if (get_the_author_meta('description', $author_id)) : ?>
            ,"description": "<?php echo esc_attr(get_the_author_meta('description', $author_id)); ?>"
            <?php endif; ?>
        }
        </script>
        <?php
    }
}
add_action('wp_head', 'fahad_blog_schema_org_author');

/**
 * تعديل عنوان الصفحة لتحسين SEO
 */
function fahad_blog_wp_title($title, $sep) {
    global $paged, $page;
    
    if (is_feed()) {
        return $title;
    }
    
    // إضافة رقم الصفحة إذا لزم الأمر
    if ($paged >= 2 || $page >= 2) {
        $title .= sprintf(
            __('الصفحة %s', 'fahad-blog'),
            max($paged, $page)
        );
        
        $title .= " $sep ";
    }
    
    // إضافة اسم الموقع في نهاية العنوان
    $title .= get_bloginfo('name');
    
    // إضافة وصف الموقع في صفحة البداية
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title .= " $sep $site_description";
    }
    
    return $title;
}
add_filter('wp_title', 'fahad_blog_wp_title', 10, 2);

/**
 * تعديل روابط الأرشيف للتحسين من SEO
 */
function fahad_blog_optimize_archive_urls($link) {
    return str_replace(['?post_type=post', '?cat='], ['', '/category/'], $link);
}
add_filter('post_type_archive_link', 'fahad_blog_optimize_archive_urls');
add_filter('category_link', 'fahad_blog_optimize_archive_urls');

/**
 * إضافة معلومات وصفية (alt text) للصور تلقائيًا
 */
function fahad_blog_auto_image_alt($content) {
    global $post;
    
    if (!$post) {
        return $content;
    }
    
    $pattern = '/<img(.*?)alt=[\'|"](.*?)[\'|"](.*?)>/i';
    $replace_pattern = '/<img(.*?)alt=[\'"]{2}(.*?)>/i';
    
    // البحث عن الصور التي لا تحتوي على alt text
    if (preg_match_all($replace_pattern, $content, $matches)) {
        foreach ($matches[0] as $match) {
            $post_title = get_the_title();
            $new_img = str_replace('alt=""', 'alt="' . esc_attr($post_title) . '"', $match);
            $content = str_replace($match, $new_img, $content);
        }
    }
    
    return $content;
}
add_filter('the_content', 'fahad_blog_auto_image_alt');

/**
 * تحسين تنسيق XML Sitemap
 */
function fahad_blog_sitemap_stylesheet() {
    // التحقق إذا كان الطلب لخريطة موقع XML
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'sitemap.xml') !== false) {
        header('Content-Type: application/xml; charset=UTF-8');
        $xsl_path = FAHAD_BLOG_URI . '/assets/xml/sitemap.xsl';
        echo '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . esc_url($xsl_path) . '"?>';
    }
}
add_action('template_redirect', 'fahad_blog_sitemap_stylesheet', 0);

/**
 * تحسين الروابط الداخلية بإضافة noopener و noreferrer للروابط الخارجية
 */
function fahad_blog_enhance_external_links($content) {
    $pattern = '/<a(.*?)href=[\'|"](https?:\/\/.*?)[\'|"](.*?)>/i';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $url = $matches[2];
        $site_url = home_url();
        
        // التحقق إذا كان الرابط خارجي
        if (strpos($url, $site_url) === false && strpos($matches[1], 'rel=') === false) {
            return '<a' . $matches[1] . 'href="' . $url . '"' . $matches[3] . ' target="_blank" rel="noopener noreferrer">';
        }
        
        return $matches[0];
    }, $content);
    
    return $content;
}
add_filter('the_content', 'fahad_blog_enhance_external_links');