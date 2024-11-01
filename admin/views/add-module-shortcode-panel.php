<div id="supapress-insert-shortcode-panel" style="display:none;">
    <div class="supapress-insert-shortcode-panel">
        <h1><?php _e( 'Add Supafolio Module', 'supapress' ); ?></h1>
        <?php _e( 'Add a Supafolio module to your post.', 'supapress' ); ?>
        <?php if ( ! empty( $modules ) && is_array( $modules ) ) : ?>
            <p>
                <label for="supapress-choose-module">
                    <select id="supapress-choose-module" class="supapress-dropdown">
                        <option value="">- <?php _e( 'None', 'supapress' ) ?> -</option>
                        <?php foreach ($modules as $module ) : ?>
                            <option value="<?php echo $module->id(); ?>"><?php echo $module->title(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </p>
            <p>
                <span class="errors"></span>
                <input type="button" value="<?php _e( 'Insert Supafolio module', 'supapress' ); ?>" id="supapress-insert-shortcode" class="button button-primary button-large" name="" />
            </p>
        <?php else : ?>
            <p><em><?php _e( 'Could not find any Supafolio modules to add to this post.', 'supapress' ); ?></em></p>
            <strong><a href="admin.php?page=supapress-new"><?php _e( 'Please add and configure new Supafolio module first.', 'supapress' ); ?></a></strong>
        <?php endif; ?>
    </div>
</div>
