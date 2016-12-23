<?php

function aliem_plotly_shortcode($atts) {
    global $post;

    $atts = shortcode_atts([
        'id' => '',
    ], $atts);
    extract($atts);

    if ($id == '')
        return "<strong style='color: red;'>Plot must have an associated id!</strong>";

    $id = "plot$id";
    $custom = get_post_custom($post->ID);
    $plot = $custom[$id][0];

    if (!$plot)
        return "<strong style='color: red;'>Plot data not found for '$id'</strong>";

    ?>
    <script type='text/javascript'>
        (function() {
            var script = document.getElementById('plotly-js');
            var id = '<?php echo $id ?>';
            if (!script) {
                var head = document.querySelector('head');
                script = document.createElement('script');
                script.type = 'text/javascript';
                script.id = 'plotly-js';
                head.appendChild(script);
                script.addEventListener('load', mountPlot);
                script.src = 'https://cdn.plot.ly/plotly-latest.min.js';
            }
            else if (typeof window.Plotly === 'undefined') {
                script.addEventListener('load', mountPlot);
            }
            else {
                mountPlot();
            }
            function mountPlot() {
                var json = <?php echo $plot ?>;
                Plotly.newPlot(id, json.data, json.layout, {
                    displayModeBar: false,
                });
            }
        })();
    </script>
    <?php
    return "<div id='$id'></div>";
}
add_shortcode('plot', 'aliem_plotly_shortcode');
