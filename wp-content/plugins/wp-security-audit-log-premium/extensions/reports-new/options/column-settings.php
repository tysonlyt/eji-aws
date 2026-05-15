<?php
/**
 * Advanced settings of the plugin
 *
 * @package wsal
 *
 * @since 5.0.0
 */

use WSAL\Helpers\Settings_Helper;
use WSAL\Extensions\Views\Reports;
use WSAL\Helpers\Settings\Settings_Builder;

$columns          = Settings_Helper::get_option_value( Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME, array() );
$selected_columns = array();

if ( ! empty( $columns ) ) {
	foreach ( Reports::get_report_columns() as $column_name => $column_values ) {
		if ( isset( $columns[ $column_name ] ) ) {
			$selected_columns[ 'column_' . $column_name . '_enabled' ] = (bool) $columns[ $column_name ];

			unset( $columns[ $column_name ] );
		}
	}
}

$columns = array_merge( $columns, $selected_columns );

Settings_Builder::set_current_options( $columns );

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Generate Report Settings', 'wp-security-audit-log' ),
		'id'            => 'generate-report-settings-tab',
		'type'          => 'tab-title',
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Columns', 'wp-security-audit-log' ),
		'id'            => 'columns',
		'type'          => 'header',
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'text'          => esc_html__( 'Use the below setting to choose which metadata you want to include in your activity log reports.', 'wp-security-audit-log' ),
		'type'          => 'hint',
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

foreach ( Reports::generate_report_columns_header( true ) as $column_name => $column_values ) {

	// Column with no "default" value are part of the statistic reports only - so don't show them.
	if ( isset( $column_values['default'] ) ) {
		Settings_Builder::build_option(
			array(
				'name'          => $column_values['name'],
				'id'            => 'column_' . $column_name . '_enabled',
				'type'          => 'checkbox',
				'default'       => (bool) $column_values['default'],
				'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
			)
		);
	}
}

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Cron job', 'wp-security-audit-log' ),
		'id'            => 'cronjob',
		'type'          => 'header',
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Cron records to process per job', 'wp-security-audit-log' ),
		'id'            => 'cron_records_to_process',
		'type'          => 'number',
		'min'           => 10,
		'max'           => 10000,
		'default'       => (int) 500,
		'hint'          =>
			\esc_html__( 'Use the below setting to configure the number of records that should be processed per call during the report generation process. The higher the number is, the faster the reports are generated, however, the more resources they consume. If you have not encountered any issues with reports generation, it is recommended to leave the default setting, 500 records.', 'wp-security-audit-log' ),
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Generated reports auto purge', 'wp-security-audit-log' ),
		'id'            => 'reports_auto_purge',
		'type'          => 'header',
		'hint'          => sprintf(
			/* translators: path to the reports' storage directory */
			esc_html__( 'Reports are saved in the plugin\'s directory in the upload folder. The directory name and path are below. By default the plugin deletes reports that are older than 30 days. Use the settings below to change this behavior.', 'wp-security-audit-log' ),
			Settings_Helper::get_working_dir_path_static( 'reports', true )
		),
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Reports directory', 'wp-security-audit-log' ),
		'type'          => 'informational',
		'info'          => Settings_Helper::get_working_dir_path_static( 'reports', true ),
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Enable reports auto purge', 'wp-security-audit-log' ),
		'id'            => 'reports_auto_purge_enabled',
		'toggle'        => '#reports_auto_purge_older_than_days-item',
		'type'          => 'checkbox',
		'default'       => true,
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Delete reports older than (days)', 'wp-security-audit-log' ),
		'id'            => 'reports_auto_purge_older_than_days',
		'type'          => 'number',
		'min'           => 10,
		'max'           => 180,
		'default'       => (int) 30,
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

// Settings_Builder::build_option(
// array(
// 'title'         => esc_html__( 'At what time to send the reports', 'wp-security-audit-log' ),
// 'id'            => 'report_send_time_option',
// 'type'          => 'header',
// 'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
// )
// );

// Settings_Builder::build_option(
// array(
// 'name'          => esc_html__( 'Time', 'wp-security-audit-log' ),
// 'id'            => 'report_send_time',
// 'type'          => 'text',
// 'validate'      => 'time',
// 'step'          => 3600,
// 'default'       => '08:00',
// 'hint'          => 'By default periodic reports are sent at 8:00AM on the first day of the period\'s termination. ',
// 'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
// )
// );


Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Periodic reports email summary', 'wp-security-audit-log' ),
		'id'            => 'reports_email_summary',
		'type'          => 'header',

		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__(
			'Send empty summary emails ',
			'wp-security-audit-log'
		),
		'id'            => 'reports_send_empty_summary_emails',
		'id'            => 'reports_send_empty_summary_emails',
		'type'          => 'checkbox',
		'default'       => false,
		'hint'          => esc_html__( 'Do you want to receive an email even if there are no event IDs that match the criteria for the periodic reports? ', 'wp-security-audit-log' ),
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__(
			'Send reports as attachments ',
			'wp-security-audit-log'
		),
		'id'            => 'reports_send_reports_attachments_emails',
		'type'          => 'checkbox',
		'default'       => true,
		'hint'          => esc_html__( 'Enable this setting to send the generated reports as email attachments. Please note that if the report file size is very big the email can be blocked or might not be sent.', 'wp-security-audit-log' ),
		'settings_name' => Reports::REPORT_GENERATE_COLUMNS_SETTINGS_NAME,
	)
);

?>
<script>
	jQuery( ".wsal-options-tab-column-settings, #wsal-options-tab-column-settings" ).on( "activated", function() {
		jQuery( ".wsal-save-button").css('display', 'block');
		jQuery('.wsal-save-button').text('Save Changes');

		if (jQuery('#generate_report_tab_selected').length) {
			jQuery('#generate_report_tab_selected').val(0);
		}
	});
</script>
