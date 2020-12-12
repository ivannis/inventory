import React, {useEffect} from 'react';
import './Layout.css';

export default function Layout({ title = 'React app', children }) {
  useEffect(() => {
    document.title = title;
  }, [title]);

  return (
      <div className="container">
          <main>
              {children}
          </main>
      </div>
  );
}
