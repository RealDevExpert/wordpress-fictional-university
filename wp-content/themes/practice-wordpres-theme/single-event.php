<?php
  get_header();
  pageBanner(
    array(
      'title' => get_the_title()
    )
  );

  while(have_posts()) {
    the_post();?>

    <div class="container container--narrow page-section">
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo site_url('/events'); ?>">
            <i class="fa fa-home" aria-hidden="true"></i> Events Home
          </a>
            <span class="metabox__main">
              <?php  the_title(); ?>
            </span>
        </p>
      </div>

      <section class="generic-content">
        <?php the_content() ?>
      </section>
      <section>
        <?php
          $relatedPrograms = get_field('related_programs');
          
          if ($relatedPrograms) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Related Program(s)</h2>';
            echo '<ul class="link-list min-list">';
            foreach ($relatedPrograms as $program) { ?>
              <!-- print_r($value->post_title); -->
              <li>
                <a href="<?php echo get_the_permalink($program); ?>">
                  <?php echo get_the_title($program); ?>
                </a>
              </li>
            <?php
            }
            echo '</ul>';
          }
          // print_r($relatedPrograms[0]->post_title);
        ?>
      </section>
    </div>
    <?php }

  get_footer();
?>
