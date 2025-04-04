<?php
/**
 * قالب لعرض حالة عدم وجود مقالات
 *
 * @package Fahad_Blog
 * @version 2.0
 */
?>

<div class="no-results">
    <h2><?php esc_html_e('لم يتم العثور على مقالات', 'fahad-blog'); ?></h2>
    <p><?php esc_html_e('لم يتم العثور على مقالات في هذا القسم. يمكنك تجربة البحث أو زيارة الصفحة الرئيسية.', 'fahad-blog'); ?></p>
    <?php fahad_blog_search_form(); ?>
</div>