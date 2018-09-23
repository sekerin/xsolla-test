import React, { Component }                          from 'react';
import PropTypes                                     from 'prop-types';
import { connect }                                   from 'react-redux';
import { PageHeader, Grid, Row, Col, Alert, Button } from 'react-bootstrap';
import ButtonLoader                                  from 'react-bootstrap-button-loader';
import { listRequest, remove, create, update }       from '../../redux/actions/filesListAction';
import {
  modalShow,
  modalHide,
  modalShowUpdate,
  modalChangeFile
}                                                    from '../../redux/actions/modalAction';
import Table                                         from './Table';
import Modal                                         from './Modal';

const propsTypes = {
  dispatch: PropTypes.func.isRequired,
  items: PropTypes.any,
  loading: PropTypes.bool,
  errors: PropTypes.array
};

class FileList extends Component {
  constructor() {
    super();

    this.handleRefresh = this.handleRefresh.bind(this);

    this.handleShowModal = this.handleShowModal.bind(this);
    this.handleModalClose = this.handleModalClose.bind(this);

    this.handleModalAdd = this.handleModalAdd.bind(this);
    this.handleModalUpdate = this.handleModalUpdate.bind(this);

    this.handleChangeFile = this.handleChangeFile.bind(this);

    this.handleEditItem = this.handleEditItem.bind(this);
    this.handleDelete = this.handleDelete.bind(this);
  }

  componentDidMount() {
    this.handleRefresh();
  }

  handleRefresh() {
    this.props.dispatch(listRequest());
  }

  handleShowModal() {
    this.props.dispatch(modalShow());
  }

  handleModalClose() {
    this.props.dispatch(modalHide());
  }

  handleModalAdd(file) {
    this.props.dispatch(create(file));
  }

  handleModalUpdate(id, name) {
    this.props.dispatch(update(id, name));
  }

  handleChangeFile(file) {
    this.props.dispatch(modalChangeFile(file));
  }

  handleEditItem(item) {
    this.props.dispatch(modalShowUpdate(item));
  }

  handleDelete(id) {
    this.props.dispatch(remove(id));
  }

  render() {
    return (
      <div>
        <PageHeader>Files list</PageHeader>
        {this.props.errors && (
          <Alert bsStyle='danger'>
            {this.props.errors}
          </Alert>
        )}
        <Grid>
          <Row className='show-grid'>
            <Col xs={8} md={8}>
              <ButtonLoader loading={this.props.loading} onClick={this.handleRefresh}>Get files</ButtonLoader>
              <Button bsStyle='success' onClick={this.handleShowModal}>Add</Button>
            </Col>
          </Row>
          <Row className='show-grid'>
            <Col xs={8} md={8}>
              <Table
                data={this.props.items}
                onEditItem={this.handleEditItem}
                onDelete={this.handleDelete}
              />
            </Col>
          </Row>
        </Grid>
        <Modal
          onChangeFile={this.handleChangeFile}
          onClickClose={this.handleModalClose}
          onClickAdd={this.handleModalAdd}
          onClickUpdate={this.handleModalUpdate}
        />
      </div>
    );
  }
}

FileList.propTypes = propsTypes;

function mapStateToProps(state) {
  const { items, loading, errors } = state.filesList;

  return { items, loading, errors };
}

export default connect(mapStateToProps)(FileList);
