import React from "react";
import Alert from "../../Alert";

export default function StockItems({ items }) {

    if (items.length === 0) {
        return (
            <div className="history">
                <Alert type='error'>There are no items in stock.</Alert>
            </div>
        )
    }

    const renderItems = items.map((item) =>
        <li key={item.id}>
            <div className="item">
                <div className="movement">
                    <div className="unit">
                        <strong>{item.quantity}</strong> units
                    </div>
                    <div className="price">
                        {item.unit_price} NZD
                    </div>
                </div>
            </div>
            <div className="date">
                {item.created_at}
            </div>
        </li>
    );

    return (
        <div className="history">
            <ul>
                {renderItems}
            </ul>
        </div>
    );
}
