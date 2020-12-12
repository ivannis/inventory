import React from "react";
import './style.css';

export default function Alert({ children, type = 'success' }) {

  return (
      <div className={type}>
          {children}
      </div>
  );
}
