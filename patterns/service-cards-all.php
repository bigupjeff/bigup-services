<?php
/**
 * Pattern
 *
 * Service Cards All
 *
 */

$strings = array(
	'button' => __( 'Learn More', 'bigup-services' ),
);

$markup = <<<END
<!-- wp:query {"queryId":4,"query":{"postType":"service","order":"asc","sortByOrder":true,"perPage":"100","inherit":false},"namespace":"service-query-loop","className":"queryLoopCards-coloured","layout":{"type":"constrained"}} -->
<div class="wp-block-query queryLoopCards-coloured">
	<!-- wp:group {"align":"wide","className":"has-overflow-hidden has-border-radius-medium has-shadow-soft","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide has-overflow-hidden has-border-radius-medium has-shadow-soft">
		<!-- wp:post-template {"align":"wide","className":"queryLoopCards-coloured","layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"dimensions":{"minHeight":"100%"},"elements":{"link":{"color":{"text":"var:preset|color|bur-fg"}}}},"textColor":"bur-fg","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group has-bur-fg-color has-text-color has-link-color" style="min-height:100%;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
			<!-- wp:post-featured-image {"aspectRatio":"auto","height":"14rem","style":{"layout":{"selfStretch":"fit","flexSize":null}},"className":"gs_reveal"} /-->
			<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|40"}},"layout":{"selfStretch":"fill","flexSize":null},"elements":{"link":{"color":{"text":"var:preset|color|bur-fg-alt"}}}},"textColor":"bur-fg-alt","className":"gs_reveal","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
			<div class="wp-block-group gs_reveal has-bur-fg-alt-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
				<div class="wp-block-group">
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
					<div class="wp-block-group">
						<!-- wp:bigup-services/service-icon /-->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50","padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
						<!-- wp:post-title {"textAlign":"center","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"xx-large"} /-->
						<!-- wp:post-excerpt {"showMoreOnNewLine":false,"excerptLength":40} /-->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"style":{"color":{"duotone":["#094850","#f9644e"]},"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|40","bottom":"var:preset|spacing|50"},"blockGap":"0","margin":{"top":"0","bottom":"0"}},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
			<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:read-more {"content":"{$strings['button']}","style":{"elements":{"link":{"color":{"text":"var:preset|color|bur-fg-alt"}}}},"backgroundColor":"bur-bg-alt","textColor":"bur-fg-alt","className":"wp-block-button__link"} /-->
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
	'title'       => __( 'Service Cards All', 'bigup-services' ),
	'description' => _x( 'Display all services as cards', 'Block pattern description', 'bigup-services' ),
	'categories'  => array( 'bigupweb-services' ),
	'keywords'    => array( 'services', 'section' ),
	'content'     => $markup,
);