import express          from 'express';
import React            from 'react';
import ReactDOMServer   from 'react-dom/server';
import { StaticRouter } from 'react-router-dom';
import { Provider }     from 'react-redux';
import configureStore   from './redux/configureStore';
import App              from 'components/App';

const app = express();

app.use((req, res) => {
  const store = configureStore({ filesList: { items: [], modal: false } });

  const componentHTML = ReactDOMServer.renderToString(
    <Provider store={store}>
      <StaticRouter location={req.url} context={req}><App/></StaticRouter>
    </Provider>
  );

  const state = store.getState();

  return res.end(renderHTML(componentHTML, state));
});

const assetUrl = process.env.NODE_ENV !== 'production' ? `//${process.env.ASSETS_HOST}` : '/';

const devTools = process.env.NODE_ENV !== 'production' ? '<div id="dev-tools"></div>' : '';

function renderHTML(componentHTML, initialState) {
  return `
    <!DOCTYPE html>
      <html>
      <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Hello</title>
          <script type="application/javascript">
            window.REDUX_INITIAL_STATE = ${JSON.stringify(initialState)};
          </script>
      </head>
      <body>
        <div id="react-view">${componentHTML}</div>${devTools}
        <script type="application/javascript" src="${assetUrl}/public/assets/bundle.js"></script>
      </body>
    </html>
  `;
}

const PORT = process.env.PORT || 3001;

app.listen(PORT, () => {
  console.log(`Server listening on: ${PORT}`);
});
