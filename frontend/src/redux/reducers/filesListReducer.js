import {
  FILES_LIST_PROCESS_START,
  FILES_LIST_PROCESS_FINISH,
  FILES_LIST_ERROR
} from '../actions/filesListAction';

const initialState = {
  items: [],
  errors: null,
  loading: false
};

export default function (state = initialState, action) {
  switch (action.type) {
    case FILES_LIST_PROCESS_START:
      return Object.assign({}, state, { loading: true });
    case FILES_LIST_PROCESS_FINISH:
      return {
        errors: null,
        loading: false,
        items: action.items
      };
    case FILES_LIST_ERROR:
      return Object.assign({}, state, { errors: action.errors, loading: false });
    default:
      return state;
  }
}
