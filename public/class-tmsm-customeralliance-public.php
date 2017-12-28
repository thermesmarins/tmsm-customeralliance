<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/nicomollet
 * @since      1.0.0
 *
 * @package    Tmsm_Customeralliance
 * @subpackage Tmsm_Customeralliance/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tmsm_Customeralliance
 * @subpackage Tmsm_Customeralliance/public
 * @author     Nicolas Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Customeralliance_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Transient cache (in seconds)
	 *
	 * @var int
	 */
	private $transient_cache = 60 * 60 * 8;

	/**
	 * Maximum number of reviews to call
	 * @var int
	 */
	private $reviews_limit = 150;

	/**
	 * Reviews per page
	 * @var int
	 */
	private $reviews_perpage = 10;

	/**
	 * Customer Alliance API version number
	 * @var int
	 */
	private $api_version = 4;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tmsm_Customeralliance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tmsm_Customeralliance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmsm-customeralliance-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tmsm_Customeralliance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tmsm_Customeralliance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmsm-customeralliance-public.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Register the shortcodes
	 *
	 * @since    1.0.3
	 */
	public function register_shortcodes() {
		add_shortcode( 'customeralliance-stats', array( $this, 'stats_shortcode') );
		add_shortcode( 'customeralliance-badge', array( $this, 'badge_shortcode') );
	}

	/**
	 * Stats shortcode
	 *
	 * @since    1.0.3
	 */
	public function stats_shortcode($atts) {
		$atts = shortcode_atts( array(
			'id'         => '',
			'post_id'    => '',
			'access_key' => '',
			'lang'       => 'fr',
			'theme'      => 'color',
			'bootstrap'  => '3',
		), $atts, 'customeralliance-stats' );

		$customeralliance_stats = $this->customeralliance_transient_stats( $atts['id'], $atts['access_key'], $atts['lang'] );

		$customeralliance_reviews = $this->customeralliance_transient_reviews( $atts['id'], $atts['access_key'], $atts['lang'] );

		$output = '<div class="customeralliance-stats customeralliance-stats-bootstrap-'. $atts['bootstrap'] .'">

		<div class="customeralliance-intro">
					<div class="customeralliance-logo">
						<img src="'. plugin_dir_url( __FILE__ ) . 'img/customeralliance-logo-big-'.$atts['theme'].'.png'.'" alt="'.esc_attr__('Customer Alliance Logo','tmsm-customeralliance').'">
					</div>

					<div class="customeralliance-about">
						<h3>
							'.__('Independent reviews by Customer Alliance','tmsm-customeralliance').'
						</h3>
						<p class="last-paragraph">
							<a role="button" data-toggle="collapse" href="#whatiscustomeralliance" aria-expanded="false" aria-controls="whatiscustomeralliance" class="customeralliance-certificate-btn"><span class="glyphicon glyphicon-info-sign"></span>'.__('What is Customer Alliance Certificate?','tmsm-customeralliance').'</a>
						</p>
						<div id="whatiscustomeralliance" class="customeralliance-certificate-content collapse">
							<p>'.__('Customer Alliance is an independent review provider for businesses, helping them collect authentic customer feedback. This Review Certificate bridges the trust gap between businesses and you – the customer.','tmsm-customeralliance').'</p>
							<p>'.__('<b>How does the review process work?</b><br/> Our questionnaire is sent to all customers; which means, every single review here was submitted by a real customer and was not manipulated.','tmsm-customeralliance').'</p>
							<p class="last-paragraph">'.__('<b>What is the Customer Satisfaction Index?</b><br/>The Customer Satisfaction Index offers an average rating based on all guest feedback. It shows you how happy the customers were with the business. 100% represents the highest possible score.','tmsm-customeralliance').'</p>
						</div>
					</div>
				</div>

			</div>
		</div>


		<div itemscope="" itemtype="http://schema.org/Hotel">


				<div class="customeralliance-rating">
					<h2>
						'.__('Our Customer Satisfaction Index','tmsm-customeralliance').'
					</h2>
					<div class="customeralliance-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
						<meta itemprop="itemreviewed" content="'. esc_attr( $customeralliance_reviews->business->name ) .'">
						<meta itemprop="ratingCount" content="'. esc_attr( $customeralliance_reviews->business->reviewCount ) .'">
						<meta itemprop="reviewCount" content="'.$customeralliance_stats->globalStatistics->reviewCount .'">
						<div class="customeralliance-global-rating">
							<meta content="5" itemprop="bestRating">
							<meta content="0" itemprop="worstRating">
							<meta content="'. esc_attr(round( floatval( $customeralliance_stats->globalStatistics->averageRatingPercentage /20 ),1 ) ) .'" itemprop="ratingValue">
							<span>'. round( floatval( $customeralliance_stats->globalStatistics->averageRatingPercentage ) ) .'</span>%
						</div>
						<p>
							'.sprintf( __('%d reviews <em>on %d portals</em>','tmsm-customeralliance'), $customeralliance_stats->globalStatistics->reviewCount,
				$customeralliance_stats->globalStatistics->portalCount ).'
						</p>
					</div>
				</div>

				<div class="customeralliance-portals">
					<h3>'. __('Average rating','tmsm-customeralliance') .'</h3>
					<div class="inside">';
						if ( ! empty( $customeralliance_stats->portalStatistics->portal ) ) {
							foreach ( $customeralliance_stats->portalStatistics->portal as $portal ) {
								$output .='
								<div class="customeralliance-portals-item">
									<span title="'. esc_attr( $portal->name ) .'">'. $portal->name .'</span>
									<strong>'. round( $portal->averageRatingPercentage ) .'%</strong>
								</div>';
							}
						}
$output .= '
					</div>
				</div>

				<div class="customeralliance-categories">
					<h3>'. __('Rating criterias','tmsm-customeralliance') .'</h3>
					<div class="inside">';

						if ( ! empty( $customeralliance_stats->globalStatistics->ratings->category ) ) {
							foreach ( $customeralliance_stats->globalStatistics->ratings->category as $category ) {
								$output.='
								<div class="customeralliance-categories-item">
									<div class="category-rating">
										<span class="value">'. $category->averageRating .'</span>&nbsp;<span class="max">/&nbsp;5</span>
									</div>
									<span class="category-name">
										'. $category->label .'
									</span>
									<div class="progress">
										<div class="bar progress-bar" role="progressbar" aria-valuenow="'. esc_attr( $category->averageRatingPercentage ) .'" aria-valuemin="0" aria-valuemax="100" style="width:'. $category->averageRatingPercentage .'%">
											<span class="value">'. round( floatval( $category->averageRating ), 1 ) .'
												&nbsp;/&nbsp;5</span>
										</div>
									</div>
								</div>';

							 }
						}

					$output.='
					</div>
				</div>


			</div>';

				if ( ! empty( $customeralliance_reviews->reviews->review ) ) {
					$customeralliance_reviews_cpt     = 0;
					$reviewpage_previous              = 0;
					$customeralliance_reviews_total   = count( $customeralliance_reviews->reviews->review );
					$customeralliance_reviews_maxpage = ceil( $customeralliance_reviews_total / $this->reviews_perpage );
					$output.='
					<div class="customeralliance-reviews" data-total="'. $customeralliance_reviews_total .'" data-maxpage="'. $customeralliance_reviews_maxpage .'">';



						foreach ( $customeralliance_reviews->reviews->review as $review ) {
							if ( empty( $review->author ) ) {
								$review->author = __('Anonymous', 'tmsm-customeralliance');
							}
							$customeralliance_reviews_cpt ++;
							$reviewdate = DateTime::createFromFormat( 'Y-m-d H:i:s', $review->date );
							$now        = new DateTime( 'now' );
							$interval   = $now->diff( $reviewdate );
							$days       = $interval->format( '%d' );
							$months     = $interval->format( '%m' );
							$years      = $interval->format( '%y' );
							$reviewpage = ceil( $customeralliance_reviews_cpt / $this->reviews_perpage );

							if ( $years > 0 ) {
								$reviewdateago = sprintf( __('%d year(s) ago', 'tmsm-customeralliance'), $years );
							} elseif ( $months > 0 ) {
								$reviewdateago = sprintf( __('%d month(s) ago', 'tmsm-customeralliance'), $months );
							} else {
								$reviewdateago = sprintf( __('%d day(s) ago', 'tmsm-customeralliance'), $days );
							}

							$reviewtype = ($review->reviewerType == 'business' ? __('Business traveller', 'tmsm-customeralliance'):__('Leisure traveller', 'tmsm-customeralliance'));

							$output.='

								<div class="customeralliance-reviews-item" id="customeralliance-reviews-item-'. $review->id .'" role="tablist" data-page="'. $reviewpage .'" '. ( $reviewpage != $reviewpage_previous ? 'id="customeralliance-reviews-page-' . $reviewpage . '"' : '' ) .'>
												<a class="customeralliance-reviews-item-heading" role="tab" id="customeralliance-reviews-item-heading-'. $review->id .' '.  ( ! empty( $review->yourComment ) ? 'has-reply' : '' ) .'" data-parent="#customeralliance-reviews-item-'. $review->id .'" role="button" data-toggle="collapse" href="#customeralliance-reviews-item-group-'. $review->id .'" aria-expanded="true" aria-controls="customeralliance-reviews-item-heading-'. $review->id .'">
													<span class="customeralliance-reviews-item-name" title="'. esc_attr( $review->author ) .'">'. $review->author .'</span>
													<span class="customeralliance-reviews-item-type">'.$reviewtype .'</span>
													<span class="customeralliance-reviews-item-age">'. sprintf( __('%d-%d years old', 'tmsm-customeralliance'), $review->reviewerAge, $review->reviewerAge + 9 ) .'</span>

													<span class="customeralliance-reviews-item-score" data-value="'. round( floatval( $review->overallRating ) * 20 ) .'">
												'. round( floatval( $review->overallRating ) * 20 ) .'%
											</span>
													<span class="customeralliance-reviews-item-comment">
												'. trim( $review->overallComment ) .'
											</span>
													<span class="customeralliance-reviews-item-time">
												'. $reviewdateago .'
											</span>';

													if ( ! empty( $review->yourComment ) ) {
														$output .= '
														<span class="customeralliance-reviews-item-reply">
														'. nl2br( $review->yourComment ) .'
												</span>';
													}
													$output .='
												</a>

										<div id="customeralliance-reviews-item-group-'. $review->id .'" class="customeralliance-reviews-item-group" role="tabpanel" aria-labelledby="customeralliance-reviews-item-heading-'. $review->id .'" aria-expanded="true">
											<ul class="accordion-inner list-group customeralliance-reviews-item-rating">';

												if ( ! empty( $review->subcategoryRatings->subcategoryRating ) ) {
													foreach ( $review->subcategoryRatings->subcategoryRating as $rating ) {
				$output .='
														<li class="list-group-item customeralliance-reviews-item-rating-item">
															<div class="customeralliance-reviews-item-rating-score">
																<p class="first-heading">'.$rating->category .'</p>
																	<div class="progress">
																		<div class="bar progress-bar" role="progressbar" aria-valuenow="'. floatval( $rating->rating ) * 20 .'" aria-valuemin="0" aria-valuemax="100" style="width:'. floatval( $rating->rating ) * 20 .'%">
																			<span class="value">'. $rating->rating .'
																				&nbsp;/&nbsp;5</span>
																		</div>
																	</div>
																	<div class="customeralliance-reviews-item-rating-value">
																		<span class="value">'. $rating->rating .'</span>&nbsp;<span class="max">/&nbsp;5</span>
																	</div>

															</div>
															<div class="customeralliance-reviews-item-rating-comment">
																<p class="last-paragraph">'. $rating->comment .'</p>
															</div>
														</li>
														';

													}
												}
												$output .='
											</ul>

											<div itemprop="review" itemscope="" itemtype="http://schema.org/Review" style="display: none">
												<span itemprop="itemReviewed">'. $customeralliance_reviews->business->name .'</span>
												<span itemprop="author">'. $review->author .'</span>
												<time itemprop="datePublished" datetime="'. $reviewdate->format( 'Y-m-d' ) .'">'. $reviewdate->format( 'r' ) .'></time>
												<span itemprop="reviewBody">'. trim( $review->overallComment ) .'</span>
												<span itemtype="http://schema.org/Rating" itemscope="itemscope" itemprop="reviewRating">
													<span itemprop="worstRating" content="0"></span>
													<span itemprop="bestRating" content="5"></span>
													<span itemprop="ratingValue" content="'. round( floatval( $review->overallRating ), 1 ) .'"></span>
												</span>
											</div>
										</div>
							</div>
							';

							$reviewpage_previous = $reviewpage;
						}
				$output .='
					</div>';
				}

				if ( $customeralliance_reviews_maxpage > 1 ) {
					$output .= '<p>
						<a id="customeralliance-reviews-btnmore" href="#customeralliance-reviews-page-2" data-hrefmodel="customeralliance-reviews-page-" data-page="2" data-maxpage="'. $customeralliance_reviews_maxpage .'">'. __('Read more…', 'tmsm-customeralliance') .'</a>
					</p>';
				}

				$output .='

			</div>

		</div>

	</div>

</div>';

		return $output;
	}

	/**
	 * Call Customer Alliance Web Service to get statistics
	 *
	 * @param $customer_id
	 * @param $access_key
	 * @param $lang
	 *
	 * @return SimpleXMLElement
	 */
	public function customeralliance_transient_stats($customer_id, $access_key, $lang) {

		$url = 'https://api.customer-alliance.com/statistics?id=' . $customer_id . '&access_key='. $access_key . '&_locale=' . $lang . '&language=' . $lang;

		$transient = get_transient( 'customeralliance_stats_' . $lang . '_' . $customer_id );
		if ( empty( $transient )) {
			$file = simplexml_load_file( $url );
			$transient = $file;
			set_transient( 'customeralliance_stats_' . $lang . '_' . $customer_id, $file->asXML(), $this->transient_cache );
		}

		$customeralliance_stats = simplexml_load_string( $transient );
		return $customeralliance_stats;
	}

	/**
	 * Call Customer Alliance Web Service to get statistics
	 *
	 * @param $customer_id
	 * @param $access_key
	 * @param $lang
	 *
	 * @return SimpleXMLElement
	 */
	public function customeralliance_transient_reviews($customer_id, $access_key, $lang) {

		$url = 'https://api.customer-alliance.com/reviews/list?&limit='.$this->reviews_limit.'&version='.$this->api_version.'&id=' . $customer_id . '&access_key='. $access_key . '&_locale=' . $lang . '&language=' . $lang;

		$transient = get_transient( 'customeralliance_reviews_' . $lang . '_' . $customer_id );
		if ( empty( $transient )) {
			$file = simplexml_load_file( $url );
			$transient = $file;
			set_transient( 'customeralliance_reviews_' . $lang . '_' . $customer_id, $file->asXML(), $this->transient_cache );
		}

		$customeralliance_reviews = simplexml_load_string( $transient );
		return $customeralliance_reviews;
	}

	/**
	 * Badge shortcode
	 *
	 * @since    1.0.3
	 */
	public function badge_shortcode($atts) {
		$atts = shortcode_atts( array(
			'id'         => '',
			'access_key' => '',
			'lang'       => 'fr',
			'theme'      => 'color',
			'bootstrap'  => '',
		), $atts, 'customeralliance-badge' );

		$customeralliance_stats = $this->customeralliance_transient_stats( $atts['id'], $atts['access_key'], $atts['lang'] );

		$customeralliance_reviews = $this->customeralliance_transient_reviews( $atts['id'], $atts['access_key'], $atts['lang'] );

		if ( ! empty( $atts['post_id'] ) ) {
			$link = get_permalink( $atts['post_id'] );
		} else {
			$link = '';
		}

		$output = '';
		if(!empty($customeralliance_stats)){
			$output = '<div class="customeralliance-badge customeralliance-badge-bootstrap-'.$atts['bootstrap'].'" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating"><meta itemprop="itemreviewed" content="'.esc_attr( $customeralliance_reviews->business->name ) .'"><meta itemprop="ratingCount" content="'.esc_attr( $customeralliance_reviews->business->reviewCount ).'"><meta itemprop="reviewCount" content="'. $customeralliance_stats->globalStatistics->reviewCount .'"><meta content="5" itemprop="bestRating"><meta content="0" itemprop="worstRating"><meta content="'. esc_attr(round( floatval( $customeralliance_stats->globalStatistics->averageRatingPercentage /20 ),1 ) ) .'" itemprop="ratingValue"><a href="'. $link .'" title="'. esc_attr(__('Read the customer reviews','tmsm-customeralliance')).'"><span class="customeralliance-title">'. __('Our Customer Satisfaction Index','tmsm-customeralliance') .'</span><span class="customeralliance-rating">'. round( floatval( $customeralliance_stats->globalStatistics->averageRatingPercentage ) ) .'%</span><span class="customeralliance-content">'. sprintf( '%d reviews <em>on %d portals</em>', $customeralliance_stats->globalStatistics->reviewCount, $customeralliance_stats->globalStatistics->portalCount ).'</span><span class="customeralliance-more">'. esc_attr(__('Read the customer reviews','tmsm-customeralliance')).'</span><span class="customeralliance-logo"><img width="191" height="161" src="'. plugin_dir_url( __FILE__ ) . 'img/customeralliance-logo-small-'.$atts['theme'].'.png" alt="'. __('Read the customer reviews','tmsm-customeralliance') .'"></span></a></div>';
		}
		return $output;
	}

}
