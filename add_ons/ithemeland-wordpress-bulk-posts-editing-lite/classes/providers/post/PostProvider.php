<?php

namespace wpbel\classes\providers\post;

use wpbel\classes\providers\column\PostColumnProvider;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Post;

class PostProvider
{
    private static $instance = null;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function get_items($items, $children, $columns)
    {
        return $this->items($items, $children, $columns);
    }

    protected function items($items, $children, $columns)
    {
        $output = '';
        $post_Repository = new Post();
        if (!empty($items)) {
            $column_provider = PostColumnProvider::get_instance();
            $show_id_column = Column::SHOW_ID_COLUMN;
            $next_static_columns = Column::get_static_columns();
            foreach ($items as $post_id) {
                $item = $post_Repository->get_post(intval($post_id));
                include WPBEL_VIEWS_DIR . "data_table/row.php";
            }
        }
        return $output;
    }
}
