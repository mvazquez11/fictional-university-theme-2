<?php

add_action('rest_api_init', 'universityRegisterSearch');

//---Register new custom endpoint
function universityRegisterSearch()
{
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'universitySearchResults'
  ));
}

//---Process the request
function universitySearchResults($data)
{
  $mainQuery = new WP_Query(array(
    //---For single post
    //'post_type' => 'professor',
    //---For multiple posts
    'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
    //---'s' as search
    //---'term' is the param in the URL that stores the value to be searched
    's' => sanitize_text_field($data['term'])
  ));

  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array()
  );

  //---Find the post type and generate content that is taken from API
  while ($mainQuery->have_posts()) {
    $mainQuery->the_post();

    //---Build the API Endpoint according to the content
    if (get_post_type() == 'post' or get_post_type() == 'page') {
      array_push($results['generalInfo'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'postType' => get_post_type(),
        'authorName' => get_the_author()
      ));
    }

    if (get_post_type() == 'professor') {
      array_push($results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    if (get_post_type() == 'program') {
      array_push($results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    if (get_post_type() == 'campus') {
      array_push($results['campuses'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    if (get_post_type() == 'event') {
      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
  }

  return $results;
}
