import React from "react";
import './style.css';

export default function Header({ title, description }) {

  return (
      <header>
        <h1 className="title">
          {title}
        </h1>

        <p className="description">
          {description}
        </p>
      </header>
  );
}
