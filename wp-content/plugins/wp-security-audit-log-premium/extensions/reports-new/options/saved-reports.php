<?php
/**
 * Advanced settings of the plugin
 *
 * @package wsal
 *
 * @since 5.0.0
 */

use WSAL\Extensions\Views\Reports;
use WSAL\Helpers\Settings\Settings_Builder;
use WSAL\Reports\List_Generated_Reports;

	Settings_Builder::build_option(
		array(
			'title'         => esc_html__( 'All saved reports', 'wp-security-audit-log' ),
			'id'            => 'general-settings-tab',
			'type'          => 'tab-title',
			'settings_name' => Reports::GENERATE_REPORT_SETTINGS_NAME,
		)
	);

	$events_list = new List_Generated_Reports( \WSAL_Views_AuditLog::get_page_arguments() );
	$events_list->prepare_items();
	?>
		<style>
			#saved-reports-viewer-content {
				margin-left: 5px;
				margin-right: 5px;
			}
		</style>
		<div id="saved-reports-viewer-content">
			<?php
			echo '<div style="clear:both; float:right">';
			$events_list->search_box(
				__( 'Search', 'wp-security-audit-log' ),
				strtolower( $events_list::get_table_name() ) . '-find'
			);
			echo '</div>';
			$events_list->display();
			?>
		</div>
		<script>
			
			jQuery( ".wsal-options-tab-saved-reports, #wsal-options-tab-saved-reports" ).on( "activated", function() {
				jQuery( ".wsal-save-button").css('display', 'none');
				jQuery('.wsal-save-button').text('Save Changes');

				if (jQuery('#generate_report_tab_selected').length) {
					jQuery('#generate_report_tab_selected').val(0);
				}

				if (jQuery('#generate_statistic_report_tab_selected').length) {
					jQuery('#generate_statistic_report_tab_selected').val(0);
				}
			});
		</script>
