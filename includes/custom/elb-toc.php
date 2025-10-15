<?php
/**
 * Liveblog Table of Contents (TOC) functionality
 * 
 * Displays a TOC above liveblog entries showing the 10 latest entries
 * with their times and titles.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the Table of Contents for liveblog entries
 *
 * @param int $liveblog_id The current liveblog post ID
 * @param array $args Additional arguments (not used currently)
 * @return void
 */
function elb_render_toc( $liveblog_id, $args = array() ) {
	// Only render on actual liveblog posts
	if ( ! elb_is_liveblog() ) {
		return;
	}

	// Get the 10 latest entries for this liveblog
	$entries_args = array(
		'post_type'      => 'elb_entry',
		'posts_per_page' => 10,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_key'       => '_elb_liveblog',
		'meta_value'     => $liveblog_id,
	);

	$entries = get_posts( apply_filters( 'elb_toc_entries_args', $entries_args, $liveblog_id ) );

	// Don't display TOC if there are no entries
	if ( empty( $entries ) ) {
		return;
	}

	// Start TOC output with inline styles
	?>
	<style>
		.elb-toc {
			margin: 20px 0;
			padding: 20px;
			background: #f9f9f9;
			border: 1px solid #e0e0e0;
			border-radius: 4px;
		}
		.elb-toc-title {
			margin: 0 0 15px 0;
			font-size: 18px;
			font-weight: bold;
			color: #1F5772;
		}
		.elb-toc-list {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		.elb-toc-item {
			margin: 0 0 10px 0;
			padding: 0;
		}
		.elb-toc-item:last-child {
			margin-bottom: 0;
		}
		.elb-toc-time {
			display: block;
			font-size: 12px;
			font-weight: bold;
			color: #000000;
			margin: 0 0 3px 0;
		}
		.elb-toc-link {
			color: #1F5772;
			text-decoration: none;
		}
		.elb-toc-link:hover {
			text-decoration: underline;
		}
	</style>

	<div class="elb-toc">
		<h3 class="elb-toc-title"><?php echo esc_html( apply_filters( 'elb_toc_title', __( 'Με μια Ματιά', ELB_TEXT_DOMAIN ) ) ); ?></h3>
		<ul class="elb-toc-list">
			<?php foreach ( $entries as $entry ) : ?>
				<?php
				// Get entry time formatted according to site settings
				// Use datetime format to show both date and time for clarity
				$datetime_format = elb_get_datetime_format();
				$entry_time = get_the_time( $datetime_format, $entry );
				
				// Build the anchor link using the existing helper function
				$entry_url = elb_get_entry_url( $entry );
				?>
				<li class="elb-toc-item">
					<span class="elb-toc-time"><?php echo esc_html( $entry_time ); ?></span>
					<a href="<?php echo esc_url( $entry_url ); ?>" class="elb-toc-link">
						<?php echo esc_html( $entry->post_title ? $entry->post_title : __( '(No title)', ELB_TEXT_DOMAIN ) ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

// Hook into elb_before_liveblog to display TOC before entries
add_action( 'elb_before_liveblog', 'elb_render_toc', 10, 2 );
