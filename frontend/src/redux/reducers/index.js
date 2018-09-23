import { combineReducers } from 'redux';
import filesListReducer    from './filesListReducer';

export default combineReducers({
  filesList: filesListReducer
});
