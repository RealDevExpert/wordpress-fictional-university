<?php

  require get_theme_file_path('./inc/search-route.php');

  function university_custom_rest() {
    register_rest_field('post', 'authorName', array(
      'get_callback' => function() { return get_the_author();}
    ));
  }

  add_action('rest_api_init', 'university_custom_rest');

  function pageBanner($args = NULL) {
    // php logic lives here
    if (!($args['title'])) {
      $args['title'] = get_the_title();
    }
    
    if (!($args['subtitle'])) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (!($args['photo'])) {
      if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      } else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
    ?>
    <div class="page-banner">
      <!-- <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>)"></div> -->
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);">
      </div>
      <div class="page-banner__content container container--narrow">
        
        <h1 class="page-banner__title"> <?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
          <!-- <p><?php print_r($pageBannerImage); ?></p> -->
        </div>
      </div>
    </div>
  <?php
  }

  // load .css and .js files
  function university_files() {
    wp_enqueue_script('google-map', '//maps.googleapis.com/maps/api/js?key=AIzaSyANXUC_wkaCk-531noo8uVkCo8i7FJrti4', NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

    wp_localize_script('main-university-js', 'universityData', array(
      'root_url' => get_site_url()
    ));
  }
  add_action('wp_enqueue_scripts', 'university_files');

  function university_features() {
    // register_nav_menu('headerMenuLocation', 'Header Menu location');
    // register_nav_menu('footerLocationOne', 'Footer Location One');
    // register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 400, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
  }

  add_action('after_setup_theme', 'university_features');

  function university_adjust_queries ($query) {
    if (!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
      $query->set('posts_per_page', -1);
    }
    if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', -1);
    }
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
      $today = date ('Ymd');
      $query->set('meta_key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
        // filter out events from the past
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        )
      ));
    }
  }
  add_action('pre_get_posts', 'university_adjust_queries');

  function universityMapKey($api) {
    // it is not working. It needs billing details on google console
    $api['key'] = 'AIzaSyANXUC_wkaCk-531noo8uVkCo8i7FJrti4';
    return $api;
  }

  add_filter('acf/fields/google_map/api', 'universityMapKey');

  // Redirect subscriber uers to the homepage
  add_action('admin_init', 'redirectSubsToFrontend');

  function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 
    'subscriber') {
      wp_redirect(site_url('/'));
      exit;
    }
  }

  // Hide top admin bar for subscribers
  add_action('wp_loaded', 'noSubsAdminBar');

  function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 
    'subscriber') {
      show_admin_bar(false);
    }
  }
?>