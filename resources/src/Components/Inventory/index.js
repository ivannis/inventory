import React from "react";
import Alert from "../Alert";
import './style.css';

export default function InventoryHistory({ items, order = 'desc' }) {

    if (items.length === 0) {
        return (
            <div className="history">
                <Alert type='error'>There is no history of movements.</Alert>
            </div>
        )
    }

    let history = order === 'desc' ? items.slice().reverse() : items;

    const renderPrice = (item) => {
        if (item.unit_price) {
            return (
                <div className="price">
                    {item.unit_price} NZD
                </div>
            )
        }
    }

    const renderItems = history.map((item) =>
        <li key={item.id}>
            <div className="item">
                <div className="type">{item.type}</div>
                <div className="movement">
                    <div className="unit">
                        <strong>{item.quantity}</strong> units
                    </div>
                    {renderPrice(item)}
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
