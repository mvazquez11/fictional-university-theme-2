<?php

function university_files()
{
  //---Can be duplicated many times for each file needed

  //---with 'true' loads at the bottom of the webpage

  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  //---Checks where is it hosted for live reload
  if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) {
    wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
  } else {
    //---Traditional copy files
    wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.bc49dbb23afb98cfc0f7.js'), NULL, '1.0', true);
    wp_enqueue_script('our-main-styles', get_theme_file_uri('/bundled-assets/styles.bc49dbb23afb98cfc0f7.css'), NULL, '1.0', true);
  }
}


add_action('wp_enqueue_scripts', 'university_files');

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
}

add_action('after_setup_theme', 'university_features');

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
