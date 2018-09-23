import React, { Component }                  from 'react';
import PropTypes                             from 'prop-types';
import { connect }                           from 'react-redux';
import { PageHeader, Grid, Row, Col, Alert } from 'react-bootstrap';
import ButtonLoader                          from 'react-bootstrap-button-loader';
import { listRequest }                       from '../../redux/actions/filesListAction';
import Table                                 from './Table';

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
  }

  componentDidMount() {
    this.handleRefresh();
  }

  handleRefresh() {
    this.props.dispatch(listRequest());
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
              <ButtonLoader loading={this.props.loading} onClick={this.handleRefresh}>GetTable</ButtonLoader>
            </Col>
          </Row>
          <Row className='show-grid'>
            <Col xs={8} md={8}>
              <Table
                data={this.props.items}
              />
            </Col>
          </Row>
        </Grid>
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
