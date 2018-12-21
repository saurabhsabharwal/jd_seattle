-- Do not drop this yet as other extensions may fall in error state. Just empty it for now and let

-- DROP TABLE IF EXISTS `#__sellacious_prices_cache`;
-- DROP TABLE IF EXISTS `#__sellacious_products_cache`;

CREATE TABLE IF NOT EXISTS `#__sellacious_cache_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'A unique id, coz we have multipricing',
  `price_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL,
  `seller_uid` int(11) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_price` double NOT NULL,
  `margin` double NOT NULL,
  `margin_type` int(11) NOT NULL,
  `list_price` double NOT NULL,
  `calculated_price` double NOT NULL,
  `ovr_price` double NOT NULL,
  `product_price` double NOT NULL DEFAULT '0',
  `is_fallback` int(11) NOT NULL,
  `qty_min` int(11) NOT NULL,
  `qty_max` int(11) NOT NULL,
  `sdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `client_catid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__sellacious_cache_products` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `variant_id` int(11) NOT NULL DEFAULT '0',
  `seller_uid` int(11) NOT NULL DEFAULT '0',
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_uid` int(11) NOT NULL,
  `product_title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `product_alias` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `product_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `product_sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `category_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_titles` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `spl_category_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `spl_category_titles` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer_sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `manufacturer_id` int(11) NOT NULL DEFAULT '0',
  `product_features` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_introtext` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_count` int(11) DEFAULT NULL,
  `variant_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_alias` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_sku` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_features` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_price_mod` double NOT NULL DEFAULT '0',
  `variant_price_mod_perc` int(11) NOT NULL DEFAULT '0',
  `seller_count` int(11) DEFAULT NULL,
  `seller_catid` int(11) NOT NULL DEFAULT '0',
  `seller_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_website` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_company` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_store` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_address` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_commission` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `forex_rate` double NOT NULL,
  `manufacturer_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer_username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer_catid` int(11) NOT NULL,
  `manufacturer_company` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `listing_type` int(11) NOT NULL,
  `item_condition` int(11) NOT NULL,
  `length` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `width` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `height` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vol_weight` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whats_in_box` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_mode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `download_limit` int(11) NOT NULL,
  `download_period` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview_mode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview_url` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `flat_shipping` int(11) NOT NULL,
  `shipping_flat_fee` double NOT NULL,
  `return_days` int(11) NOT NULL,
  `exchange_days` int(11) NOT NULL,
  `quantity_min` int(11) NOT NULL,
  `quantity_max` int(11) NOT NULL,
  `psx_id` int(11) NOT NULL,
  `vsx_id` int(11) NOT NULL,
  `price_display` int(11) NOT NULL,
  `product_price` double NOT NULL,
  `multi_price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `over_stock` int(11) NOT NULL,
  `stock_reserved` int(11) NOT NULL,
  `stock_sold` int(11) NOT NULL,
  `product_active` int(11) NOT NULL,
  `variant_active` int(11) NOT NULL,
  `seller_active` int(11) NOT NULL DEFAULT '0',
  `is_selling` int(11) NOT NULL,
  `is_selling_variant` int(11) NOT NULL,
  `listing_active` int(11) NOT NULL,
  `listing_purchased` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `listing_start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `listing_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order_count` int(11) NOT NULL,
  `order_units` int(11) NOT NULL,
  `product_rating` double NOT NULL,
  `core_fields` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_fields` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `metakey` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadesc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int(11) NOT NULL COMMENT 'Cache state',
  PRIMARY KEY (`product_id`,`variant_id`,`seller_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__sellacious_category_commissions` (
  `product_catid` int(11) NOT NULL,
  `seller_catid` int(11) NOT NULL,
  `commission` varchar(15) NOT NULL,
  UNIQUE KEY `seller_catid` (`seller_catid`,`product_catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__sellacious_seller_commissions` (
  `product_catid` int(11) NOT NULL,
  `seller_uid` int(11) NOT NULL,
  `commission` varchar(15) CHARACTER SET utf8mb4 NOT NULL,
  UNIQUE KEY `seller_catid` (`seller_uid`,`product_catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__sellacious_field_values`
  ADD COLUMN `is_json` int(11) NOT NULL AFTER `field_id`,
  ADD COLUMN `field_html` text COLLATE utf8mb4_unicode_ci NOT NULL AFTER `field_value`;

ALTER TABLE `#__sellacious_emailtemplates`
  ADD COLUMN `recipients` text NOT NULL AFTER `body`,
  ADD COLUMN `sender` text NOT NULL AFTER `recipients`,
  ADD COLUMN `cc` text NOT NULL AFTER `sender`,
  ADD COLUMN `bcc` text NOT NULL AFTER `cc`,
  ADD COLUMN `replyto` text NOT NULL AFTER `bcc`,
  ADD COLUMN `send_actual_recipient` INT NOT NULL DEFAULT 0 AFTER `replyto`;

ALTER TABLE `#__sellacious_mailqueue`
  ADD COLUMN `sender` text NOT NULL AFTER `recipients`,
  ADD COLUMN `cc` text NOT NULL AFTER `sender`,
  ADD COLUMN `bcc` text NOT NULL AFTER `cc`,
  ADD COLUMN `replyto` text NOT NULL AFTER `bcc`;
