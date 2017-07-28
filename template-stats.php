<div class="customeralliance-stats customeralliance-stats-bootstrap-<?= $customeralliance_config['bootstrapversion'] ?>">

	<div class="hero-unit jumbotron">
		<div class="customeralliance-intro">
			<div class="<?= $customeralliance_config['bootstrap'][ $customeralliance_config['bootstrapversion'] ]['row'] ?>">

				<div class="span3 col-sm-3">
					<div class="customeralliance-logo">
						<img src="<?= plugin_dir_url( __FILE__ ) . 'assets/img/customeralliance-logo-big-'.$customeralliance_config['theme'].'.png' ?>" alt="<?= $customeralliance_labels[ $customeralliance_lang ]['logo'] ?>">
					</div>
				</div>

				<div class="span8 offset1 col-sm-8 col-sm-offset-1">
					<div class="customeralliance-about">
						<h3 class="first-heading">
							<?= $customeralliance_labels[ $customeralliance_lang ]['about'] ?>
						</h3>
						<p class="last-paragraph">
							<a role="button" data-toggle="collapse" href="#whatiscustomeralliance" aria-expanded="false" aria-controls="whatiscustomeralliance" class="customeralliance-certificate-btn"><span class="glyphicon glyphicon-info-sign"></span><?= $customeralliance_labels[ $customeralliance_lang ]['certificate'] ?></a>
						</p>
						<div id="whatiscustomeralliance" class="customeralliance-certificate-content collapse">
							<p><?= $customeralliance_labels[ $customeralliance_lang ]['certificate_part1'] ?></p>
							<p><?= $customeralliance_labels[ $customeralliance_lang ]['certificate_part2'] ?></p>
							<p class="last-paragraph"><?= $customeralliance_labels[ $customeralliance_lang ]['certificate_part3'] ?></p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>


	<div class="<?= $customeralliance_config['bootstrap'][ $customeralliance_config['bootstrapversion'] ]['row'] ?>">
		<div itemscope="" itemtype="http://schema.org/Hotel">

			<div class="span4 col-sm-4">

				<div class="customeralliance-rating">
					<h2>
						<?= $customeralliance_labels[ $customeralliance_lang ]['header'] ?>
					</h2>
					<div class="customeralliance-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
						<meta itemprop="itemreviewed" content="<?= esc_attr( $customeralliance_reviews->business->name ) ?>">
						<meta itemprop="ratingCount" content="<?= esc_attr( $customeralliance_reviews->business->reviewCount ) ?>">
						<meta itemprop="reviewCount" content="<?= $customeralliance_stats->globalStatistics->reviewCount ?>">
						<div class="customeralliance-global-rating">
							<meta content="5" itemprop="bestRating">
							<meta content="0" itemprop="worstRating">
							<meta content="<?= esc_attr(round( floatval( $customeralliance_stats->globalStatistics->averageRatingPercentage /20 ),1 ) ) ?>" itemprop="ratingValue">
							<span><?= round( floatval( $customeralliance_stats->globalStatistics->averageRatingPercentage ) ) ?></span>%
						</div>
						<p>
							<?= $customeralliance_labels[ $customeralliance_lang ]['reviewsnumber'] ?>
						</p>
					</div>
				</div>

				<div class="customeralliance-portals">
					<h3><?= $customeralliance_labels[ $customeralliance_lang ]['averagerating'] ?></h3>
					<div class="inside">
						<?php if ( ! empty( $customeralliance_stats->portalStatistics->portal ) ) {
							foreach ( $customeralliance_stats->portalStatistics->portal as $portal ) { ?>
								<div class="customeralliance-portals-item">
									<span title="<?= esc_attr( $portal->name ) ?>"><?= $portal->name ?></span>
									<strong><?= round( $portal->averageRatingPercentage ) ?>%</strong>
								</div>

							<?php }
						}
						?>

					</div>
				</div>

				<div class="customeralliance-categories">
					<h3><?= $customeralliance_labels[ $customeralliance_lang ]['categories'] ?></h3>
					<div class="inside">
						<?php if ( ! empty( $customeralliance_stats->globalStatistics->ratings->category ) ) {
							foreach ( $customeralliance_stats->globalStatistics->ratings->category as $category ) {
								?>
								<div class="customeralliance-categories-item">
									<div class="category-rating">
										<span class="value"><?= $category->averageRating ?></span>&nbsp;<span class="max">/&nbsp;5</span>
									</div>
									<span class="category-name">
										<?= $category->label ?>
									</span>
									<div class="progress">
										<div class="bar progress-bar" role="progressbar" aria-valuenow="<?= esc_attr( $category->averageRatingPercentage ) ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $category->averageRatingPercentage ?>%">
											<span class="value"><?= round( floatval( $category->averageRating ), 1 ) ?>
												&nbsp;/&nbsp;5</span>
										</div>
									</div>
								</div>

							<?php }
						}
						?>

					</div>
				</div>


			</div>
			<div class="span8 col-sm-8">

				<?php if ( ! empty( $customeralliance_reviews->reviews->review ) ) {
					$customeralliance_reviews_cpt     = 0;
					$reviewpage_previous              = 0;
					$customeralliance_reviews_total   = count( $customeralliance_reviews->reviews->review );
					$customeralliance_reviews_maxpage = ceil( $customeralliance_reviews_total / $customeralliance_config['reviewsperpage'] );
					?>
					<div class="customeralliance-reviews" data-total="<?= $customeralliance_reviews_total ?>" data-maxpage="<?= $customeralliance_reviews_maxpage ?>">

						<?php

						foreach ( $customeralliance_reviews->reviews->review as $review ) {
							if ( empty( $review->author ) ) {
								$review->author = $customeralliance_labels[ $customeralliance_lang ]['anonymous'];
							}
							$customeralliance_reviews_cpt ++;
							$reviewdate = DateTime::createFromFormat( 'Y-m-d H:i:s', $review->date );
							$now        = new DateTime( 'now' );
							$interval   = $now->diff( $reviewdate );
							$days       = $interval->format( '%d' );
							$months     = $interval->format( '%m' );
							$years      = $interval->format( '%y' );
							$reviewpage = ceil( $customeralliance_reviews_cpt / $customeralliance_config['reviewsperpage'] );

							if ( $years > 0 ) {
								$reviewdateago = sprintf( $customeralliance_labels[ $customeralliance_lang ]['years'], $years );
							} elseif ( $months > 0 ) {
								$reviewdateago = sprintf( $customeralliance_labels[ $customeralliance_lang ]['months'], $months );
							} else {
								$reviewdateago = sprintf( $customeralliance_labels[ $customeralliance_lang ]['days'], $days );
							}
							?>

							<div class="accordion">

								<div class="accordion-group panel-group customeralliance-reviews-item customeralliance-reviews-item-<?= $review->id ?>" role="tablist" data-page="<?= $reviewpage ?>" <?= ( $reviewpage != $reviewpage_previous ? 'id="customeralliance-reviews-page-' . $reviewpage . '"' : '' ) ?>>
									<div class="panel panel-default">
										<div class="accordion-heading panel-heading" role="tab" id="customeralliance-reviews-item-heading-<?= $review->id ?> <?= ( ! empty( $review->yourComment ) ? 'has-reply' : '' ) ?>">
											<p class="panel-title">
												<a class="accordion-toggle collapsed" data-parent="#customeralliance-reviews-item-<?= $review->id ?>" role="button" data-toggle="collapse" href="#customeralliance-reviews-item-group-<?= $review->id ?>" aria-expanded="true" aria-controls="customeralliance-reviews-item-heading-<?= $review->id ?>">
													<span class="customeralliance-reviews-item-name" title="<?= esc_attr( $review->author ) ?>"><?= $review->author ?></span>
													<span class="customeralliance-reviews-item-type"><?= $customeralliance_labels[ $customeralliance_lang ][ trim( $review->reviewerType ) ] ?></span>
													<span class="customeralliance-reviews-item-age"><?= sprintf( $customeralliance_labels[ $customeralliance_lang ]['age'], $review->reviewerAge, $review->reviewerAge + 9 ) ?></span>

													<span class="customeralliance-reviews-item-score" data-value="<?= round( floatval( $review->overallRating ) * 20 ) ?>">
												<?= round( floatval( $review->overallRating ) * 20 ) ?>%
											</span>
													<span class="customeralliance-reviews-item-comment">
												<?= trim( $review->overallComment ) ?>
											</span>
													<span class="customeralliance-reviews-item-time">
												<?= $reviewdateago ?>
											</span>
													<?php if ( ! empty( $review->yourComment ) ) { ?>
														<span class="customeralliance-reviews-item-reply">
													<?= nl2br( $review->yourComment ) ?>
												</span>
													<?php } ?>
												</a>
											</p>

										</div>
										<div id="customeralliance-reviews-item-group-<?= $review->id ?>" class="accordion-body panel-collapse collapse" role="tabpanel" aria-labelledby="customeralliance-reviews-item-heading-<?= $review->id ?>" aria-expanded="true">
											<ul class="accordion-inner list-group customeralliance-reviews-item-rating">

												<?php if ( ! empty( $review->subcategoryRatings->subcategoryRating ) ) {
													foreach ( $review->subcategoryRatings->subcategoryRating as $rating ) {
														?>
														<li class="list-group-item customeralliance-reviews-item-rating-item">
															<div class="<?= $customeralliance_config['bootstrap'][ $customeralliance_config['bootstrapversion'] ]['row'] ?>">
																<div class="span4 col-sm-4">
																	<div class="customeralliance-reviews-item-rating-score">
																		<p class="first-heading"><?= $rating->category ?></p>
																		<div class="progress">
																			<div class="bar progress-bar" role="progressbar" aria-valuenow="<?= floatval( $rating->rating ) * 20 ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= floatval( $rating->rating ) * 20 ?>%">
																				<span class="value"><?= $rating->rating ?>
																					&nbsp;/&nbsp;5</span>
																			</div>
																		</div>
																		<div class="customeralliance-reviews-item-rating-value">
																			<span class="value"><?= $rating->rating ?></span>&nbsp;<span class="max">/&nbsp;5</span>
																		</div>
																	</div>
																</div>
																<div class="span8 col-sm-8">
																	<div class="customeralliance-reviews-item-rating-comment">
																		<p class="last-paragraph"><?= $rating->comment ?></p>
																	</div>
																</div>
															</div>
														</li>

														<?php
													}
												}
												?>
											</ul>

											<div itemprop="review" itemscope="" itemtype="http://schema.org/Review" class="hidden">
												<span itemprop="itemReviewed"><?= $customeralliance_reviews->business->name ?></span>
												<span itemprop="author"><?= $review->author ?></span>
												<time itemprop="datePublished" datetime="<?= $reviewdate->format( 'Y-m-d' ) ?>"><?= $reviewdate->format( 'r' ) ?></time>
												<span itemprop="reviewBody"><?= trim( $review->overallComment ) ?></span>
												<span itemtype="http://schema.org/Rating" itemscope="itemscope" itemprop="reviewRating">
													<span itemprop="worstRating" content="0"></span>
													<span itemprop="bestRating" content="5"></span>
													<span itemprop="ratingValue" content="<?= round( floatval( $review->overallRating ), 1 ) ?>"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php
							$reviewpage_previous = $reviewpage;
						} ?>

					</div>
				<?php } ?>

				<?php if ( $customeralliance_reviews_maxpage > 1 ) { ?>
					<p>
						<a id="customeralliance-reviews-btnmore" href="#customeralliance-reviews-page-2" data-hrefmodel="customeralliance-reviews-page-" data-page="2" data-maxpage="<?= $customeralliance_reviews_maxpage ?>" class="btn btn-default btn-block"><?= $customeralliance_labels[ $customeralliance_lang ]['reviewsmore'] ?></a>
					</p>
				<?php } ?>

			</div>

		</div>

	</div>

</div>