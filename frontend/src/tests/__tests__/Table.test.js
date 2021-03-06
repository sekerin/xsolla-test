import React from 'react';

import { shallow }       from 'enzyme';
import { shallowToJson } from 'enzyme-to-json';

import Table from '../../components/FilesList/Table';

describe('Test Table component', () => {
  it('Empty Table', () => {
    const wrapper = shallow(<Table onEditItem={jest.fn()} onDelete={jest.fn()} data={[]}/>);
    expect(shallowToJson(wrapper)).toMatchSnapshot();
  });
  it('Table with data', () => {
    const wrapper = shallow(<Table onEditItem={jest.fn()} onDelete={jest.fn()} data={[{ name: 'file' }]}/>);
    expect(shallowToJson(wrapper)).toMatchSnapshot();
  });
});
