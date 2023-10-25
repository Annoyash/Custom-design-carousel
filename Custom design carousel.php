// Custom post type
function custom_enqueue_slick_scripts() {
    // Enqueue Slick Carousel CSS
    wp_enqueue_style('slick-carousel', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');

    // Enqueue Slick Carousel JS and its dependency jQuery
    wp_enqueue_script('jquery');
    wp_enqueue_script('slick-carousel', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
}

add_action('wp_enqueue_scripts', 'custom_enqueue_slick_scripts');



// custom service
function custom_cases_shortcode($atts) {
    $atts = shortcode_atts(array(
        'posts_per_page' => -1,
    ), $atts);

    $query_args = array(
        'post_type' => 'cases',
        'posts_per_page' => $atts['posts_per_page'],
    );

    $cases_query = new WP_Query($query_args);

    if ($cases_query->have_posts()) {
        $output = '<style>
            /* Custom CSS for Slick Carousel navigation */
            .slick-prev,
            .slick-next {                
                font-size: 24px;
                padding: 10px 15px;
                cursor: pointer;
                z-index: 1;
            }
			
			
			.slick-track {
				position: relative;
				top: 0;
				left: 0;
				margin-left: auto;
				margin-right: auto;
				display: flex;
				justify-content: space-between; 
			}


            .slick-slider {
                margin-top: 50px;
            }

            .carousel-navigation {
                padding: 0px 15px;
                display: flex;
                position: relative;
                text-align: right;
                justify-content: flex-end; 
                align-items: center;
            }

            .slide-counter {
                width: 50px;
                color: #050708;
                text-align: center;
                font-family: Poppins;
                font-size: 20px;
                font-style: normal;
                font-weight: 400;
                line-height: 28px;
            }

            /* Add hover effect for case thumbnails */
            .case-thumbnail img {
				height: 300px;
				width: 400px;
				border-radius: 12px;
            }

            .case-thumbnail:hover img {
                background-color: #FEC731; /* Change the background-color on hover */
                opacity: 0; /* Change the opacity on hover */
				
				
            }

            /* Add excerpt style */
            .case-excerpt {
                display: none; /* Initially hide excerpt */
                background-color: #FEC731;
				width: 400px;
				height: 300px;
                padding: 10px;
                position: absolute;
                top: 0%;
                text-align: center;
                font-size: 16px;
                font-weight: 400;
                z-index: 2; /* Place excerpt above the image */
				

            }

            /* Show excerpt on thumbnail hover */
            .case-thumbnail:hover .case-excerpt {
                display: flex;
				text-align: left;
				padding: 25px;
				color: #050708;
				font-family: Poppins;
				font-size: 24px;
				font-style: normal;
				font-weight: 500;
				line-height: 30px; /* 125% */
				border-radius: 12px;
            }

            /* Style for case container */
            .case {
                position: relative;
            }
			
			h2.case-title {
				margin-top: 20px;
				color: #050708;
				font-family: Poppins;
				font-size: 22px;
				font-style: normal;
				font-weight: 500;
				line-height: 29px;
			}
			
			.case-title a {
				text-decoration: none !important;
				color: inherit;
			}
        </style>';

        $output .= '<div class="outer-container">';
        $output .= '<div class="carousel-navigation">';
        $output .= '<span class="slick-prev"><img src="https://bt.testingserver.host/wp-content/uploads/2023/09/prev.png" /></span>';
        $output .= '<span class="slide-counter">1/' . $cases_query->found_posts . '</span>';
        $output .= '<span class="slick-next"><img src="https://bt.testingserver.host/wp-content/uploads/2023/09/next.png" /></span>';
        $output .= '</div>';

        $output .= '<div class="cases-carousel">';
        $output .= '<div class="slick-carousel">';

        while ($cases_query->have_posts()) {
			$cases_query->the_post();
			$output .= '<div class="case">';
			$output .= '<div class="case-thumbnail"><img src="' . get_the_post_thumbnail_url() . '"/><div class="case-excerpt">' . get_the_excerpt() . '</div></div>';
			$output .= '<h2 class="case-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
			$output .= '</div>';
		}


        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';

        // Add Slick Carousel initialization script
        $output .= '<script>
            jQuery(document).ready(function($) {
                var $slickCarousel = $(".slick-carousel");
                var $slideCounter = $(".slide-counter");

                $slickCarousel.slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: false, // Changed to false for a while
                    autoplaySpeed: 3000,
                    prevArrow: $(".slick-prev"),
                    nextArrow: $(".slick-next"),
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });

                $slickCarousel.on("beforeChange", function(event, slick, currentSlide, nextSlide) {
                    $slideCounter.text((nextSlide + 1) + "/" + slick.slideCount);
                });
            });
        </script>';

        wp_reset_postdata();
    } else {
        $output = 'No cases found.';
    }

    return $output;
}
add_shortcode('cases', 'custom_cases_shortcode');
