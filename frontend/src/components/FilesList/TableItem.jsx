import React, { Component } from 'react';
import PropTypes            from 'prop-types';

const propsTypes = {
  name: PropTypes.string.isRequired
};

class TableItem extends Component {
  constructor() {
    super();
  }

  render() {
    return (
      <tr>
        <td><a href={`//${BACKEND_URL}/${this.props.name}`} download>{this.props.name}</a></td>
      </tr>
    );
  }
}

TableItem.propTypes = propsTypes;

export default TableItem;
