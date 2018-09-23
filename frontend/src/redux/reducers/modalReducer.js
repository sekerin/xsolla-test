import {
  MODAL_SHOW,
  MODAL_HIDE,
  MODAL_CHANGE_FILE,
  MODAL_ERROR,
  MODAL_UPDATE, MODAL_LOAD
} from '../actions/modalAction';

const initialState = {
  value: '',
  modal: false,
  item: null,
  error: null,
  loading: false
};

export default function (state = initialState, action) {
  switch (action.type) {
    case MODAL_CHANGE_FILE:
      return Object.assign({}, state, { value: action.value });
    case MODAL_SHOW:
      return Object.assign({}, state, { modal: true, value: '', error: null });
    case MODAL_HIDE:
      return Object.assign({}, state, initialState);
    case MODAL_LOAD:
      return Object.assign({}, state, { loading: true });
    case MODAL_UPDATE:
      return Object.assign({}, state, {
        modal: true,
        value: action.item.name,
        item: action.item,
        error: null
      });
    case MODAL_ERROR:
      return Object.assign({}, state, { error: action.error, loading: false });
    default:
      return state;
  }
}
