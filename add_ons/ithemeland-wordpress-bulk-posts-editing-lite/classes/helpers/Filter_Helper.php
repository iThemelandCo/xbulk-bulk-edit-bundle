<?php

namespace wpbel\classes\helpers;

use wpbel\classes\repositories\Search;

class Filter_Helper
{
    public static function get_active_filter_data()
    {
        $search_repository = new Search();
        if (isset($search_repository->get_current_data()['last_filter_data'])) {
            $filter_data = $search_repository->get_current_data()['last_filter_data'];
        } else {
            $preset = $search_repository->get_preset($search_repository->get_use_always());
            if (!isset($preset['filter_data'])) {
                $preset['filter_data'] = [];
            }
            $filter_data = $preset['filter_data'];
        }

        return $filter_data;
    }
}
