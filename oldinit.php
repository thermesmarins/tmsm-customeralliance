<?php


class CustomerAlliance_Shortcode {
	static $add_script = false;
	static $api_version = 4;
	static $transient_cache = 60 * 60 * 8;
	static $reviews_limit = 150;
	static $reviews_perpage = 10;
	static $customer_id;
	static $access_key;
	static $lang = 'fr';
	static $theme = 'color';
	static $bootstrapversion = 3;
	static $shortcode;
	static $post_id;

	static function init() {
		if(!is_admin()){
			add_shortcode( 'customeralliance-stats', array( __CLASS__, 'handle_shortcode_stats' ) );
			add_shortcode( 'customeralliance-badge', array( __CLASS__, 'handle_shortcode_badge' ) );
			add_shortcode( 'customeralliance-test', array( __CLASS__, 'handle_shortcode_test' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );
			add_action( 'plugins_loaded', array( __CLASS__, 'load_plugin_textdomain' ) );
		}
	}

	/**
	 * Languages
	 */
	function load_plugin_textdomain(){
		load_plugin_textdomain(
			'tmsm-customeralliance',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	static function update_xml() {

		$customeralliance_url_reviews    = 'https://api.customer-alliance.com/reviews/list?&limit=' . self::$reviews_limit . '&version='
		                                   . self::$api_version . '&id=' . self::$customer_id . '&access_key=' . self::$access_key . '&_locale='
		                                   . self::$lang . '&language=' . self::$lang;
		$customeralliance_url_statistics = 'https://api.customer-alliance.com/statistics?id=' . self::$customer_id . '&access_key='
		                                   . self::$access_key . '&_locale=' . self::$lang . '&language=' . self::$lang;


		$transient_reviews = get_transient( 'customeralliance_reviews' . self::$lang );
		if ( ! $transient_reviews ) {
			$customeralliance_reviews = simplexml_load_file( $customeralliance_url_reviews );
			set_transient( 'customeralliance_reviews_' . self::$lang . '_' . self::$customer_id, $customeralliance_reviews->asXML(),
				self::$transient_cache );
		}

		$transient_statistics = get_transient( 'customeralliance_stats_' . self::$lang . '_' . self::$customer_id );
		if ( ! $transient_statistics ) {
			$customeralliance_stats = simplexml_load_file( $customeralliance_url_statistics );
			set_transient( 'customeralliance_stats_' . self::$lang . '_' . self::$customer_id, $customeralliance_stats->asXML(),
				self::$transient_cache );
		}

		return true;
	}

	static function handle_shortcode_stats( $atts ) {
		self::$shortcode  = 'stats';
		self::$add_script = true;

		return self::handle_shortcode( $atts );
	}

	static function handle_shortcode_badge( $atts ) {
		self::$shortcode = 'badge';

		return self::handle_shortcode( $atts );
	}

	static function handle_shortcode( $atts ) {

		$atts = shortcode_atts( array(
			'id'         => '',
			'post_id'    => '',
			'access_key' => '',
			'lang'       => 'fr',
			'theme'      => 'color',
			'bootstrap'  => '3',
		), $atts );

		self::$customer_id      = esc_attr( $atts['id'] );
		self::$post_id          = esc_attr( $atts['post_id'] );
		self::$access_key       = esc_attr( $atts['access_key'] );
		self::$lang             = esc_attr( $atts['lang'] );
		self::$theme            = esc_attr( $atts['theme'] );
		self::$bootstrapversion = esc_attr( $atts['bootstrap'] );
		$customeralliance_lang  = esc_attr( $atts['lang'] );

		if ( ! empty( self::$post_id ) ) {
			$link = get_permalink( self::$post_id );
		} else {
			$link = '';
		}

		$customeralliance_config = [
			'id'               => self::$customer_id,
			'post_id'          => self::$post_id,
			'access_key'       => self::$access_key,
			'lang'             => self::$lang,
			'theme'            => self::$theme,
			'bootstrapversion' => self::$bootstrapversion,
			'reviewsperpage'   => self::$reviews_perpage,
			'reviewslimit'     => self::$reviews_limit,
			'bootstrap'        => [
				'3' => [
					'row' => 'row',
				],
				'2' => [
					'row' => 'row-fluid',
				],

			],
		];


		self::update_xml();

		if ( ! function_exists( 'simplexml_load_file' ) ) {
			return '<p>Erreur de récupération des flux XML (101)</p>';
			return __('','tmsm-customeralliance');
		}

		$transient_reviews        = get_transient( 'customeralliance_reviews_' . self::$lang . '_' . self::$customer_id );
		$customeralliance_reviews = simplexml_load_string( $transient_reviews );

		$transient_statistics   = get_transient( 'customeralliance_stats_' . self::$lang . '_' . self::$customer_id );
		$customeralliance_stats = simplexml_load_string( $transient_statistics );

		if ( ! $customeralliance_reviews || ! $customeralliance_stats ) {
			return '<p>Erreur de récupération des flux XML (102)</p>';
		}

		$customeralliance_labels = [
			'fr' => [
				'badge'             => 'Notre indice de satisfaction client',
				'error'             => 'Erreur de récupération des flux XML (103)',
				'reviewsnumber'     => sprintf( '%d avis <em>sur %d portail(s)</em>', $customeralliance_stats->globalStatistics->reviewCount,
					$customeralliance_stats->globalStatistics->portalCount ),
				'readreviews'       => 'Lire les avis clients',
				'readmore'          => 'Voir plus…',
				'header'            => 'Notre indice de satisfaction client',
				'about'             => 'Avis collectés indépendamment par Customer Alliance',
				'logo'              => 'Avis Clients Customer Alliance',
				'averagerating'     => 'Note moyenne',
				'categories'        => 'Critères d\'évaluation',
				'private'           => 'Loisirs',
				'business'          => 'Affaires',
				'reviewsmore'       => 'Afficher plus d\'avis',
				'age'               => '%d-%d ans',
				'days'              => 'il y a %d jour(s)',
				'months'            => 'il y a %d mois',
				'years'             => 'il y a %d an(s)',
				'anonymous'         => 'Anonyme',
				'certificate'       => 'Qu’est-ce que le Certificat Customer Alliance ?',
				'certificate_part1' => 'Customer Alliance est un fournisseur d’avis indépendant au service des entreprises. Nous les aidons à collecter des avis certifiés auprès de leurs clients. Ainsi, notre “Review Certificate” permet de créer une relation de confiance entre l’entreprise et vous - le client.',
				'certificate_part2' => '<b>Comment les avis sont-ils collectés ?</b><br/> Notre questionnaire est envoyé à tous les clients. Par conséquent, chaque avis que vous trouverez a été laissé par un véritable client et ne peut avoir été manipulé. Seuls les clients ayant été en lien avec l’entreprise peuvent laisser un avis.',
				'certificate_part3' => '<b>Qu’est ce que l’indice de satisfaction client ?</b><br/>L’indice de satisfaction client est basé sur une moyenne calculée à partir de tous les avis clients. Il montre dans quelle mesure les clients sont satisfaits des prestations de l\'entreprise, 100% étant la note la plus élevée.',
			],
			'en' => [
				'badge'             => 'Our Customer Satisfaction Index',
				'error'             => 'Error XML',
				'reviewsnumber'     => sprintf( '%d reviews <em>on %d portals</em>', $customeralliance_stats->globalStatistics->reviewCount,
					$customeralliance_stats->globalStatistics->portalCount ),
				'readreviews'       => 'Read the customer reviews',
				'readmore'          => 'Read more…',
				'header'            => 'Our Customer Satisfaction Index',
				'about'             => 'Independent reviews by Customer Alliance',
				'logo'              => 'Customer Alliance reviews',
				'averagerating'     => 'Average rating',
				'categories'        => 'Rating criterias',
				'private'           => 'Leisure traveller',
				'business'          => 'Business traveller',
				'reviewsmore'       => 'Read more reviews',
				'age'               => '%d-%d years old',
				'days'              => '%d day(s) ago',
				'months'            => '%d month(s) ago',
				'years'             => '%d year(s) ago',
				'anonymous'         => 'Anonymous',
				'certificate'       => 'What is Customer Alliance Certificate?',
				'certificate_part1' => 'Customer Alliance is an independent review provider for businesses, helping them collect authentic customer feedback. This Review Certificate bridges the trust gap between businesses and you – the customer.',
				'certificate_part2' => '<b>How does the review process work?</b><br/> Our questionnaire is sent to all customers; which means, every single review here was submitted by a real customer and was not manipulated.',
				'certificate_part3' => '<b>What is the Customer Satisfaction Index?</b><br/>The Customer Satisfaction Index offers an average rating based on all guest feedback. It shows you how happy the customers were with the business. 100% represents the highest possible score.',
			],
		];


		$content = '';
		if ( self::$shortcode == 'stats' ) {
			ob_start( null, 1 << 20 );
			include( 'template-stats.php' );
			$content = ob_get_clean();
		}

		if ( self::$shortcode == 'badge' ) {
			ob_start();
			include( 'template-badge.php' );
			$content = ob_get_clean();
		}

		return $content;
	}

	/**
	 * Load frontend JS
	 */
	function enqueue_scripts() {
		global $post;
		if ( has_shortcode( $post->post_content, 'customeralliance-stats' ) ) {
			//if (self::$add_script) {
			wp_enqueue_script( 'customeralliance-js', plugin_dir_url( __FILE__ ) . 'assets/js/customeralliance.js', array( 'jquery' ), null, true );
		}
	}

	/**
	 * Load frontend CSS
	 */
	function enqueue_styles() {
		wp_enqueue_style( 'customeralliance-css', plugin_dir_url( __FILE__ ) . 'assets/css/customeralliance.css', false, null );
	}

}
