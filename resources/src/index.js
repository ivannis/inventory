import React from 'react';
import ReactDOM from 'react-dom';
import Welcome from './Pages/Welcome';
import reportWebVitals from './reportWebVitals';
import { ReactQueryDevtools } from 'react-query-devtools'
import { QueryCache, ReactQueryCacheProvider, ReactQueryConfigProvider } from 'react-query'
import './index.css';

let cacheTime = 3000;
let staleTime = 1000;

const queryCache = new QueryCache();
const queryConfig = {
    queries: {
        staleTime,
        cacheTime,
    },
};

ReactDOM.render(
  <React.StrictMode>
      <ReactQueryCacheProvider queryCache={queryCache}>
          <ReactQueryConfigProvider config={queryConfig}>
              <Welcome />
          </ReactQueryConfigProvider>
          <ReactQueryDevtools initialIsOpen={false} />
      </ReactQueryCacheProvider>
  </React.StrictMode>,
  document.getElementById('root')
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
