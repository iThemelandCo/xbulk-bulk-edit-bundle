<?php

namespace wobef\classes\repositories;

class History
{
    const BULK_OPERATION = 'bulk';
    const INLINE_OPERATION = 'inline';

    private $wpdb;
    private $history_table;
    private $history_items_table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->history_table = $this->wpdb->prefix . 'wobef_history';
        $this->history_items_table = $this->wpdb->prefix . 'wobef_history_items';
    }

    public static function get_operation_types()
    {
        return [
            self::BULK_OPERATION => esc_html__('Bulk Operation', WBEBL_NAME),
            self::INLINE_OPERATION => esc_html__('Inline Operation', WBEBL_NAME),
        ];
    }

    public static function get_operation_type($operation_type)
    {
        $operation_types = self::get_operation_types();
        return (isset($operation_types[$operation_type])) ? $operation_types[$operation_type] : "";
    }

    public function create_history(array $data)
    {
        $format = ['%d', '%s', '%s', '%s'];
        $this->wpdb->insert($this->history_table, $data, $format);
        return $this->wpdb->insert_id;
    }

    public function create_history_item(array $data)
    {
        $format = ['%d', '%d', '%s', '%s'];
        $this->wpdb->insert($this->history_items_table, $data, $format);
        return $this->wpdb->insert_id;
    }

    public function get_histories(array $where = [])
    {
        $where_items = "history.reverted = 0 ";
        if (!empty($where)) {
            foreach ($where as $field => $value) {
                $field = esc_sql($field);
                $value = esc_sql($value);
                switch ($field) {
                    case 'operation_type':
                        $where_items .= " AND history.{$field} = '{$value}'";
                        break;
                    case 'user_id':
                        $where_items .= " AND history.{$field} = {$value}";
                        break;
                    case 'fields':
                        $fields = explode(',', $value);
                        if (!empty($fields) && is_array($fields)) {
                            foreach ($fields as $field_item) {
                                $where_items .= " AND history.{$field} LIKE '%{$field_item}%'";
                            }
                        }
                        break;
                    case 'operation_date':
                        $from = (!empty($value['from'])) ? date('Y-m-d H:i:s', strtotime($value['from'])) : null;
                        $to = (!empty($value['to'])) ? date('Y-m-d H:i:s', (strtotime($value['to']) + 86400)) : null;
                        if (!empty($from) || !empty($to)) {
                            if (!empty($from) & !empty($to)) {
                                $where_items .= " AND (history.{$field} BETWEEN '{$from}' AND '{$to}')";
                            } else if (!empty($from)) {
                                $where_items .= " AND history.{$field} >= '{$from}'";
                            } else {
                                $where_items .= " AND history.{$field} < '{$to}'";
                            }
                        }
                        break;
                }
            }
        }

        if (!current_user_can('administrator')) {
            $user_id = get_current_user_id();
            $where_items .= " AND history.user_id = {$user_id}";
        }

        return $this->wpdb->get_results("SELECT * FROM {$this->history_table} history WHERE {$where_items} ORDER BY history.id DESC");
    }

    public function get_history_items($history_id)
    {
        return $this->wpdb->get_results($this->wpdb->prepare("SELECT history_items.*, posts.post_title FROM {$this->history_items_table} history_items INNER JOIN {$this->wpdb->prefix}posts posts ON (history_items.historiable_id = posts.ID) WHERE history_id = %d", intval($history_id)));
    }

    public function get_latest_history()
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->history_table} WHERE reverted = 0 ORDER BY id DESC LIMIT 1");
    }

    public function get_latest_reverted()
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->history_table} WHERE reverted = 1 ORDER BY id DESC LIMIT 1");
    }

    public function get_history($history_id)
    {
        return $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM {$this->history_table} WHERE id = %d", intval($history_id)));
    }

    public function update_history($history_id, $where)
    {
        $result = $this->wpdb->update($this->history_table, $where, [
            'id' => intval($history_id)
        ]);

        return !empty($result);
    }
}
