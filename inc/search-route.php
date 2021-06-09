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
function universitySearchResults()
{
  $professors = new WP_Query(array(
    'post_type' => 'professor',
  ));

  $professorResults = array();

  //---Find the professors and generate content that is taken from API
  while ($professors->have_posts()) {
    $professors->the_post();
    array_push($professorResults, array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
    ));
  }

  return $professorResults;
}
