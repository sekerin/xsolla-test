import express          from 'express';
import React            from 'react';
import ReactDOMServer   from 'react-dom/server';
import { StaticRouter } from 'react-router-dom';
import App              from 'components/App';

const app = express();

app.use((req, res) => {
  const componentHTML = ReactDOMServer.renderToString(
    <StaticRouter location={req.url} context={req}><App/></StaticRouter>
  );

  return res.end(renderHTML(componentHTML));
});

const assetUrl = process.env.NODE_ENV !== 'production' ? `//${process.env.ASSETS_HOST}` : '/';

function renderHTML(componentHTML) {
  return `
    <!DOCTYPE html>
      <html>
      <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Frontend</title>
          <link rel="stylesheet" type="text/css" href="${assetUrl}/public/assets/styles.css">
      </head>
      <body>
        <div id="react-view">${componentHTML}</div>
        <script type="application/javascript" src="${assetUrl}/public/assets/bundle.js"></script>
      </body>
    </html>
  `;
}

const PORT = process.env.PORT || 3001;

app.listen(PORT, () => {
  console.log(`Server listening on: ${PORT}`);
});
