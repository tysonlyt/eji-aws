<?php

use BetterFrameworkPackage\Component\Control as LibRoot;

/**
 * @var array $configs
 */
?>
<script type="text/html" id="tmpl-bs-pro-feature">

	<div class="bs-pro-feature-modal bs-modal-state-lock {{#video_url}} have-video {{/video_url}} {{{ modal_id }}}">

		<a href="#" class="bs-close-modal"></a>

		<div class="bs-pro-feature-content">
			<div class="bs-pro-feature-text">

				<div class="bs-pro-feature-header">
					<div class="bs-pro-feature-icon lock">
						<?php \BetterFrameworkPackage\Component\Control\print_icon( 'dashicons-lock' ); ?>
					</div>

					<div class="bs-pro-feature-icon unlock">
						<?php \BetterFrameworkPackage\Component\Control\print_icon( 'dashicons-unlock' ); ?>
					</div>
				</div>

				<h4 class="label">{{{ title }}}</h4>

				<div class="desc">
					{{{ desc }}}
				</div>

				<div class="action-buttons">
					<a class="button button-primary" href="{{button_url}}" target="_blank">{{ button_text }}</a>
				</div>

				<div class="action-buttons">
					<a class="purchased_text" href="{{ purchased_url }}" target="_blank">{{ purchased_text }}</a>
				</div>

			</div>

			{{#video_url}}
			<div class="bs-pro-feature-modal-video">
				<iframe width="400" height="224" src="https://www.youtube.com/embed/{{video_id}}"
				        title="YouTube video player"
				        frameborder="0"
				        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				        allowfullscreen></iframe>
			</div>
			{{/video_url}}
		</div>

		<div class="bs-pro-feature-footer">
			<div class="bs-pro-feature-icon">
            <span class="bf-icon bf-icon-svg">
                <svg class="bf-svg-tag" xmlns="http://www.w3.org/2000/svg" width="28.874" height="39.441"
                     viewBox="0 0 28.874 39.441">
                    <g id="noun-discount-3215831" transform="translate(0)">
                        <path id="Path_902" data-name="Path 902"
                              d="M183.991,373.916l-2.32,9.017a.621.621,0,0,1-.545.459h-.049a.611.611,0,0,1-.557-.361l-2.473-5.558-5.681,2.149a.613.613,0,0,1-.741-.887l4.848-8.129c.092.006.184.012.276.012.061,0,.251-.006.288-.012l.037.055.012.006a3.77,3.77,0,0,0,3.557,2.54,3.83,3.83,0,0,0,1.677-.392.092.092,0,0,1,.049.012l.025.03c.086.086.165.171.276.282a3.784,3.784,0,0,0,1.323.777Z"
                              transform="translate(-170.756 -343.951)" fill="#00a32a"/>
                        <path id="Path_903" data-name="Path 903"
                              d="M378.029,379.427a.613.613,0,0,1-.686.184l-5.681-2.149-2.473,5.558a.611.611,0,0,1-.557.361h-.049a.621.621,0,0,1-.545-.459l-2.32-9.023a3.88,3.88,0,0,0,1.341-.79l.049-.037.037-.049c.043-.043.086-.086.129-.135l.074-.074a.178.178,0,0,1,.061-.012,3.7,3.7,0,0,0,1.659.392,3.8,3.8,0,0,0,3.581-2.583l.061-.012c.074,0,.153.006.227.006.1,0,.2-.006.3-.012l4.848,8.129a.61.61,0,0,1-.055.7Z"
                              transform="translate(-350.078 -343.941)" fill="#00a32a"/>
                        <path id="Path_904" data-name="Path 904"
                              d="M189.007,33.336a2.528,2.528,0,0,0-1.432-3.55c-1.5-.5-.263-1.906-1.6-3.263a2.542,2.542,0,0,0-1.916-.741.879.879,0,0,0-.122.006c-1.547,0-.875-1.663-2.651-2.406a2.6,2.6,0,0,0-2.081.067,1.293,1.293,0,0,1-1.549-.312,2.523,2.523,0,0,0-3.789,0c-.976.976-1.542.055-2.663.055a2.523,2.523,0,0,0-2.394,1.714c-.5,1.512-1.893.246-3.257,1.61a2.515,2.515,0,0,0-.735,1.916c0,1.728-1.65,1-2.394,2.779a2.614,2.614,0,0,0,.067,2.088c.7,1.437-1.169,1.534-1.169,3.453a2.542,2.542,0,0,0,.9,1.928c.909.909.012,1.6.012,2.638a2.54,2.54,0,0,0,1.7,2.394c1.523.519.251,1.9,1.616,3.269,1.234,1.234,1.943.382,2.755.955a1.253,1.253,0,0,1,.5.661,2.53,2.53,0,0,0,3.514,1.451,1.309,1.309,0,0,1,1.506.269c.025.037.2.213.233.245a2.543,2.543,0,0,0,3.624-.245,1.343,1.343,0,0,1,1.512-.269,2.528,2.528,0,0,0,3.514-1.457c.488-1.5,1.907-.26,3.257-1.61,1.389-1.389.078-2.752,1.61-3.269a2.528,2.528,0,0,0,1.42-3.569,1.322,1.322,0,0,1,.3-1.457,2.535,2.535,0,0,0,.9-1.934c0-1.883-1.827-1.984-1.187-3.416ZM175.757,46.96a10.21,10.21,0,1,1,10.187-10.21A10.21,10.21,0,0,1,175.757,46.96Z"
                              transform="translate(-161.32 -22.281)" fill="#00a32a"/>
                        <path id="Path_905" data-name="Path 905"
                              d="M301.155,160.672c0,.615-.375,1.114-.839,1.114s-.839-.5-.839-1.114.375-1.114.839-1.114.839.5.839,1.114"
                              transform="translate(-288.906 -149.054)" fill="#00a32a"/>
                        <path id="Path_906" data-name="Path 906"
                              d="M379.441,234.04c-.618,0-.838.576-.838,1.114s.22,1.12.838,1.12c.778,0,.839-.858.839-1.12S380.22,234.04,379.441,234.04Z"
                              transform="translate(-361.977 -217.836)" fill="#00a32a"/>
                        <path id="Path_907" data-name="Path 907"
                              d="M241.808,93.957a8.986,8.986,0,1,0,8.962,8.986A8.984,8.984,0,0,0,241.808,93.957Zm-5.2,6.134a2.159,2.159,0,0,1,2.167-2.381,2.392,2.392,0,1,1-2.167,2.381Zm3.342,7.045a.68.68,0,0,1-.568.33.64.64,0,0,1-.439-.181.626.626,0,0,1-.1-.79l4.847-7.771a.64.64,0,0,1,.555-.307.682.682,0,0,1,.435.154.637.637,0,0,1,.079.843Zm4.879,1.035a2.393,2.393,0,0,1,0-4.763A2.16,2.16,0,0,1,247,105.79,2.138,2.138,0,0,1,244.832,108.171Z"
                              transform="translate(-227.367 -88.472)" fill="#00a32a"/>
                    </g>
                </svg>
            </span>
			</div>

			{{{ discount_desc }}}
		</div>
	</div>
</script>

<script type="text/html" id="tmpl-bs-pro-feature-after">

	<div class="bs-pro-feature-modal bs-pro-feature-interested {{{ modal_id }}}">

		<a href="#" class="bs-close-modal"></a>

		<div class="bs-pro-feature-content">
			<div class="bs-pro-feature-text">

				<div class="bs-pro-feature-header">

					<div class="bs-pro-feature-icon info">
						<?php \BetterFrameworkPackage\Component\Control\print_icon( 'bsfi-info' ); ?>
					</div>
				</div>

				<h4 class="label">{{{ interested_title }}}</h4>

				<div class="desc desc-bold">
					{{{ interested_p1 }}}
				</div>

				<div class="desc">
					{{{ interested_p2 }}}
				</div>

				<div class="desc">
					{{{ interested_p3 }}}
				</div>

				<a href="#" class="bs-close-modal"></a>

			</div>

		</div>
	</div>
</script>

<?php
foreach ( $configs as $config ) {
	$config['template']['modal_id'] = $config['id']; // for template usage
	?>
	<script type="application/json" id="cnf-bs-pro-feature-<?php echo esc_attr( $config['id'] ); ?>"
	><?php echo json_encode( $config ); ?></script>
<?php } ?>
