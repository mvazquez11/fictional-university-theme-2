<?php

function university_files()
{
  //---Can be duplicated many times for each file needed

  //---with 'true' loads at the bottom of the webpage

  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  //---Load js google maps script
  //---'true' param helps to load the js at the bottom, not in head
  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyBqnPePJ30hHNXnkCWPhwMW4L2sbhKDVo0', NULL, '1.0', true);

  //---Checks where is it hosted for live reload
  if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) {
    wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
  } else {
    //---Traditional copy files
    wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.b7dc06852279f82680bf.js'), NULL, '1.0', true);
    wp_enqueue_script('our-main-styles', get_theme_file_uri('/bundled-assets/styles.b7dc06852279f82680bf.css'), NULL, '1.0', true);
  }
}


add_action('wp_enqueue_scripts', 'university_files');


//---pageBanner() - Function to dinamically process the content of the banner for each page and section
function pageBanner($args = null)
{
  //---Php logic will live here
  //---If there is no title defined it will take the default title value
  if (!$args['title']) {
    $args['title'] = get_the_title();
  }

  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!$args['photo']) {
    if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
      //---Pulls specific size of the image
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }
?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>
<?php }

//---Loads stuff and external things like css or js
function university_features()
{
  //---Add menu in navbar
  // register_nav_menu('headerMenuLocation', 'Header Menu Location');
  //---Add menu footer location 1 in Menu Option
  // register_nav_menu('footerLocationOne', 'Footer Location One');
  //---Add menu footer location 2 in Menu Option
  // register_nav_menu('footerLocationTwo', 'Footer Location Two');
  //---Add title to pages
  add_theme_support('title-tag');
  //---Enable thumbnails for posts
  add_theme_support('post-thumbnails');
  //---Params
  // 1 - reference name
  // 2 - width
  // 3- height
  // 4 - crop image? (true/false)
  add_image_size('professorLandscape', 400, 260, true);
  add_image_size('professorPortrait', 480, 650, true);

  add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

//---For events page
function university_adjust_queries($query)
{
  //---For programs
  if (!is_admin() and is_post_type_archive('program') and is_main_query()) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }

  //--Only applies for intended URL
  if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('order_by', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      ),
    ));
  }
}


add_action('pre_get_posts', 'university_adjust_queries');

//---Google Analytics
//---This function adds the key configured in the Google API
//---and then it is added as a field with value for ACF
function universityMapKey($api)
{
  $api['key'] = 'AIzaSyBqnPePJ30hHNXnkCWPhwMW4L2sbhKDVo0';
  return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');


/* Shortcode test - Totally aside from the course */
function my_info_func($atts)
{
  $a = shortcode_atts(array('display' => 'all',), $atts);

  if ('all' == $a['display']) {
    return "John Doe; john@doe.com; (123) 456-7890";
  }

  if ('name' == $a['display']) {
    return "John Doe";
  }

  if ('email' == $a['display']) {
    return "john@doe.com";
  }

  if ('phone' == $a['display']) {
    return "(123) 456-7890";
  }

  return "Invalid display";
}

add_shortcode('my-info', 'my_info_func');
