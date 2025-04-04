<?php
/**
 * قالب لعرض المقالات ذات الصلة
 *
 * @package Fahad_Blog
 * @version 2.0
 */
?>

<article class="related-post">
    <a href="<?php the_permalink(); ?>" class="related-post-link">
        <div class="related-post-image">
            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail('blog-thumbnail', [
                    'class' => 'lazy-load',
                    'loading' => 'lazy',
                    'alt' => get_the_title(),
                ]);
            } else {
                echo '<div class="related-post-no-image"><i class="fas fa-newspaper" aria-hidden="true"></i></div>';
            }
            ?>
        </div>
        <div class="related-post-content">
            <h4 class="related-post-title"><?php the_title(); ?></h4>
            <div class="related-post-date">
                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                <?php echo get_the_date(); ?>
            </div>
        </div>
    </a>
</article>