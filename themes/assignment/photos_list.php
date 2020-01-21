<?php
/* Template Name: Photos List */
get_header();
$get_id = get_the_ID();
?>
<div class="container">
  <div class="grid grid_photos row">
    <?php
    //  global $the_query_p;
    $paged2 = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args_p = array('post_type' => 'photos', 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => '5', 'paged' => $paged2);
    $the_query_p = new WP_Query($args_p);
    // echo $the_query_p->request;
    ?>
    <?php if ($the_query_p->have_posts()) {
      $count_home_banner = $the_query_p->post_count;
      $feat_image = "";
      while ($the_query_p->have_posts()) : $the_query_p->the_post();
        if (has_post_thumbnail()) {
          $feat_image = wp_get_attachment_image_src(get_post_thumbnail_id($the_query_p->post->ID), 'photos-list');
          $p_image = $feat_image[0];
          ?>
          <div class="col-4 <?php echo $the_query_p->post->ID; ?> <?php echo $the_query_p->post->post_title; ?>">
            <img src="<?php echo $p_image; ?>" alt="" />
          </div>
    <?php
        }
      endwhile;
    }
    ?>
  </div>

  <?php
  // don't display the button if there are not enough posts
  if ($the_query_p->max_num_pages > 1) {
    ?>
    <div class="row justify-content-center">
      <div id="js-loadMore-projects" class="loadmore-button text-center col-4">
        <a class="photos_loadmore" rel="nofollow"><span class="load-more-lower">Show more</span></a>
      </div>
    </div>
  <?php
  } ?>
</div>


<?php
get_footer();
