import React from "react";
import './style.css';

export default function StockStatus({ stock }) {
    function getType(quantity) {
        if (quantity > 30) {
            return 'high'
        }

        if (quantity > 0) {
            return 'medium'
        }

        return 'out';
    }

    return (
        <div className="stock-card">
            <div className="stock">
                <strong className={getType(stock.quantity)}>{stock.quantity}</strong> items
            </div>
            <div className="value">
                <strong>{stock.valuation}</strong> NZD
            </div>
        </div>
    );
}
