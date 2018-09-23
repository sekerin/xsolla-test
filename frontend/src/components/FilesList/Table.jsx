import React, { Component } from 'react';
import PropTypes            from 'prop-types';
import { Table }            from 'react-bootstrap';
import TableItem            from './TableItem';

const propsTypes = {
  data: PropTypes.any
};

class TableModule extends Component {
  constructor() {
    super();

    this.getData = this.getData.bind(this);
  }

  getData() {
    const data = [];

    this.props.data.forEach((item) => {
      data.push(
        <TableItem
          key={item.name}
          name={item.name}
        />
      );
    });
    return (data) ? data : 'No objects';
  }

  render() {
    return (
      <Table
        striped
        bordered
        condensed
        hover
        responsive
      >
        <thead>
          <tr>
            <td>Name</td>
            <td>Actions</td>
          </tr>
        </thead>
        <tbody>{this.getData()}</tbody>
      </Table>
    );
  }
}

TableModule.propTypes = propsTypes;

export default TableModule;
