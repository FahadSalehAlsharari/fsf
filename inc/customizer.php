<?php
/**
 * إعدادات مخصص قالب فهد الشراري للمدونة
 *
 * @package Fahad_Blog
 * @version 2.0
 */

// منع الوصول المباشر للملف
if (!defined('ABSPATH')) {
    exit;
}

/**
 * إضافة إعدادات مخصص القالب
 */
function fahad_blog_customize_register($wp_customize) {
    
    // إضافة قسم إعدادات العامة
    $wp_customize->add_section('fahad_blog_general_settings', [
        'title'       => __('إعدادات عامة', 'fahad-blog'),
        'priority'    => 30,
        'description' => __('إعدادات عامة للقالب', 'fahad-blog'),
    ]);
    
    // إضافة خيار لتعيين الوضع الافتراضي (داكن/فاتح)
    $wp_customize->add_setting('fahad_blog_default_theme', [
        'default'           => 'dark',
        'sanitize_callback' => 'fahad_blog_sanitize_select',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_default_theme', [
        'label'       => __('الوضع الافتراضي', 'fahad-blog'),
        'section'     => 'fahad_blog_general_settings',
        'type'        => 'select',
        'choices'     => [
            'dark'  => __('داكن', 'fahad-blog'),
            'light' => __('فاتح', 'fahad-blog'),
        ],
    ]);
    
    // إضافة خيار لتفعيل/تعطيل زر تبديل الوضع
    $wp_customize->add_setting('fahad_blog_enable_theme_switch', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_enable_theme_switch', [
        'label'       => __('تفعيل زر تبديل الوضع', 'fahad-blog'),
        'section'     => 'fahad_blog_general_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة خيار للتحكم في شعار الموقع
    $wp_customize->add_setting('fahad_blog_logo_text', [
        'default'           => 'FS',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_logo_text', [
        'label'       => __('نص الشعار (إذا لم يكن هناك شعار مخصص)', 'fahad-blog'),
        'section'     => 'fahad_blog_general_settings',
        'type'        => 'text',
    ]);
    
    // إضافة قسم إعدادات الأداء
    $wp_customize->add_section('fahad_blog_performance_settings', [
        'title'       => __('إعدادات الأداء', 'fahad-blog'),
        'priority'    => 35,
        'description' => __('إعدادات تحسين أداء الموقع', 'fahad-blog'),
    ]);
    
    // إضافة خيار لتفعيل/تعطيل تحميل الصور البطيء
    $wp_customize->add_setting('fahad_blog_enable_lazy_load', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_enable_lazy_load', [
        'label'       => __('تفعيل تحميل الصور البطيء', 'fahad-blog'),
        'section'     => 'fahad_blog_performance_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة خيار لتفعيل/تعطيل تأخير تحميل CSS
    $wp_customize->add_setting('fahad_blog_enable_css_defer', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_enable_css_defer', [
        'label'       => __('تفعيل تأخير تحميل CSS غير الحرج', 'fahad-blog'),
        'section'     => 'fahad_blog_performance_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة قسم إعدادات المقالات
    $wp_customize->add_section('fahad_blog_post_settings', [
        'title'       => __('إعدادات المقالات', 'fahad-blog'),
        'priority'    => 40,
        'description' => __('إعدادات عرض المقالات', 'fahad-blog'),
    ]);
    
    // إضافة خيار لتفعيل/تعطيل معلومات الكاتب
    $wp_customize->add_setting('fahad_blog_show_author_bio', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_show_author_bio', [
        'label'       => __('عرض معلومات الكاتب', 'fahad-blog'),
        'section'     => 'fahad_blog_post_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة خيار لتفعيل/تعطيل المقالات ذات الصلة
    $wp_customize->add_setting('fahad_blog_show_related_posts', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_show_related_posts', [
        'label'       => __('عرض المقالات ذات الصلة', 'fahad-blog'),
        'section'     => 'fahad_blog_post_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة خيار لتفعيل/تعطيل جدول المحتويات
    $wp_customize->add_setting('fahad_blog_show_toc', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_show_toc', [
        'label'       => __('عرض جدول المحتويات', 'fahad-blog'),
        'section'     => 'fahad_blog_post_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة خيار لعدد المقالات ذات الصلة
    $wp_customize->add_setting('fahad_blog_related_posts_count', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_related_posts_count', [
        'label'       => __('عدد المقالات ذات الصلة', 'fahad-blog'),
        'section'     => 'fahad_blog_post_settings',
        'type'        => 'number',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 6,
            'step' => 1,
        ],
    ]);
    
    // إضافة قسم روابط التواصل الاجتماعي
    $wp_customize->add_section('fahad_blog_social_settings', [
        'title'       => __('روابط التواصل الاجتماعي', 'fahad-blog'),
        'priority'    => 45,
        'description' => __('إضافة روابط حسابات التواصل الاجتماعي', 'fahad-blog'),
    ]);
    
    // إضافة حقول للروابط الاجتماعية
    $social_platforms = [
        'linkedin'  => __('LinkedIn', 'fahad-blog'),
        'github'    => __('GitHub', 'fahad-blog'),
        'twitter'   => __('Twitter', 'fahad-blog'),
        'facebook'  => __('Facebook', 'fahad-blog'),
        'instagram' => __('Instagram', 'fahad-blog'),
        'youtube'   => __('YouTube', 'fahad-blog'),
    ];
    
    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting("fahad_blog_social_{$platform}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]);
        
        $wp_customize->add_control("fahad_blog_social_{$platform}", [
            'label'       => $label,
            'section'     => 'fahad_blog_social_settings',
            'type'        => 'url',
            'input_attrs' => [
                'placeholder' => __('أدخل رابط حسابك', 'fahad-blog'),
            ],
        ]);
    }
    
    // إضافة قسم إعدادات الألوان
    $wp_customize->add_section('fahad_blog_colors', [
        'title'       => __('ألوان القالب', 'fahad-blog'),
        'priority'    => 50,
        'description' => __('تخصيص ألوان القالب', 'fahad-blog'),
    ]);
    
    // إضافة خيار للون الرئيسي
    $wp_customize->add_setting('fahad_blog_primary_color', [
        'default'           => '#00adb5',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fahad_blog_primary_color', [
        'label'       => __('اللون الرئيسي', 'fahad-blog'),
        'section'     => 'fahad_blog_colors',
    ]));
    
    // إضافة خيار للون الثانوي
    $wp_customize->add_setting('fahad_blog_secondary_color', [
        'default'           => '#393e46',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fahad_blog_secondary_color', [
        'label'       => __('اللون الثانوي', 'fahad-blog'),
        'section'     => 'fahad_blog_colors',
    ]));
    
    // إضافة خيار للون الخلفية (الوضع الداكن)
    $wp_customize->add_setting('fahad_blog_dark_bg_color', [
        'default'           => '#222831',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fahad_blog_dark_bg_color', [
        'label'       => __('لون الخلفية (الوضع الداكن)', 'fahad-blog'),
        'section'     => 'fahad_blog_colors',
    ]));
    
    // إضافة خيار للون النص (الوضع الداكن)
    $wp_customize->add_setting('fahad_blog_dark_text_color', [
        'default'           => '#eeeeee',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fahad_blog_dark_text_color', [
        'label'       => __('لون النص (الوضع الداكن)', 'fahad-blog'),
        'section'     => 'fahad_blog_colors',
    ]));
    
    // إضافة قسم تذييل الموقع
    $wp_customize->add_section('fahad_blog_footer_settings', [
        'title'       => __('إعدادات تذييل الموقع', 'fahad-blog'),
        'priority'    => 55,
        'description' => __('تخصيص تذييل الموقع', 'fahad-blog'),
    ]);
    
    // إضافة خيار لنص حقوق النشر
    $wp_customize->add_setting('fahad_blog_copyright_text', [
        'default'           => __('جميع الحقوق محفوظة', 'fahad-blog'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_copyright_text', [
        'label'       => __('نص حقوق النشر', 'fahad-blog'),
        'section'     => 'fahad_blog_footer_settings',
        'type'        => 'text',
    ]);
    
    // إضافة خيار لعرض روابط الشبكات الاجتماعية في التذييل
    $wp_customize->add_setting('fahad_blog_show_social_footer', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_show_social_footer', [
        'label'       => __('عرض روابط الشبكات الاجتماعية في التذييل', 'fahad-blog'),
        'section'     => 'fahad_blog_footer_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة خيار لرابط سياسة الخصوصية
    $wp_customize->add_setting('fahad_blog_privacy_page', [
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_privacy_page', [
        'label'       => __('صفحة سياسة الخصوصية', 'fahad-blog'),
        'section'     => 'fahad_blog_footer_settings',
        'type'        => 'dropdown-pages',
    ]);
    
    // إضافة خيار للرابط الرئيسي
    $wp_customize->add_setting('fahad_blog_main_site_url', [
        'default'           => 'https://fahadalsharari.com',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_main_site_url', [
        'label'       => __('رابط الموقع الرئيسي', 'fahad-blog'),
        'section'     => 'fahad_blog_footer_settings',
        'type'        => 'url',
    ]);
    
    // إضافة خيارات تخصيص للصفحة الرئيسية
    $wp_customize->add_section('fahad_blog_homepage_settings', [
        'title'       => __('إعدادات الصفحة الرئيسية', 'fahad-blog'),
        'priority'    => 60,
        'description' => __('تخصيص عرض الصفحة الرئيسية', 'fahad-blog'),
    ]);
    
    // إضافة خيار لتفعيل/تعطيل قسم المقدمة
    $wp_customize->add_setting('fahad_blog_show_intro', [
        'default'           => true,
        'sanitize_callback' => 'fahad_blog_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_show_intro', [
        'label'       => __('عرض قسم المقدمة', 'fahad-blog'),
        'section'     => 'fahad_blog_homepage_settings',
        'type'        => 'checkbox',
    ]);
    
    // إضافة خيار لعنوان المقدمة
    $wp_customize->add_setting('fahad_blog_intro_title', [
        'default'           => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_intro_title', [
        'label'       => __('عنوان المقدمة', 'fahad-blog'),
        'section'     => 'fahad_blog_homepage_settings',
        'type'        => 'text',
    ]);
    
    // إضافة خيار لوصف المقدمة
    $wp_customize->add_setting('fahad_blog_intro_desc', [
        'default'           => get_bloginfo('description'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_intro_desc', [
        'label'       => __('وصف المقدمة', 'fahad-blog'),
        'section'     => 'fahad_blog_homepage_settings',
        'type'        => 'textarea',
    ]);
    
    // إضافة خيار لعدد المقالات في الصفحة الرئيسية
    $wp_customize->add_setting('fahad_blog_posts_per_page', [
        'default'           => get_option('posts_per_page'),
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    
    $wp_customize->add_control('fahad_blog_posts_per_page', [
        'label'       => __('عدد المقالات في الصفحة الواحدة', 'fahad-blog'),
        'section'     => 'fahad_blog_homepage_settings',
        'type'        => 'number',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 30,
            'step' => 1,
        ],
    ]);
}
add_action('customize_register', 'fahad_blog_customize_register');

/**
 * إضافة CSS مخصص من خلال إعدادات المخصص
 */
function fahad_blog_customizer_css() {
    // الحصول على القيم المخصصة
    $primary_color = get_theme_mod('fahad_blog_primary_color', '#00adb5');
    $secondary_color = get_theme_mod('fahad_blog_secondary_color', '#393e46');
    $dark_bg_color = get_theme_mod('fahad_blog_dark_bg_color', '#222831');
    $dark_text_color = get_theme_mod('fahad_blog_dark_text_color', '#eeeeee');
    
    // بداية CSS المخصص
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --background-color: <?php echo esc_attr($dark_bg_color); ?>;
            --text-color: <?php echo esc_attr($dark_text_color); ?>;
        }
        
        /* أنماط إضافية بناءً على اللون الرئيسي */
        .primary-color, .nav-menu li a:hover, .nav-menu li a:focus, .logo {
            color: var(--primary-color);
        }
        
        .primary-bg, .theme-toggle:hover, .search-submit, .scroll-to-top:hover {
            background-color: var(--primary-color);
        }
        
        .entry-category:hover, .blog-category:hover, .post-share a:hover {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        /* تخصيص زر "اقرأ المزيد" */
        .read-more {
            color: var(--primary-color);
        }
        
        .read-more:hover {
            text-decoration: underline;
        }
        
        /* تخصيص روابط التنقل */
        .nav-menu li a::after {
            background-color: var(--primary-color);
        }
        
        /* تخصيص لون الحدود */
        blockquote {
            border-right-color: var(--primary-color);
        }
        
        /* تخصيص الأزرار */
        .btn-primary, .submit {
            background-color: var(--primary-color);
        }
        
        .btn-primary:hover, .submit:hover {
            background-color: var(--text-color);
            color: var(--primary-color);
        }
    </style>
    <?php
}
add_action('wp_head', 'fahad_blog_customizer_css');

/**
 * تنقية اختيارات القوائم المنسدلة
 */
function fahad_blog_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * تنقية مربعات الاختيار
 */
function fahad_blog_sanitize_checkbox($input) {
    return (isset($input) && true == $input ? true : false);
}

/**
 * تطبيق إعدادات القالب
 */
function fahad_blog_apply_theme_settings() {
    // الوضع الافتراضي (داكن/فاتح)
    $default_theme = get_theme_mod('fahad_blog_default_theme', 'dark');
    
    // تحميل JavaScript للوضع
    ?>
    <script type="text/javascript">
        // تحديد الوضع الافتراضي من الإعدادات
        (function() {
            const defaultTheme = '<?php echo esc_js($default_theme); ?>';
            const savedTheme = localStorage.getItem('theme');
            
            // إذا لم يكن هناك وضع محفوظ، استخدم الافتراضي
            if (!savedTheme) {
                localStorage.setItem('theme', defaultTheme);
                
                if (defaultTheme === 'light') {
                    document.body.setAttribute('data-theme', 'light');
                }
            }
        })();
    </script>
    <?php
    
    // تطبيق عدد المقالات في الصفحة
    if (!is_admin()) {
        add_filter('pre_option_posts_per_page', function($value) {
            $custom_value = get_theme_mod('fahad_blog_posts_per_page', $value);
            return $custom_value;
        });
    }
}
add_action('wp_head', 'fahad_blog_apply_theme_settings');

/**
 * تحميل ملف JavaScript مخصص للمخصص
 */
function fahad_blog_customize_preview_js() {
    wp_enqueue_script('fahad-blog-customizer', get_template_directory_uri() . '/assets/js/customizer.js', ['customize-preview'], FAHAD_BLOG_VERSION, true);
}
add_action('customize_preview_init', 'fahad_blog_customize_preview_js');

/**
 * الحصول على روابط الشبكات الاجتماعية من الإعدادات
 * 
 * @return array مصفوفة من روابط الشبكات الاجتماعية
 */
function fahad_blog_get_social_links() {
    $social_links = [];
    $social_platforms = [
        'linkedin'  => [
            'label' => __('LinkedIn', 'fahad-blog'),
            'icon'  => 'fab fa-linkedin',
        ],
        'github'    => [
            'label' => __('GitHub', 'fahad-blog'),
            'icon'  => 'fab fa-github',
        ],
        'twitter'   => [
            'label' => __('Twitter', 'fahad-blog'),
            'icon'  => 'fab fa-twitter',
        ],
        'facebook'  => [
            'label' => __('Facebook', 'fahad-blog'),
            'icon'  => 'fab fa-facebook-f',
        ],
        'instagram' => [
            'label' => __('Instagram', 'fahad-blog'),
            'icon'  => 'fab fa-instagram',
        ],
        'youtube'   => [
            'label' => __('YouTube', 'fahad-blog'),
            'icon'  => 'fab fa-youtube',
        ],
    ];
    
    foreach ($social_platforms as $platform => $data) {
        $url = get_theme_mod("fahad_blog_social_{$platform}", '');
        if (!empty($url)) {
            $social_links[$platform] = [
                'url'   => $url,
                'label' => $data['label'],
                'icon'  => $data['icon'],
            ];
        }
    }
    
    return $social_links;
}