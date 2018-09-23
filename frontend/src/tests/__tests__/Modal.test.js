import React            from 'react';
import { StaticRouter } from 'react-router-dom';

import { shallow }       from 'enzyme';
import { shallowToJson } from 'enzyme-to-json';

import Modal          from '../../components/FilesList/Modal';
import configureStore from '../../redux/configureStore';

describe('Test Modal component', () => {
  it('Empty Modal', () => {
    const store = configureStore({});
    const wrapper = shallow(
      <Modal
        store={store}
        onClickAdd={jest.fn()}
        onClickUpdate={jest.fn()}
        onClickClose={jest.fn()}
        onChangeFile={jest.fn()}
      />
    );
    expect(shallowToJson(wrapper)).toMatchSnapshot();
  });
});
