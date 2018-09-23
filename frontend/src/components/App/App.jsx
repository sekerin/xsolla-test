import React, { Component }    from 'react';
import PropTypes               from 'prop-types';
import { Route, Link, Switch } from 'react-router-dom';

import Grid   from 'react-bootstrap/lib/Grid';
import Navbar from 'react-bootstrap/lib/Navbar';

import FilesList from '../FilesList';

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
            <Navbar.Toggle/>
          </Navbar.Header>
        </Navbar>
        <Grid>
          <Switch>
            <Route exact path='/' component={FilesList}/>
          </Switch>
        </Grid>
      </div>
    );
  }
}

App.propTypes = propTypes;

export default App;
