<?php

class ALiEM {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'load_client_scripts'], 999);
        add_action('admin_enqueue_scripts', [$this, 'load_admin_scripts']);
        require_once(dirname(__FILE__) . '/lib/index.php');
    }
    public function load_client_scripts() {
        global $post;

        wp_enqueue_style('aliem', get_stylesheet_uri());
        wp_enqueue_script('printfriendly', 'https://pf-cdn.printfriendly.com/ssl/main.js');

        wp_register_script('social-media-index', get_stylesheet_directory_uri() . '/lib/js/social-media-index.js', [], false, true);

        switch($post->ID) {
            case 12480:
                wp_enqueue_script('social-media-index');
                wp_localize_script('social-media-index', '__smi', $this->localize_smi_plot());
        }
    }
    public function load_admin_scripts($hook) {
        if (in_array($hook, ['post-new.php', 'post.php', 'page-new.php', 'page.php'])) {
            wp_enqueue_style( 'aliem_admin_style', get_stylesheet_directory_uri() . '/admin.css' );
        }
    }
    public function localize_smi_plot() {
        $file1 = file_get_contents('https://raw.githubusercontent.com/aliemteam/datasets/master/social-media-index/social-media-index.csv');
        $file2 = file_get_contents('https://raw.githubusercontent.com/aliemteam/datasets/master/social-media-index/latest-numbers.csv');

        $rows1 = array_map('str_getcsv', array_values(array_filter(explode("\n", $file1))));
        $rows2 = array_map('str_getcsv', array_values(array_filter(explode("\n", $file2))));

        $headings1 = array_splice($rows1, 0, 1)[0];
        $headings2 = array_splice($rows2, 0, 1)[0];

        $data1 = [];
        $data2 = [
            '__headings' => array_slice($headings2, 1),
        ];

        $filtered = array_values(array_filter($rows1, function($v) {
            return count(array_filter(array_slice($v, 2), function($n) {
                return $n !== 'NR' && $n < 26;
            })) > 0;
        }));

        foreach(array_slice($headings1, 2) as $k => $v) {
            $data1[$k] = [ "date" => $v ];
            foreach($filtered as $row) {
                $data1[$k][$row[0]] = $row[$k+2] === 'NR' ? NULL : (int) $row[$k+2];
            }
        }

        foreach ($rows2 as $row) {
            $data2[$row[0]] = [];
            foreach(array_slice($row, 1, null, true) as $k => $v) {
                $data2[$row[0]][$headings2[$k]] = $v;
            }
        }

        return [
            'headings' => $headings1,
            'rows' => $rows1,
            'data' => $data1,
            'rawData' => $data2,
        ];
    }
}

new ALiEM;
