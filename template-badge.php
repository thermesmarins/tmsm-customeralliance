<div class="customeralliance-badge customeralliance-badge-bootstrap-<?= $customeralliance_config['bootstrapversion'] ?>" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">

	<meta itemprop="itemreviewed" content="<?= esc_attr( $customeralliance_xml_reviews->business->name ) ?>">
	<meta itemprop="ratingCount" content="<?= esc_attr( $customeralliance_xml_reviews->business->reviewCount ) ?>">
	<meta itemprop="reviewCount" content="<?= $customeralliance_xml_statistics->globalStatistics->reviewCount ?>">
	<meta content="5" itemprop="bestRating">
	<meta content="0" itemprop="worstRating">
	<meta content="<?= esc_attr(round( floatval( $customeralliance_xml_statistics->globalStatistics->averageRatingPercentage /20 ),1 ) ) ?>" itemprop="ratingValue">

	<a href="<?= $link ?>" title="<?= esc_attr($customeralliance_labels[$customeralliance_lang]['readreviews'])?>">
		<span class="customeralliance-title">
			<?= $customeralliance_labels[$customeralliance_lang]['badge'] ?>
		</span>
		<span class="customeralliance-rating">
			<?= round( floatval( $customeralliance_xml_statistics->globalStatistics->averageRatingPercentage ) ) ?>%
		</span>
		<span class="customeralliance-content">
			<?= $customeralliance_labels[$customeralliance_lang]['reviewsnumber'] ?>
		</span>

		<span class="customeralliance-more"><?= $customeralliance_labels[$customeralliance_lang]['readreviews']?></span>
		<span class="customeralliance-logo">
			<img src="<?= plugin_dir_url( __FILE__ ) . 'assets/img/customeralliance-logo-small-'.$customeralliance_config['theme'].'.png' ?>" alt="<?= $customeralliance_labels[ $customeralliance_lang ]['logo'] ?>">
		</span>

	</a>

</div>