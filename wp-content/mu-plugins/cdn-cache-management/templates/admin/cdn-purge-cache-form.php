<form id="modalinside" class="wrap" method="POST">
    <div class="wrap">
        <h1>
            <?php _e('CDN Cache: Custom Purge', 'cdn-cache-wp') ?>
        </h1>
        <p>
            <?php _e("To purge specific files and/or URL's on the CDN, please provide them in the form below.", 'cdn-cache-wp'); ?>
        </p>
    </div>
    <div class="card">
        <h2 class="title">
            <?php _e('Custom Purge', 'cdn-cache-wp'); ?>
        </h2>
        <p>
            <?php _e('Separate URLs one per line.', 'cdn-cache-wp'); ?>
        </p>
        <p>
            <b><?php _e('Example:', 'cdn-cache-wp'); ?></b>
        <p>
            <tt>
                <?php _e('/wp-content/theme/style.css', 'cdn-cache-wp'); ?>
                <br>
                <?php _e('/some-url/', 'cdn-cache-wp'); ?>
            </tt>
        </p>
        <textarea id="cdn-urls" style="width: 100%" name="urls"></textarea>
        <input type="hidden" name="cdn_purge_cache" value='custom-files'>
        <p>
            <button type="submit" class="button primary submit">
                <?php _e('Submit', 'cdn-cache-wp'); ?>
            </button>
        </p>
    </div>
    <?php
    if($check_purge_all){
        ?>
        <input type="hidden" class="cdn-purge-all" name="cdn_purge_all" value="<?php echo $cdn_cache_page_url; ?>">
        <?php
    }
    ?>
</form>