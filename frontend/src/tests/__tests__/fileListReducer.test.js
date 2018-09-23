import React            from 'react';
import FilesListReducer from '../../redux/reducers/filesListReducer';

describe('Test filesListReducer reducer', () => {
  it('filesListReducer FILES_LIST_PROCESS_START must set loading to true', () => {
    const state = FilesListReducer({}, { type: 'FILES_LIST_PROCESS_START' });
    expect(state).toEqual({ loading: true });
  });

  it('filesListReducer FILES_LIST_PROCESS_FINISH set loading to false, errors to null and replace items', () => {
    const items = { items: [{}] };
    const state = FilesListReducer({}, { type: 'FILES_LIST_PROCESS_FINISH', items });
    expect(state).toEqual({ errors: null, loading: false, items });
  });

  it('filesListReducer FILES_LIST_ERROR must set loading to false and set errors array', () => {
    const errors = { errors: [{}] };
    const state = FilesListReducer({}, { type: 'FILES_LIST_ERROR', errors });
    expect(state).toEqual({ loading: false, errors });
  });
});
