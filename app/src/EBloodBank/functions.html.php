<?php
/**
 * HTML functions file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

/**
 * @return string
 * @since 1.0
 */
function escAttr($data, $encoding = 'UTF-8')
{
    return htmlspecialchars($data, ENT_QUOTES, $encoding, false);
}

/**
 * @return string
 * @since 1.0
 */
function escHTML($data, $encoding = 'UTF-8')
{
    return htmlspecialchars($data, ENT_HTML5, $encoding, false);
}

/**
 * @return string
 * @since 1.0
 */
function escURL($url)
{
    return escAttr($url);
}

/**
 * Convert an associative array to HTML attributes list.
 *
 * Convert an associative array of attributes and their values
 * ['attribute' => 'value']
 * to an inline list of HTML attributes.
 *
 * @return string
 * @since  1.0
 */
function toAttributes(array $atts, array $args = null)
{
    $output = '';

    if (empty($atts)) {
        return $output;
    }

    $args = array_merge(
        array(
            'after' => '',
            'before' => ' ',
        ),
        (array) $args
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

        $output .= $key . '="' . escAttr($value) . '" ';
    }

    if (! empty($output)) {
        $output = $args['before'] . trim($output) . $args['after'];
    }

    return $output;
}
