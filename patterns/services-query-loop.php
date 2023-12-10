<?php
/**
 * Pattern
 *
 * Services Query Loop
 *
 */

$strings = array(
	'button' => __( 'Book Appointment', 'bigup-cpt-service' ),
);

$markup = <<<END
<!-- wp:query {"queryId":10,"query":{"perPage":"3","pages":0,"offset":0,"postType":"service","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"parents":[]},"layout":{"type":"constrained"}} -->
<div class="wp-block-query">
	<!-- wp:group {"align":"wide","className":"has-overflow-hidden has-border-radius-medium","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide has-overflow-hidden has-border-radius-medium">
		<!-- wp:post-template {"align":"wide","style":{"spacing":{"blockGap":"0"}},"className":"queryLoopCards-coloured","layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"dimensions":{"minHeight":"100%"},"elements":{"link":{"color":{"text":"var:preset|color|bur-fg"}}}},"textColor":"bur-fg","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group has-bur-fg-color has-text-color has-link-color" style="min-height:100%;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
			<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}},"layout":{"selfStretch":"fill","flexSize":null},"elements":{"link":{"color":{"text":"var:preset|color|bur-fg-alt"}}}},"textColor":"bur-fg-alt","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
			<div class="wp-block-group has-bur-fg-alt-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
					<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"right":"0","left":"0","top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
					<div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
						<!-- wp:bigupweb/service-icon /-->
					</div>
					<!-- /wp:group -->
					<!-- wp:post-title {"level":3,"fontSize":"xx-large"} /-->
					<!-- wp:post-excerpt {"showMoreOnNewLine":false,"excerptLength":30,"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"0","right":"0"}}}} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"style":{"color":{"duotone":["#094850","#f9644e"]},"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"blockGap":"0","margin":{"top":"0","bottom":"0"}},"layout":{"selfStretch":"fit","flexSize":null}},"backgroundColor":"bur-bg-alt","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group has-bur-bg-alt-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"bur-accent"} -->
					<div class="wp-block-button">
						<a class="wp-block-button__link has-bur-accent-background-color has-background wp-element-button">Book Appointment</a>
					</div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:query -->
END;

return array(
	'title'       => __( 'Services Query Loop', 'bigup-cpt-service' ),
	'description' => _x( 'Display services in a section of wrapping-rows', 'Block pattern description', 'bigup-cpt-service' ),
	'categories'  => array( 'bigupweb-services' ),
	'keywords'    => array( 'services', 'section' ),
	'content'     => $markup,
);