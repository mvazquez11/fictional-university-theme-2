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
        //---0 - Default, size:
        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
      ));
    }

    if (get_post_type() == 'program') {
      array_push($results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'id' => get_the_id(),
      ));
    }

    if (get_post_type() == 'campus') {
      array_push($results['campuses'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));
      $description = null;
      if (has_excerpt()) {
        $description = get_the_excerpt();
      } else {
        $description = wp_trim_words(get_the_content(), 18);
      };

      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'month' => $eventDate->format('M'),
        'day' => $eventDate->format('d'),
        'description' => $description

      ));
    }
  }

  //---Only executed if there are programs
  if ($results['programs']) {
    $programsMetaQuery = array('relation' => 'OR');

    foreach ($results['programs'] as $item) {
      array_push(
        $programsMetaQuery,
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"' . $item['id'] . '"'
        )
      );
    }

    //---Set relation professor-program to display it in search
    $programRelationshipQuery = new WP_Query(array(
      'post_query' => 'professor',
      'meta_query' => $programsMetaQuery
    ));

    while ($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post();

      if (get_post_type() == 'professor') {
        //---0 - Default, size:
        array_push($results['professors'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),

          'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
        ));
      }
    }

    //---Remove duplicates with array_unique
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));

    return $results;
  }
}
