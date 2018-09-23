import React            from 'react';
import { StaticRouter } from 'react-router-dom';

import { mount }       from 'enzyme';
import { mountToJson } from 'enzyme-to-json';

import App from '../../components/App/index';

it('renders without crashing', () => {
  const output = mount(<StaticRouter context={{}}><App/></StaticRouter>);
  expect(mountToJson(output)).toMatchSnapshot();
});
