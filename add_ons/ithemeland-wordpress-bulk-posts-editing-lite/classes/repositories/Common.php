<?php

namespace wpbel\classes\repositories;

class Common
{
    const OPTION_NAME = "wpbel_common";

    public function update(array $data)
    {
        if (count($data) < 1) {
            return false;
        }

        $items = $this->get_items();
        foreach ($data as $key => $value) {
            $items[$key] = $value;
        }
        update_option(self::OPTION_NAME, serialize($items));
        return $this->get_items();
    }

    public function get_items()
    {
        $items = get_option(self::OPTION_NAME);
        return !empty($items) ? unserialize($items) : [];
    }
}
