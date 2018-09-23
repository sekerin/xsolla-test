import React, { Component } from 'react';
import PropTypes            from 'prop-types';
import { Button }           from 'react-bootstrap';

const propsTypes = {
  name: PropTypes.string.isRequired,
  onDelete: PropTypes.func.isRequired
};

class TableItem extends Component {
  constructor() {
    super();

    this.handleDelete = this.handleDelete.bind(this);
  }

  handleDelete() {
    this.props.onDelete(this.props.name);
  }

  render() {
    return (
      <tr>
        <td><a href={`//${BACKEND_URL}/${this.props.name}`} download>{this.props.name}</a></td>
        <td>
          <Button onClick={this.handleDelete} bsStyle='danger' bsSize='xsmall'>Delete</Button>
        </td>
      </tr>
    );
  }
}

TableItem.propTypes = propsTypes;

export default TableItem;
