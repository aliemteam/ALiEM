<?php

namespace ALIEM\Shortcodes;

function plot($atts) {
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
            var isMobile = <?php echo wp_is_mobile() ? 'true' : 'false' ?>;
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
                console.log(json);
                createTitle(json.layout.title, id);
                json.layout.title = '';
                json.layout.margin = { t: 0 };
                if (isMobile) {
                    json.layout.xaxis.showticklabels = false;
                    json.layout.hovermode = 'x'
                }
                Plotly.newPlot(id, json.data, json.layout, {
                    displayModeBar: false,
                });
            }
            function createTitle(title, id) {
                var el = document.createElement('div');
                el.className = 'plotly-title'
                el.style.fontWeight = '500';
                el.style.textAlign = 'center';
                el.innerText = title;
                document.getElementById(id).parentNode.insertBefore(el, document.getElementById(id));
            }
        })();
    </script>
    <?php
    return "<div id='$id' style='width: calc(100% + 40px); height: auto; position: relative; margin: auto -20px;'></div>";
}
add_shortcode('plot', 'ALIEM\Shortcodes\plot');
