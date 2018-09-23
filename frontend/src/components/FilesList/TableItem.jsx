import React, { Component } from 'react';
import PropTypes            from 'prop-types';
import { Button }           from 'react-bootstrap';

const propsTypes = {
  name: PropTypes.string.isRequired,
  onEditItem: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired
};

class TableItem extends Component {
  constructor() {
    super();

    this.handleEdit = this.handleEdit.bind(this);
    this.handleDelete = this.handleDelete.bind(this);
  }

  handleEdit() {
    this.props.onEditItem({
      name: this.props.name
    });
  }

  handleDelete() {
    this.props.onDelete(this.props.name);
  }

  render() {
    return (
      <tr>
        <td><a href={`//${BACKEND_URL}/${this.props.name}`} download>{this.props.name}</a></td>
        <td>
          <Button onClick={this.handleEdit} bsStyle='warning' bsSize='xsmall'>Edit</Button>
          <Button onClick={this.handleDelete} bsStyle='danger' bsSize='xsmall'>Delete</Button>
        </td>
      </tr>
    );
  }
}

TableItem.propTypes = propsTypes;

export default TableItem;
