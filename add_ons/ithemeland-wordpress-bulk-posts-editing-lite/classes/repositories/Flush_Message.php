<?php

namespace wpbel\classes\repositories;

class Flush_Message
{
    private $flush_message_option_name;

    public function __construct()
    {
        $this->flush_message_option_name = "wpbel_flush_message";
    }

    public function set(array $data)
    {
        return update_option($this->flush_message_option_name, serialize($data));
    }

    public function get()
    {
        $flush_message = unserialize(get_option($this->flush_message_option_name));
        $this->delete();
        return $flush_message;
    }

    public function delete()
    {
        return delete_option($this->flush_message_option_name);
    }
}
