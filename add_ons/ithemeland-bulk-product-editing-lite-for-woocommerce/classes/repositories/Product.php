<?php

namespace wcbef\classes\repositories;

use wcbef\classes\helpers\Formula;
use wcbef\classes\helpers\Others;
use wcbef\classes\helpers\Pagination;
use wcbef\classes\helpers\Render;
use wcbef\classes\helpers\Session;

class Product
{
    public function get_product($product_id)
    {
        return wc_get_product(intval($product_id));
    }

    public function get_product_ids_by_custom_query($join, $where, $types_in = 'all')
    {
        global $wpdb;
        switch ($types_in) {
            case 'all':
                $types = "'product','product_variation'";
                break;
            case 'product':
                $types = "'product'";
                break;
            case 'product_variation':
                $types = "'product_variation'";
                break;
        }
        $products = $wpdb->get_results("SELECT posts.ID, posts.post_parent FROM $wpdb->posts AS posts {$join} WHERE posts.post_type IN ($types) AND ({$where})", ARRAY_N);
        $products = array_unique(Others::array_flatten($products, 'int'));
        if ($key = array_search(0, $products) !== false) {
            unset($products[$key]);
        }
        return implode(',', $products);
    }

    public function get_products($args)
    {
        $posts = new \WP_Query($args);
        return $posts;
    }

    public function get_products_list($data, $active_page)
    {
        Session::set('last_filter_data', $data);
        $settings = new Setting();
        $column_name = (Session::has('wcbef_sort_by')) ? Session::get('wcbef_sort_by') : $settings->get_default_sort_by();
        $sort_type = (Session::has('wcbef_sort_type')) ? Session::get('wcbef_sort_type')  : $settings->get_default_sort();
        $args = \wcbef\classes\helpers\Setting::get_arg_order_by(esc_sql($column_name), [
            'order' => esc_sql($sort_type),
            'posts_per_page' => Session::get('wcbef_count_per_page'),
            'paged' => $active_page,
            'paginate' => true,
            'post_type' => ['product'],
            'fields' => 'ids',
        ]);
        $products_args = \wcbef\classes\helpers\Product::set_filter_data_items($data, $args);
        $products = $this->get_products($products_args);
        $variations = 'children';
        $columns = Session::get('wcbef_active_columns');
        if (isset($data['product_ids']['parent_only']) && $data['product_ids']['parent_only'] == 'yes') {
            $variations = [];
        } elseif ((isset($data['product_regular_price']) && (!empty($data['product_regular_price']['from']) || !empty($data['product_regular_price']['to'])) || isset($data['product_sale_price']) && (!empty($data['product_sale_price']['from']) || !empty($data['product_sale_price']['to'])))) {
            $var_filter_data = $data;
            unset($var_filter_data['product_ids']);
            $variations_args = \wcbef\classes\helpers\Product::set_filter_data_items($var_filter_data, [
                'posts_per_page' => '-1',
                'fields' => 'id=>parent',
                'post_type' => ['product_variation'],
            ]);
            $variations_ids = ($this->get_products($variations_args))->posts;
            $variations = [];
            foreach ($variations_ids as $variation_id) {
                $variations[$variation_id->post_parent][] = $variation_id->ID;
            }
        }

        $products_list = Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/products.php', compact('products', 'variations', 'columns'));
        if (!empty($products) && !empty($active_page)) {
            $pagination = Pagination::products($active_page, $products->max_num_pages);
        }

        $result = new \stdClass();
        $result->products_list = $products_list;
        $result->pagination = $pagination;
        $result->count = $products->found_posts;
        return $result;
    }

    private function set_value_with_operator($old_value, $data)
    {
        if (!empty($data['operator'])) {
            $new_val = (isset($data['round_item']) && !empty($data['round_item'])) ? \wcbef\classes\helpers\Product::round($data['value'], $data['round_item']) : $data['value'];
            switch ($data['operator']) {
                case 'text_append':
                    $value = $old_value . $data['value'];
                    break;
                case 'text_prepend':
                    $value = $data['value'] . $old_value;
                    break;
                case 'text_new':
                    $value = $data['value'];
                    break;
                case 'text_delete':
                    $value = str_replace($data['value'], '', $old_value);
                    break;
                case 'text_replace':
                    if (!empty($data['value']) && !empty($data['replace'])) {
                        $value = ($data['sensitive'] == 'yes') ? str_replace($data['value'], $data['replace'], $old_value) : str_ireplace($data['value'], $data['replace'], $old_value);
                    } else {
                        $value = $old_value;
                    }
                    break;
                case 'text_remove_duplicate':
                    $value = $old_value;
                    break;
                case 'taxonomy_append':
                    $value = array_unique(array_merge($old_value, $data['value']));
                    break;
                case 'taxonomy_replace':
                    $value = $data['value'];
                    break;
                case 'taxonomy_delete':
                    $value = array_values(array_diff($old_value, $data['value']));
                    break;
                case 'number_new':
                    $value = $new_val;
                    break;
                case 'number_delete':
                    $value = str_replace($data['value'], '', $old_value);
                    break;
                case 'number_clear':
                    $value = '';
                    break;
                case 'number_formula':
                    $formula = new Formula();
                    $value = $formula->calculate(str_replace('X', $old_value, $data['value']));
                    break;
                case 'increase_by_value':
                    $value = floatval($old_value) + floatval($new_val);
                    break;
                case 'decrease_by_value':
                    $value = floatval($old_value) - floatval($new_val);
                    break;
                case 'increase_by_percent':
                    $value = floatval($old_value) + floatval(floatval($old_value) * floatval($new_val) / 100);
                    break;
                case 'decrease_by_percent':
                    $value = floatval($old_value) - floatval(floatval($old_value) * floatval($new_val) / 100);
                    break;
                case 'increase_by_value_from_sale':
                    $value = (isset($data['sale_price'])) ? floatval($data['sale_price']) + floatval($new_val) : $data;
                    break;
                case 'increase_by_percent_from_sale':
                    $value = (isset($data['sale_price'])) ? floatval($data['sale_price']) + floatval(floatval($data['sale_price']) * floatval($new_val) / 100) : $data;
                    break;
                case 'decrease_by_value_from_regular':
                    $value = (isset($data['regular_price'])) ? floatval($data['regular_price']) - floatval($data['value']) : $data;
                    break;
                case 'decrease_by_percent_from_regular':
                    $value = (isset($data['regular_price'])) ? floatval($data['regular_price']) - (floatval($data['regular_price']) * floatval($new_val) / 100) : $data;
                    break;
            }
        } else {
            $value = $data['value'];
        }
        return $value;
    }

    private function parse_value(\WC_Product $product, $value)
    {
        $parent = $this->get_product(intval($product->get_parent_id()));
        $value = str_replace('{title}', $product->get_title(), $value);
        $value = str_replace('{id}', $product->get_id(), $value);
        $value = str_replace('{sku}', $product->get_sku(), $value);
        $value = str_replace('{menu_order}', $product->get_menu_order(), $value);
        $value = str_replace('{parent_id}', $product->get_parent_id(), $value);
        $value = str_replace('{regular_price}', $product->get_regular_price(), $value);
        $value = str_replace('{sale_price}', $product->get_sale_price(), $value);
        if ($parent instanceof \WC_Product) {
            $value = str_replace('{parent_title}', $parent->get_title(), $value);
            $value = str_replace('{parent_sku}', $product->get_sku(), $value);
        } else {
            $value = str_replace('{parent_title}', '', $value);
            $value = str_replace('{parent_sku}', '', $value);
        }
        return $value;
    }

    private function field_update($product_id, $data)
    {
        $field_type = esc_sql($data['field_type']);
        $field = esc_sql($data['field']);
        $value = (is_numeric($data['value'])) ? esc_sql($data['value']) : sprintf('%s', $data['value']);
        $operator = $data['operator'];

        $product = $this->get_product(intval(esc_sql($product_id)));
        if (!$product instanceof \WC_Product) {
            return false;
        }
        if (!is_numeric($value)) {
            $value = $this->parse_value($product, $data['value']);
        }
        if (!empty($data['replace'])) {
            $data['replace'] = $this->parse_value($product, $data['replace']);
        }

        switch ($field_type) {
            case 'taxonomy':
                $this->taxonomy_update($product->get_id(), $data);
                break;
            case 'custom_field':
                $this->custom_field_update($product->get_id(), $data);
                break;
            case 'main_field':
                switch ($field) {
                    case 'post_title':
                        if ($operator == 'text_remove_duplicate') {
                            $products = $this->get_products([
                                'posts_per_page' => '-1',
                                'post_type' => ['product'],
                                'wcbef_general_column_filter' => [
                                    [
                                        'field' => 'ID',
                                        'value' => esc_sql($product->get_id()),
                                        'operator' => 'not_in',
                                    ],
                                    [
                                        'field' => 'post_title',
                                        'value' => esc_sql($product->get_title()),
                                        'operator' => 'exact',
                                    ],
                                ],
                            ]);
                            if (!empty($products->posts)) {
                                foreach ($products->posts as $post) {
                                    wp_delete_post(intval($post->ID));
                                }
                            }
                        } else {
                            $value = $this->set_value_with_operator($product->get_title(), $data);
                            $product->set_name(esc_sql($value));
                        }
                        break;
                    case 'post_content':
                        $value = $this->set_value_with_operator($product->get_description(), $data);
                        $product->set_description($value);
                        break;
                    case 'post_excerpt':
                        $value = $this->set_value_with_operator($product->get_short_description(), $data);
                        $product->set_short_description($value);
                        break;
                    case 'post_status':
                        $product->set_status($value);
                        break;
                    case 'post_date':
                        $product->set_date_created($value);
                        break;
                    case 'manage_stock':
                        $product->set_manage_stock((!empty($value) && $value == 'yes') ? true : false);
                        break;
                    case '_thumbnail_id':
                        $product->set_image_id(intval($value));
                        break;
                    case 'product_cat':
                        $value = $this->set_value_with_operator($product->get_category_ids(), $data);
                        $product->set_category_ids(esc_sql($value));
                        break;
                    case 'product_tag':
                        $value = $this->set_value_with_operator($product->get_tag_ids(), $data);
                        $product->set_tag_ids(esc_sql($value));
                        break;
                    case 'regular_price':
                        $value = $this->set_value_with_operator($product->get_regular_price(), [
                            'value' => $value,
                            'round_item' => (!empty($data['round_item'])) ? $data['round_item'] : '',
                            'sale_price' => $product->get_sale_price(),
                            'operator' => $operator
                        ]);
                        if ($value <= $product->get_sale_price()) {
                            $product->set_sale_price(0);
                        }
                        $product->set_regular_price(esc_sql($value));
                        break;
                    case 'sale_price':
                        if ($value != '') {
                            $value = $this->set_value_with_operator($product->get_sale_price(), [
                                'value' => $value,
                                'round_item' => (!empty($data['round_item'])) ? $data['round_item'] : '',
                                'regular_price' => $product->get_regular_price(),
                                'operator' => $operator
                            ]);
                            $value = ($value >= $product->get_regular_price()) ? floatval($product->get_regular_price() - 0.01) : esc_sql($value);
                        } else {
                            $value = $value;
                        }
                        $product->set_sale_price($value);
                        break;
                    case 'catalog_visibility':
                        try {
                            $product->set_catalog_visibility($value);
                        } catch (\Exception $exception) {
                            break;
                        }
                        break;
                    case 'post_slug':
                        $value = $this->set_value_with_operator($product->get_slug(), $data);
                        $product->set_slug(esc_sql($value));
                        break;
                    case 'sku':
                        $value = $this->set_value_with_operator($product->get_sku(), $data);
                        try {
                            $product->set_sku(esc_sql($value));
                        } catch (\Exception $exception) {
                            break;
                        }
                        break;
                    case 'purchase_note':
                        $value = $this->set_value_with_operator($product->get_purchase_note(), $data);
                        $product->set_purchase_note($value);
                        break;
                    case 'menu_order':
                        $product->set_menu_order(intval($value));
                        break;
                    case 'sold_individually':
                        $product->set_sold_individually($value);
                        break;
                    case 'reviews_allowed':
                        $product->set_reviews_allowed($value);
                        break;
                    case 'gallery':
                        $product->set_gallery_image_ids($value);
                        break;
                    case 'date_on_sale_from':
                        $product->set_date_on_sale_from($value);
                        break;
                    case 'date_on_sale_to':
                        $product->set_date_on_sale_to($value);
                        break;
                    case 'tax_status':
                        try {
                            $product->set_tax_status($value);
                        } catch (\Exception $exception) {
                            return false;
                        }
                        break;
                    case 'tax_class':
                        $product->set_tax_class($value);
                        break;
                    case 'shipping_class':
                        $product->set_shipping_class_id($value);
                        break;
                    case 'width':
                        $product->set_width($value);
                        break;
                    case 'height':
                        $product->set_height($value);
                        break;
                    case 'length':
                        $product->set_length($value);
                        break;
                    case 'weight':
                        $product->set_weight($value);
                        break;
                    case 'stock_status':
                        $product->set_stock_status($value);
                        break;
                    case 'stock_quantity':
                        $product->set_manage_stock(true);
                        $product->set_stock_quantity($value);
                        break;
                    case 'backorders':
                        $product->set_backorders($value);
                        break;
                    case 'product_type':
                        return wp_set_object_terms(intval($product_id), $value, 'product_type');
                        break;
                    case 'product_url':
                        $this->custom_field_update(intval($product_id), [
                            'value' => $value,
                            'field' => '_product_url',
                        ]);
                        break;
                    case 'button_text':
                        $this->custom_field_update(intval($product_id), [
                            'value' => $value,
                            'field' => '_button_text',
                        ]);
                        break;
                    case 'featured':
                        $product->set_featured((!empty($value) && ($value == 'yes')) ? true : false);
                        break;
                    case 'virtual':
                        $product->set_virtual((!empty($value) && $value == 'yes') ? true : false);
                        break;
                    case 'downloadable':
                        $product->set_downloadable((!empty($value) && $value == 'yes') ? true : false);
                        break;
                    case 'downloadable_files':
                        if (is_array($value) && !empty($value['files_name']) && !empty($value['files_url'])) {
                            $downloads = [];
                            $files_name = esc_sql($value['files_name']);
                            $files_url = esc_sql($value['files_url']);
                            for ($i = 0; $i < count($files_name); $i++) {
                                $md5 = md5($files_url[$i]);
                                $download_file = new \WC_Product_Download();
                                $download_file->set_id($md5);
                                $download_file->set_name($files_name[$i]);
                                $download_file->set_file($files_url[$i]);
                                if ($download_file->is_allowed_filetype()) {
                                    $downloads[$md5] = $download_file;
                                }
                            }
                            if (!empty($downloads)) {
                                $product->set_downloads($downloads);
                            } else {
                                return false;
                            }
                        }
                        break;
                    case 'download_limit':
                        $product->set_download_limit($value);
                        break;
                    case 'download_expiry':
                        $product->set_download_expiry($value);
                        break;
                    case 'total_sales':
                        $product->set_total_sales($value);
                        break;
                    case 'review_count':
                        $product->set_review_count($value);
                        break;
                    case 'average_rating':
                        $product->set_average_rating($value);
                        break;
                    case 'upsell_ids':
                        $value = $this->set_value_with_operator($product->get_upsell_ids(), $data);
                        $product->set_upsell_ids(esc_sql($value));
                        break;
                    case 'cross_sell_ids':
                        $value = $this->set_value_with_operator($product->get_cross_sell_ids(), $data);
                        $product->set_cross_sell_ids(esc_sql($value));
                        break;
                    case 'post_author':
                        wp_update_post([
                            'ID' => intval($product->get_id()),
                            'post_author' => intval($value),
                        ]);
                        break;
                    case '_children':
                        $this->custom_field_update($product->get_id(), [
                            'field' => '_children',
                            'value' => array_map('intval', $value),
                        ]);
                        break;
                    default:
                        return false;
                        break;
                }
                break;
        }
        $product->save();
        wp_update_post(['ID' => $product->get_id()]);
        return true;
    }

    public function update(array $product_ids, array $data)
    {
        if (empty($product_ids)) {
            return false;
        }

        if (!empty($data)) {
            foreach ($product_ids as $product_id) {
                $result = $this->field_update($product_id, $data);
                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }

    private function custom_field_update($product_id, $data)
    {
        $old_value = get_post_meta(intval($product_id), $data['field']);
        $old_value = isset($old_value[0]) ? $old_value[0] : '';
        $value = $this->set_value_with_operator($old_value, $data, $data['operator']);
        return update_post_meta(intval($product_id), esc_sql($data['field']), esc_sql($value));
    }

    private function taxonomy_update($product_id, $new_attributes)
    {
        if (is_array($new_attributes) && !empty($new_attributes)) {
            if (strpos($new_attributes['taxonomy'], 'pa_') !== false) {
                return $this->product_attribute_update($product_id, $new_attributes);
            } else {
                return $this->custom_taxonomy_update($product_id, $new_attributes);
            }
        }
        return false;
    }

    private function custom_taxonomy_update($product_id, $new_taxonomies)
    {
        $old_value = wp_get_post_terms(intval($product_id), $new_taxonomies['taxonomy'], array('fields' => 'ids'));
        $value = $this->set_value_with_operator($old_value, $new_taxonomies, $new_taxonomies['operator']);
        return wp_set_post_terms(intval($product_id), $value, $new_taxonomies['taxonomy']);
    }

    public function product_attribute_update($product_id, $data)
    {
        if (is_array($data) && !empty($data)) {
            $product = $this->get_product($product_id);
            if (!($product instanceof \WC_Product)) {
                return false;
            }

            $attr = new \WC_Product_Attribute();
            $attributes_result = $product->get_attributes();
            $product_attributes = (!empty($attributes_result) ? $attributes_result : []);
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            $value = (is_array($data['value'])) ? array_map('intval', $data['value']) : [];
            if (is_array($attribute_taxonomies) && !empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $attribute_taxonomy) {
                    if (!empty($product_attributes) && isset($product_attributes[strtolower(urlencode($data['taxonomy']))])) {
                        $old_attr = $product_attributes[strtolower(urlencode($data['taxonomy']))];
                        if ($old_attr->get_name() == $data['taxonomy']) {
                            $value = $this->set_value_with_operator($old_attr->get_options(), ['value' => $value], $data['operator']);
                            $attr->set_id($old_attr->get_id());
                            $attr->set_name($old_attr->get_name());
                            $attr->set_options($value);
                            $attr->set_position($old_attr->get_position());
                            $attr->set_visible(1);
                            $attr->set_variation(true);
                            $product_attributes[] = $attr;
                        }
                    } else {
                        if ('pa_' . $attribute_taxonomy->attribute_name == $data['taxonomy']) {
                            $attr->set_id($attribute_taxonomy->attribute_id);
                            $attr->set_name('pa_' . $attribute_taxonomy->attribute_name);
                            $attr->set_options($value);
                            $attr->set_position(count($product_attributes));
                            $attr->set_visible(1);
                            $attr->set_variation(true);
                            $product_attributes[] = $attr;
                        }
                    }
                }
            }

            $product->set_attributes($product_attributes);
            $product->save();
            return true;
        }
        return false;
    }

    public function get_attributes()
    {
        return wc_get_attribute_taxonomies();
    }

    public function get_taxonomies()
    {
        $taxonomies_value = [];
        $taxonomies = get_taxonomies([], 'objects');
        foreach ($taxonomies as $taxonomy) {
            if (taxonomy_exists($taxonomy->name)) {
                $taxonomies_value[$taxonomy->name] = [
                    'label' => (strpos($taxonomy->name, 'pa_') !== false) ? wc_attribute_label($taxonomy->name) : $taxonomy->label,
                    'terms' => get_terms([
                        'taxonomy' => $taxonomy->name,
                        'hide_empty' => false,
                    ]),
                ];
            }
        }
        return $taxonomies_value;
    }

    public function get_product_fields(\WC_Product $product_object): array
    {
        $post_object = get_post($product_object->get_id());
        $post_meta = get_post_meta($product_object->get_id());
        return [
            'id' => $product_object->get_id(),
            'post_parent' => $product_object->get_parent_id(),
            'type' => $product_object->get_type(),
            'post_title' => $product_object->get_name(),
            'post_slug' => $product_object->get_slug(),
            'post_content' => $product_object->get_description(),
            'post_excerpt' => $product_object->get_short_description(),
            'post_date' => (!empty($product_object->get_date_created()) && !empty($product_object->get_date_created()->date('Y/m/d'))) ? $product_object->get_date_created()->format('Y/m/d') : '',
            'post_status' => $product_object->get_status(),
            'regular_price' => $product_object->get_regular_price(),
            'sale_price' => $product_object->get_sale_price(),
            '_thumbnail_id' => [
                'id' => $product_object->get_image_id(),
                'small' => $product_object->get_image([40, 40]),
                'big' => $product_object->get_image([600, 600]),
            ],
            'gallery' => $product_object->get_gallery_image_ids(),
            'manage_stock' => $product_object->get_manage_stock(),
            'product_cat' => $product_object->get_category_ids(),
            'product_tag' => $product_object->get_tag_ids(),
            'catalog_visibility' => $product_object->get_catalog_visibility(),
            'featured' => $product_object->get_featured(),
            'date_on_sale_from' => (!empty($product_object->get_date_on_sale_from()) && !empty($product_object->get_date_on_sale_from()->date('Y/m/d'))) ? $product_object->get_date_on_sale_from()->format('Y/m/d') : '',
            'date_on_sale_to' => (!empty($product_object->get_date_on_sale_to()) && !empty($product_object->get_date_on_sale_to()->date('Y/m/d'))) ? $product_object->get_date_on_sale_to()->format('Y/m/d') : '',
            'downloadable' => $product_object->get_downloadable(),
            'sku' => $product_object->get_sku(),
            'stock_status' => $product_object->get_stock_status(),
            'sold_individually' => $product_object->get_sold_individually(),
            'shipping_class' => $product_object->get_shipping_class_id(),
            'upsell_ids' => $product_object->get_upsell_ids(),
            'cross_sell_ids' => $product_object->get_cross_sell_ids(),
            'purchase_note' => $product_object->get_purchase_note(),
            'reviews_allowed' => $product_object->get_reviews_allowed(),
            'average_rating' => $product_object->get_average_rating(),
            'virtual' => $product_object->get_virtual(),
            'download_limit' => $product_object->get_download_limit(),
            'download_expiry' => $product_object->get_download_expiry(),
            'stock_quantity' => $product_object->get_stock_quantity(),
            'tax_class' => $product_object->get_tax_class(),
            'tax_status' => $product_object->get_tax_status(),
            'width' => $product_object->get_width(),
            'height' => $product_object->get_height(),
            'length' => $product_object->get_length(),
            'weight' => $product_object->get_weight(),
            'backorders' => $product_object->get_backorders(),
            'menu_order' => $product_object->get_menu_order(),
            'total_sales' => $product_object->get_total_sales(),
            'review_count' => $product_object->get_review_count(),
            'product_type' => $product_object->get_type(),
            'button_text' => (!empty($post_meta['_button_text'])) ? $post_meta['_button_text'] : '',
            'product_url' => (!empty($post_meta['_product_url'])) ? $post_meta['_product_url'] : '',
            '_children' => (!empty($post_meta['_children'])) ? $post_meta['_children'] : '',
            'downloadable_files' => $product_object->get_downloads(),
            'post_author' => $post_object->post_author,
            'meta_field' => $post_meta,
            'attribute' => $product_object->get_attributes(),
        ];
    }

    public function create(array $data = [])
    {
        $product = new \WC_Product();
        $product->set_name((isset($data['name'])) ? $data['name'] : 'New Product');
        $product->set_status('draft');
        return $product->save();
    }
}
