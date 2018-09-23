import React, { Component }    from 'react';
import PropTypes               from 'prop-types';
import { Link } from 'react-router-dom';

import Grid   from 'react-bootstrap/lib/Grid';
import Navbar from 'react-bootstrap/lib/Navbar';

const propTypes = {
  children: PropTypes.node
};

class App extends Component {
  render() {
    return (
      <div>
        <Navbar>
          <Navbar.Header>
            <Navbar.Brand>
              <Link to='/'>File Manager</Link>
            </Navbar.Brand>
          </Navbar.Header>
        </Navbar>
        <Grid>
          Hello, world!
        </Grid>
      </div>
    );
  }
}

App.propTypes = propTypes;

export default App;
