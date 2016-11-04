<?php get_header(); ?>

<!-- container -->
<div class="container">
  <div class="row">
    <div class="col-md-3" style="padding: 20px 20px;">

      <?php get_sidebar('sidebar'); ?>
    </div>
    <div class="col-md-9 col-sm-12" style="padding: 20px 20px;">
      <!-- top page -->
      <div class="top-page">

          <span class="heading-page alignleft">
              <?php foxtemas_page_title();?>
          </span>

          <?php foxtemas_menu_filter(); ?>

          <div class="clearfix"></div>
      </div>
      <!-- end top page -->


      <?php
      // paged
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;



      // ==========================================================================
      //   Filtros
      // ==========================================================================

      /* Filtro Dias */

      if(!empty($_GET['filter'])) {
          // Dia / semana / Mês filter
          function filter_where( $where = '' ) {

              $filter_days = '';

              if($_GET['filter'] == 'day') {
                  $filter_days = '1';
              }
              if ($_GET['filter'] == 'week') {
                  $filter_days = '7';
              }
              if($_GET['filter'] == 'month') {
                  $filter_days = '30';
              }

              // posts in the last 30 days
              $where .= " AND post_date > '" . date('Y-m-d', strtotime('-'.$filter_days.' days')) . "'";
              return $where;
          }

          add_filter( 'posts_where', 'filter_where' );
      }


      // Filtro order mais votados e mais visualizados (wp-postviews | wp-postratings - plugins )
      if(!empty($_GET['order'])) {

          if($_GET['order'] == 'top') {
              //
              // mais votados
              //
              $args_main = array(
                  'post_type' => 'post',
                  'paged' => $paged,
                  'meta_key' => 'ratings_average',
                  'orderby' => 'meta_value_num',
                  'order' => 'DESC'
                  );
          } elseif($_GET['order'] == 'views') {
              //
              // mais visualizados
              //
              $args_main = array(
                  'post_type' => 'post',
                  'paged' => $paged,
                  'meta_key' => 'views',
                  'orderby' => 'meta_value_num',
                  'order' => 'DESC',
                  );
          }

      } else {
          $args_main = array(
              'post_type' => 'post',
              'paged' => $paged,
              );
      }


      $wp_query = new WP_Query( $args_main ); ?>

      <?php
      if(!empty($_GET['filter'])) {
          remove_filter( 'posts_where', 'filter_where' );
      } // Remove filtro dias ?>

      <?php if ( $wp_query->have_posts() ) : $i = 1; ?>

          <ul class="list-posts">

          <!-- the loop -->
          <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();

              // vars
              $duration = get_field('duration');
              $video_hd = get_field('video_hd');
              $patrocionado = get_field('modo_patrocionado');
              $url_patrocinado = get_field('url_patrocionada');

          ?>

              <li class="item-<?php echo $i;?>">
                  <!-- thumb post -->
                  <div class="thumb-post">

                      <?php if($duration) {?>
                      <span class="time">
                          <?php echo $duration;?>
                      </span>
                      <?php }?>

                      <span class="rating"><span class="fa fa-thumbs-up"></span> <?php foxtemas_rating_percentage(); ?></span>

                      <?php if($video_hd == '1') {?>
                      <span class="video-hd">HD</span>
                      <?php }?>

                      <?php foxtemas_badge_new(); ?>

                      <?php if($patrocionado == true) {?>
                      <a href="<?php echo $url_patrocinado;?>" title="<?php the_title();?>" rel="nofollow" target="_blank" class="absolute-thumb-post no-text">
                      <?php } else {?>
                      <a href="<?php the_permalink();?>" title="<?php the_title();?>" class="absolute-thumb-post no-text">
                      <?php }?>
                          <?php the_title();?>
                      </a>

                      <div class="cover">
                          <?php foxtemas_thumbnail('capa_video', false); ?>
                      </div>

                  </div>
                  <!-- end thumb post -->

                  <!-- name post -->
                  <div class="name-post">
                      <h2>
                          <?php if($patrocionado == true) {?>
                          <a href="<?php echo $url_patrocinado;?>" title="<?php the_title();?>" rel="nofollow" target="_blank">
                          <?php } else {?>
                          <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                          <?php } ?>
                              <?php the_title();?>
                          </a>
                      </h2>
                  </div>
                  <!-- end name post -->
              </li>

              <?php if($i%5 == 0) {?>
              <li class="clearfix"></li>
              <?php }?>

          <?php $i++; endwhile; ?>
          <!-- end of the loop -->

          </ul>

          <div class="clearfix"></div>


          <?php if(function_exists('foxtemas_pagenavi')) foxtemas_pagenavi(); ?>


          <?php wp_reset_postdata(); wp_reset_query(); ?>

      <?php else:  ?>
          <p class="aligncenter"><?php _e( 'Sorry, no posts matched your criteria.', 'onixxx' ); ?></p>
      <?php endif; ?>
    </div>





    <div class="bg-container">





        <!-- end right container -->

        <div class="clearfix"></div>
    </div>


    <div class="line-footer"></div>

    <div class="clearfix"></div>

    </div>
</div>
<!-- end container -->

<?php get_footer();?>
