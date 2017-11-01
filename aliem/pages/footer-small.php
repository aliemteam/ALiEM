<?php

if (!defined('ABSPATH')) {
    exit(1);
}

do_action('avada_after_main_content'); ?>

</div>  <!-- fusion-row -->
</main>  <!-- #main -->

<?php

get_template_part('partials/footer', 'copyright');
wp_footer();

?>

</body>
</html>
