import React from 'react';

import { shallow }       from 'enzyme';
import { shallowToJson } from 'enzyme-to-json';

import FileList       from '../../components/FilesList/FilesList';
import configureStore from '../../redux/configureStore';

describe('Test FilesList component', () => {
  it('Empty snapshot', () => {
    const store = configureStore({ filesList: { items: [], modal: false } });
    const wrapper = shallow(<FileList store={store}/>);
    expect(shallowToJson(wrapper)).toMatchSnapshot();
  });
  it('FileList with data', () => {
    const store = configureStore({ filesList: { items: [{ name: 'fileName' }], modal: false } });
    const wrapper = shallow(<FileList store={store}/>);
    expect(shallowToJson(wrapper)).toMatchSnapshot();
  });
});
