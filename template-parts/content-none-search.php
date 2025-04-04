<?php
/**
 * قالب لعرض حالة عدم وجود نتائج بحث
 *
 * @package Fahad_Blog
 * @version 2.0
 */
?>

<div class="no-results">
    <div class="search-icon">
        <i class="fas fa-search" aria-hidden="true"></i>
    </div>
    <h2><?php esc_html_e('لم يتم العثور على نتائج', 'fahad-blog'); ?></h2>
    <p><?php esc_html_e('لم نتمكن من العثور على أي نتائج تطابق بحثك. يمكنك تجربة كلمات بحث أخرى أو تصفح المحتوى من خلال القوائم.', 'fahad-blog'); ?></p>
    
    <div class="search-again">
        <h3><?php esc_html_e('حاول البحث مرة أخرى', 'fahad-blog'); ?></h3>
        <?php fahad_blog_search_form(); ?>
    </div>
    
    <div class="search-suggestions">
        <h3><?php esc_html_e('اقتراحات للبحث', 'fahad-blog'); ?></h3>
        <ul>
            <li><?php esc_html_e('تأكد من كتابة جميع الكلمات بشكل صحيح.', 'fahad-blog'); ?></li>
            <li><?php esc_html_e('جرب كلمات مفتاحية مختلفة.', 'fahad-blog'); ?></li>
            <li><?php esc_html_e('جرب كلمات مفتاحية أكثر عمومية.', 'fahad-blog'); ?></li>
        </ul>
    </div>
</div>