<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
  function chld_thm_cfg_locale_css( $uri ){
    if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
      $uri = get_template_directory_uri() . '/rtl.css';
    return $uri;
  }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

// END ENQUEUE PARENT ACTION

function credit_code() { 
  $site_title = get_bloginfo( 'name' );
  $message = "<div id='footer-credits'>Copyright © " . date("Y") . " Prime L P Real Estate" . ' <br>
  Prime L P Real Estate L.L.C is a company registered in Dubai, United Arab Emirates (License No. 1231257), Palm Jumeirah, Dubai, PO Box 00000.<br>We are regulated by the Real Estate Regulatory Agency under office number 0000.</div>';
  return $message;
} 
add_shortcode('credit', 'credit_code');

function add_whatsapp_icon_to_head() {
  ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <a href="https://wa.me/971565203469" target="_blank">
    <div class="whatsapp-icon-container">
      <i class="fab fa-whatsapp whatsapp-icon"></i>
    </div>
  </a>
  <style>
        .pswp {
            position: relative;
        }

        .pswp__img {
            object-fit: contain;
        }

        .pswp__thumbs {
            width: 100%;
            position: absolute;
            bottom: 0px; /* Adds some space at the bottom */
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px; /* Adds space between thumbnails */
            padding: 5px; /* Adds some padding around the thumbnails container */
            background: rgba(0, 0, 0, 0.5); /* Adds a semi-transparent black background */
            border-radius: 5px; /* Adds rounded corners */
        }

        .pswp__thumbs img {
            width: 50px;
            height: 50px;
            cursor: pointer;
            border-radius: 3px; /* Adds rounded corners to each thumbnail */
            transition: all 0.3s ease; /* Adds a smooth transition effect */
        }

        .pswp__thumbs img:hover {
            transform: scale(1.1); /* Scales the image on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Adds a shadow on hover */
        }

    </style>
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true" style="position: relative">
        <!-- Background of PhotoSwipe.
                 It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>
        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">
            <!-- Container that holds slides.
                      PhotoSwipe keeps only 3 of them in the DOM to save memory.
                      Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <!--  Controls are self-explanatory. Order can be changed. -->
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                    <!-- element will get class pswp__preloader--active when preloader is running -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
            <div class="pswp__thumbs">
            </div>
        </div>
    </div>
  <?php
}

add_action('wp_head', 'add_whatsapp_icon_to_head');

function display_parent_communities($atts) {
    // Default Shortcode attributes
  $atts = shortcode_atts(array(
    'featured' => 'no',
    'country' => '',
    'country_excl' => '',
        'lifestyle' => ''  // Added new attribute for lifestyle
      ), $atts);

    // Convert country, country_excl, and lifestyle attributes to arrays
  $countries = array_filter(array_map('trim', explode(',', $atts['country'])));
  $countries_excl = array_filter(array_map('trim', explode(',', $atts['country_excl'])));
    $lifestyles = array_filter(array_map('trim', explode(',', $atts['lifestyle']))); // Convert lifestyle attribute to array

    // Initialize output and counter for matched communities
    $output = '<div class="community-grid">';
    $matched_communities = 0;

    // Query WordPress terms
    $terms = get_terms(array(
      'taxonomy' => 'location',
      'hide_empty' => false,
      'parent' => 0,
      'meta_key' => 'order',
      'orderby' => 'meta_value_num',
      'order' => 'ASC'
    ));

    if (!empty($terms)) {
      foreach ($terms as $term) {
        $is_featured = get_field('featured', 'location_' . $term->term_id);
        $location = get_field('building_location', 'location_' . $term->term_id);
        $country_name = isset($location['country_short']) ? $location['country_short'] : '';
            $term_lifestyle = get_field('lifestyle', 'location_' . $term->term_id);  // Fetch lifestyle from ACF

            // Match country and lifestyle
            $country_match = (empty($countries) || in_array($country_name, $countries)) && !in_array($country_name, $countries_excl);
            $lifestyle_match = empty($lifestyles) || (!empty($term_lifestyle) && array_intersect($term_lifestyle, $lifestyles));  // Check if lifestyles match

            if (($atts['featured'] === 'yes' && $is_featured || $atts['featured'] === 'no') && $country_match && $lifestyle_match) {  // Added lifestyle match condition

                // Fetch and prepare term image
              $term_image = get_field('image', 'location_' . $term->term_id);
              $image_style = '';
              if ($term_image) {
                if (isset($term_image['ID'])) {
                  $image_data = wp_get_attachment_image_src($term_image['ID'], 'medium_large');
                  if ($image_data) {
                    $image_url = $image_data[0];
                  }
                } else {
                  $image_url = $term_image['url'];
                }
              } else {
                $image_url = '/wp-content/uploads/2023/05/temp8-1.jpg';
              }

              $image_style = 'background-image: url(' . $image_url . ');';

                // Append the matched term to output
              $output .= '<div class="community-item" style="' . $image_style . '">';
              $output .= '<h2>' . $term->name . '</h2>';
              $output .= '<a href="' . get_term_link($term) . '">View More</a>';
              $output .= '</div>';

              $matched_communities++;
            }
          }
        }

    $output .= '</div>';  // Close community-grid

    return ($matched_communities > 0) ? $output : 'No locations found.';
  }

  add_shortcode('communities', 'display_parent_communities');








  function display_inner_communities() {
    $queried_object = get_queried_object();
    $obslug = $queried_object->slug;
    if ($queried_object && isset($queried_object->taxonomy)) {
      $taxonomy = $queried_object->taxonomy;
    } else {
      return 'No locations found.';
    }
  // Check if the current page is a child term
    

    // Query the terms
    $terms = get_terms(array(
      'taxonomy' => $taxonomy,
      'hide_empty' => false,
        'parent' => $queried_object->term_id, // Include only children terms
      ));




    // Check if there are terms
    if (!empty($terms)) {
      $output = '<div class="community-grid">';

        // Get the count of terms
      $term_count = count($terms);

        // Loop through the terms
      foreach ($terms as $term) {
      $term_image = get_field('image', $taxonomy . '_' . $term->term_id); // Replace 'image' with your ACF field name
      $image_style = '';

      if ($term_image) {
        // Check if the 'ID' key exists in the $term_image array
        if (isset($term_image['ID'])) {
          $image_data = wp_get_attachment_image_src($term_image['ID'], 'medium_large');
          if ($image_data) {
            $image_url = $image_data[0];
          }
        } else {
          $image_url = $term_image['url'];
        }
      } else {
        $image_url = '/wp-content/uploads/2023/05/temp8-1.jpg';
      }

      $image_style = 'background-image: url(' . $image_url . ');';

      $output .= '<div class="community-item inner" style="' . $image_style . '">';
      $output .= '<h2>' . $term->name . '</h2>';
      $output .= '<a href="' . get_term_link($term) . '">View More</a>';
      $output .= '</div>';
    }


    $output .= '</div>';

        // Check if the term count is 3 or less
    if ($term_count <= 3) {
      $output .= '<style>
      .community-item.inner { 
        width: calc(100% / ' . $term_count . '); 
        padding: 10% 4%;
      }
      @media (max-width: 981px) {
        .community-item.inner {
          width: 100% !image; 
          padding: 18% 8% !important;
          </style>';
        }

        return $output;
      }
    }
    add_shortcode('communities-inner', 'display_inner_communities');

    function inner_header() {
      $taxonomy = get_queried_object();
      $taxonomy_name = $taxonomy->name;

      $term_id = $taxonomy->term_id;
      $image_id = get_term_meta($term_id, 'image', true);

      $background_image = '';

      if (!empty($image_id)) {
        $image_url = wp_get_attachment_image_src($image_id, 'full');
        if ($image_url) {
          $background_image = 'background-image: url(' . $image_url[0] . ');';
        }
      }

      $location = get_field('building_location', 'location_' . $taxonomy->term_id);
      $country_name = isset($location['country_short']) ? $location['country_short'] : '';

      $output = '<div class="inner-header" style="' . $background_image . '">';
      $output .= '<h1>' . $taxonomy_name . '</h1>';
      $output .= '<span style="display: none;">'.$country_name.'</span>';
      $output .= '</div>';



      return $output;
    }

    add_shortcode('community-header', 'inner_header');


    function child_communities() {
      $queried_object = get_queried_object();
      $obslug = $queried_object->slug;
      if ($queried_object->parent != 0) {
        // Execute a different shortcode for child terms

      }
      $shortcode = do_shortcode('[properties location='.$obslug.']');
      return $shortcode;
    }
    add_shortcode('child-communities', 'child_communities');


// Enqueue the required scripts and styles for Slick Slider
    function enqueue_testimonial_slider_scripts() {
      wp_enqueue_style( 'slick-slider', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
      wp_enqueue_style( 'slick-slider-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css' );
      wp_enqueue_script( 'slick-slider', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_testimonial_slider_scripts' );

// Create the testimonial slider shortcode
    function testimonial_slider_shortcode() {
      ob_start();

    // Query the 'testimonial' custom post type
      $args = array(
        'post_type'      => 'testimonial',
        'posts_per_page' => -1,
      );
      $query = new WP_Query( $args );

    // Check if testimonials exist
      if ( $query->have_posts() ) {
        echo '<div class="testimonial-slider">';
        while ( $query->have_posts() ) {
          $query->the_post();
          echo '<div class="testimonial-slide">';
          echo '<div class="testimonial-content">' . get_the_content() . '</div>';
          echo '<h3>' . get_the_title() . '</h3>';

          echo '</div>';
        }
        echo '</div>';
        ?>
        <script type="text/javascript">
          jQuery(document).ready(function($) {
            $('.testimonial-slider').slick();
          });
        </script>
        <?php
      }

      wp_reset_postdata();

      $output = ob_get_clean();
      return $output;
    }
    add_shortcode( 'testimonial-slider', 'testimonial_slider_shortcode' );

    function property_page_slider_shortcode($atts) {
      $post_id = get_the_ID();

    // Retrieve the ACF Photo Gallery field
      $gallery = acf_photo_gallery('gallery', $post_id);
      $output = '';

    // Check if the gallery exists and has images
      if ($gallery && count($gallery) > 0) {
        $output .= '<div class="property-page-slider">';
        $output .= '<div class="page-title"><h1>'.get_the_title().'</h1></div>';
        $output .= '<div class="slider">'; // Wrap the slider with a container


        foreach ($gallery as $image) {
            $output .= '<div style="background-image: url(' . $image['full_image_url'] . ')"></div>'; // Use background-image instead of <img>
          }

        $output .= '</div>'; // Close the slider container
        $output .= '</div>';
      }

    // Enqueue the Slick Slider scripts and initialize the slider
      $output .= '<script>
      jQuery(document).ready(function($) {
        $(".slider").slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
                // Add any additional Slick Slider options here
          });
          });
          </script>';

          return $output;
        }
        add_shortcode('property-page-slider', 'property_page_slider_shortcode');

        function meta_table_shortcode($atts) {
          $output = '<table class="meta-table" style="border-collapse: collapse;">';

    // Retrieve the current post ID
          $post_id = get_the_ID();

    // Retrieve the ACF fields
          $bedrooms = get_field('bedrooms', $post_id);
          $bathrooms = get_field('bathrooms', $post_id);
          $price = get_field('price', $post_id);
          $area = get_field('area', $post_id);
          $price_in__dollars=get_field('price_in__dollars', $post_id);
          $down_payment=get_field('down_payment', $post_id);
          $during_construction=get_field('during_construction', $post_id);
          $hand_over=get_field('hand_over', $post_id);
          $dubai_marina=get_field('dubai_marina', $post_id);
          $palm_jumeriah=get_field('palm_jumeriah', $post_id);
          $dubai_mall=get_field('dubai_mall', $post_id);
          $burj_khalifa=get_field('burj_khalifa', $post_id);
          $dubai_airport=get_field('dubai_airport', $post_id);

    //echo "Here is your down payment: " . $down_payment;

    // Retrieve the taxonomies
          $property_type = wp_get_post_terms($post_id, 'property-type', array('fields' => 'names'));
          $location = wp_get_post_terms($post_id, 'location', array('fields' => 'names'));
          $availability_taxonomy = wp_get_post_terms($post_id, 'availability', array('fields' => 'names'));

          $non_empty_fields = 0;

    // Calculate the number of fields that have values
          if (!empty($bedrooms)) {
            $non_empty_fields++;
          }

          if (!empty($bathrooms)) {
            $non_empty_fields++;
          }

          if (!empty($price)) {
            $non_empty_fields++;
          }

          if (!empty($price_in__dollars)) {
            $non_empty_fields++;
          }

          if (!empty($area)) {
            $non_empty_fields++;
          }

          if (!empty($property_type)) {
            $non_empty_fields++;
          }

          if (!empty($location)) {
            $non_empty_fields++;
          }

          $output .= '<style>
          .meta-table td {
            display: none;
            width: calc(100% / ' . $non_empty_fields . ');
          }
          </style>';

          $output .= '<tr>';

          if (!empty($bedrooms)) {
            $output .= '<td style="display: table-cell;"><h3>Bedrooms</h3>' . $bedrooms['label'] . '</td>';
          }

          if (!empty($bathrooms)) {
            $output .= '<td style="display: table-cell;"><h3>Bathrooms</h3>' . $bathrooms['label'] . '</td>';
          }

          if (!empty($price)) {
            $output .= '<td style="display: table-cell;"><h3>Price</h3>AED ' . number_format($price) . '</td>';
          }

          if (!empty($price_in__dollars)) {
            $output .= '<td style="display: table-cell;"><h3>Price</h3>$ ' . number_format($price_in__dollars) . '</td>';
          }

          if (!empty($area)) {
            $output .= '<td style="display: table-cell;"><h3>Property Size</h3>' . $area . ' sq ft</td>';
          }

          if (!empty($property_type)) {
            $output .= '<td style="display: table-cell;"><h3>Property Type</h3>' . implode(', ', $property_type) . '</td>';
          }

          if (!empty($location)) {
            $output .= '<td style="display: table-cell;"><h3>Property Location</h3>' . implode(', ', $location) . '</td>';
          }

          $output .= '</tr>';

          $output .= '</table>';

          return $output;
        }
        add_shortcode('meta-table', 'meta_table_shortcode');


        function generate_property_code($post) {
          $bathrooms = get_field('bathrooms', $post);
          $bedrooms = get_field('bedrooms', $post);
          $price = get_field('price', $post);
          $location = wp_get_post_terms($post->ID, 'location')[0]->name;
          $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
          $property_type = implode(', ', wp_get_post_terms($post->ID, 'property-type', array('fields' => 'names')));
          $property_size = get_field("area");
          $numeric_property_size = floatval($property_size);

          $link = get_the_permalink($post->ID);

          $output = '<div class="property-item" style="background-image: url(' . esc_url($thumbnail_url) . ');">';
          $output .= '<div class="property-inner">';
          $output .= '<h2>' . get_the_title($post->ID) . '</h2>';
          $output .= '<h2>AED ' . number_format($price) . '</h2>';
          $output .= '<table>';
          $output .= '<tr>';
          $output .= '<td><img src="/wp-content/uploads/icons/bedroom.png"><p>' . esc_html($bedrooms['label']) . '</p></td>';
          $output .= '<td><img src="/wp-content/uploads/icons/bathroom.png"><p>' . esc_html($bathrooms['label']) . '</p></td>';
          $output .= '</tr>';
          $output .= '<tr>';
          $output .= '<td><img src="/wp-content/uploads/icons/location.png"><p>' . esc_html($location) . '</p></td>';
          $output .= '<td><img src="/wp-content/uploads/icons/plot.png"><p>' . number_format($numeric_property_size) . ' sq ft</p></td>';
          $output .= '</tr>';
          $output .= '</table>';
          $output .= '<a href="' . esc_url($link) . '">View More</a>';
          $output .= '</div></div>';
          return $output;
        }

        function display_properties($atts) {
          $atts = shortcode_atts(
            array(
              'type' => '',
              'location' => '',
              'property-type' => '',
              'bedrooms' => '',
              'bathrooms' => '',
              'price' => '',
              'area' => '',
            ),
            $atts
          );

          $args = array(
            'post_type' => 'property',
            'tax_query' => array(),
            'meta_query' => array(),
            'meta_key' => 'price',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
          );

          if (!empty($atts['type'])) {
            $args['tax_query'][] = array(
              'taxonomy' => 'availability',
              'field' => 'slug',
              'terms' => $atts['type'],
            );
          }

          if (!empty($atts['location'])) {
            $args['tax_query'][] = array(
              'taxonomy' => 'location',
              'field' => 'slug',
              'terms' => $atts['location'],
            );
          }

          if (!empty($atts['property-type'])) {
            $args['tax_query'][] = array(
              'taxonomy' => 'property-type',
              'field' => 'slug',
              'terms' => $atts['property-type'],
            );
          }

          if (!empty($atts['bedrooms'])) {
            $args['meta_query'][] = array(
              'key' => 'bedrooms',
              'value' => $atts['bedrooms'],
              'compare' => '=',
            );
          }

          if (!empty($atts['bathrooms'])) {
            $args['meta_query'][] = array(
              'key' => 'bathrooms',
              'value' => $atts['bathrooms'],
              'compare' => '=',
            );
          }

          if (!empty($atts['price'])) {
            $args['meta_query'][] = array(
              'key' => 'price',
              'value' => $atts['price'],
              'compare' => '<=',
              'type' => 'NUMERIC',
            );
          }

          if (!empty($_POST['area'])) {
        // Parse the area range from the selected value
            $area_range = explode('-', $_POST['area']);
            $min_area = isset($area_range[0]) ? intval($area_range[0]) : 0;
            $max_area = isset($area_range[1]) ? intval($area_range[1]) : 999999;

        // Add to meta_query
            $args['meta_query'][] = array(
              'key' => 'area',
              'value' => array($min_area, $max_area),
              'type' => 'NUMERIC',
              'compare' => 'BETWEEN',
            );
          }

          if (isset($_GET['sort_price'])) {
            $sort_order = ($_GET['sort_price'] === 'asc') ? 'ASC' : 'DESC';
            $args['order'] = $sort_order;
          }

          $query = new WP_Query($args);

          $output = '';

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();
              $output .= generate_property_code($query->post);
            }
            wp_reset_postdata();
          } else {
            $output .= '<div class="noresults">Unfortunately, we can\'t find any properties that match your requirements. Return to <a href="/buy"> here </a> to try again.</div>';
          }

          return $output;
        }
        add_shortcode('properties', 'display_properties');

        function property_filter_form($atts) {
          $shortcode_atts = array(
            'type' => '',
            'property-type' => '',
          );

          $atts = shortcode_atts(
            $shortcode_atts,
            $atts
          );



          ob_start();
          ?>

          <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST" id="filter">
            <div class="select-container">
              <select name="property-type">
                <option value="">PROPERTY TYPE</option>
                <option value="apartment">Apartment</option>
                <option value="penthouse">Penthouse</option>
                <option value="townhouse">Townhouse</option>
                <option value="villa">Villa</option>
                <option value="mansion">Mansion</option>
                <option value="plot">Plot</option>
              </select>
            </div>
            <div class="select-container">
              <select name="bedrooms">
                <option value="">BEDROOMS</option>
                <option value="studio">Studio</option>
                <option value="1bed">1 Bedroom</option>
                <option value="2bed">2 Bedrooms</option>
                <option value="3bed">3 Bedrooms</option>
                <option value="4bed">4 Bedrooms</option>
                <option value="5bed">5 Bedrooms</option>
                <option value="6bed">6 Bedrooms</option>
                <option value="7bed">7+ Bedrooms</option>
              </select>
            </div>
            <div class="select-container">
              <select name="area">
                <option value="">SIZE RANGE</option>
                <option value="500-1000">500 - 1,000 Sq Ft</option>
                <option value="1000-2000">1,000 - 2,000 Sq Ft</option>
                <option value="2000-3000">2,000 - 3,000 Sq Ft</option>
                <option value="3000-4000">3,000 - 4,000 Sq Ft</option>
                <option value="4000-5000">4,000 - 5,000 Sq Ft</option>
                <option value="5000-10000">5,000 - 10,000 Sq Ft</option>
                <option value="10000">10,000+ Sq Ft</option>
              </select>
            </div>
            <div class="select-container">
              <select name="price_range">
                <option value="">PRICE RANGE</option>
                <option value="500000-1000000">500K AED - 1M AED</option>
                <option value="1000000-2000000">1M AED - 2M AED</option>
                <option value="2000000-3000000">2M AED - 3M AED</option>
                <option value="3000000-4000000">3M AED - 4M AED</option>
                <option value="4000000-5000000">4M AED - 5M AED</option>
                <option value="5000000-10000000">5M AED - 10M AED</option>
                <option value="10000000-20000000">10M AED - 20M AED</option>
                <option value="20000000-30000000">20M AED - 30M AED</option>
                <option value="30000000-40000000">30M AED - 40M AED</option>
                <option value="40000000-50000000">40M AED - 50M AED</option>
                <option value="50000000">50m+ AED</option>
              </select>
            </div>
            <input type="hidden" name="action" value="myfilter">
            <input type="hidden" name="availability" value="<?php echo esc_attr($atts['type']); ?>">

            <input type="hidden" name="sort_price" id="sort_price" value="<?php echo isset($_GET['sort_price']) ? esc_attr($_GET['sort_price']) : ''; ?>">
            <?php wp_nonce_field('myfilter', 'property_filter_nonce'); ?>
            <button id="submit-filter" type="submit" name="filter">Filter</button>
            <button id="reset-filter" type="reset" name="reset" class="reset-filter">Reset Filter</button>
          </form>
          <script>
            function convertToNumber() {
              const inputElement = document.getElementById('max_price');
              let inputValue = inputElement.value.trim().toLowerCase();

              if (inputValue.endsWith('k')) {
                const numValue = parseFloat(inputValue) * 1000;
                if (!isNaN(numValue)) {
                  inputElement.value = numValue;
                }
              } else if (inputValue.endsWith('m')) {
                const numValue = parseFloat(inputValue) * 1000000;
                if (!isNaN(numValue)) {
                  inputElement.value = numValue;
                }
              }
            }
          </script>
          <?php
          return ob_get_clean();
        }
        add_shortcode('property_filters', 'property_filter_form');

        function property_filter_action() {
          $args = array(
            'post_type' => 'property',
            'tax_query' => array(),
            'meta_query' => array(),
            'meta_key' => 'price',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
          );

          if (!empty($_POST['bedrooms'])) {
            if ($_POST['bedrooms'] === '7bed') {
      // For 7+ bedrooms, find all properties with 7 or more bedrooms
              $acceptable_values = array('7bed', '8bed', '9bed', '10bed', '11bed', '12bed', '13bed', '14bed', '15bed');
              $args['meta_query'][] = array(
                'key' => 'bedrooms',
        'value' => $acceptable_values, // Array of acceptable values
        'compare' => 'IN',  // Check if the value is in the array
      );
            } else {
      // For other options, find properties with the exact number of bedrooms
              $args['meta_query'][] = array(
                'key' => 'bedrooms',
                'value' => $_POST['bedrooms'],
                'compare' => '=',
              );
            }
          }


          if (!empty($_POST['bathrooms'])) {
            $args['meta_query'][] = array(
              'key' => 'bathrooms',
              'value' => $_POST['bathrooms'],
              'compare' => '=',
            );
          }

          if (!empty($_POST['price_range'])) {
        // Parse the price range from the selected value
            $price_range = explode('-', $_POST['price_range']);
            $min_price = isset($price_range[0]) ? intval($price_range[0]) : 0;
            $max_price = isset($price_range[1]) ? intval($price_range[1]) : PHP_INT_MAX;

        // Add to meta_query
            $args['meta_query'][] = array(
              'key' => 'price',
              'value' => array($min_price, $max_price),
              'type' => 'NUMERIC',
              'compare' => 'BETWEEN',
            );
          }


          if (!empty($_POST['availability'])) {
            $args['tax_query'][] = array(
              'taxonomy' => 'availability',
              'field' => 'slug',
              'terms' => $_POST['availability'],
            );
          }

          if (!empty($_POST['property-type'])) {
            $args['tax_query'][] = array(
              'taxonomy' => 'property-type',
              'field' => 'slug',
              'terms' => $_POST['property-type'],
            );
          }

          if (!empty($_POST['area'])) {
        // Parse the area range from the selected value
            $area_range = explode('-', $_POST['area']);
            $min_area = isset($area_range[0]) ? intval($area_range[0]) : 0;
            $max_area = isset($area_range[1]) ? intval($area_range[1]) : 999999;

        // Add to meta_query
            $args['meta_query'][] = array(
              'key' => 'area',
              'value' => array($min_area, $max_area),
              'type' => 'NUMERIC',
              'compare' => 'BETWEEN',
            );
          }

          if (isset($_POST['sort_price']) && !empty($_POST['sort_price'])) {
            $_POST['sort_price'] = ($_POST['sort_price'] === 'asc') ? 'asc' : 'desc';
            $args['order'] = strtoupper($_POST['sort_price']);
          }

          $query = new WP_Query($args);
          $output = '';

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();
              $output .= generate_property_code($query->post);
            }
            wp_reset_postdata();
          } else {
            $output .= '<div class="noresults">Unfortunately, we can\'t find any properties that match your requirements. Return to <a href="/buy"> here </a> to try again.</div>';
          }

          echo $output;
          wp_die();
        }
        add_action('wp_ajax_myfilter', 'property_filter_action');
        add_action('wp_ajax_nopriv_myfilter', 'property_filter_action');

        function my_enqueue_scripts() {
          wp_enqueue_script('my-script', get_stylesheet_directory_uri() . '/js/ajax.js', array('jquery'), '1.0', true);
          wp_localize_script('my-script', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        }
        add_action('wp_enqueue_scripts', 'my_enqueue_scripts');





        function get_child_terms() {
          $parent_term_slug = $_GET['parent_term'];
          $parent_term = get_term_by('slug', $parent_term_slug, 'location');

          $terms = get_terms('location', array(
            'parent' => $parent_term->term_id,
        'hide_empty' => false, // This will include terms even if they are not associated with any posts
      ));

          echo json_encode($terms);

          wp_die(); 
        }
add_action('wp_ajax_get_child_terms', 'get_child_terms'); // For logged in users
add_action('wp_ajax_nopriv_get_child_terms', 'get_child_terms'); // For non-logged in users

function display_properties_results() {
  $args = array(
    'post_type' => 'property',
  );

    // Determine which location to use based on $_GET
  $location = '';
  if (!empty($_GET['location_child'])) {
    $location = sanitize_text_field( $_GET['location_child'] );
  } elseif (!empty($_GET['location_parent'])) {
    $location = sanitize_text_field( $_GET['location_parent'] );
  }

  if (!empty($location)) {
    $args['tax_query'][] = array(
      'taxonomy' => 'location',
      'field' => 'slug',
      'terms' => $location,
    );
  }

    // Add availability to tax_query
  if (!empty($_GET['availability'])) {
    $availability = sanitize_text_field( $_GET['availability'] );
    $args['tax_query'][] = array(
      'taxonomy' => 'availability',
      'field' => 'slug',
      'terms' => $availability,
    );
  }

    // Add meta queries based on $_GET
  if (!empty($_GET['bedrooms'])) {
    $bedrooms = sanitize_text_field( $_GET['bedrooms'] );
    $args['meta_query'][] = array(
      'key' => 'bedrooms',
      'value' => $bedrooms,
      'compare' => '=',
    );
  }

  if (!empty($_GET['bathrooms'])) {
    $bathrooms = sanitize_text_field( $_GET['bathrooms'] );
    $args['meta_query'][] = array(
      'key' => 'bathrooms',
      'value' => $bathrooms,
      'compare' => '=',
    );
  }
  
    // Property type
  if (!empty($_POST['property-type'])) {
    $args['tax_query'][] = array(
      'taxonomy' => 'property-type',
      'field' => 'slug',
      'terms' => $_POST['property-type'],
    );
  }

  if (!empty($_POST['price_range'])) {
        // Parse the price range from the selected value
    $price_range = explode('-', $_POST['price_range']);
    $min_price = isset($price_range[0]) ? intval($price_range[0]) : 0;
    $max_price = isset($price_range[1]) ? intval($price_range[1]) : PHP_INT_MAX;

        // Add to meta_query
    $args['meta_query'][] = array(
      'key' => 'price',
      'value' => array($min_price, $max_price),
      'type' => 'NUMERIC',
      'compare' => 'BETWEEN',
    );
  }


  $query = new WP_Query($args);

  if ($query->have_posts()) {
    $output = '';

    while ($query->have_posts()) {
      $query->the_post();
      $bathrooms = get_field('bathrooms');
      $bedrooms = get_field('bedrooms');
      $price = get_field('price');
      $location = wp_get_post_terms(get_the_ID(), 'location')[0]->name;
      $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
      $link = get_the_permalink();
      $output .= '<div class="property-item" style="background-image: url(' . esc_url($thumbnail_url) . ');">';
      $output .= '<div class="property-inner">';
      $output .= '<h2>' . esc_html( get_the_title() ) . '</h2>';
      $output .= '<table>';
      $output .= '<tr>';
      $output .= '<td><img src="/wp-content/uploads/icons/bedroom.png"><p>' . esc_html($bedrooms['label']) . '</p></td>';
      $output .= '<td><img src="/wp-content/uploads/icons/bathroom.png"><p>' . esc_html($bathrooms['label']) . '</p></td>';
      $output .= '</tr>';
      $output .= '<tr>';
      $output .= '<td><img src="/wp-content/uploads/icons/location.png"><p>' . esc_html($location) . '</p></td>';
      $output .= '<td><img src="/wp-content/uploads/icons/price.png"><p>AED ' . number_format($price) . '</p></td>';
      $output .= '</tr>';
      $output .= '</table>';
      $output .= '<a href="' . esc_url($link) . '">View More</a>';
      $output .= '</div></div>';
    }
    wp_reset_postdata();
  } else {
    $output = 'No properties found.';
  }

  return $output;
}
add_shortcode('property-results', 'display_properties_results');


add_shortcode('advice-grid', 'advice_grid_shortcode');

function advice_grid_shortcode($atts)
{
  ob_start();
  $query = new WP_Query(array(
        'post_type' => 'post',  // Change 'post' to the desired post type
        'posts_per_page' => -1,
      ));

  if ($query->have_posts()) {
    $output = '<div class="advice-grid">';

    while ($query->have_posts()) {
      $query->the_post();
      $thumbnail_url = get_the_post_thumbnail_url();
      $output .= '<div class="grid-item">';
      $output .= '<div class="grid-item-image" style="background-image: url(' . esc_url($thumbnail_url) . ')"></div>';
      $output .= '<div class="grid-item-content">';
      $output .= '<h2>' . get_the_title() . '</h2>';
      $output .= '<div class="post-excerpt">' . wp_trim_words(get_the_excerpt(), 30, '...') . '</div>';
      $output .= '<a href="' . esc_url(get_permalink()) . '" class="read-more">Read more</a>';
      $output .= '</div>';
      $output .= '</div>';
    }

    $output .= '</div>';

    echo $output;
  }

  wp_reset_postdata();
  return ob_get_clean();
}


add_shortcode('location-list', 'location_list_shortcode');

function location_list_shortcode()
{
  $parent_terms = get_terms(array(
    'taxonomy' => 'location',
    'hide_empty' => false,
        'parent' => 0, // Only retrieve parent terms
      ));

  $output = '<div class="footer-links"><ul>';

  foreach ($parent_terms as $term) {
    $term_link = get_term_link($term);
    $output .= '<li>';

    if (!is_wp_error($term_link)) {
      $output .= '<a href="' . esc_url($term_link) . '">' . $term->name . '</a>';
    } else {
      $output .= $term->name;
    }

    $output .= '</li>';
  }
  $output .= '
  <li><a href="/beachfront-living">Beachfront Living</a></li>
  <li><a href="/waterfront-living">Waterfront Living</a></li>
  <li><a href="/city-living">City Living</a></li>
  <li><a href="/suburban-family-living">Suburban Family Living</a></li>
  ';

  $output .= '</ul></div>';

  return $output;
}


function property_list_shortcode()
{
  $terms = get_terms(array(
    'taxonomy' => 'location',
        'hide_empty' => false, // Include empty terms as well
      ));

  $output = '<div class="property-list"><ul>';

  foreach ($terms as $term) {
    $term_name = 'Properties in ' . $term->name;
    $term_link = get_term_link($term);

    if (!is_wp_error($term_link)) {
      $output .= '<li><a href="' . esc_url($term_link) . '">' . $term_name . '</a></li>';
    } else {
      $output .= '<li>' . $term_name . '</li>';
    }
  }

  $output .= '</ul></div>';

  return $output;
}

add_shortcode('property-list', 'property_list_shortcode');


add_shortcode('community-info', 'community_info');
function community_info() {
  global $post;
  
  $terms = get_the_terms( $post, 'location' );
  $output = '';

  foreach ( $terms as $term ) {
    $location = get_field( 'building_location', $term->taxonomy . '_' . $term->term_id );
    $content = $term->description;
    $location_name = $term->name;

        // Check if $location is empty and retrieve from parent term if it is
    if (empty($location) && $term->parent) {
      $parent_term = get_term($term->parent, 'location');
      $location = get_field('building_location', $parent_term->taxonomy . '_' . $parent_term->term_id);
    }
  }
  
  if ((empty($location)) || (empty($content))) {
    $class = ' single-box';
  }
  
  $output .= '<h2>'.$location_name.'</h2><div class="community-container'.$class.'">';
  
  if( $location ) {
    $output .= '<div class="community-map"><h3>Location</h3>';
    $output .= '<iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=300&amp;hl=en&amp;q='.urlencode( $location['address'] ) .'&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>';  
    $output .= '</div>';
  }
  
  if ( $content ) {
    $output .= '<div class="community-content"><h3>About</h3>';
    $output .= $content;
    $output .= '</div>';
  }
  
  return $output;
}



function my_acf_google_map_api( $api ){
  $api['key'] = 'AIzaSyCtPNz-aU8PZS84lnrFSnzl4HYUbmxXHXk';
  return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');



function partner_slider_shortcode() {

  $partners = new WP_Query(array(
    'post_type'      => 'partner',
    'posts_per_page' => -1,
  ));

  if ($partners->have_posts()) {
    $output = '<div class="partner-slider">';
    while ($partners->have_posts()) {
      $partners->the_post();
      $thumbnail = get_the_post_thumbnail();
      $title = get_the_title();
      $content = get_the_content();
      if (empty($thumbnail)) {
        $thumbnail = '<img src="/wp-content/uploads/2023/05/Prime-Location-Horizontal-Logo-Blue-background.png" style="filter: invert(1);">';
      }

      $output .= '<div class="partner-item">';
      $output .= $thumbnail;
      $output .= '<h3>'.$title.'</h3>';
                //$output .= '<div class="partner-content">'.$content.'</div>';
      $output .= '</div>';
    }

    $output .= '</div>';

    $output .= '<script type="text/javascript">
    jQuery(document).ready(function($) {
      var slider = $(".partner-slider");
      slider.slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 3000,
        cssEase: "linear",
        infinite: true,
        swipe: true,  // Enable swiping
        responsive: [
        {
          breakpoint: 768,  // breakpoint in px
          settings: {
            slidesToShow: 1,  // set to 1 slide at a time
            autoplaySpeed: 6000,  // Slide every 6 seconds
            speed: 600  // Transition speed (optional)
          }
        }
        ]
        });
        });

        </script>';


      }

      return $output;
    }
    add_shortcode('partner-slider', 'partner_slider_shortcode');


    function partner_grid_shortcode() {

      $partners = new WP_Query(array(
        'post_type'      => 'partner',
        'posts_per_page' => -1,
      ));

      if ($partners->have_posts()) {
        $output = '<div class="partner-grid">';
        while ($partners->have_posts()) {
          $partners->the_post();
          $thumbnail = get_the_post_thumbnail();
          $title = get_the_title();
          $content = get_the_content();
          if (empty($thumbnail)) {
            $thumbnail = '<img src="/wp-content/uploads/2023/05/Prime-Location-Horizontal-Logo-Blue-background.png" style="filter: invert(1);">';
          }

          $output .= '<div class="partner-grid-item">';
          $output .= $thumbnail;
          $output .= '<h3>'.$title.'</h3>';
          $output .= '<div class="partner-content">'.$content.'</div>';
          $output .= '</div>';
        }
        $output .= '</div>';
      }

      return $output;
    }
    add_shortcode('partner-grid', 'partner_grid_shortcode');


// Add custom column
    add_filter('manage_edit-location_columns', function($columns) {
      $columns['order'] = 'Order';
      return $columns;
    });

// Populate custom column
    add_action('manage_location_custom_column', function($content, $column_name, $term_id) {
      if ($column_name === 'order') {
        $order = get_field('order', 'location_' . $term_id);
        return $order;
      }
    }, 10, 3);

    add_filter('manage_edit-location_sortable_columns', function($columns) {
    error_log('Sortable columns filter triggered');  // Debug line
    $columns['order'] = 'order';
    return $columns;
  });


    add_filter('get_terms_args', function($args, $taxonomies) {
      if (is_admin() && isset($_GET['orderby']) && $_GET['orderby'] === 'order') {
        error_log('Sorting logic triggered');  // Debug line
        $args['meta_key'] = 'order';
        $args['orderby'] = 'meta_value_num';
      }
      return $args;
    }, 10, 2);


    add_filter('terms_clauses', function($clauses, $taxonomy, $args) {
      if (is_admin() && isset($_GET['orderby']) && $_GET['orderby'] === 'order') {
        global $wpdb;
        $clauses['join'] .= " LEFT JOIN {$wpdb->termmeta} AS tm ON t.term_id = tm.term_id";
        $clauses['where'] .= " AND tm.meta_key = 'order'";
        $clauses['orderby'] = "ORDER BY tm.meta_value+0";
      }
      return $clauses;
    }, 10, 3);


    add_shortcode('location-map', 'location_map');
    function location_map() {

      $location = get_field( 'building_location');

      $output = '';
      if( $location ) {
        $output .= '<div class="location-map">';
        $output .= '<iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=300&amp;hl=en&amp;q='.urlencode( $location['address'] ) .'&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>';  
        $output .= '</div>';
      }

      return $output;


    }



// Create a shortcode to display ACF gallery
    function custom_acf_gallery_shortcode($atts) {
    // Extract shortcode attributes
      extract(shortcode_atts(array(
        'post_id' => get_the_ID(), // Default to the current post
        'field_name' => 'gallery', // Change this to your ACF field name
      ), $atts));

    // Get the ACF gallery images
      $gallery_images = get_field($field_name, $post_id);

    // Check if there are images
      if ($gallery_images) {
        // Output the gallery HTML
        $output = '<div class="acf-gallery">';
        foreach ($gallery_images as $image) {
          $output .= '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" />';
        }
        $output .= '</div>';
        return $output;
      } else {
        return 'No images found.';
      }
    }

// Register the shortcode
    add_shortcode('acf_gallery', 'custom_acf_gallery_shortcode');


    function meta_table_shortcodes($atts) {
      $output = '<table class="meta-table" style="border-collapse: collapse;">';


      $dubai_marina=get_field('dubai_marina', $post_id);
      $palm_jumeriah=get_field('palm_jumeriah', $post_id);
      $dubai_mall=get_field('dubai_mall', $post_id);
      $burj_khalifa=get_field('burj_khalifa', $post_id);
      $dubai_airport=get_field('dubai_airport', $post_id);

    //echo "Here is your down payment: " . $down_payment;



      $non_empty_fields = 0;

    // Calculate the number of fields that have values
      if (!empty($dubai_marina)) {
        $non_empty_fields++;
      }

      if (!empty($palm_jumeriah)) {
        $non_empty_fields++;
      }

      if (!empty($dubai_mall)) {
        $non_empty_fields++;
      }

      if (!empty($burj_khalifa)) {
        $non_empty_fields++;
      }

      if (!empty($dubai_airport)) {
        $non_empty_fields++;
      }



      $output .= '<style>
      .meta-table td {
        display: none;
        width: calc(100% / ' . $non_empty_fields . ');
      }
      </style>';

      $output .= '<tr>';

      if (!empty($dubai_marina)) {
        $output .= '<td style="display: table-cell;"><h3>Dubai Marina </h3>' . $dubai_marina. 'Min </td>';
      }

      if (!empty($palm_jumeriah)) {
        $output .= '<td style="display: table-cell;"><h3>Palm Jumeriah </h3>' . $palm_jumeriah . 'Min </td>';
      }



      if (!empty($burj_khalifa)) {
        $output .= '<td style="display: table-cell;"><h3>Burj Khalifa</h3>' . $burj_khalifa . 'Min </td>';
      }

      if (!empty($dubai_airport)) {
        $output .= '<td style="display: table-cell;"><h3>Dubai Airport</h3>' . $dubai_airport . 'Min </td>';
      }



      if (!empty($dubai_mall)) {
        $output .= '<td style="display: table-cell;"><h3>Dubai Mall</h3>' . $dubai_mall . 'Min </td>';
      }


      $output .= '</tr>';

      $output .= '</table>';

      return $output;
    }
    add_shortcode('meta-tables', 'meta_table_shortcodes');


    function payment_shortcode($atts) {
      $output = '<table class="meta-table" style="border-collapse: collapse;">';


      $down_payment=get_field('down_payment', $post_id);
      $during_construction=get_field('during_construction', $post_id);
      $hand_over=get_field('hand_over', $post_id);

    //echo "Here is your down payment: " . $down_payment;



      $non_empty_fields = 0;

    // Calculate the number of fields that have values
      if (!empty($down_payment)) {
        $non_empty_fields++;
      }

      if (!empty($during_construction)) {
        $non_empty_fields++;
      }

      if (!empty($hand_over)) {
        $non_empty_fields++;
      }





      $output .= '<style>
      .meta-table td {
        display: none;
        width: calc(100% / ' . $non_empty_fields . ');
      }
      </style>';

      $output .= '<tr>';

      if (!empty($down_payment)) {
        $output .= '<td style="display: table-cell;"><h3>Down Payment </h3>' . $down_payment. '% </td>';
      }

      if (!empty($during_construction)) {
        $output .= '<td style="display: table-cell;"><h3>During Construction </h3>' . $during_construction . '% </td>';
      }



      if (!empty($hand_over)) {
        $output .= '<td style="display: table-cell;"><h3>Hand Over</h3>' . $hand_over . '% </td>';
      }




      $output .= '</tr>';

      $output .= '</table>';

      return $output;
    }
    add_shortcode('payment_info', 'payment_shortcode');
    add_shortcode('singleproperty','singleproperty_callback');
    function singleproperty_callback(){
      ob_start();
      $fields=get_fields();
      ?>
      <style type="text/css">
        .real-estate-content__stats {
          -moz-column-gap: 10px;
          column-gap: 10px;
          row-gap: 10px;
          display: grid;
          grid-template-columns: repeat(3,1fr);
          list-style: none;
          margin-bottom: 25px;
          padding-left: 0;
          padding: 0px !important;

        }
        .real-estate-content__stats__item {
          font-size: 17px;
          line-height: 29px;
          padding-bottom: 0;
          list-style: none;
        }
        .real-estate-content__title
        {
          color:#f4a896;
        }
        .real-estate-content__title, .real-estate-index-header__title {
          font-size: 38px;
          font-weight: 800;
          line-height: 46px;
          margin-bottom: 25px;
        }
        .real-estate-content__price {
          align-items: center;
          color: #73787e;
          display: flex;
          flex-wrap: wrap;
          font-family: Inter,sans-serif;
          font-size: 22px;
          line-height: 38px;
          margin-bottom: 18px;
        }
        .real-estate-content__price strong {
          font-weight: 800;
          margin-right: 38px;
          position: relative;
        }
        .real-estate-content__location {
          gap: 10px;
          align-items: center;
          display: flex;
          margin-bottom: 40px;
          margin-top: 25px;
        }
        .real-estate-content__location__title {
          color: #73787e;
          font-family: Inter,sans-serif;
          font-size: 26px;
          font-weight: 800;
          line-height: 38px;
        }
        .real-estate-content__about {
          margin-bottom: 40px;
        }
        .real-estate-content__section--payment-plan {
          margin-bottom: 60px;
        }
        .real-estate-content__section--payment-plan .real-estate-content__section__title {
        }
        .real-estate-content__section--payment-plan p {
          background-color: #358597;
          color: #fff;
          font-size: 15px;
          font-weight: bolder;
          margin-bottom: 3px;
          padding: 6px 12px !important;
          width: 50%;
        }

        .real-estate-content__equipment {
          display: grid;
          grid-template-columns: repeat(3,1fr);
          list-style: none;
          margin-bottom: 60px;
          padding-left: 0;
          gap: 5px;
          padding: 0px !important;
        }
        .real-estate-content__equipment__item {
          justify-content: center;
          align-items: center;
          color: var(--text-color);
          display: flex;
          padding-bottom: 0;
          gap: 4px;
          background: #358597;
          color: white;
          padding: 5px;
        }
        .real-estate-content__poi {
          display: grid;
          grid-template-columns: repeat(5,1fr);
          list-style: none;
          margin-bottom: 60px;
          padding-left: 0;
          row-gap: 30px;
        }
        .real-estate-content__poi__item {
          display: flex;
          flex-direction: column;
          font-size: 17px;
          line-height: 22px;
          padding-bottom: 0;
        }
        .real-estate-content__poi__item a {
          color: var(--text-color);
        }
        .real-estate-content__location__image img {
          height: 100%;
          -o-object-fit: cover;
          object-fit: cover;
          width: 100%;
          border-radius: 50px;
        }
        .real-estate-content__location__image{
          width: 50px;
          height: 50px;
          border-radius: 50px;
        }
        .gallery {
          grid-gap: 15px;
          display: grid;
          grid-template-columns: repeat(3,1fr);
        }
        .gallery__item {
          padding-top: 56.07%;
          position: relative;
          width: 100%;
        }
        .gallery__item__image {
          height: 100%;
          left: 0;
          -o-object-fit: cover;
          object-fit: cover;
          position: absolute;
          top: 0;
          width: 100%;
        }
        .recommended-real-estates {
          display: flex;
          flex-direction: column;
          position: relative;
          width: 100%;
        }
        .recommended-real-estates__title {
          color: #222a38;
          position: relative;
          width: -moz-fit-content;
          width: fit-content;
        }
        .real-estates__list, .recommended-real-estates__list {
          -moz-column-gap: 5px;
          column-gap: 5px;
          display: grid;
          grid-template-columns: repeat(4,1fr);
          width: 100%;
        }
        .real-estate-card {
          color: #fff;
          display: flex;
          flex-direction: column;
          padding-right: 10px;
          position: relative;
          transition: all .3s ease-in-out;
          margin-top:25px;
        }
        .real-estate-card .carousel {
          filter: drop-shadow(0 0 4px rgba(0,0,0,.25));
        }
        .real-estate-card__text {
          color: #000;
          margin-top: auto;
          text-decoration: none;
        }
        .real-estate-card__label {
          background-color: rgba(34,42,56,.702);
          font-size: 17px;
          left: 5px;
          padding: 5px 18px;
          position: absolute;
          top: 5px;
          z-index: 10;
        }
        .real-estate-card__text__title {
          font-size: 19px;
          font-weight: 600;
          line-height: 25px;
          margin-top: 5px;
          color:#f4a896;
        }
        .real-estate-card__text__description {
          display: inline-block;
          font-size: 16px;
          line-height: 21px;
          margin-bottom: 15px;
        }
        .real-estate-card__text__location {
          display: block;
          font-size: 15px;
          margin-bottom: 12px;
          text-transform: uppercase;
      color:#f4a896;
        }
        ul.real-estate-card__text__stats {
    margin-bottom: 10px;
}
        .real-estate-card__text__stats {
          border-bottom: 1px solid #fff;
          border-top: 1px solid #fff;
          display: flex;
          justify-content: space-between;
          list-style: none;
          margin-bottom: 22px;
          padding: 13px 0;
        }
        .real-estate-card__text__stats__item {
          align-items: center;
          display: flex;
          padding-bottom: 0;
        }
        .real-estate-card__text__stats {
          border-bottom: 1px solid #000;
          border-top: 1px solid #000;
          display: flex;
          justify-content: space-between;
          list-style: none;
          margin-bottom: 22px;
          padding: 13px 0;
        }
        .real-estate-card__text__stats{
          padding: 3% !important;
        }
        .map{
          margin-bottom: 60px;
        }
        @media screen and (max-width: 768px) {
          .real-estate-content__stats {
            grid-template-columns: repeat(1,1fr);
          }
          .real-estate-content__equipment {
            grid-template-columns: repeat(1,1fr);
          }
          .real-estate-content__poi {
            grid-template-columns: repeat(2,1fr);
          }
          .gallery {
          grid-gap: 29px;
          display: grid;
          grid-template-columns: repeat(1,1fr);
        }
        .real-estates__list, .recommended-real-estates__list {
          grid-template-columns: repeat(1,1fr);
        }
        }
        ul.real-estate-content__poi, li.real-estate-content__equipment__item {
            padding: 0px;
            margin-top: 0px !important;
        }
        .et_pb_column{
          z-index: 0 !important;
        }
      </style>
      <h1 class="real-estate-content__title"><?php echo get_the_title(); ?></h1>
      <div>
        <ul class="real-estate-content__stats">
          <li class="real-estate-content__stats__item">
            <span>Usable area: </span>
            <strong><?php echo $fields['area']; ?> ft<sup>2</sup></strong>
          </li>
          <li class="real-estate-content__stats__item">
            <span>Bedrooms: </span>
            <strong><?php echo $fields['bedrooms']['value']; ?></strong>
          </li>

          <li class="real-estate-content__stats__item">

            <span>Year of completion: </span>
            <strong><?php echo $fields['completion_area']; ?></strong>
            
          </li>

          <li class="real-estate-content__stats__item">
            <span>No. of bedrooms: </span>
            <strong><?php echo $fields['bedrooms']['value']; ?></strong>
          </li>
          <li class="real-estate-content__stats__item">
            <span>No. of bathrooms: </span>
            <strong><?php echo $fields['bathrooms']['value']; ?></strong>
          </li>

        </ul>
        <div class="real-estate-content__price">
          <strong x-data=""><small>from</small>&nbsp;AED <?php echo $fields['price_in_aed'];  ?>/$<?php echo $fields['price_in__dollars'];  ?></strong>
          <small>
            <?php echo $fields['area']; ?> ft<sup>2</sup>
          </small>
        </div>
        <div class="real-estate-content__location">
          <?php
          $terms = wp_get_post_terms(get_the_ID(), 'location');
          $found_term=false;
          if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
              $term_name = $term->name;
              $term_permalink = get_term_link($term);
              $found_term=$term->term_id;
              ?>
              <a href="<?php echo $term_permalink; ?>" class="real-estate-content__location__title" target="_blank">
                <strong><?php echo $term_name; ?></strong>
              </a>
              <?php
            }
          }
          ?>
          <div style="display: flex;gap: 10px;flex-wrap: wrap;">
          <?php if($found_term): ?>
            <?php
            $args = array(
                  'post_type' => 'property', // Replace with your post type if different
                  'posts_per_page' => -1, // Retrieve all posts
                  'tax_query' => array(
                    array(
                          'taxonomy' => 'location', // Replace with your taxonomy slug
                          'field' => 'id',
                          'terms' => $found_term,
                        ),
                  ),
                );
            $query = new WP_Query($args);
            // if ($query->have_posts()) {
            //   while ($query->have_posts()) {
            //     $query->the_post();
            //     $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            //     if ($featured_image_url) {
                  ?>
                  <!-- <a href="<?php echo get_permalink(); ?>" class="real-estate-content__location__image">
                    <div style="background-image: url(<?php echo $featured_image_url; ?>);width: 50px;border-radius: 100%;background-size: cover;background-position: center;height: 50px;">
                    </div>
                 </a> -->
                 <?php
           //     }
           //   }
           //   wp_reset_postdata();
           // }
           ?>
         <?php endif; ?>
         </div>
       </div>
       <div class="real-estate-content__about">
        <?php echo $fields['description']; ?>
      </div>
       <div class="real-estate-content__section real-estate-content__section--gallery">
        <h3 class="real-estate-content__section__title">Gallery</h3>
        <div class="gallery real-estate-content__gallery" id="gallery">
          <?php
          if(isset($fields['gallery'])){
              // print_r($fields['gallery']);
            foreach($fields['gallery'] as $img){
              ?>
              <div class="bg-center h-auto h-575 max-w-full min-w-full rounded large-image bg-cover view-image" data-src="<?php echo $img['full_image_url']; ?>" onclick="changeImage(this,'<?php echo $img['full_image_url']; ?>')" style="background-image: url(<?php echo $img['full_image_url']; ?>);background-size: cover;background-position: center;height: 130px;background-repeat: no-repeat;">
              </div>
              <?php
            }

          }
          ?>

        </div>
      </div>
      <div class="real-estate-content__section real-estate-content__section--payment-plan">
        <h3 class="real-estate-content__section__title">Payment plan</h3>
        <p><?php echo $fields['down_payment']; ?>% Down Payment</p><p><?php echo $fields['during_construction']; ?>% During Construction</p><p><?php echo $fields['hand_over']; ?>% on Hand Over</p>
      </div>
      <div class="real-estate-content__section">
        <h3 class="real-estate-content__section__title">Property Amenities</h3>
        <ul class="real-estate-content__equipment real-estate-content__equipment--extended" :class="extend &amp;&amp; 'real-estate-content__equipment--extended'" x-data="{extend: false}">
          <?php
          if(isset($fields['property_amenities']) && !empty($fields['property_amenities'])){
            foreach($fields['property_amenities'] as $amen){
              ?>
              <li class="real-estate-content__equipment__item">
                <img src="<?php echo $amen['amenities_icon'];?>" width="20" height="10">
                <span><?php echo  $amen['amenities_label'];?></span>
              </li>
              <?php
            }
          }
          ?>
        </ul>
      </div>
      <div class="real-estate-content__section">
        <h3 class="real-estate-content__section__title">Points of interest</h3>
        <ul class="real-estate-content__poi">
          <?php
          if(isset($fields['points_of_interest']) && !empty($fields['points_of_interest'])){
            foreach($fields['points_of_interest'] as $points){
              ?>
              <li class="real-estate-content__poi__item">
                <a href="#">
                  <?php echo  $points['point_name'];?>
                </a>
                <strong><?php echo $points['point_distance']; ?> min</strong>
              </li>
              <?php
            }
          }
          ?>

        </ul>
      </div>
      <div class="map">
        <?php
        $mapdata=$fields['property_maps']; 
        echo $mapdata;
        ?>

      </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.2/photoswipe.min.css" integrity="sha512-LFWtdAXHQuwUGH9cImO9blA3a3GfQNkpF2uRlhaOpSbDevNyK1rmAjs13mtpjvWyi+flP7zYWboqY+8Mkd42xA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe-ui-default.min.js"></script>
    <?php
    return ob_get_clean();
  }
  add_shortcode('agent','agent_callback');
  function agent_callback(){
    ob_start();
    ?>
    <style type="text/css">
      .real-estate-contact {
        border: 1px solid #73787e;
        margin-bottom: 90px;
        padding: 20px 36px 40px 30px;
        position: sticky;
        top: 100px;
      }
      .real-estate-contact {
        border: 1px solid #73787e;
        margin-bottom: 90px;
        padding: 20px 36px 40px 30px;
        position: sticky;
        top: 100px;
      }
      
      .real-estate-contact__agent {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
      }
      .real-estate-contact__agent__text__name {
        font-size: 19px;
        line-height: 23px;
        margin-top: 0;
      }
      .real-estate-contact__agent__text__position {
        color: #73787e;
        display: block;
        line-height: 23px;
        margin-bottom: 4px;
      }
      .real-estate-contact__agent__text__contact {
        align-items: center;
        display: flex;
        font-size: 17px;
        line-height: 29px;
        margin-bottom: 5px;
      }
      .real-estate-contact__form__title {
        margin-bottom: 10px;
        margin-top: 0;
      }
      /*.real-estate-contact__form .text-input {
        margin-bottom: 40px;
      }*/
      .text-input input[type=email], .text-input input[type=number], .text-input input[type=password], .text-input input[type=tel], .text-input input[type=text], .text-input textarea {
        background-color: transparent;
        border: unset;
        border-bottom: 1px solid #222a38;
        color: #040f24;
        font-size: 14px;
        width: 100%;
        padding-bottom: 15px;
        margin-bottom: 10px;
      }
      .text-input label {
        bottom: 6px;
        color: var(--text-color);
        cursor: auto;
        font-size: 14px;
        font-weight: 400;
        left: 0;
        max-width: 100%;
        overflow: hidden;
        padding-top: 7px;
        text-overflow: ellipsis;
        transition: .3s;
        white-space: nowrap;
      }
      .real-estate-contact__form .btn {
        width: 100%;
      }
      .btn--blue {
        margin-top: 30px;
        background-color: #f4a896;
        box-shadow: 0 0 10px rgba(0,0,0,.25);
        color: #fff;
        font-size: 17px;
        font-weight: 700;
        letter-spacing: .01em;
        padding: 20px;
        text-align: center;
        display: block;
      }
      .real-estate-contact__form__sent {
        color: #f4a896;
        display: block;
        font-size: 17px;
        font-weight: 700;
        padding: 20px;
        text-align: center;
      }
      .real-estate-contact__agent__image img {
        border-radius: 100%;
        height: 107px;
        width: 107px;
      }
      .real-estate-contact__title {
        font-size: 21px;
        line-height: 30px;
        margin-bottom: 19px;
        margin-top: 0;
      }
      .real-estate-contact__agent__text__name {
        font-size: 19px;
        line-height: 23px;
        margin-top: 0;
        margin-bottom: 4px;
      }
      .real-estate-contact__form__title {
        margin-bottom: 10px;
        margin-top: 0;
      }
    </style>
    <?php
    $fields=get_fields(get_the_id()); 
    ?>
    <div class="real-estate-contact" id="__FPSC_ID_1_1696683592046" style="">
      <h3 class="real-estate-contact__title">Your agent for this property</h3>
      <div class="real-estate-contact__agent">


        <div class="real-estate-contact__agent__text">
          <div class="real-estate-contact__agent__text__name">
            <?php echo $fields['agent_info']['agent_title']; ?>
          </div>
          <span class="real-estate-contact__agent__text__position">
            <?php echo $fields['agent_info']['agent_designation']; ?>
          </span>
          <span class="real-estate-contact__agent__text__contact">
            <i class="envelope-icon"></i>
            <?php echo $fields['agent_info']['agent_email']; ?>
          </span>
          <span class="real-estate-contact__agent__text__contact">
            <i class="phone-icon"></i>
            <a href="tel:+971 56 520 3469"><?php echo $fields['agent_info']['agent_contact_no']; ?></a>
          </span>
        </div>
        <div class="real-estate-contact__agent__image">
          <img src="<?php echo $fields['agent_info']['agent_image']; ?>" alt="Profile picture of the  <?php echo $fields['agent_info']['agent_title']; ?> - Bayview by The Address Resort - One Bedroom Apartment">
        </div>


      </div>
      <form method="POST" action="https://www.buydubai.estate/!/forms/real_estate_contact" class="real-estate-contact__form" x-data="asyncForm()"><input type="hidden" name="_token" value="8c1pMlGMV98rPGOEuDyshujcHc9fABToJGJgiukw">
        <h3 class="real-estate-contact__form__title">Leave us contact details</h3>
        <div class="text-input">
          <input type="text" id="name" name="name" placeholder="name" required="">
        </div>
        <div class="text-input">
          <input type="text" id="contact" name="contact" placeholder="contact" required="">
        </div>
        <a href="" x-show="!sent" @click.prevent="send()" class="btn btn--blue">Contact me</a>
        <span x-show="sent &amp;&amp; !failed" class="real-estate-contact__form__sent" style="display: none;">Successfully sent</span>
        <span x-show="sent &amp;&amp; failed" class="real-estate-contact__form__sent real-estate-contact__form__sent--error" style="display: none;">
          Something went wrong
        </span>
      </form>
    </div>
    <?php
    return ob_get_clean();
  }

  add_shortcode('recommended','recommended_callback');
  function recommended_callback(){
    ob_start();
    ?>
    <?php
    $terms = wp_get_post_terms(get_the_ID(), 'location');
    $found_term=false;
    if (!empty($terms) && !is_wp_error($terms)) {
      foreach ($terms as $term) {
        $term_name = $term->name;
        $term_permalink = get_term_link($term);
        $found_term=$term->term_id;
        break;
      }
    }
    ?>
    <h3 class="recommended-real-estates__title">
      Next Apartments in the area <?php echo $term_name; ?>
    </h3>
    <div class="recommended-real-estates">
      <div class="recommended-real-estates__list">
        <?php if($found_term):?>
          <?php
          $args = array(
            'post_type' => 'property', 
            'posts_per_page' => -1, 
            'tax_query' => array(
              array(
                'taxonomy' => 'location', 
                'field' => 'id',
                'terms' => $found_term,
              ),
            ),
          );
          $query = new WP_Query($args);
          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();
              $fields=get_fields(get_the_id()); 
              $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
              ?>
              <div class="recommended-real-estates__list__item real-estate-card">
                <div class="carousel" style="background-size: cover;background-repeat: no-repeat;height: 190px;background-position: center;background-image: url(<?php echo $featured_image_url; ?>);" x-data="carousel()" >
                  <div class="real-estate-card__label">
                    Year of completion:<?php echo $fields['completion_area']; ?>
                  </div>
                  <a href="/real-estates-in-dubai/bayview-by-the-address-resort-three-bedroom-apartment" class="carousel__items">
                    <div class="carousel__items__item carousel__items__item--active" :class="active == 0 &amp;&amp; 'carousel__items__item--active'">
                    </div>
                  </a>
                  <div class="carousel__controls">
                    <a href="#" @click.prevent="activate('0')" class="carousel__controls__item carousel__controls__item--active" :class="active == 0 &amp;&amp; 'carousel__controls__item--active'"></a>
                    <a href="#" @click.prevent="activate('1')" class="carousel__controls__item" :class="active == 1 &amp;&amp; 'carousel__controls__item--active'"></a>
                    <a href="#" @click.prevent="activate('2')" class="carousel__controls__item" :class="active == 2 &amp;&amp; 'carousel__controls__item--active'"></a>
                  </div>
                </div>
                <a href="<?php echo get_the_permalink(); ?>" class="real-estate-card__text">
                  <h4 class="real-estate-card__text__title"><?php echo get_the_title(); ?></h4>
                  <span class="real-estate-card__text__description">Prime and exclusive location at <?php echo $term_name; ?></span>
                  <strong class="real-estate-card__text__location"><?php echo $term_name; ?></strong>
                  <ul class="real-estate-card__text__stats">
                    <li class="real-estate-card__text__stats__item">
                      <i class="meter-icon"></i><span><?php echo $fields['area']; ?> ft<sup>2</sup></span>
                    </li>
                    <li class="real-estate-card__text__stats__item">
                      <i class="bed-icon"></i><span><?php echo $fields['bedrooms']['value']; ?></span>
                    </li>
                    <li class="real-estate-card__text__stats__item">
                      <i class="bathtub-icon"></i><span><?php echo $fields['bathrooms']['value']; ?></span>
                    </li>
                    <li class="real-estate-card__text__stats__item">
                      <i class="fa-sharp fa-solid fa-loveseat"></i><span>Furnished</span>
                    </li>
                  </ul>
                  <strong class="real-estate-card__text__price" x-data="" x-html="$store.currency.formatPrice('2252742', '1')"><small>from</small>&nbsp;AED<?php echo $fields['price_in_aed'];  ?>/$<?php echo $fields['price_in__dollars'];  ?></strong>
                </a>
              </div>
              <?php
            }
            wp_reset_postdata();
          }
          ?>
        <?php endif; ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }