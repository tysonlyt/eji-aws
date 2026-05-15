<?php
//phpcs:disable
/**
 * @var string $popup_template
 */
?>
<noscript>

    <style>
        #cpp-js-disabled {
            top: 0;
            left: 0;
            color: #111;
            width: 100%;
            height: 100%;
            z-index: 9999;
            position: fixed;
            font-size: 25px;
            text-align: center;
            background: #fcfcfc;
            padding-top: 200px;
        }

    </style>

    <div id="cpp-js-disabled">
        <h4>
			<?php if ( 'enable' === wpshield_cp_option( 'javascript/alert-popup' ) ): ?>

				<?php file_exists( $popup_template ) && include $popup_template; ?>

			<?php endif; ?>
        </h4>
    </div>

</noscript>