<?php 

/*
 * Plugin Name:       Related-Posts
 * Plugin URI:        https://shabbir.site/plugins/Related-Posts/
 * Description:       The Related Posts WordPress plugin is designed to enhance user engagement on your website by displaying a list of related posts beneath or alongside your content. 
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mostafiz Shabbir
 * Author URI:        https://mostafiz.netlify.app/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       related-posts
 * Domain Path:       /languages
 */


/*
 * Exit if accessed directly
 */
 if ( ! defined( 'ABSPATH' ) ) {
    exit; 
 }  
 
 require_once(ABSPATH . 'wp-admin/includes/plugin.php');
 /*
  * The Main Plugin Class
  */

 final class Related_Posts{

    /*
    * class constructor function
    */
    private function __construct() {
        $this->define_constants();
        add_action( 'plugins_loaded', [ $this, 'init_related_posts_plugin' ] );
     }
 
    private static $instance;
    /*
    * initialize singleton instance
    */
    public static function get_instance() {
        if( ! self::$instance ){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /*
    * private function for define constants. 
    */

    private function define_constants(){
        $plugin_data = get_plugin_data( __FILE__);

        $version = $plugin_data['Version'];

        define( 'RELATED_POSTS_VERSION', $version );
        define( 'RELATED_POSTS_FILE', __FILE__ );
        define( 'RELATED_POSTS_PATH', __DIR__ );
        define( 'RELATED_POSTS_URL', plugins_url( '', RELATED_POSTS_FILE ) );
        define( 'RELATED_POSTS_ASSETS', RELATED_POSTS_URL . '/assets' );    
    }

    /*
    * initialize the Related Posts plugin
    */
    public function init_related_posts_plugin(){
        add_filter( 'the_content', [ $this, 'show_related_posts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'related_posts_enqueue' ] );
    }

    public function related_posts_enqueue(){

        wp_enqueue_style( 'related-posts', RELATED_POSTS_ASSETS . '/css/related-posts.css', [], RELATED_POSTS_VERSION );

        wp_enqueue_style( 'related-owl.carousel', RELATED_POSTS_ASSETS . '/owlcarousel/assets/owl.carousel.min.css', [], RELATED_POSTS_VERSION );

        wp_enqueue_style( 'related-owl.theme.default', RELATED_POSTS_ASSETS . '/owlcarousel/assets/owl.theme.default.min.css', [], RELATED_POSTS_VERSION );

        wp_enqueue_script( 'related-owl.carouseljs', RELATED_POSTS_ASSETS . '/owlcarousel/owl.carousel.min.js', [ 'jquery' ], RELATED_POSTS_VERSION, true );

        wp_enqueue_script( 'related-posts-js', RELATED_POSTS_ASSETS . '/js/related-posts.js', [ 'jquery' ], RELATED_POSTS_VERSION, true );

    }


    public function show_related_posts($content){
        if( is_singular('post') ){

            $post_id = get_the_ID();
            $category = get_the_category($post_id);
            $cat_id = $category[0]->term_id;
           

            $related_posts = get_posts([
                'post_type' => 'post',
                'numberposts' => 5,
                'orderby'        => 'rand',
                'post__not_in' => [$post_id],
                'tax_query' => [
                    [
                    'taxonomy'  => 'category',
                    'field'     => 'term_id',
                    'terms'     => $cat_id,
                    ]
                ]
            ]);
            // $this->dump($related_posts );
            if( $related_posts ){
                $content .='<h2>Realated Posts</h2>';
                $content .= '<div class="related-posts-container owl-carousel">';

              
                foreach($related_posts as $related_post){
                    
                    // $this->dump($related_post);

                   

                    $content .= '<div class="related-post-item"><a href="' . get_permalink($related_post->ID) . '">';
                    $content .= '<div class="related-post-image">'
                    .get_the_post_thumbnail($related_post->ID);
                    $content .= '</div>';
                    $content .= '<div class="related-post">
                    <div class="related-post-date">'
                    .get_the_date('F j, Y', $related_post->ID)
                    .'</div><h2>'
                    .mb_strimwidth(get_the_title($related_post->ID),0, 20, '')
                    .'</h2><p>'
                    .mb_strimwidth(get_post($related_post->ID)->post_content, 0, 40, '...')
                    .'</p></div> <div class="related-post-meta">
                    <div class="related-post-author">'
                    .get_avatar( get_the_author_meta( 'email', $related_post->post_author ), 240, '', '', [ 'class' => 'related-author-img' ] )
                    .'<p>By '
                    .get_the_author_meta('display_name', $related_post->post_author)
                    .'</p></div></div>
                    </a></div>';
                }
                $content .= '</div>';
            
            }

        }

        return $content;

       
    }

   public function dump($con){
        echo '<pre>';
            var_dump($con);
        echo '</pre>';
    }

   

 }

 /*
  * initialize the main plugin
  */
 function related_posts_function(){
   return Related_Posts::get_instance();
 }

 /*
 * kick-off the plugin
 */

 related_posts_function();
 

//  echo RELATED_POSTS_VERSION;

//  die();