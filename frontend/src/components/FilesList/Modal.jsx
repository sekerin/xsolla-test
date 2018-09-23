import React, { Component } from 'react';
import PropTypes            from 'prop-types';
import { connect }          from 'react-redux';
import {
  Button,
  Modal,
  ModalBody,
  ModalFooter,
  ModalHeader,
  ModalTitle,
  Alert,
  FormGroup,
  ControlLabel,
  FormControl
}                           from 'react-bootstrap';
import ButtonLoader         from 'react-bootstrap-button-loader';

const propsTypes = {
  dispatch: PropTypes.func.isRequired,
  modal: PropTypes.bool,
  value: PropTypes.any,
  error: PropTypes.array,
  item: PropTypes.any,
  loading: PropTypes.bool,
  onClickAdd: PropTypes.func.isRequired,
  onClickUpdate: PropTypes.func.isRequired,
  onClickClose: PropTypes.func.isRequired,
  onChangeFile: PropTypes.func.isRequired
};

class SimpleModal extends Component {
  constructor() {
    super();

    this.handleChange = this.handleChange.bind(this);
    this.handleClickClose = this.handleClickClose.bind(this);
    this.handleClickAdd = this.handleClickAdd.bind(this);
    this.handleClickUpdate = this.handleClickUpdate.bind(this);
  }

  handleChange(e) {
    this.props.onChangeFile(e.target.files[0]);
  }

  handleClickClose() {
    this.props.onClickClose();
  }

  handleClickAdd() {
    if (this.props.value) {
      this.props.onClickAdd(this.props.value);
    }
  }

  handleClickUpdate() {
    if (this.props.value) {
      this.props.onClickUpdate(this.props.item.name, this.props.value);
    }
  }

  render() {
    return (
      <Modal show={this.props.modal}>
        <ModalHeader>
          <ModalTitle>{this.props.item ? this.props.item.name : 'Add name'}</ModalTitle>
        </ModalHeader>
        {this.props.error && (
          <Alert bsStyle='danger'>
            {this.props.error}
          </Alert>
        )}
        <ModalBody>
          <form>
            <FormGroup>
              <ControlLabel>File: {this.props.value.name}</ControlLabel>
              <FormControl
                onChange={this.handleChange}
                type='file'
              />
              <FormControl.Feedback/>
            </FormGroup>
          </form>
        </ModalBody>
        <ModalFooter>
          <Button onClick={this.handleClickClose}>Close</Button>
          <ButtonLoader
            bsStyle='primary'
            loading={this.props.loading}
            onClick={this.props.item ? this.handleClickUpdate : this.handleClickAdd}
          >Save</ButtonLoader>
        </ModalFooter>
      </Modal>
    );
  }
}

SimpleModal.propTypes = propsTypes;

function mapStateToProps(state) {
  const { modal, value, error, item, loading } = state.modal;

  return { modal, value, error, item, loading };
}

export default connect(mapStateToProps)(SimpleModal);
