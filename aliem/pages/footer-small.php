<?php

if (!defined('ABSPATH')) {
    exit(1);
}

do_action('avada_after_main_content'); ?>

</div>  <!-- fusion-row -->
</main>  <!-- #main -->

<?php $footer_copyright_center_class = (Avada()->settings->get('footer_copyright_center_content')) ? ' fusion-footer-copyright-center' : ''; ?>
<footer id="footer" class="fusion-footer-copyright-area<?php echo esc_attr($footer_copyright_center_class); ?>">
    <div class="fusion-row">
        <div class="fusion-copyright-content">
            <?php do_action('avada_footer_copyright_content'); ?>
        </div> <!-- fusion-fusion-copyright-content -->
    </div> <!-- fusion-row -->
</footer> <!-- #footer -->

<?php

wp_footer();
echo Avada()->settings->get('space_body');

?>

</body>
</html>
