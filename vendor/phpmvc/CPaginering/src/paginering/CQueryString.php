<?php

namespace Phpmvc\paginering;


class CQueryString {

/**
 * Use the current querystring as base, modify it according to $options and return the modified query string.
 *
 * @param array $options to set/change.
 * @param string $prepend this to the resulting query string
 * @return string with an updated query string.
 */

    public function getQueryString($options, $prepend='?') {
      // parse query string into array
      $query = array();
      parse_str($_SERVER['QUERY_STRING'], $query);

      // Modify the existing query string with new options
      $query = array_merge($query, $options);

      // Return the modified querystring
      return $prepend . http_build_query($query);

    }


}
