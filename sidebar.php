<?php
/**
 * قالب الشريط الجانبي
 *
 * @package Fahad_Blog
 * @version 2.0
 */

// إذا لم يكن هناك ودجات نشطة في الشريط الجانبي، توقف وعدم عرضه
if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <div class="container">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside>