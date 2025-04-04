<?php
/**
 * قالب عرض المقالات في الصفحة الرئيسية والأرشيف
 *
 * @package Fahad_Blog
 * @version 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?>>
    <a href="<?php the_permalink(); ?>" class="blog-card-link" aria-hidden="true" tabindex="-1">
        <div class="blog-card-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('blog-thumbnail', [
                    'class' => 'lazy-load',
                    'loading' => 'lazy',
                    'alt' => get_the_title(),
                ]); ?>
            <?php else : ?>
                <div class="blog-card-no-image">
                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                </div>
            <?php endif; ?>
        </div>
    </a>
    
    <div class="blog-card-content">
        <header class="blog-card-header">
            <?php 
            $categories = get_the_category();
            if ($categories) :
                echo '<div class="blog-card-categories">';
                foreach ($categories as $category) {
                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="blog-category">' . esc_html($category->name) . '</a>';
                }
                echo '</div>';
            endif;
            ?>
            
            <h2 class="blog-card-title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>
        </header>
        
        <div class="blog-card-meta">
            <span class="blog-card-date">
                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                <?php echo get_the_date(); ?>
            </span>
            
            <span class="blog-card-author">
                <i class="fas fa-user" aria-hidden="true"></i>
                <?php the_author(); ?>
            </span>
            
            <?php if (comments_open()) : ?>
                <span class="blog-card-comments">
                    <i class="fas fa-comment" aria-hidden="true"></i>
                    <?php comments_number('0', '1', '%'); ?>
                </span>
            <?php endif; ?>
            
            <span class="blog-card-reading-time">
                <i class="fas fa-clock" aria-hidden="true"></i>
                <?php
                $reading_time = fahad_blog_calculate_reading_time();
                printf(
                    _n('%d دقيقة', '%d دقائق', $reading_time, 'fahad-blog'),
                    $reading_time
                );
                ?>
            </span>
        </div>
        
        <div class="blog-card-excerpt">
            <?php the_excerpt(); ?>
        </div>
    </div>
</article>