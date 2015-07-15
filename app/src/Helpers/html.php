<?php

/**
 * Output HTML attributes list.
 *
 * @return void
 * @since  1.0
 */
function html_atts(array $atts, array $args = null)
{
    echo get_html_atts($atts, $args);
}

/**
 * Convert an associative array to HTML attributes list.
 *
 * Convert an associative array of attributes and their values 'attribute => value' To
 * an inline list of HTML attributes.
 *
 * @return string
 * @since  1.0
 */
function get_html_atts(array $atts, array $args = null)
{
    $output = '';

    if (empty($atts)) {
        return $output;
    }

    $args = array_merge(
        array(
        'after' => '',
        'before' => ' ',
        ), (array) $args
    );

    foreach ($atts as $key => $value) {
        $key = strtolower($key);

        if (is_bool($value)) {
            if ($value === true) {
                $value = $key;
            } else {
                continue;
            }
        } elseif (is_array($value)) {
            $value = array_filter($value);
            $value = implode(' ', $value);
        }

        $output .= $key . '="' . esc_attr($value) . '" ';
    }

    if (! empty($output)) {
        $output = $args['before'] . trim($output) . $args['after'];
    }

    return $output;
}

/**
 * @return string
 * @since 1.0
 */
function esc_attr($data, $encoding = 'UTF-8')
{
    return htmlspecialchars($data, ENT_QUOTES, $encoding);
}

/**
 * @return string
 * @since 1.0
 */
function esc_html($data, $encoding = 'UTF-8')
{
    return htmlspecialchars($data, ENT_HTML5, $encoding);
}
