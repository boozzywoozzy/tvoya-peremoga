<?php

function blog_modern_style($atts, $current)
{
    global $post, $mk_options;
    extract($atts);

    $image_height = $grid_image_height;

    if ($layout == 'full') {
        $image_width = $grid_width - 40;
    } else {
        $image_width = (($content_width / 100) * $grid_width) - 40;
    }



    $output = $has_image = '';



    $post_type       = get_post_meta($post->ID, '_single_post_type', true);

    switch ($image_size) {
        case 'full':
            $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
            $image_output_src = $image_src_array[0];
            break;
        case 'crop':
            $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
            $image_output_src = bfi_thumb($image_src_array[0], array(
                'width' => $image_width * $image_quality,
                'height' => $image_height * $image_quality
            ));
            break;
        case 'large':
            $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true);
            $image_output_src = $image_src_array[0];
            break;
        case 'medium':
            $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium', true);
            $image_output_src = $image_src_array[0];
            break;
        default:
            $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
            $image_output_src = bfi_thumb($image_src_array[0], array(
                'width' => $image_width * $image_quality,
                'height' => $image_height * $image_quality
            ));
            break;
    }



    $lightbox_full_size = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);

    if ($post_type == '') {
        $post_type = 'image';
    }

    $output .= '<article id="' . get_the_ID() . '" class="mk-blog-modern-item modern-'.$item_id.' mk-isotop-item ' . $post_type . '-post-type">' . "\n";

    // Image post type
    if ($post_type == 'image' || $post_type == '') {

        if (has_post_thumbnail()) {

            $show_lightbox = get_post_meta($post->ID, '_disable_post_lightbox', true);
            if (($show_lightbox == 'true' || $show_lightbox == '') && $disable_lightbox == 'true') {
                $lightbox_code = ' class="mk-lightbox blog-modern-lightbox" data-fancybox-group="blog-modern" href="' . $lightbox_full_size[0] . '"';
            } else {
                $lightbox_code = ' href="' . get_permalink() . '"';
            }
            $output .= '<div class="featured-image"><a title="' . get_the_title() . '"' . $lightbox_code . '>';

            $output .= '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . mk_thumbnail_image_gen($image_output_src, $image_width, $image_height) . '" itemprop="image" />';

            $output .= '<div class="image-hover-overlay"></div>';
            $output .= '<div class="post-type-badge"><i class="mk-li-' . $post_type . '"></i></div>';
            $output .= '</a></div>';
        }
    }

    if ($post_type == 'portfolio') {

            $featured_image_id = get_post_thumbnail_id();
            $attachment_ids = get_post_meta($post->ID, '_gallery_images', true);

            if(!empty($attachment_ids)) {
                   if(!empty($featured_image_id)) {
                        $final_attachment_ids = $featured_image_id . ',' .  $attachment_ids;
                   } else {
                        $final_attachment_ids = $attachment_ids;
                   }

                   $output .= '<div class="blog-gallery-type">';
                    $output .= do_shortcode('[mk_swipe_slideshow images="' . $final_attachment_ids . '" image_width="' . $image_width . '" image_height="' . $image_height . '"]');
                    $output .= '</div>';

            } else {
                $show_lightbox = get_post_meta($post->ID, '_disable_post_lightbox', true);
                if (($show_lightbox == 'true' || $show_lightbox == '') && $disable_lightbox == 'true') {
                    $lightbox_code = ' class="mk-lightbox blog-modern-lightbox" data-fancybox-group="blog-modern" href="' . $lightbox_full_size[0] . '"';
                } else {
                    $lightbox_code = ' href="' . get_permalink() . '"';
                }
                $output .= '<div class="featured-image"><a title="' . get_the_title() . '"' . $lightbox_code . '>';

                $output .= '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . mk_thumbnail_image_gen($image_output_src, $image_width, $image_height) . '" itemprop="image" />';

                $output .= '<div class="image-hover-overlay"></div>';
                $output .= '<div class="post-type-badge"><i class="mk-li-' . $post_type . '"></i></div>';
                $output .= '</a></div>';
            }

    }

    if ($post_type == 'video') {

        $video_id   = get_post_meta($post->ID, '_single_video_id', true);
        $video_site = get_post_meta($post->ID, '_single_video_site', true);

        $output .= '<div class="featured-image">';
        if ($video_site == 'vimeo') {
            $output .= '<div class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://player.vimeo.com/video/' . $video_id . '?title=0&amp;byline=0&amp;portrait=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
        }

        if ($video_site == 'youtube') {
            $output .= '<div class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://www.youtube.com/embed/' . $video_id . '?showinfo=0&amp;theme=light&amp;color=white&amp;rel=0" frameborder="0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
        }

        if ($video_site == 'dailymotion') {
            $output .= '<div style="width:' . $image_width . 'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://www.dailymotion.com/embed/video/' . $video_id . '?logo=0" frameborder="0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
        }
        $output .= '</div>';


    }







    if ($post_type == 'audio') {

        $iframe = get_post_meta($post->ID, '_audio_iframe', true);

        if (empty($iframe)) {
            $mp3_file     = get_post_meta($post->ID, '_mp3_file', true);
            $ogg_file     = get_post_meta($post->ID, '_ogg_file', true);
            $audio_author = get_post_meta($post->ID, '_single_audio_author', true);
            $output.= do_shortcode('[mk_audio mp3_file="'.$mp3_file.'" ogg_file="'.$ogg_file.'" thumb="'.$image_src_array[ 0 ].'" audio_author="'.$audio_author.'"]');

        } else {
            $output .= '<div class="audio-iframe">' . $iframe . '</div>';
        }

    }
    if ($disable_comments_share != 'false') {
        $output .= '<div class="blog-modern-social-section">';
        if ($mk_options['single_blog_social'] == 'true'):
            $output .= '<div class="blog-share-container">';
            $output .= '<div class="blog-modern-share mk-toggle-trigger"><i class="mk-moon-share-2"></i></div>';
            $output .= '<ul class="blog-social-share mk-box-to-trigger">';
            $output .= '<li><a class="facebook-share" data-title="' . get_the_title() . '" data-url="' . get_permalink() . '" href="https://www.facebook.com/tvoya.peremoga3/"><i class="mk-jupiter-icon-simple-facebook"></i></a></li>';
            $output .= '<li><a class="vk-share" data-image="' . $lightbox_full_size[0] . '" data-title="' . get_the_title() . '" data-url="' . get_permalink() . '" href="https://vk.com/club48802465"><i class="mk-jupiter-icon-simple-vk"></i></a></li>';
            $output .= '<li><a class="instagram-share" data-image="' . $lightbox_full_size[0] . '" data-title="' . get_the_title() . '" data-url="' . get_permalink() . '" href="https://instagram.com/tvoya_peremoga/"><i class="mk-jupiter-icon-simple-instagram"></i></a></li>';
            $output .= '</ul>';
            $output .= '</div>';
        endif;
        if ($mk_options['enable_blog_single_comments'] == 'true'):
            if (get_post_meta($post->ID, '_disable_comments', true) != 'false') {
                ob_start();
                comments_number('0', '1', '%');
                $output .= '<a href="' . get_permalink() . '#comments" class="blog-modern-comment"><i class="mk-moon-bubble-9"></i><span>' . ob_get_contents() . '</span></a>';
                ob_get_clean();
            }
        endif;

        if (function_exists('mk_love_this')) {
            ob_start();
            mk_love_this();
            $output .= '<div class="mk-love-holder">' . ob_get_contents() . '</div>';
            ob_get_clean();
        }

        $output .= '</div>';
    }
    $output .= '<div class="mk-blog-meta">';

        $output .= '<div class="mk-blog-meta-wrapper">';
            $output .= '<div class="mk-blog-author">' . __('От', 'mk_framework') . ' ';
                ob_start();
                the_author_posts_link();
                $output .= ob_get_contents();
                ob_get_clean();
            $output .= '</div>'; // end: [mk-blog-author]
            $output .= '<span class="mk-categories">' . __('В', 'mk_framework') . ' ' . get_the_category_list(', ') . ' ' . __('Опубликовано', 'mk_framework') . ' </span>';
            $output .= '<time datetime="' . get_the_date() . '">';
            $output .= '<a href="' . get_month_link(get_the_time("Y"), get_the_time("m")) . '">' . get_the_date() . '</a>';
            $output .= '</time>';
        $output .= '</div>'; // end: [mk-blog-meta-wrapper]

        $output .= '<h3 class="the-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

        if ($full_content == 'true') {
            $content = str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content()));
            $output .= '<div class="the-excerpt"><p>' . $content . '</p></div>';
        } else {
            if($excerpt_length != 0) {
                ob_start();
                mk_excerpt_max_charlength($excerpt_length);
                $output .= '<div class="the-excerpt"><p>' . ob_get_clean() . '</p></div>';
            }
        }

        $output .= '<a class="blog-modern-btn" href="' . get_permalink() . '">' . __('ЧИТАТЬ ДАЛЕЕ', 'mk_framework') . '</a>';
        $output .= '<div class="clearboth"></div>';
    $output .= '</div>'; // end: [mk-blog-meta]

    $output .= '<div class="clearboth"></div></article>';


    return $output;

}
