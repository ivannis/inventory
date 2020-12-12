import React from "react";
import './style.css';

export default function ProductList({ products, onClick }) {

    const handleClick = (e, product) => {
        e.preventDefault();

        if (onClick) {
            onClick(product)
        }
    }

    const renderProducts = products.map((product) =>
        <li key={product.id}>
            <a href="!#" onClick={(e) => handleClick(e, product)}>{product.name}</a>
        </li>
    );

    return (
        <div className="products">
            <ul>
                {renderProducts}
            </ul>
        </div>
    );
}
