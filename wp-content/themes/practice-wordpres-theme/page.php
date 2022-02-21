<?php
  get_header();

  while(have_posts()) {
    the_post();
    pageBanner(array(
      'photo' => 'https://images.unsplash.com/photo-1497200977899-ea39ad7677a1?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&dl=andreas-gucklhorn-VA9lnem0FEc-unsplash.jpg&w=1920'
    ));
    ?>

    <div class="container container--narrow page-section">
      <?php
        $theParent = wp_get_post_parent_id(get_the_ID());
        if ($theParent) { ?>
          <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
              <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
            </p>
          </div>
          <?php
        };
      ?>
      

      <?php
      $testArray = get_pages(array(
        // if the current page has children, it will return an array
        'child_of' => get_the_ID()
      ));
      // if the current page has a parent page or it is a parent page
      if ($theParent or $testArray) { ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent) ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <?php
            if (has_post_parent()) {
              $findChildrenOf = $theParent;
            } else {
              $findChildrenOf = get_the_ID();
            };
            wp_list_pages(array(
              'title_li' => NULL,
              'child_of' => $findChildrenOf,
              // sort children pages by order set in Page Attributes in the admin area
              'sort_column' => 'menu_order'
            ));
          ?>
        </ul>
      </div>
      <?php } ?>

      <div class="generic-content">
        <?php the_content(); ?>
      </div>
    </div>
    
    <?php }
    get_footer();
?>