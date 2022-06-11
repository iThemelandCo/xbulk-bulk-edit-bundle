<?php

namespace wbebl\classes\helpers;

class Industry_Helper
{
    public static function get_industries()
    {
        return [
            'Automotive and Transportation' => esc_html__('Automotive', WBEBL_NAME),
            'AdTech and AdNetwork' => esc_html__('AdTech and AdNetwork', WBEBL_NAME),
            'Agency' => esc_html__('Agency', WBEBL_NAME),
            'B2B Software' => esc_html__('B2B Software', WBEBL_NAME),
            'B2C Internet Services' => esc_html__('B2C Internet Services', WBEBL_NAME),
            'Classifieds' => esc_html__('Classifieds', WBEBL_NAME),
            'Consulting and Market Research' => esc_html__('Consulting and Market Research', WBEBL_NAME),
            'CPG, Food and Beverages' => esc_html__('CPG', WBEBL_NAME),
            'Education' => esc_html__('Education', WBEBL_NAME),
            'Education (student)' => esc_html__('Education (Student)', WBEBL_NAME),
            'Equity Research' => esc_html__('Equity Research', WBEBL_NAME),
            'Financial services' => esc_html__('Financial Services', WBEBL_NAME),
            'Gambling / Gaming' => esc_html__('Gambling and Gaming', WBEBL_NAME),
            'Hedge Funds and Asset Management' => esc_html__('Hedge Funds and Asset Management', WBEBL_NAME),
            'Investment Banking' => esc_html__('Investment Banking', WBEBL_NAME),
            'Logistics and Shipping' => esc_html__('Logistics and Shipping', WBEBL_NAME),
            'Payments' => esc_html__('Payments', WBEBL_NAME),
            'Pharma and Healthcare' => esc_html__('Pharma and Healthcare', WBEBL_NAME),
            'Private Equity and Venture Capital' => esc_html__('Private Equity and Venture Capital', WBEBL_NAME),
            'Media and Entertainment' => esc_html__('Publishers and Media', WBEBL_NAME),
            'Government Public Sector & Non Profit' => esc_html__('Public Sector, Non Profit, Fraud and Compliance', WBEBL_NAME),
            'Retail / eCommerce' => esc_html__('Retail and eCommerce', WBEBL_NAME),
            'Telecom and Hardware' => esc_html__('Telecom', WBEBL_NAME),
            'Travel and Hospitality' => esc_html__('Travel', WBEBL_NAME),
            'Other' => esc_html__('Other', WBEBL_NAME),
        ];
    }
}
