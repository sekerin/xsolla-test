import { combineReducers } from 'redux';
import filesListReducer    from './filesListReducer';
import modalReducer        from './modalReducer';

export default combineReducers({
  filesList: filesListReducer,
  modal: modalReducer
});
