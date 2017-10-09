<?php

if (!defined('ABSPATH')) {
    exit(1);
}

global $social_icons;
$c_page_id = Avada()->fusion_library->get_page_id();

do_action('avada_after_main_content'); ?>

</div>  <!-- fusion-row -->
</main>  <!-- #main -->

<?php do_action('avada_after_main_container'); ?>
<?php if (!is_page_template('blank.php')) : ?>

    <div class="footer">
        <?php $display_footer = get_post_meta($c_page_id, 'pyre_display_footer', true); ?>
        <?php if ((Avada()->settings->get('footer_widgets') && 'no' !== $display_footer) || (!Avada()->settings->get('footer_widgets') && 'yes' === $display_footer)) : ?>

            <footer role="contentinfo" class="fusion-footer-widget-area fusion-widget-area">
                <div class="fusion-row">
                    <div class="fusion-columns fusion-columns-<?php echo esc_attr(Avada()->settings->get('footer_widgets_columns')); ?> fusion-widget-area">
                        <?php
                        /**
                         * Check the column width based on the amount of columns chosen in Theme Options.
                         */
                        $footer_widget_columns = Avada()->settings->get('footer_widgets_columns');
                        $footer_widget_columns = (!$footer_widget_columns) ? 1 : $footer_widget_columns;
                        $column_width = ('5' === Avada()->settings->get('footer_widgets_columns')) ? 2 : 12 / $footer_widget_columns;
                        ?>

                        <?php
                        /**
                         * Render as many widget columns as have been chosen in Theme Options.
                         */
                        ?>
                        <?php for ($i = 1; $i < 7; $i++) : ?>
                            <?php if ($i <= Avada()->settings->get('footer_widgets_columns')) : ?>
                                <div class="fusion-column<?php echo (Avada()->settings->get('footer_widgets_columns') === $i) ? ' fusion-column-last' : ''; ?> col-lg-<?php echo esc_attr($column_width); ?> col-md-<?php echo esc_attr($column_width); ?> col-sm-<?php echo esc_attr($column_width); ?>">
                                    <?php
                                        if (function_exists('dynamic_sidebar')) {
                                            dynamic_sidebar('avada-footer-widget-' . $i);
                                        }
                                    ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div> <!-- fusion-columns -->
                </div> <!-- fusion-row -->
            </footer> <!-- fusion-footer-widget-area -->
        <?php endif; // End footer wigets check.?>

        <footer id="footer" class="fusion-footer-copyright-area">
            <div class="fusion-row">
                <div class="fusion-copyright-content">
                    <?php do_action('avada_footer_copyright_content'); ?>
                </div> <!-- fusion-fusion-copyright-content -->
            </div> <!-- fusion-row -->
        </footer> <!-- #footer -->
    </div> <!-- fusion-footer -->

<?php endif; // End is not blank page check.?>

</div> <!-- wrapper -->
<?php
wp_footer();
echo Avada()->settings->get('space_body');
?>
</body>
</html>
