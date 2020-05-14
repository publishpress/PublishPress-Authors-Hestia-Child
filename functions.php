<?php

/**
 * By default we fit on the same theme's layout for the authors sections.
 * But this child theme supports custom layouts..
 *
 * You can define the following constants in the "wp-config.php" file adding the custom layout slug as the value:
 *
 * HESTIA_CHILD_AUTHORS_LAYOUT_AFTER_POST
 * HESTIA_CHILD_AUTHORS_LAYOUT_POST_META
 */

add_action('wp_enqueue_scripts', 'hestiaChildEnqueueStyles');
add_filter('hestia_blog_post_meta', 'hestiaChildFilterPostMeta');
add_filter('hestia_single_post_meta', 'hestiaChildFilterSinglePostMeta');

add_action('hestia_after_single_post_article', 'hestiaChildAfterSiglePostArticle', 5);

function hestiaChildEnqueueStyles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

function hestiaChildFilterPostMeta($output)
{
    if (!defined('HESTIA_CHILD_AUTHORS_LAYOUT_POST_META')) {
        $authors     = get_multiple_authors();
        $authorNames = [];

        foreach ($authors as $author) {
            $authorNames[] = sprintf(
            /* translators: %1$s is Author name, %2$s is author link */
                '<a href="%2$s" title="%1$s" class="url"><b class="author-name fn">%1$s</b></a>',
                $author->display_name,
                $author->link
            );
        }
        $authorNames = implode(', ', $authorNames);
    } else {
        $authorNames = do_shortcode(
            sprintf(
                '[author_box layout="%s" show_title="false"]',
                HESTIA_CHILD_AUTHORS_LAYOUT_POST_META
            )
        );
    }

    $output = sprintf(
    /* translators: %1$s is Author name wrapped, %2$s is Time */
        esc_html__('By %1$s, %2$s', 'hestia'),
        $authorNames,
        sprintf(
        /* translators: %1$s is Time since post, %2$s is author Close tag */
            esc_html__('%1$s ago %2$s', 'hestia'),
            sprintf(
            /* translators: %1$s is Time since, %2$s is Link to post */
                '<a href="%2$s">%1$s',
                hestiaChildGetTimeArgs(),
                esc_url(get_permalink())
            ),
            '</a>'
        )
    );

    return $output;
}

function hestiaChildFilterSinglePostMeta($output)
{
    if (!defined('HESTIA_CHILD_AUTHORS_LAYOUT_SINGLE_POST_META')) {
        $authors     = get_multiple_authors();
        $authorNames = [];

        foreach ($authors as $author) {
            $authorNames[] = sprintf(
            /* translators: %1$s is Author name, %2$s is author link */
                '<a href="%2$s" title="%1$s" class="url"><b class="author-name fn">%1$s</b></a>',
                $author->display_name,
                $author->link
            );
        }
        $authorNames = implode(', ', $authorNames);
    } else {
        $authorNames = do_shortcode(
            sprintf(
                '[author_box layout="%s" show_title="false"]',
                HESTIA_CHILD_AUTHORS_LAYOUT_SINGLE_POST_META
            )
        );
    }

    $output = sprintf(
    /* translators: %1$s is Author name wrapped, %2$s is Time */
        esc_html__( 'Published by %1$s on %2$s', 'hestia' ),
        $authorNames,
        sprintf(
        /* translators: %1$s is Time since post, %2$s is author Close tag */
            esc_html__('%1$s ago %2$s', 'hestia'),
            sprintf(
            /* translators: %1$s is Time since, %2$s is Link to post */
                '<a href="%2$s">%1$s',
                hestiaChildGetTimeArgs(),
                esc_url(get_permalink())
            ),
            '</a>'
        )
    );

    return $output;
}

/**
 * Copied from Hestia theme
 *
 * (Hestia_Blog_Post_Layout)->get_time_tags
 *
 * @return string
 * @copyright Andrei Baicus <andrei@themeisle.com>
 */
function hestiaChildGetTimeArgs()
{
    $time = '';

    $time .= '<time class="entry-date published" datetime="' . esc_attr(get_the_date('c')) . '" content="' . esc_attr(
            get_the_date('Y-m-d')
        ) . '">';
    $time .= esc_html(human_time_diff(get_the_time('U'), time()));
    $time .= '</time>';
    if (get_the_time('U') === get_the_modified_time('U')) {
        return $time;
    }
    $time .= '<time class="updated hestia-hidden" datetime="' . esc_attr(get_the_modified_date('c')) . '">';
    $time .= esc_html(human_time_diff(get_the_modified_date('U'), time()));
    $time .= '</time>';

    return $time;
}

function hestiaChildAfterSiglePostArticle()
{
    remove_all_actions('hestia_after_single_post_article');

    global $post;
    $categories = get_the_category($post->ID);
    ?>

    <div class="section section-blog-info">
        <div class="row">
            <div class="col-md-6">
                <div class="entry-categories"><?php esc_html_e('Categories:', 'hestia'); ?>
                    <?php
                    foreach ($categories as $category) {
                        echo '<span class="label label-primary"><a href="' . esc_url(
                                get_category_link($category->term_id)
                            ) . '">' . esc_html($category->name) . '</a></span>';
                    }
                    ?>
                </div>
                <?php the_tags(
                    '<div class="entry-tags">' . esc_html__('Tags: ', 'hestia') . '<span class="entry-tag">',
                    '</span><span class="entry-tag">',
                    '</span></div>'
                ); ?>
            </div>
            <?php do_action('hestia_blog_social_icons'); ?>
        </div>
        <hr>
        <?php
        $author_description = get_the_author_meta('description');
        if (empty($author_description)) {
            return;
        }

        if (!defined('HESTIA_CHILD_AUTHORS_LAYOUT_AFTER_POST')) :
            $authors = get_multiple_authors();

            foreach ($authors as $author) :
                ?>
                <div class="card card-profile card-plain">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="card-avatar">
                                <a href="<?php echo esc_url($author->link); ?>"
                                   title="<?php echo esc_attr($author->display_name); ?>">
                                    <?php echo $author->get_avatar(100); ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h4 class="card-title"><?php echo $author->display_name; ?></h4>
                            <p class="description"><?php echo $author->description; ?></p>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        else:
            echo do_action('pp_multiple_authors_show_author_box', false, HESTIA_CHILD_AUTHORS_LAYOUT_AFTER_POST);
        endif;

        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    </div>
    <?php
}
