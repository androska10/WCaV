

CREATE TABLE IF NOT EXISTS klines (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    open_time BIGINT NOT NULL,
    open_price VARCHAR(20) NOT NULL,
    high_price VARCHAR(20) NOT NULL,
    low_price VARCHAR(20) NOT NULL,
    close_price VARCHAR(20) NOT NULL,
    volume VARCHAR(20) NOT NULL,
    close_time BIGINT NOT NULL,
    quote_asset_volume VARCHAR(20),
    number_of_trades INT,
    taker_buy_base_asset_volume VARCHAR(20),
    taker_buy_quote_asset_volume VARCHAR(20),
    ignore_field VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_open_time (open_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
1499040000000,      // Kline open time
    "0.01634790",       // Open price
    "0.80000000",       // High price
    "0.01575800",       // Low price
    "0.01577100",       // Close price
    "148976.11427815",  // Volume
    1499644799999,      // Kline close time
    "2434.19055334",    // Quote asset volume
    308,                // Number of trades
    "1756.87402397",    // Taker buy base asset volume
    "28.46694368",      // Taker buy quote asset volume
    "0"                 // Unused field. Ignore.
*/