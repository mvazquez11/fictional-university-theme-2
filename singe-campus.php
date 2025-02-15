<?php
get_header();

while (have_posts()) {
  the_post();
  pageBanner();
?>
  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>">
          <i class="fa fa-home" aria-hidden="true"></i>All Campuses</a>
        <span class="metabox__main"><?php the_title(); ?></span>
      </p>
    </div>
    <div class="generic-content"><?php the_content(); ?></div>

    <?php
    $mapLocation = get_field('map_location');
    ?>

    <div class="acf-map">
      <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
        <h3><a href="<?php the_permalink(); ?>"></a><?php the_title(); ?></h3>
        <?php echo $mapLocation['address']; ?>
      </div>
    </div>
    <?php
    $today = date('Ymd');
    $relatedProfessors = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'professor',
      'order_by' => 'title',
      'order' => 'ASC',
      'meta_query' => array(
        array(
          'key' => 'related_program',
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"'
        )
      ),
    ));

    if ($relatedProfessors->have_posts()) {
      echo '<hr class="section-break"/>';
      echo '<h2 class="headline headline--medium"> ' . get_the_title() . ' Professors</h2>';

      echo '<ul class="professor-cards">';
      while ($relatedProfessors->have_posts()) {
        $relatedProfessors->the_post(); ?>
        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink(); ?>">
            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" />
            <span class="professor-card__name"><?php the_title(); ?></span>
          </a>
        </li>
      <?php }

      echo '</ul>';
    }

    wp_reset_postdata();

    //---Upcoming events
    $today = date('Ymd');
    $homePageEvents = new WP_Query(array(
      'posts_per_page' => 2,
      'post_type' => 'event',
      'meta_key' => 'event_date',
      'order_by' => 'meta_value_num',
      'order' => 'ASC',
      'meta_query' => array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        ),
        array(
          'key' => 'related_program',
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"'
        )
      ),
    ));

    if ($homePageEvents->have_posts()) {
      echo '<hr class="section-break"/>';
      echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

      while ($homePageEvents->have_posts()) {
        $homePageEvents->the_post();
        get_template_part('template-parts/content-event');
      }
      ?>
  </div>
<?php }
  }

  get_footer();
?>
