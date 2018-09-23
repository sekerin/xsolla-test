import React            from 'react';
import { StaticRouter } from 'react-router-dom';

import { shallow }       from 'enzyme';
import { shallowToJson } from 'enzyme-to-json';

import App from '../../components/App/index';

describe('Test App component', () => {
  it('Empty snapshot', () => {
    const wrapper = shallow(<StaticRouter context={{}}><App/></StaticRouter>);
    expect(shallowToJson(wrapper)).toMatchSnapshot();
  });
});
