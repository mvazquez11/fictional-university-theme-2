<?php

get_header();

while (have_posts()) {
  the_post();
  pageBanner(array(
    'title' => 'Hello there this is the title',
    'photo' => 'https://image.winudf.com/v2/image/Y29tLm5hdHVyZXdhbGxwYXBlci5rZHhpbWFuX3NjcmVlbl8wXzE1MjQzOTg0OTFfMDA2/screen-0.jpg?fakeurl=1&type=.jpg'
  ));
?>


  <div class="container container--narrow page-section">

    <?php
    $theParent =  wp_get_post_parent_id(get_the_ID());
    if ($theParent) { ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
    <?php } ?>


    <!-- FIX LATER
    <div class="page-links">
      <h2 class="page-links__title"><a href="#">About Us</a></h2>
      <ul class="min-list">
        <li class="current_page_item"><a href="#">Our History</a></li>
        <li><a href="#">Our Goals</a></li>
      </ul>
    </div>
    -->

    <div class="generic-content">
      <?php the_content(); ?>
    </div>

  </div>

<?php
}

get_footer();
?>
