<?php
/**
 * الوظائف الأساسية لقالب فهد الشراري للمدونة
 *
 * @package Fahad_Blog
 * @version 2.0
 */

// منع الوصول المباشر للملف
if (!defined('ABSPATH')) {
    exit;
}

/**
 * وظيفة تحقق مما إذا كانت الصفحة الحالية صفحة مقال
 *
 * @return bool صحيح إذا كانت الصفحة الحالية هي مقال
 */
function fahad_blog_is_blog_post() {
    return (is_single() && 'post' === get_post_type());
}

/**
 * وظيفة تحقق مما إذا كانت الصفحة الحالية صفحة أرشيف
 *
 * @return bool صحيح إذا كانت الصفحة الحالية هي أرشيف
 */
function fahad_blog_is_archive_page() {
    return (is_archive() || is_category() || is_tag() || is_author() || is_day() || is_month() || is_year());
}

/**
 * إضافة فئات وأوصاف للعناصر في القائمة
 *
 * @param string $item_output مخرجات العنصر
 * @param object $item كائن العنصر
 * @param int $depth عمق العنصر
 * @param array $args معلمات القائمة
 * @return string مخرجات العنصر المعدلة
 */
function fahad_blog_nav_menu_item_classes($item_output, $item, $depth, $args) {
    // إذا كان العنصر له وصف، أضفه إلى النص
    if (!empty($item->description)) {
        $item_output = str_replace($args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output);
    }
    
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'fahad_blog_nav_menu_item_classes', 10, 4);

/**
 * إضافة فئة للعناصر الفرعية في القائمة
 *
 * @param array $classes فئات العنصر
 * @param object $item كائن العنصر
 * @param array $args معلمات القائمة
 * @return array فئات العنصر المعدلة
 */
function fahad_blog_submenu_classes($classes, $item, $args) {
    if (in_array('menu-item-has-children', $classes)) {
        $classes[] = 'has-dropdown';
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'fahad_blog_submenu_classes', 10, 3);

/**
 * حساب وقت القراءة المقدر للمقال
 *
 * @param int $post_id معرف المقال، إذا كان فارغًا يستخدم المقال الحالي
 * @return int وقت القراءة المقدر بالدقائق
 */
function fahad_blog_calculate_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    
    // افتراض معدل قراءة 200 كلمة في الدقيقة
    $reading_time = ceil($word_count / 200);
    
    // التأكد من أن وقت القراءة لا يقل عن دقيقة واحدة
    return max(1, $reading_time);
}

/**
 * الحصول على روابط مشاركة المقال
 *
 * @param int $post_id معرف المقال، إذا كان فارغًا يستخدم المقال الحالي
 * @return array مصفوفة من روابط المشاركة
 */
function fahad_blog_get_sharing_links($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_url = urlencode(get_permalink($post_id));
    $post_title = urlencode(get_the_title($post_id));
    
    $sharing_links = [
        'twitter' => [
            'url' => "https://twitter.com/intent/tweet?url={$post_url}&text={$post_title}",
            'icon' => 'fab fa-twitter',
            'label' => __('مشاركة على تويتر', 'fahad-blog'),
        ],
        'facebook' => [
            'url' => "https://www.facebook.com/sharer/sharer.php?u={$post_url}",
            'icon' => 'fab fa-facebook-f',
            'label' => __('مشاركة على فيسبوك', 'fahad-blog'),
        ],
        'whatsapp' => [
            'url' => "https://api.whatsapp.com/send?text={$post_title} - {$post_url}",
            'icon' => 'fab fa-whatsapp',
            'label' => __('مشاركة على واتساب', 'fahad-blog'),
        ],
        'telegram' => [
            'url' => "https://t.me/share/url?url={$post_url}&text={$post_title}",
            'icon' => 'fab fa-telegram-plane',
            'label' => __('مشاركة على تيليجرام', 'fahad-blog'),
        ],
    ];
    
    /**
     * تصفية لتمكين المطورين من تعديل روابط المشاركة
     */
    return apply_filters('fahad_blog_sharing_links', $sharing_links, $post_id);
}

/**
 * الحصول على المقالات ذات الصلة
 *
 * @param int $post_id معرف المقال، إذا كان فارغًا يستخدم المقال الحالي
 * @param int $count عدد المقالات المراد جلبها
 * @return object استعلام ووردبريس للمقالات ذات الصلة
 */
function fahad_blog_get_related_posts($post_id = null, $count = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    $category_ids = [];
    
    if ($categories) {
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
    }
    
    if (empty($category_ids)) {
        return null;
    }
    
    $args = [
        'category__in'        => $category_ids,
        'post__not_in'        => [$post_id],
        'posts_per_page'      => $count,
        'ignore_sticky_posts' => 1,
        'orderby'             => 'rand',
    ];
    
    return new WP_Query($args);
}

/**
 * إنشاء فتات التنقل (Breadcrumbs)
 *
 * @return string فتات التنقل HTML
 */
function fahad_blog_get_breadcrumbs() {
    $breadcrumbs = '<div class="breadcrumbs">';
    $breadcrumbs .= '<span class="breadcrumb-item home"><a href="' . esc_url(home_url('/')) . '"><i class="fas fa-home" aria-hidden="true"></i> ' . esc_html__('الرئيسية', 'fahad-blog') . '</a></span>';
    
    if (fahad_blog_is_blog_post()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
            $breadcrumbs .= '<span class="breadcrumb-item category"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></span>';
        }
        
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html(get_the_title()) . '</span>';
    } elseif (is_category()) {
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html(single_cat_title('', false)) . '</span>';
    } elseif (is_tag()) {
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html(single_tag_title('', false)) . '</span>';
    } elseif (is_author()) {
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html(get_the_author()) . '</span>';
    } elseif (is_search()) {
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html__('نتائج البحث', 'fahad-blog') . '</span>';
    } elseif (is_page()) {
        $parent_id = wp_get_post_parent_id(get_the_ID());
        if ($parent_id) {
            $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
            $breadcrumbs .= '<span class="breadcrumb-item parent"><a href="' . esc_url(get_permalink($parent_id)) . '">' . esc_html(get_the_title($parent_id)) . '</a></span>';
        }
        
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html(get_the_title()) . '</span>';
    } elseif (is_404()) {
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html__('صفحة غير موجودة', 'fahad-blog') . '</span>';
    } elseif (is_archive()) {
        $breadcrumbs .= '<span class="breadcrumb-separator"><i class="fas fa-angle-left" aria-hidden="true"></i></span>';
        $breadcrumbs .= '<span class="breadcrumb-item current">' . esc_html(get_the_archive_title()) . '</span>';
    }
    
    $breadcrumbs .= '</div>';
    
    return $breadcrumbs;
}

/**
 * معالجة الصور اللازمة التحميل
 *
 * @param string $content محتوى المقال
 * @return string المحتوى المعدل مع صور لازمة التحميل
 */
function fahad_blog_process_lazy_images($content) {
    if (is_admin() || is_feed()) {
        return $content;
    }
    
    // استبدال الصور العادية بصور لازمة التحميل
    $content = preg_replace('/<img(.*?)src="(.*?)"(.*?)>/i', '<img$1src="' . FAHAD_BLOG_URI . '/assets/images/placeholder.svg" data-src="$2"$3 loading="lazy" class="lazy-load">', $content);
    
    return $content;
}
add_filter('the_content', 'fahad_blog_process_lazy_images');

/**
 * إنشاء جدول المحتويات تلقائيًا من محتوى المقال
 *
 * @param string $content محتوى المقال
 * @return string المحتوى معدلًا مع معرفات للعناوين
 */
function fahad_blog_auto_ids_for_headings($content) {
    if (!is_singular('post')) {
        return $content;
    }
    
    $pattern = '/<h([2-4])(.*?)>(.*?)<\/h[2-4]>/i';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $level = $matches[1];
        $attrs = $matches[2];
        $title = $matches[3];
        
        // إنشاء معرف من النص (slug)
        $id = sanitize_title($title);
        
        // التحقق مما إذا كان الـ ID موجودًا بالفعل وإضافة رقم إذا لزم الأمر
        if (strpos($attrs, 'id=') === false) {
            $attrs = $attrs . ' id="' . $id . '"';
        }
        
        return "<h{$level}{$attrs}>{$title}</h{$level}>";
    }, $content);
    
    return $content;
}
add_filter('the_content', 'fahad_blog_auto_ids_for_headings');

/**
 * الحصول على جدول المحتويات من محتوى المقال
 *
 * @param string $content محتوى المقال
 * @return string HTML لجدول المحتويات
 */
function fahad_blog_get_table_of_contents($content) {
    if (!is_singular('post')) {
        return '';
    }
    
    $headings = [];
    $pattern = '/<h([2-4])(.*?)id="(.*?)"(.*?)>(.*?)<\/h[2-4]>/i';
    
    if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $level = (int)$match[1];
            $id = $match[3];
            $title = strip_tags($match[5]);
            
            $headings[] = [
                'level' => $level,
                'id' => $id,
                'title' => $title
            ];
        }
    }
    
    if (count($headings) < 3) {
        return '';
    }
    
    $toc = '<div class="table-of-contents">';
    $toc .= '<h3 class="toc-title"><i class="fas fa-list" aria-hidden="true"></i> ' . esc_html__('محتويات المقال', 'fahad-blog') . '</h3>';
    $toc .= '<div class="toc-content" id="toc-content">';
    
    $current_level = 0;
    $toc .= '<ul>';
    
    foreach ($headings as $heading) {
        if ($heading['level'] > $current_level) {
            // نزول لمستوى أعمق
            $diff = $heading['level'] - $current_level;
            for ($i = 0; $i < $diff; $i++) {
                $toc .= '<ul>';
            }
            $current_level = $heading['level'];
        } elseif ($heading['level'] < $current_level) {
            // صعود لمستوى أعلى
            $diff = $current_level - $heading['level'];
            for ($i = 0; $i < $diff; $i++) {
                $toc .= '</ul></li>';
            }
            $current_level = $heading['level'];
        } else {
            // نفس المستوى
            $toc .= '</li>';
        }
        
        $toc .= '<li><a href="#' . esc_attr($heading['id']) . '">' . esc_html($heading['title']) . '</a>';
    }
    
    // إغلاق القوائم المفتوحة
    for ($i = 0; $i < $current_level; $i++) {
        $toc .= '</li></ul>';
    }
    
    $toc .= '</div>'; // .toc-content
    $toc .= '</div>'; // .table-of-contents
    
    return $toc;
}

/**
 * تحسين أنماط محرر المحتوى
 */
function fahad_blog_add_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'fahad_blog_add_editor_styles');

/**
 * تعديل الفئات الأساسية للصفحة لدعم التخصيصات
 *
 * @param array $classes فئات الصفحة
 * @return array فئات معدلة
 */
function fahad_blog_body_classes($classes) {
    // إضافة فئة javascript للمتصفحات التي تدعم جافاسكريبت
    $classes[] = 'no-js'; // سيتم استبدالها بواسطة جافاسكريبت
    
    // إضافة فئة إذا كان الشريط الجانبي نشطًا
    if (is_active_sidebar('sidebar-1') && !is_singular()) {
        $classes[] = 'has-sidebar';
    }
    
    // إضافة فئات للمقالات الفردية
    if (is_singular('post')) {
        $classes[] = 'single-post-view';
    }
    
    // إضافة فئة للصفحة الرئيسية
    if (is_home() || is_front_page()) {
        $classes[] = 'home-view';
    }
    
    // إضافة فئة للأرشيف
    if (fahad_blog_is_archive_page()) {
        $classes[] = 'archive-view';
    }
    
    return $classes;
}
add_filter('body_class', 'fahad_blog_body_classes');

/**
 * تحميل JavaScript لتبديل فئة `no-js` إلى `js`
 */
function fahad_blog_javascript_detection() {
    echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action('wp_head', 'fahad_blog_javascript_detection', 0);

/**
 * حماية المقالات من التخزين المؤقت في متصفحات مختلفة
 */
function fahad_blog_disable_browser_caching() {
    if (is_user_logged_in()) {
        header('Cache-Control: private, no-store, max-age=0');
    }
}
add_action('wp', 'fahad_blog_disable_browser_caching');